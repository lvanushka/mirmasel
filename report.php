<?php
session_start();
$sql="select * from tradepoints";
$sql="select sum(vol),sum(price0*vol), sum(price*vol)";
$sql="select * from prodin";
$sql="select * from ptodflow";