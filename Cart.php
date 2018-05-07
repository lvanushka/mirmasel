<?php
session_start();
$php_errormsg='E_ALL';
require_once './inc/config.inc';
require_once './php/Func.php';
if (isset($_SESSION["userName"])){
$conn=Con();
$itemID=intval($_REQUEST['itemID']);
$userName=$_SESSION['userName'];
$userId=intval($_SESSION['userID']);
$volume=floatval($_REQUEST['volume']);
$price=$_REQUEST['price'];
$tpid=intval($_SESSION['tradePoint']);
if (isset($_REQUEST['deleteID'])) {//Удаление позиции из корзины
        if(($_SESSION["userRole"]<=1)&&($_SESSION['location']=='CurrentRootOrder'))
        {$sql='delete from rootcart where id='.$_REQUEST['deleteID'];
        if($_SESSION['userRole']==1) $sql.=' and uid='.$userId;}
        else $sql='delete from cart where id='.$_REQUEST['deleteID'].' and uid='.$userId;
   $stmt=$conn->prepare($sql) or die('<h2 class="subWindowN">'.$conn->error.'</h2>');
   $stmt->execute() or die('<h2 class="subWindowN">'.$conn->error.'</h2>');
   
   echo $_REQUEST['deleteID'].' was deleted from Cart rows:'.$stmt->affected_rows;die();}
   if (($_SESSION['userRole']<=1)&&($_SESSION['location']=='Contra'))
   {if ($_SESSION['contra']==0&&$_SESSION['tradePoint']!=0){
           $sql='insert into rootcart(hid,prodName,cid,vol,price,priceOut,bonus,uid,note,step) '
        ."SELECT hid,prodName,0,$volume,priceIn,priceOut,bonus,$userId,note,step FROM prodIn
left join rootorders on rootorders.id=prodIn.roid where hid=$itemID and tpid=$tpid";}
else
    {
    $twins=$conn->query('select * from rootcart where hid='.$itemID);
    echo 'Debug string: the same positions Number is: '.$twins->num_rows;
        $sql="insert into rootcart(hid,prodName,cid,vol,price,priceOut,bonus,uid) "
         .'select id,prodName,Contraid,'.$volume.',price,(price*1.3),((price*1.3-price)*0.3),'.$userId.' from heap where id='.$itemID;
    
    }}
else $sql="insert into cart values(null,(select id from products where id=$itemID),"
        . "(select hid from products where id=$itemID),$volume,$price,$userId)";
   if (!$stmt=$conn->prepare($sql)) echo($conn->error);
 //  $stmt->bind_param(i,$userId);
   if (!$stmt->execute())echo $stmt->error;
   if (!$stmt->store_result()) echo $stmt->error;
   echo ' Contra='.$_SESSION['contra'];
   echo ' Location='.$_SESSION['location'];
   echo ' Добавлено в корзину '.$itemID.' ar='.$stmt->affected_rows;
}
else {echo false;}