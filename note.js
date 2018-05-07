function ordersList($conn){
    session_start();
    if (!isset($_REQUEST['ordersPage'])) $currentOrderPage=0;else $currentOrderPage=$_REQUEST['ordersPage'];
    $userId=$_SESSION['userID'];
            echo "<h1 style='text-align:center;color:yellow;background-color:rgba(250,250,250,0.3)'>"
           . "Список заказов</h1><br><button onclick='Order(3)'>Печать списка заказов</button>";
   $sql="select orders.id,time,slid,total,paid,fin,name from orders,person where uid=person.id"; 
    if (isset($_SESSION['userRole'])&&($_SESSION['userRole']!=0)){$sql.=" and uid=? ";
   $stmt=$conn->prepare($sql);$stmt->bind_param(i, $userId);}
      else {$sql.=" order by orders.id desc";$stmt=$conn->prepare($sql);}
   $stmt->execute();$res=$stmt->store_result();
   $j=$stmt->affected_rows;
   $pages=floor($j/20);
   $start=$currentOrderPage*20;
    $sql="select orders.id,time,slid,total,paid,fin,name from orders,person where uid=person.id";
    if (isset($_SESSION['userRole'])&&($_SESSION['userRole']!=0)){
   $sql.=" and uid=? order by orders.id desc limit $start,20";
   $stmt=$conn->prepare($sql);$stmt->bind_param(i, $userId);}
else {$sql.=" order by orders.id desc limit $start,20";$stmt=$conn->prepare($sql);}
$stmt->bind_result($order['id'],$order['time'],$order['seller'],$order['total'],$order['paid'],$order['fin'],$order['username']);
   $stmt->execute();$res=$stmt->store_result();$j=$stmt->affected_rows;
   if ($j==0) {echo "<h1 style='color:red; text-shadow: 2px 3px 3px green;'>Заказов НЕТ</h1>";die();} else {
   echo "<h1>Заказы</h1><br><table class='catalog'>";
   echo "<tr style='background-color: white;'><th>№</th><th>Дата / Время</th><th>Сумма</th><th>Оплата</th><th>Доставка</th><th>Пользователь</th></tr>";
while ($stmt->fetch()){
if ($order['paid']==true) $order['paid']="<p style='color:white;text-shadow:1px 1px 1px blue;font-size:110%;'>Оплачено</p>";
else $order['paid']="<p style='color:red;text-shadow:1px 1px 1px white;font-size:115%;'>Не оплачено</p>";
if ($order['fin']==true) $order['fin']="<p style='color:white;text-shadow:1px 1px 1px blue;font-size:110%;'>Исполнен</p>";
else $order['fin']="<p style='color:red;text-shadow:1px 1px 1px white;font-size:115%;'>Не Исполнен</p>";
$oid=$order['id'];
echo "<tr onclick='OrderDetails($oid)'><td>$order[id]</td><td>$order[time]".
//'</td><td>'.$order['seller'].
"</td><td>$order[total]</td><td align='center'>$order[paid]</td><td>$order[fin]</td><td>$order[username]</td></tr>";$totalOrders+=$order['total'];
}
echo "</table><br><h2 style='color:white;text-align:right' >Всего заказов:$j на общую сумму $totalOrders руб.</h2><br>";
       }
       if ($pages>0) { echo '<div id="Navi" align=center>'; for ($i=0;$i<=$pages;$i++){$page=$i+1;
       if ($currentOrderPage==$i) echo "$page ";
else if (!(is_null($keyword))) echo "<a href='javascript:Catalog($i,\"$keyword\")' style='color:yellow;font-size:120%'>$page </a>";
else echo "<a href='javascript:setOrdersPage($i)' style='color:yellow;'>$page </a>";}
  echo "</div>";
       }
}