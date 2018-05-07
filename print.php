<?php
session_start();
if (!(isset($_SESSION['userID'])&&(($_SESSION['userRole']>1)))){
require_once './inc/config.inc';
echo '<!DOCTYPE html><html><head><meta charset="utf-8">'
. '<link rel="stylesheet" href="style/printList.css" type="text/css"/>'
        .'<script src="js/jquery-2.2.2.js" type="text/javascript"></script>'
        . '<title>Список товаров</title></head><body onload="window.print()">';
$conn=Con();
$sql='select products.id,left(partn,15),products.prodName,products.price,products.vol,TPshName,step'
        . ' from heap,products,tradepoints where (heap.id=products.hid) and (products.tpid=tradepoints.id)';
if (isset($_REQUEST['tradePoint']) and ($_REQUEST['tradePoint']!=0)){
$tradePoint=$_REQUEST['tradePoint'];
$sql.=" and products.tpid=".$_REQUEST['tradePoint'];
}
$sql.=" order by products.prodName";
 $stmt=$conn->prepare($sql);
      $stmt->bind_result($item['pid'],
                      $item['partN'],
                      $item['prodName'],
                      $item['prodPrice'],
                      $item['prodVol'],
                      $item['tradePoint'],
                      $item['step']);
$stmt->execute() or die($conn->error);
$stmt->store_result() or die($conn->error);

echo ('<table id="printList"><tr><th>№</th><th>Кат.№</th><th>Наименование</th><th>Цена</th>');
echo '<th>Есть</th>';if ($tradePoint==0) echo '<th>Место</th></tr>';else echo "</tr>";
$total=0;$j=0;
         while ($stmt->fetch()){
             $j++;
           if ($item[prodVol]>0){$i+=$item[prodVol];$total+=$item[prodVol]*$item[prodPrice];}
$prodShortName=$item[prodName];
if ($item[prodVol]<=0)  echo "<tr class='out'>";else  echo "<tr>";
echo "<td>$j</td><td>$item[partN]</td><td>$prodShortName</td><td>$item[prodPrice]</td>";
echo "<td>";echo ($item['step']=='i'?round($item[prodVol])."</td>":$item[prodVol]."</td>");
if ($tradePoint==0)  echo "<td>$item[tradePoint]</td></tr>";else echo "</tr>";
                  }
echo "</table><h3>Всего $j наименований товаров на общую сумму $total</h3>"
        . "<button onclick='$(\".out\").toggle();'>Скрыть 0</button></body></html>";}
else    header ("Location:http://mirmasel.xyz");