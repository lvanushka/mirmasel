<?php
session_start();
require_once './inc/config.inc';
if(!(isset($_SESSION["userRole"]))){header("Location:/index.php");die();}
$timez='+interval 4 hour';
$userId=$_SESSION["userID"];
$orderId=$_REQUEST['orderId'];
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><link rel="stylesheet" href="style/printList.css" type="text/css"/>'
. '<title>Товарный чек №'.$orderId.'</title>'.'<link rel="stylesheet" href="style/print.css" type="text/css"/>'
        . '</head><body onload="window.print()">';
$conn=Con();
$infosql='select Address,orders.time'.$timez.',person.FullName from tradepoints
left join orders on orders.tpid=tradepoints.id left join person on person.id=orders.slid
where orders.id=?';
if (!$infostmt=$conn->prepare($infosql))echo '<h2 class="subWindowHeaderN">'.$con->error.'</h2>';
if (!$infostmt->bind_param('i',$orderId)) echo '<h2 class="subWindowHeaderN">'.$infostmt->error.'</h2>';
if (!$infostmt->bind_result($TPAddress,$orderDate,$userFullName))echo '<h2 class="subWindowHeaderN">'.$infostmt->error.'</h2>';
if(!$infostmt->execute()) echo '<h2 class="subWindowHeaderN">'.$con->error.'</h2>'; echo '<h2 class="subWindowHeaderN">'.$con->error.'</h2>';
while ($infostmt->fetch());
$sql='select products.prodname,prodflow.price,sum(prodflow.vol) as volume,prodflow.price*sum(prodflow.vol) as total,step'
     .' from products left join prodflow on prodflow.pid=products.id left join orders on prodflow.oid=orders.id where prodflow.oid=?';
if($_SESSION['userRole']!=0) $sql.=' and prodflow.uid='.$userId.' group by pid'; else $sql.=' group by pid';
   $stmt=$conn->prepare($sql);   
   $stmt->bind_param('i', $orderId);
   $stmt->bind_result($cart['prodname'],$cart['price'],$cart['vol'],$cart['total'],$cart['step']);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   if ($j==0) {echo '<h1>Заказ О Т С У Т С Т В У Е Т </h1>';die();}
   echo '<p><strong>Продавец: ИП Саламатин Максим Евгеньевич</strong></p>'
        .'<p><strong>ОГРН 306223520100041</strong></p>'
        . '<p>Адрес регистрации: 658930, Алтайский край, Волчихинский район, с. Волчиха, ул. Толстого, 25</p>'
        . '<p>Фактический адрес: '.$TPAddress.'</p>'
        . '<p>Адрес в сети "Интернет":<strong> mirmasel.xyz</strong></p>'
        . '<p>Кассир: '.$userFullName.'</p>'
        . '<h3>ТОВАРНЫЙ ЧЕК №'.$orderId.'</h3>'
        . '<h3>'.$orderDate.'</h3>'
        . '<table class="catalog"><tr><th>№ п/п</th>'
        . '<th>Наименование</th><th>Ед.</th>'
        . '<th>Цена</th><th>Кол-во</th><th>Сумма</th></tr>';
$num=0;$totalCart=0;
while ($stmt->fetch()){
    $num++;
if ($cart['step']=='i') $mes='шт.'; else 
if ($cart['step']=='f') $mes='л.';
echo '<tr><td>'.$num.'</td><td>'.$cart['prodname'].'</td><td>'.$mes.'</td><td>'.$cart['price'].'</td>';
echo ($cart['step']=='i'?'<td>'.round($cart['vol']).'</td>':'<td>'.$cart['vol'].'</td>');
echo '<td>'.$cart['total']."</td></tr>";$totalCart+=$cart['total']; }
       echo '</table><br><p>Всего  наименований '.$j.' на общую сумму:'.$totalCart.' руб.</p>'.
               '<p>Получено:'.$totalCart.' руб.</p>'.'<p>Продавец:___________________</p>';
       