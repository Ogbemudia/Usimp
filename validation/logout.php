<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

//if(isset($_POST["logout"])){

session_start();

//$jwt = $_COOKIE['token'];
//header ("Set-Cookie: token=$jwt; time()-1; path=/; HttpOnly");
session_destroy();
    unset($_SESSION['userlogin']);  //Destroy This Session
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    unset($_SESSION['role']);
    unset($_SESSION["bursaryNo"]);
    unset($_SESSION["uniqueId"]);
    unset($_SESSION["role"]);
    unset($_SESSION["sex"]);
    unset($_SESSION["privilege"]);
    unset($_SESSION["right"]);
    //unset($_SESSION["auth"]);
    
    //$token = $jwt;
   
$tok="";
  header ("Set-Cookie: token=$tok; time()-1800*5; path=/; HttpOnly");
  unset($_COOKIE["token"]);
   //setcookie('token', $token, time()-1 );

    $returnData = msg(0, 403, "You have been logged out.");



    
//header("location: login.html");
echo json_encode($returnData);
exit;
//}

?>