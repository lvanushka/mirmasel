<?php
session_start();
require_once './inc/config.inc';
$sql='select * from products';
if (isset($_REQUEST['itemID'])){$itemID=$_REQUEST['itemID'];}// else die("Some kind an error accured");
   $sql.=" where id=?";
   $conn=Con();
   $stmt=$conn->prepare($sql);
   $stmt->bind_param(i, $itemID);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   if ($j===0) die("Something was worng on your way here");
   $stmt->bind_result($row['id'],$row['cat_id'],$row['scat_id'],$row['producerId'],$row['prodName'],$row['price']);
   echo ('<table align=center>');
   while ($stmt->fetch()){
       echo "<tr><td>$row[id]</td>".
               "<td><image src='img/moz4.jpg' style='height:192px;width:256px;' /></td>".
               "<td>$row[cat_id]</td>".
               "<td>$row[scat_id]</td>".
               "<td>$row[producerId]</td>".
               "<td>$row[prodName]</td>".
               "<td>$row[price]</td></tr>";
   }
   echo "</table>";
   