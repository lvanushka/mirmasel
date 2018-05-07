<?php
function Con(){
$host='localhost';
$user='root';
$password='';
$database='mirmasel_1';
$port='3306';
$con=new mysqli($host, $user, $password, $database, $port) or die(mysqli_connect_error);
$con->set_charset("utf8");
echo $con->connect_error;
return $con;
}
?>