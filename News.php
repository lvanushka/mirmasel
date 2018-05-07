<?php
require_once './inc/config.inc';
   $news[]=0;
   $conn=Con();
   $sql="select * from news";
   $stmt=$conn->prepare($sql);
   //$stmt->bind_param(i, $userId);
   $stmt->bind_result($news[0],$news[1],$news[2],$news[3]);
   $stmt->execute();
   $res=$stmt->store_result();
   $j=$stmt->affected_rows;
   echo "<p style='text-align:center;'> Наши новости.Всего $j</p><br>";
   while ($stmt->fetch()){
     echo '<table><tr><td>'.$news[0].'</td><td>'.$news[1].'</td><td>'
             .$news[2].'</td><td>'.$news[3].'</td><td>'.$news[4].'</td></tr>';
   }
    echo '</table>';
?>
