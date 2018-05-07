<?php
// Функция просмотра содержимого корзины========================================
function showCart($conn){
    session_start();
    $userId=$_SESSION['userID'];
    $userName=$_SESSION["userName"];
    $tradePoint=$_SESSION['tradePoint'];
    $_SESSION["location"]="Cart";
    $sql="select cart.id,pid,products.prodname,cart.price,sum(cart.vol) as volume,cart.price*sum(cart.vol) as total"
           . " from products, cart where ((products.id=cart.pid) and (cart.uid=$userId))"
            . "group by cart.hid";
      $stmt=$conn->prepare($sql) or die($conn->error);
      $stmt->bind_result($cart['id'],$cart['pid'],$cart['prodname'],$cart['price'],$cart['val'],$cart['total']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   echo '<h1 class="windowHeader">Корзина</h1>';
  if ($j==0){echo '<h2 style="text-align:center;color:black;background-color:rgba(250,250,250,0.3);text-shadow:1px 1px yellow">Корзина пуста</h2>';die();}
   echo "<table class='catalog'><tr><th>№</th><th>Наименование</th>"
   . "<th>Цена</th><th>Кол-во</th><th>Сумма</th>";
   $totalCart=0;
   while ($stmt->fetch()){echo "<tr><td>$cart[pid]</td>".
                               "<td>$cart[prodname]</td>".
                               "<td>$cart[price]</td>".
                               "<td>$cart[val]</td>".
                               "<td>$cart[total]</td>".
                               "<td><button onclick='deleteFromCart($cart[id])'>X</button></td>".
                               "</tr>";
           $totalCart+=$cart['total']; }
       echo '</table><h3 class="subWindowHeaderP">'
      . 'Всего в корзине наименований: '.$j.' на общую сумму '.$totalCart.' руб.</h2><br>';
  if ($j!==0){echo "<p class='cfBtn' onclick=\"Order('realise');disableControl(this);\">Оформить Заказ</p>";}
}
//Функция Создания заказа=======================================================
function realise($conn){ 
    session_start();
   $userId=$_SESSION['userID'];
   $tradePoint=$_SESSION['tradePoint'];
   if (isset($_SESSION['userRole'])&&($_SESSION['userRole']<=2)) $sellerId=$_SESSION['userID']; else $sellerId=0; 
        $sql="insert into orders values(null,null,?,(select sum(vol*price) totalCoast from cart where uid=?),false,?,false,?)";
   if (!($stmtOrders=$conn->prepare($sql))){echo "<h2 class='subWindowHeaderN'>ERROR - $conn->error";}
   if (!($stmtOrders->bind_param('iiii', $sellerId,$userId,$userId,$tradePoint))){echo "<h2 class='subWindowHeaderN'>ERROR - $stmtOrders->error";}
   $stmtOrders->execute() or die("Error while inserting into Orders ");
   $orderId=$conn->insert_id;
        $sql="insert into prodflow(pid,vol,uid,price,price0,oid,bonus) select pid,cart.vol,cart.uid,cart.price,price0,?,bonus*cart.vol from cart"
          ." left join products on products.id=cart.pid where cart.uid=?";
   if (!($stmtProdflow=$conn->prepare($sql))) {echo "<h2 class='subWindowHeaderN'>ERROR - $conn->error";}
   if (!($stmtProdflow->bind_param('ii', $orderId,$userId))){echo "<h2 class='subWindowHeaderN'>ERROR - $stmtProdflow->error";}
   $stmtProdflow->execute() or die("Error while inserting into Prodflow");
        $sql="delete from cart where uid=?";
   if (!($stmtCartClear=$conn->prepare($sql))) {echo "<h2 class='subWindowHeaderN'>ERROR - $conn->error";}
   if (!($stmtCartClear->bind_param('i',$userId))){echo "<h2 class='subWindowHeaderN'>ERROR - $stmtCartClear->error";}
   $stmtCartClear->execute() or die("Error while Clearing Cart");
   echo "<h2 class='subWindowHeaderP'>Заказ $orderId оформлен</h2>";
   $_SESSION['orderID']=$orderId;
        echo '<h1 class="windowHeader">Подтверждение оплаты</h1>';
        echo '<div style="text-align:center"><button onclick="Order(\'payForOrder\')">Подтвердить факт оплаты</button></div>';
}
//Функция оплаты заказа=========================================================
function payForOrder($conn){
    session_start();
   $userId=$_SESSION['userID'];
   $orderId=$_SESSION['orderID'];
   echo "";
   $sql="update orders set paid=true,fin=true  where id=$orderId;"
   ."update products,prodflow,orders set products.vol=products.vol-(select sum(prodflow.vol) from prodflow where oid=$orderId and prodflow.pid=products.id ) where products.id=prodflow.pid and $orderId=prodflow.oid and orders.paid=1";
           //. "insert into cashflow select ";
   $res=$conn->multi_query($sql);
   if (!$res) echo "<h1 style=\"color:white;\">$orderId</h1><h2 style=\"color:yellow;\">Ошибка обновления таблицы заказов или товарооборота</h2><br>".$conn->error;else
echo "<script>OrderDetails($orderId)</script>";}
//Детали заказа=========================================================
function orderDetails($conn,$orderId){
    session_start();
    
    $userId=$_SESSION['userID'];
    $_SESSION['orderID']=$orderId;
    $_SESSION['location']='orderDetails';
    echo '<h1 class="windowHeader">Детали заказа</h1>';
   $sql="select paid,fin from orders where id=?";
   $stmt=$conn->prepare($sql);
   $stmt->bind_param('i',$orderId);
   $stmt->bind_result($orderIsPaid,$orderIsFinished);
   $stmt->execute() or die($conn->error);$res=$stmt->store_result();
   $stmt->fetch();
   if ($orderIsPaid==TRUE) echo '<h2 class="subWindowHeaderP">Заказ О П Л А Ч Е Н</h2>';
   else  if($orderIsFinished==FALSE){
       echo '<h2 class="subWindowHeaderN">Заказ Н Е  О П Л А Ч Е Н<br>'
       ."<button onclick='Order(\"payForOrder\")'>Оплатить</button>"
       ."<button onclick='Order(\"delete\")'>Отменить</button></h2>";
   } else echo '<h2 class="subWindowHeaderN">Заказ О Т М Е Н Ё Н </h2>';
   $sql="select products.prodname,prodflow.price,sum(prodflow.vol) as volume,prodflow.price*sum(prodflow.vol) as total, prodflow.id"
   . " from products,prodflow,heap where prodflow.oid=? and prodflow.pid=products.id and products.hid=heap.id";
    if($_SESSION["userRole"]!=0) $sql.=" and prodflow.uid=$userId group by pid"; else $sql.=" group by pid";
   $stmt=$conn->prepare($sql);   
   $stmt->bind_param('i', $orderId);
   $stmt->bind_result($prodflow['prodname'],$prodflow['price'],$prodflow['vol'],$prodflow['total'],$prodflow['id']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   //echo "<h2 style=\"color:purple;background-color:rgba(250,250,250,0.5);\">Заказ №$orderId</h2>";
   if ($j==0) {echo "<h3>Заказ О Т С У Т С Т В У Е Т </h3>";die();}
   echo '<h3 style="color:yellow;text-shadow:1px 1px 2px black;">Заказ № '.$orderId.'</h3><br><table class="catalog"><tr>'
                                    .'<th>№п/п</th>'
                                    .'<th>Наименование</th>'
                                    .'<th>Цена</th>'
                                    .'<th>Кол-во</th>'
                                    .'<th>Сумма</th>'
                                    .'<th>Возврат</th></tr>';
   $num=0;$total=0;
   while ($stmt->fetch()){$num++;
   //print_r($cart);
          echo '<tr><td>'.$num.'</td>'.'<td>'.$prodflow['prodname'].
                               '</td><td>'.$prodflow['price'].'</td><td>'.$prodflow['vol'].
                               '</td><td>'.$prodflow['total'].'</td>'
                  . '<td><button onclick="goodBack('.$prodflow['id'].')">X</button></td></tr>';
$total+=$prodflow['total']; }
echo '</table><br><h2 class="subWindowHeaderP" >Всего  наименований: '.$j.' на общую сумму '.$total.' руб.</h2><br>';
         if ($j!==0){echo "<button id='printBtn' onclick=\"printOD($orderId)\" style='float:right;'>Чек</button>";
}
}
//СПИСОК заказов=========================================================
function ordersList($conn){
    session_start();
    $timez='+interval 4 hour';
    $_SESSION["location"]="ordersList";
    //$totalOrders=0;
    if (!isset($_REQUEST['ordersPage']))$currentOrderPage=0; else $currentOrderPage=$_REQUEST['ordersPage'];
    $userId=$_SESSION['userID'];
   //debug echo "userId=$_SESSION[userID]";//debug
     echo '<h1 class="windowHeader">Журнал продаж</h1>';
     echo '<div id="dateFilter"  style="text-align:center;margin:0px">
    <input id="dp" type="text" onchange="alert(this.value)" 
    style="border-radius:10px;border:red solid 1px;color:white;text-shadow:1px 0px 1px black,-1px 0px 2px yellow;font-size:120%;text-align:center;background:url(/img/filter.png) right center / 16px 16px no-repeat;" />
    <script>$("#dp").datepicker({autoClose:true,onHide:function(dp, animationCompleted){if(animationCompleted)selectOrders(this);}});</script>
    </div>';
      //<p class="cfBtn" onclick="alert($(\'#dp\').data)"><img src="/img/filter.png" width="16" height="16" alt="Filter" /></p>
   //  $sql="select orders.id,time,slid,total,paid,fin,name,TPName from orders"
   $sql="select orders.id,time$timez,slid,(select sum(prodflow.price*prodflow.vol) from prodflow where prodflow.oid=orders.id) as total,paid,fin,name,TPName from orders"
            . " left join person on uid=person.id"
            . " left join tradepoints on tradepoints.id=orders.tpid where 1=1 ";
   if ((isset($_SESSION['tradePoint']))&&($_SESSION['tradePoint']!=0)){$sql.=" and orders.tpid=$_SESSION[tradePoint]";
//debug echo "<h3>TradePoint is $_SESSION[tradePoint]</h3>";
   }
//debug    echo "<h3>userRole is $_SESSION[userRole]</h3>"; 
   if (isset($_SESSION['userRole'])&&($_SESSION['userRole']!=0)){
             $sql.=" and orders.uid=? order by orders.id desc";$stmt=$conn->prepare($sql);$stmt->bind_param('i', $userId);}
      else  {$sql.=" order by orders.id desc";$stmt=$conn->prepare($sql);}
//debug      echo "<p>$sql</p>";
   $ste=$stmt->execute();
   $sr=$stmt->store_result();
   $j=$stmt->affected_rows;
   //debug echo "<p>Rows affected 1 sr=$sr ste=$ste rows=$j br=$br</p>";
   unset($stmt);
   $pages=floor($j/20);
   $start=$currentOrderPage*20;
   $sql.=" limit $start,20";
   $stmt=$conn->prepare($sql);
   if (isset($_SESSION['userRole'])&&($_SESSION['userRole']!=0))$stmt->bind_param('i', $userId);
   $stmt->bind_result($order['id'],$order['time'],$order['seller'],
        $order['total'],$order['paid'],$order['fin'],$order['username'],$order['TPname']);
   $ste=$stmt->execute();$sr=$stmt->store_result();$rows=$stmt->affected_rows;
  //debug echo "<p>Rows affected 2 sr=$sr ste=$ste rows=$rows</p>";
   if ($j===0) {echo '<h1 style="color:red; text-shadow: 2px 3px 3px green">Заказов НЕТ</h1>';die();}
   else {echo '<table class="catalog"><tr style="background-color:white;"><th>№</th><th>Дата / Время</th>'
   .'<th>Сумма</th><th>Оплата</th><th>Доставка</th><th>Пользователь</th><th>Точка продаж</th></tr>';
 
while ($stmt->fetch()){
if ($order['paid']==true)
    $order['paid']='<td align="center" style="color:white;text-shadow:1px 1px 1px blue;font-size:110%">Оплачено</td>';
else $order['paid']='<td align="center" style="color:red;text-shadow:1px 1px 1px white;font-size:110%">Не оплачено</td>';
if ($order['fin']==true)
    $order['fin']='<td align="center" style="color:white;text-shadow:1px 1px 1px blue;font-size:110%">Исполнен</td>';
else $order['fin']='<td align="center" style="color:red;text-shadow:1px 1px 1px white;font-size:110%">Не Исполнен</td>';
echo "<tr onclick='OrderDetails($order[id])'><td>$order[id]</td><td>$order[time]</td>".
"<td>$order[total]</td>".$order['paid'].$order['fin']."<td>$order[username]</td><td>$order[TPname]</td></tr>";
$totalOrders+=$order['total'];
}
echo "</table><h2 style='color:white;text-align:right'>Всего заказов:$j на общую сумму $totalOrders руб.</h2>";
       }
       if ($pages>0) { echo '<div id="Navi" align=center>'; 
       for ($i=0;$i<=$pages;$i++){$page=$i+1;
       if ($currentOrderPage==$i) echo "$page ";
else if (!(is_null($keyword))) echo "<a href='javascript:Catalog($i,\"$keyword\")' style='color:yellow;font-size:120%'>$page </a>";
else echo "<a href='javascript:setOrdersPage($i)' style='color:yellow;'>$page </a>";}
  echo "</div>";
       }
}
 function deleteOrder($conn){
   session_start();
   echo '<h1 style="color:white;text-shadow:1px 1px 1px green;font-size:120%">deleteOrder($conn)</h1';
   $userId=$_SESSION['userID'];
   $orderId=$_SESSION['orderID'];
   echo "<h2 style='color:orange;font-size:120%'>UID=$userId=$_SESSION[userID] OID=$orderId=$_SESSION[orderID]";
$sql="update orders set fin=true where id=$orderId and uid=$userId";   
//$sql="delete from orders where id=$orderId and uid=$userId";
   $stmt=$conn->prepare($sql);
   $stmt->execute();
   $j=$stmt->affected_rows;
   // $res=$conn->multi_query($sql);
   if ($j) echo "<h2 class='subWindowHeader' style='color:red;font-size:120%'>Заказ $orderId О Т М Е Н Е Н </h2>";
   else echo "<h2 class='subWindowHeaderN'>Ошибка ".$conn->error." </h2>";
           echo "<h1 onclick='Catalog(0)' style='color:yellow;font-size:120%'>Главная</h1>";
 }
 function goodBack($conn,$prodflowId){
   session_start();
   echo '<h1 class="windowHeader">Возврат товара</h1';
   $userId=$_SESSION['userID'];
   $tradePoinId=$_SESSION['tradePoint'];
   $orderId=$_SESSION['orderID'];
$prodBack=$conn->prepare('update products set vol=vol+(select vol from prodflow where id=?) where id=(select pid from prodflow where id=?)');
$goodBack=$conn->prepare('insert into goodBack(pfid,pid,vol,uid,oid,price,price0,bonus) (select * from prodflow where id=?)');
$pfDelete=$conn->prepare('delete from prodflow where id=?');
$prodBack->bind_param('ii',$prodflowId,$prodflowId);
if (!$prodBack->execute()) {echo "<h2 class='subWindowHeaderN'>$conn->error</h2>";}
$goodBack->bind_param('i',$prodflowId);
if (!$goodBack->execute()) {echo "<h2 class='subWindowHeaderN'>$conn->error</h2>";}
$pfDelete->bind_param('i',$prodflowId); 
if (!$pfDelete->execute()) {echo "<h2 class='subWindowHeaderN'>$conn->error</h2>";}
 // echo "<script>OrderDetails($orderId)</script>";
 }
function checkCart($conn){
    // session_start();
    echo "<h1 class='windowHeader'>checkCart()</h1>";
if (isset($_SESSION['userID'])) $userId=$_SESSION['userID']; else echo'<h2 class="subWindowHeaderN">Ошибка. Пользователь не опознан</h2>';
//if (isset($_SESSION['tradePoint']))$tradePoinId=$_SESSION['tradePoint']; else echo'<h2 class="subWindowHeaderN">Ошибка. Точка продаж не выбрана</h2>';
$checkCart=$conn->prepare('select count(*) from cart where uid=?');// echo'<h2 class="subWindowHeaderN">Ошибка.'.$conn->error.'</h2>';
// echo'<h2 class="subWindowHeaderN">Ошибка.'.$conn->error.'</h2>';
$checkCart->bind_param('i',$userId);// echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
$checkCart->bind_result($items['inCart']);//echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
$checkCart->execute();
$checkCart->fetch();
$checkCart->close();
//// echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
//$checkCart->store_result(); //echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
$checkRootCartQ=$conn->prepare('select count(*) from rootcart where uid=?');
if ($checkRootCartQ->bind_param('i',$userId));else echo '<h2 class="subWindowHeaderN">Ошибка.'.$checkRootCart->error.'</h2>';
$checkRootCartQ->bind_result($items['inRootCart']);//echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
$checkRootCartQ->execute();
$checkRootCartQ->fetch();
$checkRootCartQ->close();
//// echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
//$checkRootCartQ->store_result();// echo'<h2 class="subWindowHeaderN">Ошибка.'.$checkCart->error.'</h2>';
$_SESSION['inCart']=$items['inCart'];
$_SESSION['inRootCart']=$items['inRootCart'];
echo "<h2>CartItems=$items[inCart]</h2><h2>rootCartItems=$items[inRootCart]</h2>";
//return $items;
 }