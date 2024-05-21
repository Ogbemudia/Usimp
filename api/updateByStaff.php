<?php
header('Access-Control-Allow-Origin: localhost/giz/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
//require __DIR__.'validation.php';
require ('validation.php');

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
require_once('logsfunc.php');

$accRights=array('staff');
$accPrivilege=array('update');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}



//initializing api
include_once('../core/initialize.php');
include_once('sendverify.php');
require_once('../validation/library.php');
require_once('create_profile.php');
require_once('uploads.php');



$dbname = 'unibendb';


//if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update"){
    //$uniqueId= isset($_POST['uniqueId'])? $_POST['uniqueId'] : die();
    $uniqueId= $loguniqueId;
    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    /* *******************upload cv************************** */
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update_cv") {

        $uploadedFile = $_FILES['file'];
                                
        $originalName = $uploadedFile['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                
        // Generate a new unique filename
        $newFilename = $loguniqueId .  '_cv_'.'.'.$extension;
                        
        // Specify the directory where the file will be uploaded
        $uploadDirectory = '../uploads/cv/';

        // Check if the file exists
        if (file_exists($uploadDirectory . $newFilename)) {
        // Delete the existing file
             unlink($uploadDirectory . $newFilename);
             }

        $extension = strtolower($extension);
        $allowedExtensions = array("pdf", "doc", "docx");
                                
        if (!in_array($extension, $allowedExtensions)){
            echo json_encode(array(
                'success' => 0,
                'status' => 422,
                'message' => 'File must be in PDF or Word format.'
            ));
        } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $newFilename)) {
            // File upload successful
            $data = array(
                'staff_profile.cv' => $newFilename
            );
            
            $profile = 'staff-profile';
            $updateProfile = update_by_uniqueId($data, $profile, $uniqueId);

            /* ****************************************Create logs ****************************************************** */
            if($sex=='male'){
                $gender='his';
            }elseif($sex=='female'){
                $gender='her';
            }
            $logEvent='Updated '.$gender.' Curriculum Vitae.';
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
    
    /* *******************upload pic************************** */

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "profile_pic"){
  
    $uploadedFile = $_FILES['file'];
    
    $originalName = $uploadedFile['name']; // Changed to 'name' from 'profile_pic'
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    
    // Generate a new unique filename
    $newFilename = uniqid() . '_profile_pic_' . $loguniqueId . '.' . $extension;
    
    // Specify the directory where the file will be uploaded
    $uploadDirectory = '../uploads/profilepic/';
    $extension = strtolower($extension);
    //$extension_arr = array("png","jpg","jpeg");
    $extension_arr = array("png", "jpg", "jpeg", "jpe", "jif", "jfif", "jfi");
    
    if (!in_array($extension, $extension_arr)) { // Changed to !in_array to check if extension is NOT in the array
        echo json_encode(array(
            'success' => 0,
            'status' => 422,
            'message' => 'File must be in png, jpg, jpe or jpeg format.'
        ));
    } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $newFilename)) {
        // File upload successful
        $data = array(
            'staff_profile.picName' => $newFilename
        );
        
        $profile = 'staff-profile';
        $updateProfile = update_by_uniqueId($data, $profile, $uniqueId);
        
        /* ****************************************Create logs ****************************************************** */
        if($sex=='male'){
            $gender='his';
        }elseif($sex=='female'){
            $gender='her';
        }
        $logEvent='Updated '.$gender.' profile picture.';
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


    /********************update user profile*************************** */
    if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "profile_update"){

               
                        $data=array(
                            
                            'registry.areaOfSpecialization'   => $_POST['specialization'],
                            'staff_profile.biography'     => $_POST['biography'],
                            'staff_profile.orcid_no'      => $_POST['orcid_no'],
                            'staff_profile.awards'        => [$_POST['awards']],
                            'contact.PAddress'            => $_POST['PAddress'],
                            'contact.Caddress'            => $_POST['Caddress'],
                            'contact.geoPoliticalZone'    => $_POST['geoPoliticalZone'],
                            'contact.wardOfOrigin'        => $_POST['wardOfOrigin'],
                            'contact.phone'               => $_POST['phone'],
                            'NHIS.NHIS_code'              => $_POST['NHIS_code'],
                            'NHIS.HMO'                    => $_POST['HMO'],
                            'NHIS.primary_health_care'    => $_POST['primary_health_care'],
                            'cooperative.cooperative_name'=> $_POST['cooperative_name'],
                            'cooperative.cooperative_id'  => $_POST['cooperative_id'],
                              
                            'last_updated'       => $created,                                 
            
                        );
                        $profile='staff-profile';
                        // Insert member data in the database

                       

                       if (update_by_uniqueId($data,  $profile, $uniqueId)){
                                /*************************update publication********************************/
                                $collPublication = 'publications';
                                // DB connection
                                $db = new DbManager();
                                $conn = $db->getConnection();
                                $query = ['uniqueId' => $uniqueId];
                                $option = [];
                                $queryDriver = new MongoDB\Driver\Query($query, $option);
                                $pubResult = $conn->executeQuery("$dbname.$collPublication", $queryDriver)->toArray();

                                if (count($pubResult) > 0) {
                                    $pubData = array(
                                        //'uniqueId'         => $uniqueId,
                                        //'ubsno'            => $ubsno,
                                        //'staffIDNo'        => $staffIDNo,
                                        //'title'            => $title,
                                        //'fullName'         => $first_name.' '.$middleName.' '.$surname,
                                        'biography'        => isset($_POST['biography']) ? $_POST['biography'] : null,
                                        //'email'            => $email,
                                        'orcid_no'         => isset($_POST['orcid_no']) ? $_POST['orcid_no'] : null,
                                        //'faculty'          => strtoupper($faculty),
                                        //'dept'             => strtoupper($dept),
                                        //'staff_category'   => strtoupper($staff_category),
                                        'last_update'      => $created,
                                    );
                                    $bulkWrite = new MongoDB\Driver\BulkWrite();
                                    $bulkWrite->update(['uniqueId' => $uniqueId], ['$set' => $pubData], ['multi' => false, 'upsert' => false]);
                                    $conn->executeBulkWrite("$dbname.$collPublication", $bulkWrite);
                                }


                                /*************************end update publication********************************/

                                /* ****************************************Create logs ****************************************************** */
                                $sex = trim($sex);
                                $sex = strtolower($sex);
                                if($sex=='male'){
                                    $gender='his';
                                }elseif($sex=='female'){
                                    $gender='her';
                                }
                                $logEvent='Updated '.$gender.' staff record.';
                                logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                        
                                /* ****************************************end Create logs****************************************************** */
                            
                                echo json_encode(
                                    array('success' => 1,
                                    'status' => 200,
                                    'message' => 'Update successfull.'));
                        }else{
                            echo json_encode(
                                array('success' => 0,
                                'status' => 400,
                                'message' => 'Update failed.'));
                        }
                    }


