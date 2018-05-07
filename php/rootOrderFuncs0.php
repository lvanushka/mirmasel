<?php
// Функция просмотра содержимого корзины заявок========================================
function showCart($conn){
    $_SESSION["location"]="CurrentRootOrder";
    echo "<h2>Текущая заявка $_SESSION[location]</h2>";
    echo "<table id='HeapMenu'><tr>".
            "<td onclick=\"ProdMaster()\">Добавить</td>".
            "<td onclick=\"rootOrder('showCart')\">Текущая заявка</td>".
            "<td onclick=\"rootOrder('ordersList')\">Перечень заявок</td>".
            "<td onclick=\"rootOrder('')\">? заявка</td></tr>";
    $userId=$_SESSION['userID'];
    $userName=$_SESSION["userName"];
    $tradePoint=$_SESSION['tradePoint'];
    echo "<h2>$userName</h2>";
    $sql="select person.name,rootcart.id,hid,left(heap.partN,10),rootcart.prodname,"
."rootcart.price,rootcart.priceOut,sum(rootcart.vol) as volume,rootcart.price*sum(rootcart.vol) as totalIn,".
"rootcart.priceOut*sum(rootcart.vol) as totalOut,(rootcart.bonus*rootcart.vol) as totalBonus"
." from rootcart,heap,person where (heap.id=rootcart.hid) and (person.id=rootcart.uid)";
//if ($_SESSION["userRole"]==1) $sql.=" and (rootcart.uid=$userId)";
            $sql.=" group by rootcart.hid order by rootcart.id";
      $stmt=$conn->prepare($sql) or die($conn->error);    
$stmt->bind_result($cart['userName'], //привязка результатов запроса к переменным
              $cart['id'],$cart['hid'],$cart['PartN'],
              $cart['prodname'],$cart['price'],$cart['priceOut'],
              $cart['vol'],$cart['totalIn'], $cart['totalOut'],$cart['totalBonus']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   if ($j==0) {echo "<p>Заявка пуста</p>";die();}
   echo "<table class='catalog'><tr><th>№</th><th>#</th><th>Заказчик</th><th>Кат. №</th><th>Наименование</th>"
   . "<th>Цена 0</th><th>Цена 1</th><th>Кол-во</th><th>Сумма</th><th>Bonus</th>";
   $posNumb=0;
   $totalCartIn=0;
   $totalCartOut=0;
   $totalCartBonus=0;
   while ($stmt->fetch()){$posNumb++; echo "<tr><td>$posNumb</td>".
                               "<td>$cart[hid]</td>".
                               "<td>$cart[userName]</td>".
                               "<td>$cart[PartN]</td>".
                               "<td onclick='editRootCartItem($cart[id])'>$cart[prodname]</td>".
                               "<td>$cart[price]</td>".
                               "<td>$cart[priceOut]</td>".
                               "<td>$cart[vol]</td>".
                               "<td>$cart[totalIn]</td>".
                         "<td>$cart[totalBonus]</td>".
                         "<td><button onclick='deleteFromRootCart($cart[id])'>X</button</td>".
                         "</tr>";
$totalCartIn+=$cart['totalIn'];
$totalCartOut+=$cart['totalOut'];
$totalCartBonus+=$cart['totalBonus'] ;
					
}
       echo "</table><br><h2 style='color:white;text-align:right' >"
      . "Всего в заявке наименований: $posNumb <br>"
      ."На общую сумму: $totalCartIn руб.<br>"
      ."Ожидаемая выручка: $totalCartOut руб.<br>"
      ."Bonus: $totalCartBonus руб.<br></h2><br>";
  if ($j!==0){echo "<button onclick=\"rootOrder('selectTP')\" style='float:right;'>Место назначения</button>";}
}
//Выбор места назначения
function selectTradePoint($conn){
     $sql='select id,TPname,address from tradepoints';
    $stmt=$conn->prepare($sql);
    $stmt->bind_result($tradePoint['id'],$tradePoint['name'],$tradePoint['address']);
   $stmt->execute();
   $stmt->store_result();
   echo "<table class='catalog' style='width:75%;'><th>#</th><th>Точка</th><th>Адрес</th>";
   while ($stmt->fetch()) echo "<tr onclick='selectTP($tradePoint[id])'>"
           . "<td>$tradePoint[id]</td>"
           . "<td>$tradePoint[name]</td>"
           . "<td>$tradePoint[address]</td></tr>";
   echo "</table>";
}
//Функция Создания заказа=======================================================
function realise($conn,$tradePoint){
   $userId=$_SESSION['userID'];
   //$tradePoint=$_SESSION['tradePoint'];
   if (isset($_SESSION['userRole'])&&($_SESSION['userRole']==0)) //$sellerId=$_SESSION['userID']; else $sellerId=0;
   $sql="insert into rootorders values(null,null,(select sum(vol*price) totalCoast from rootcart),false,?,false,?);";
 else die("только администратор может оформить заявку");
   $stmt=$conn->prepare($sql);
   $stmt->bind_param(ii,$userId,$tradePoint);
   $stmt->execute();
   $orderId=$conn->insert_id;
   $j=$stmt->affected_rows;
   $sql="insert into prodIn(hid,prodName,vol,roid,priceIn, priceOut,bonus) select hid,prodName,vol,$orderId,price,priceOut,bonus from rootcart;"
          ."delete from rootcart;";
   $res=$conn->multi_query($sql);
   if ($res) echo "<h3>Заказ оформлен</h3>"; else die("<h3>ERROR ".$conn->error." </h3>");
   $_SESSION['orderID']=$orderId;
        echo "<p style='text-align:center;'>--- $j Заявка № $orderId сформирована"
            . "Выбор места доставки</p><br><button onclick=\"rootOrder('payForOrder')\">Оплатить</button>";
}

//СПИСОК заказов=========================================================
function ordersList($conn,$tpid){
    
    $_SESSION["location"]="rootOrdersList";
    echo "<h2>Текущая заявка $_SESSION[location]</h2>";
    echo "<table id='HeapMenu'><tr>".
            "<td onclick=\"ProdMaster()\">Добавить</td>".
            "<td onclick=\"rootOrder('showCart')\">Текущая заявка</td>".
            "<td onclick=\"rootOrder('ordersList')\">Перечень заявок</td>".
            "<td onclick=\"rootOrder('')\">? заявка</td></tr>";
    if (!isset($_REQUEST['ordersPage'])) $currentOrderPage=0;else $currentOrderPage=$_REQUEST['ordersPage'];
    $userId=$_SESSION['userID'];
            echo "<h1 style='text-align:center;color:yellow;background-color:rgba(250,250,250,0.3)'>"
           . "Список заявок</h1><br><button onclick='alert(100500)'>Печать списка заказов</button>";
   $sql="select rootorders.id,time,total,paid,fin,tpname from rootorders,tradepoints"
           . " where rootorders.tpid=tradepoints.id"; 
    if (isset($_SESSION['userRole'])&&($_SESSION['userRole']!=0)){
        //$sql.=" and uid=? ";
   $stmt=$conn->prepare($sql);}
      else {$sql.=" order by rootorders.id desc";$stmt=$conn->prepare($sql);}
   $stmt->execute();$res=$stmt->store_result();
   $j=$stmt->affected_rows;
   $pages=floor($j/20);
   $start=$currentOrderPage*20;
    $sql="select rootorders.id,time,total,paid,fin,tpname,tradepoints.id from rootorders,tradepoints"
       . " where rootorders.tpid=tradepoints.id"; 
    if ($tpid>0) $sql.=" and tpid=$tpid";
    //echo $sql;//содержимое запроса
    if (isset($_SESSION['userRole'])&&($_SESSION['userRole']!=0))
     {$sql.=" order by rootorders.id desc limit $start,20";$stmt=$conn->prepare($sql);}
else {$sql.=" order by rootorders.id desc limit $start,20";$stmt=$conn->prepare($sql);}
$stmt->bind_result($order['id'],$order['time'],$order['total'],
                   $order['paid'],$order['fin'],$order['tradePoint'],$order['tpid']);
   $stmt->execute();$res=$stmt->store_result();$j=$stmt->affected_rows;
   if ($j==0) {echo "<h1 style='color:red; text-shadow: 2px 3px 3px green;'>"
       . "Заявок НЕТ</h1>";die();}
       else {
   echo "<h1>Заявки</h1><br><table class='catalog'>";
   echo "<tr style='background-color:grey;'>"
           . "<th>№</th>"
           . "<th>Дата/Время</th>"
           . "<th>Сумма</th>"
           . "<th>Оплата</th>"
           . "<th>Доставка</th>"
           . "<th>Адресат</th></tr>";
while ($stmt->fetch()){
if ($order['paid']==true) $order['paid']='Оплачено';else $order['paid']="Не оплачено";
if ($order['fin']==true) $order['fin']='Исполнен';else $order['fin']="Не Исполнен";
$oid=$order['id'];
echo "<tr><td>$order[id]</td><td>$order[time]</td>"
//'</td><td>'.$order['seller'].
        ."<td  onclick='rootOrderDetails($oid)'>$order[total]</td>"
        ."<td>$order[paid]</td><td>$order[fin]</td>"
        . "<td  onclick='rootOrder([\"ordersList\",$order[tpid]])'>$order[tradePoint]</td></tr>";$totalOrders+=$order['total'];
}
echo "</table><br><h2 style='color:white;text-align:right' >"
. "Всего заявок:$j на общую сумму $totalOrders руб.</h2><br>";
       }
       if ($pages>0) { echo '<div id="Navi" align=center>'; for ($i=0;$i<=$pages;$i++){$page=$i+1;
       if ($currentOrderPage==$i) echo "$page ";
else if (!(is_null($keyword))) echo "<a href='javascript:Catalog($i,\"$keyword\")' style='color:yellow;font-size:120%'>$page </a>";
else echo "<a href='javascript:setOrdersPage($i)' style='color:yellow;'>$page </a>";}
  echo "</div>";
       }
}
//Детали заказа=========================================================
function orderDetails($conn,$orderId){
    echo "<h1 style=\"color:orange;background-color:rgba(250,250,250,0.4);text-shadow:0px 0px 3px black\">"
    . "Заявка № $orderId</h1>";
            $userId=$_SESSION['userID'];
            $_SESSION['orderID']=$orderId;
            //echo "<h2>User ID $userId</h2>";
   $sql="select paid,tpname,address from rootorders,tradepoints where rootorders.id=? and rootorders.tpid=tradepoints.id";
   $stmt=$conn->prepare($sql);
   $stmt->bind_param('i',$orderId);
   $stmt->bind_result($orderIsPaid,$tpName,$tpAddress);
   $stmt->execute();$res=$stmt->store_result();
   $stmt->fetch();
   if ($orderIsPaid==true) 
       echo "<h2 style=\"color:white;background-color:rgba(250,250,250,0.4);text-shadow:1px 1px 2px black;\">"
       . "Заявка ОПЛАЧЕНА</h2>";
   else 
       echo "<h2 style=\"color:red;background-color:rgba(250,250,250,0.4);text-shadow:1px 1px 2px white \">"
       . "Заявка НЕ ОПЛАЧЕНА <button onclick=\"rootOrder('payForOrder')\">Оплатить</button></h2>";
   $sql="select heap.prodName,prodIn.priceOut,sum(prodIn.vol) as volume,"
           . "prodIn.priceOut*sum(prodIn.vol) as total "
           . "from prodIn,heap where prodIn.roid=? and prodIn.hid=heap.id group by hid";
   $stmt=$conn->prepare($sql);   
   $stmt->bind_param('i',$orderId);
   $stmt->bind_result($cart['prodname'],$cart['price'],$cart['vol'],$cart['total']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   //echo "<h2 style=\"color:purple;background-color:rgba(250,250,250,0.5);\">Заказ №$orderId</h2>";
   if ($j==0) {echo "<h3>Заявка отсутствует</h3>";die();}
   echo "<h3>$tpName</h3><h3>$tpAddress</h3><table class=\"catalog\"><tr>"
                                    . "<th>№ п/п</th>"
                                    . "<th>Наименование</th>"
                                    . "<th>Цена</th>"
                                    . "<th>Кол-во</th>"
                                    . "<th>Сумма</th></tr>";
   $num=0;$totalCart=0;
   while ($stmt->fetch()){$num++;
   //print_r($cart);
          echo "<tr><td>$num</td>".
                               '<td>'.$cart['prodname'].
                               '</td><td>'.$cart['price'].
                               '</td><td>'.$cart['vol'].
                               '</td><td>'.$cart['total'].
                               "</td></tr>";
$totalCart+=$cart['total']; }
       echo "</table><br><h2 style='color:white;text-align:left;"
        . "background-color:rgba(250,250,250,0.3);text-shadow:1px 1px 2px black' >"
      . "Всего $j наименований на общую сумму"
               . " $totalCart руб.</h2><br>";
  
       if ($j!==0){echo "<button id='printBtn' onclick=\"Print()\" style='float:right;'>Чек</button>";
}
}
//Функция оплаты заказа=========================================================
function payForOrder($conn){
   $userId=$_SESSION['userID'];
   $orderId=$_SESSION['orderID'];
   //echo "<h1>$orderId</h1>";
$sql="update products,prodIn,rootorders "
	."set products.vol=products.vol+prodIn.vol,price0=prodIn.priceIn,products.price=prodIn.priceOut "
	."where products.hid=prodIn.hid and products.tpid=rootorders.tpid and rootorders.id=$orderId and prodIn.roid=$orderId;";
$sql.="insert into products(hid,prodName,vol,price,price0,bonus,tpid)"
	."select prodIn.hid,prodIn.prodName,prodIn.vol,priceOut,priceIn,prodIn.bonus,rootorders.tpid from prodIn"
	." left join rootorders on rootorders.id=prodIn.roid"
	." left join products on products.hid=prodIn.hid and products.tpid=rootorders.tpid"
	." where prodIn.roid=$orderId and products.tpid is null;";
   $sql.="update rootorders set paid=true,fin=true  where id=$orderId;";
           //. "insert into cashflow select ";
   $res=$conn->multi_query($sql);
   $sr=$conn->store_result();
   if (!$res) echo "<h1>Ошибка оплаты $orderId </h1> ".$conn->error;else
echo "<script>rootOrderDetails($orderId)</script>";
}
