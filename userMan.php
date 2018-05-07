<?php
session_start();
require_once './inc/config.inc';
if (isset($_SESSION['userID'])&&($_SESSION['userRole']===0)){
   $users[]=0;
   $conn=Con();
   $sql="select id,name,fullName,Role,active from person";
   $stmt=$conn->prepare($sql);
   //$stmt->bind_param(i, $userId);
   $stmt->bind_result($users[0],$users[1],$users[2],$users[3],$users[4]);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   echo "<p style='text-align:center;'> Список пользователей."
   . "Всего зарегистрировано $j пользователей</p><br><table>";
   while ($stmt->fetch()){
     echo '<tr><td>'
       .$users[0].'</td><td>'.$users[1].'</td><td>'
       .$users[2].'</td><td>'.$users[3].'</td><td>'
       .$users[4].'</td></tr>';
   }
    echo '</table>';
} else {echo "<p style='text-align:center;'>Errorrrr!!!!</p>";}