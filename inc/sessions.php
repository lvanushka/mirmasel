<?php
session_start();
function tpSelector(){
    require_once 'config.inc';
    $con= Con();
$tpSelector='<select onchange="TPSelector(this.value)" id="tpSelector">'
        . '<option class="Sopts" selected disabled>Точки продаж</option>'
        . '<option class="Sopts" value="0">Все</option>';
$sql="select ID,TPname from tradepoints";
$stmt=$con->prepare($sql);
$stmt->bind_result($tp['id'],$tp['Name']);
$stmt->execute();
$res=$stmt->store_result();
while ($stmt->fetch())$tpSelector.='<option class="Sopts" value='.$tp['id'].'>'.$tp['Name'].'</option>';
$tpSelector.="</select>";
return $tpSelector;
}
if (isset($_REQUEST['tpid'])){
$_SESSION['tradePoint']=$_REQUEST['tpid'];
switch ($_SESSION['location']){
default:echo "<script>Catalog(0)</script >";break;
case 'rootOrdersList':echo "<script>rootOrder('ordersList',$_SESSION[tradePoint])</script>";break;
case 'ordersList':echo "<script>Order('ordersList')</script>";break;
case 'cashflow':echo "<script>cashFlow()</script>";break;
case 'Contra':echo "<script>ProdMaster(0,$_SESSION[contra])</script>";break;
}
}