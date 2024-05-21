<?php

//headers
header("Access-Control-Allow-Origin: localhost/Uniben/");
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
$username = $_SESSION['userlogin'];
require __DIR__.'/classes/configdb.php';




// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];





$user_id =  $_SESSION["id"];

// Uploads files
//if (isset($_POST['update'])) 
if(isset($_FILES['file']['name'])){

   
    
    //Clean form.
    
    
    //$returnData = msg(1, 201, "you have successfully updated your profile.");

    
    $date1 = date("F j, Y"); 
    $tim = date("g:i a");
    $last_update = $date1. " at ".$tim;
 
     /* Getting file name */
     $filename = $_FILES['file']['name'];
     $size = $_FILES['file']['size'];
 
     /* Location */
     $location = "../upload/" . $filename;
     $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
     $imageFileType = strtolower($imageFileType);

     $file = $_FILES['file']['tmp_name'];
    
  
     /* Valid extensions */
     //$valid_extensions = array("jpg","jpeg","png");
  
     //$response = 0;
     /* Check file extension */
     //if(in_array(strtolower($imageFileType), $valid_extensions)) {
        if ($_FILES['file']['size'] > 100000000) { // file shouldn't be larger than 10Megabyte
            $size_err= "Image larger than 10MB.";
            $returnData = msg(0, 422, $size_err);
        } elseif
        /* Upload file */
        (move_uploaded_file($file,$location)){
            $sql = "UPDATE profile SET
                
            image = '$filename', 
            size = '$size',
            last_update = '$last_update'
            
            WHERE user_id = '$user_id'
            ";
           
            if (mysqli_query($link, $sql)) {
                $update= "Image saved successfully.";
                $returnData = msg(1, 201, $update);
            } else {
                $failed_err= "Failed to update profile.";
                $returnData = msg(0, 422, $failed_err);
            }
        }
    }    
        

echo json_encode($returnData);

?>