<?php

//headers
header("Access-Control-Allow-Origin: localhost/projectgalore/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require __DIR__.'/validation.php';

require_once('classes/session.php');
login();
$category4 =  $_SESSION["category"];
if($category4 !=='admin'){
    header("location: logout.php");
    exit;
}
//$username = $_SESSION['userlogin']; 
require __DIR__.'/classes/configdb.php';


/* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
} */





// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];








// Uploads files
//if (isset($_POST['update'])) 
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "delete"){ // if save button on the form is clicked
    
    //Clean form.

    $id = trim($_POST["id"]);
    $id = mysqli_real_escape_string($link, $id);



    $sql = "DELETE FROM userlogin WHERE id = '$id'";

    if (mysqli_query($link, $sql)) {
        $returnData = msg(1, 201, "Account successfully deleted.");
       
            
    // exit;
    } else {
        $returnData = msg(0, 422, "Unable to delete account.");
    }
    mysqli_close($link);
}
            
    
    
    



echo json_encode($returnData);

?>