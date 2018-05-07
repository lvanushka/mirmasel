<?php
session_start();
if (isset($_REQUEST['filter'])) {$_SESSION['filter']=$_REQUEST['filter'];echo "Setting filter to '$_REQUEST[filter]' DONE.";}
else if (isset($_REQUEST['qsOrder']))$_SESSION['qsOrder']=$_REQUEST['qsOrder'];else echo "Something is Wrong in setting filter";
echo "Filter: $_REQUEST[filter];qsOrder: $_REQUEST[qsOrder]";