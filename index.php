<?php
session_start();
require_once 'inc/config.inc';
require_once 'inc/vars.php';
?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta http-equiv="Pragma" content="no-cache" />
        <title>Мир Масел OnLine !</title>
        <link rel="stylesheet" href="style/stylesheet1.css" type="text/css"/>
        <link rel="stylesheet" href="style/datepicker.css" type="text/css"/>
        <!--<link href="style/jquery-ui.css" rel="stylesheet">-->
        <script src="js/common.js"></script>
            <script src="js/jquery-2.2.2.js" type="text/javascript"></script>
            <script src="js/datepicker.js"></script>
        <!--<script src="js/jquery-ui.js" type="text/javascript"></script>-->
        <script src="js/clientPanel.js" type="text/javascript"></script>
   
</head>

<body onload="Catalog(0);">
    <div id="MainContainer">
<?php
require_once 'topMenu.php';
?>
        <div id="mystyle"><div id="Cat"></div></div>
        <div id="contacts" style="float:none">
            <p style="text-align: center;font-size: 8px;float: none"><a href="mailto:lvanushka@ya.ru?subject=АИС Мир Масел">Разработка и сопровождение АИС "Мир Масел"</a></p>
        </div>
    </div>
</body>
</html>
