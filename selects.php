<?php
session_start();
function fillCats(){
require_once './inc/config.inc';
$sql="select id,name from cats";
   $conn=Con();
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->bind_result($cats["id"],$cats["naim"]);
   $stmt->execute();
   $res=$stmt->store_result();       
while ($stmt->fetch()){
    echo "<option value=$cats[id]>$cats[naim]";
}
}

function fillSubCats($cid){
require_once './inc/config.inc';
$sql="select id,name from scats where cid=?";
   $conn=Con();
   $stmt=$conn->prepare($sql) or die($conn->error);
   $stmt->bind_param(i, $cid);
   $stmt->bind_result($scats["id"],$scats["naim"]);
   $stmt->execute();
   $res=$stmt->store_result();       
while ($stmt->fetch()){
    echo "<option value=$scats[id]>$scats[naim]";
}
}