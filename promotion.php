<?php
$docs=scandir("./docs",0);
//$docs0=arr
unset($docs[0]);unset($docs[1]);
//while ($docs->)
$count=count($docs);
$j=count($docs)+2;
//echo "<h1>$count</h1>";
for ($i=2;$i<$j;$i++) echo "<img alt='$docs[$i]' src='./docs/$docs[$i]' style='width:50%;height:50%;align:center'/>";
