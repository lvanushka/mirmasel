<?php
session_start();
require_once '../inc/config.inc';
$return['grettings']='';
$return['sellerPanel']='';
$return['adminPanel']='';
if (isset($_REQUEST['action'])&&($_REQUEST['action']=='logout')){
    session_unset();
    session_destroy();
          } else
if(isset($_REQUEST["userName"])&&(isset($_REQUEST["userPass"]))){
   $userName=$_REQUEST["userName"];
   $userPass=md5($_REQUEST["userPass"]);
$conn=Con();
$sql='select id,name,role,active,tpid from person where name=? and pass=?';
   $stmt=$conn->prepare($sql);
   $stmt->bind_param(ss, $userName,$userPass);
   $userData[]=0;
   $stmt->bind_result($userData['userID'],
                      $userData['userName'],
                      $userData['userRole'],
                      $userData['active'],
                      $userData['tradePoint']);
   $stmt->execute();
   $stmt->store_result();
   $stmt->fetch();
   if ($stmt->num_rows===1){
       $_SESSION['userID']=$userData['userID'];
       $_SESSION['userName']=$userData['userName'];
       $_SESSION['userRole']=$userData['userRole'];
       $_SESSION['active']=$userData['active'];
       $_SESSION['tradePoint']=$userData['tradePoint'];
   }
  }
/*unset($grettings);
unset($sellerPanel);
unset($adminPanel);*/
    require '../inc/vars.php';
       $return['grettings']=$grettings;
       $return['sellerPanel']=$sellerPanel;
       $return['adminPanel']=$adminPanel;
       echo json_encode($return);