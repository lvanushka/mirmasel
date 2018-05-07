<?php
session_start();
require_once './inc/config.inc';
    $newUserName=$_REQUEST['newUserName'];
    $newUserEmail=$_REQUEST['newUserEmail'];
    $newUserPass1=md5($_REQUEST['newUserPass1']);
    $newUserPass2=md5($_REQUEST['newUserPass2']);
    $newUserTS=$_REQUEST['newUserTS'];
    $newUserOdo=$_REQUEST['newUserOdo'];
    $newUserOil=$_REQUEST['newUserOil'];
if (isset($_SESSION['userRole']) and ($_SESSION['userRole']==0))
    {$newUserActive=true;$newUserStatus=1;} else {$newUserActive=false;$newUserStatus=2;}
$UAcon=Con() or die("<p>Ошибка создания соединения с БД</P>");
$sql="select * from person where name=?";
$stmt=$UAcon->prepare($sql);
$stmt->bind_param(s, $newUserName);
//$stmt->bind_result($rows);
$stmt->execute();
$stmt->store_result();
if ($newUserPass1!=$newUserPass2){die("<p>Введенные пароли не совпадают</P>");}
if ($stmt->affected_rows>0){die("<p>Пользователь с таким именем уже есть в системе</P>");}
$sql="insert into person values(null,?,?,?,?,?)";
$stmt=$UAcon->prepare($sql);
$stmt->bind_param(sssii, $newUserName,$newUserName,$newUserPass1,$newUserStatus,$newUserActive);
//$stmt->bind_result($rows);
$stmt->execute() or die ("<p>execution Error </P>");
$stmt->store_result();
if ($stmt->affected_rows>0){
    echo("<p>Регистрация нового пользователя прошла успешно <br>".
    "$newUserName<br>$newUserEmail<br>$newUserActive</p>");
}
else {echo ("Ошибка регистрации");}