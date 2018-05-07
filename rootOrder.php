<?php
session_start();
require_once './inc/config.inc';
require_once './php/rootOrderFuncs.php';

if ((isset($_SESSION['userID']))&&($_SESSION['userRole']<=1)){
    $userId=$_SESSION['userID'];
    $conn=Con();
    //Нулевая стадия - отображение содержимого корзины для оформления заказа
  if ((isset($_REQUEST['showCart']))&&($_REQUEST['showCart']==1)){showCart($conn);}
    else
        //Выбор места назначения
if ((isset($_REQUEST['selectTP']))&&($_REQUEST['selectTP']==1)){selectTradePoint($conn);}
    else
        //Первая стадия - формирование заказа
if (isset($_REQUEST['realise'])&&($_REQUEST['realise']==1)){realise($conn,$_REQUEST['TP']);}
//Вторая стадия - Оплата доставка заказа
    else 
    if (isset($_REQUEST['payForOrder'])&&($_REQUEST['payForOrder']==1)){payForOrder($conn);}
//Третья стадия - печать товарного чека
else
if (isset($_REQUEST['printDoc'])&&($_REQUEST['printDoc']==1)){
        echo "<p style='text-align:center;'>"
            . "Печать чека. Выдача товара</p><br><button onclick='Order(4)'>Продолжить 3</button>";
        $sql="update orders set fin=true where id=$orderId;";
           //. "insert into cashflow select ";
   $res=$conn->multi_query($sql);
   if ($res==TRUE) {echo "Shipping complete";} else {echo "Shipping ERROR";}
    }
            else
    //Список заказов
if ((isset($_REQUEST['ordersList']))&&($_REQUEST['ordersList']==1)){
    if (isset($_REQUEST['tpid']))ordersList($conn,$_REQUEST['tpid']); else ordersList($conn,0);}
    
else
//Детали Заказа
if (isset($_REQUEST['showDetails'])){
    $orderId=$_REQUEST['showDetails'];
    orderDetails($conn,$orderId);
           }}
 else {echo "<p> Ошибка Авторизации!!!!";}
?>