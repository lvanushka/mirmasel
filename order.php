<?php
session_start();
require_once './inc/config.inc';
require_once './php/Func.php';

if (isset($_SESSION['userID'])){
    $userId=$_SESSION['userID'];
    $conn=Con();
    if ((isset($_REQUEST['delete']))&&($_REQUEST['delete']==1)){deleteOrder($conn);}
    else
 //Нулевая стадия - отображение содержимого корзины для оформления заказа
  if ((isset($_REQUEST['showCart']))&&($_REQUEST['showCart']==1)){showCart($conn);}
    else
//Первая стадия - формирование заказа
if (isset($_REQUEST['realise'])&&($_REQUEST['realise']==1)){realise($conn);}
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
if ((isset($_REQUEST['ordersList']))&&($_REQUEST['ordersList']==1)){ordersList($conn);}
else
//Детали Заказа
if (isset($_REQUEST['showDetails'])){
    $orderId=$_REQUEST['showDetails'];
    orderDetails($conn,$orderId);
           }
else
//Возврат товара
if (isset($_REQUEST['goodBack'])){
    $prodflowId=$_REQUEST['goodBack'];
    goodBack($conn,$prodflowId);
}
else
    if (isset($_REQUEST['checkCart'])){checkCart($conn);}
}
 else {echo "<h1>Ошибка одработки комады ЗАКАЗ!!!!";}
?>