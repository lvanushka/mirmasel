<?php
session_start();
if ($_SESSION['userRole']==0){
require_once './inc/config.inc';
echo '<!DOCTYPE html><html><head><meta charset="utf-8">'.'<link rel="stylesheet" href="style/printList.css" media="all" type="text/css"/>'
. '<title>Текущая заявка</title></head><body onload="window.print()">';
$conn=Con();
$sql="select person.name,rootcart.prodname,"
."rootcart.priceOut,sum(rootcart.vol) as volume,rootcart.priceOut*sum(rootcart.vol) as totalOut"
//."rootcart.priceOut*sum(rootcart.vol) as totalOut,(rootcart.bonus*rootcart.vol) as totalBonus"
." from rootcart,heap,person where (heap.id=rootcart.hid) and (person.id=rootcart.uid)";
//if ($_SESSION["userRole"]==1) $sql.=" and (rootcart.uid=$userId)";
            $sql.=" group by rootcart.hid order by rootcart.id";
/* if (isset($_REQUEST['tradePoint']) and ($_REQUEST['tradePoint']!=0)){
$tradePoint=$_REQUEST['tradePoint'];
$sql.=" and products.tpid=".$_REQUEST['tradePoint'];
}
$sql.=" order by products.prodName";*/
 $stmt=$conn->prepare($sql) or die($conn->error);    
$stmt->bind_result($cart['userName'], //привязка результатов запроса к переменным
              //$cart['id'],$cart['hid'],$cart['PartN'],
              $cart['prodname'],$cart['priceOut'],
              $cart['vol'],$cart['totalOut']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   if ($j==0) {echo "<p>Заявка пуста</p>";die();}
   echo "<table id='printCRO'><tr><th>№</th><th>Наименование</th><th>Цена</th><th>Кол-во</th><th>Сумма</th>";
   $posNumb=0;
   //$totalCartIn=0;
   $totalCartOut=0;
  // $totalCartBonus=0;
   while ($stmt->fetch()){
       $posNumb++; echo "<tr><td>$posNumb</td>".
       //  "<td>$cart[hid]</td>".
       //"<td>$cart[userName]</td>".
       //"<td>$cart[PartN]</td>".
       "<td>$cart[prodname]</td>".
       //"<td>$cart[price]</td>".
       "<td>$cart[priceOut]</td>".
       "<td>$cart[vol]</td>".
       "<td>$cart[totalOut]</td>".
       //"<td>$cart[totalBonus]</td>".
       //"<td><button onclick='deleteFromRootCart($cart[id])'>X</button</td>".
       "</tr>";
//$totalCartIn+=$cart['totalIn'];
$totalCartOut+=$cart['totalOut'];
//$totalCartBonus+=$cart['totalBonus'] ;
}
       echo "</table><h3>Всего в заявке наименований: $posNumb <br>"
      //."На общую сумму: $totalCartIn руб.<br>"
      ."На общую сумму: $totalCartOut руб.</h3></body></html>";
}
else header ("Location:http://mirmasel.xyz");