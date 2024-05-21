<?php

header('Access-Control-Allow-Origin: localhost/giz/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require_once('validation.php');

require_once('../validation/classes/session.php');
login();
$logEmail = $_SESSION['userlogin'];
$loguniqueId = $_SESSION['uniqueId'];
$logUBS = $_SESSION['bursaryNo'];
$logRight = $_SESSION['right'];
$privilege = $_SESSION['privilege'];
$sex = $_SESSION['sex'];
$executorsFullName = $_SESSION['name'];
$role4 = $_SESSION['role'];


require_once('authfunc.php');

$accRights=array('staff');
$accPrivilege=array('update');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
} 

/* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
} */

//initializing api
include_once('../core/initialize.php');
require_once('../validation/library.php');
require_once('create_profile.php');
require_once('logsfunc.php');



$dbname = 'unibendb';


$uniqueId= isset($_POST['uniqueId'])? $_POST['uniqueId'] : die();
    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    /* *******************upload signature************************** */
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update_signature") {
        $uploadedFile = $_FILES['file'];
        
        $originalName = $uploadedFile['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        
        // Generate a new unique filename
        $newFilename = uniqid() . '_signature_' . $loguniqueId . '.' . $extension;
        
        // Specify the directory where the file will be uploaded
        $uploadDirectory = '../uploads/signature/';
        $extension = strtolower($extension);
        $extension_arr = array("png","jpg","jpeg");
        
        if (!in_array($extension, $extension_arr)) {
            echo json_encode(array(
                'success' => 0,
                'status' => 422,
                'message' => 'File must be in png, jpg or jpeg format.'
            ));
        } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $newFilename)) {
            // File upload successful
            $data = array(
                'signature' => $newFilename
            );
            
            $profile = 'userlogin';
            $updateProfile = update_by_uniqueId($data, $profile, $uniqueId);

            /* ****************************************Create logs ****************************************************** */
            if($sex=='male'){
                $gender='his';
            }elseif($sex=='female'){
                $gender='her';
            }
            $logEvent='Updated '.$gender.' signature.';
            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                
            /* ****************************************end Create logs****************************************************** */
            
            echo json_encode(array(
                'success' => 1,
                'status' => 200,
                'message' => 'Update successful.'
            ));
        } else {
            // File upload failed
            echo json_encode(array(
                'success' => 0,
                'status' => 422,
                'message' => 'File upload failed. Please try again later.'
            ));
        }
    }