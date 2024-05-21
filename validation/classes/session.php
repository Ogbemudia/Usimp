<?php
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

session_start();


function login(){
//Initializing the session



if (!isset($_SESSION['userlogin'])) {
    $returnData = msg(0, 422, "Please login.");
    
    //redirect to login page
//header("location: login.php");
echo json_encode($returnData);
exit;
}
}



    ?>