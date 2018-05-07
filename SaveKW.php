<?php
session_start();
require_once './inc/config.inc';
$conn=Con();
$keyword=$_REQUEST['keyword'];
$ip=$_SERVER["REMOTE_ADDR"];
echo "<h1 style='color:red'>$_REQUEST[keyword]</h1>";
if (isset($_SESSION['userID'])) $userId=$_SESSION['userID']; else $userId=-1;
if (isset($_SESSION['tradePoint'])) $tradePoint=$_SESSION['tradePoint']; else $tradePoint=-1;
//$tradePoint=$_SESSION['tradePoint'];
   $sql="insert into SearchH values(null,null,?,?,?,?)";
   $stmt=$conn->prepare($sql);
   $stmt->bind_param(siis, $keyword,$userId,$tradePoint,$ip);
   $stmt->execute() or die("Error saveKW");
?>