<?php
require_once 'sendcode.php';
 $exptim = time()+60*10;
 $expTime = time()+60*60;
 $path = 'path=/';
 
 setcookie ("idlog", $exptim, $path);
 
 $auth2 = substr(sha1(time()), 0, 6);
 $mailS= "Authorize Log In Attempt"; 
 $mailB ="Please copy the following authentication code to authenticate your login:";

 session_start();
 $mailSend=$_SESSION["userlogin"]; 
 $_SESSION["auth"] = $auth2;
 $name=$_SESSION["name"];
 $sendEmail=sendAuth($auth2, $mailSend, $name, $mailS, $mailB);
 if($sendEmail){
    $passOk = "Code heve been resent";
    echo json_encode(
    array('success' => 1,
        'status' => 201,
        'message' => $passOk));
   // require_once('vendor/auth2.php');
    }else{
     echo json_encode(
     array('success' => 0,
     'status' => 403,
     'message' => 'Somthing went wrong! try again later'));
                       }