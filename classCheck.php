<?php
require_once 'OrderClass.php';
    $user=new User($_REQUEST['userId']);
    print_r($_REQUEST['userId']."\n");
    print_r ($user);
    $a=$user->info();
    print_r($a);
?>