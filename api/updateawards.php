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
    

    /* ****************************Next of Kin**************************************** */
    if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "kin_update"){

               
                        $data=array(
                            
                            'next_of_kin.NokFirstName'           => $_POST['NokFirstName'],                                        
                            'next_of_kin.NokMiddleName'          => $_POST['NokMiddleName'],                                        
                            'next_of_kin.NokLastName'            => $_POST['NokLastName'],                                        
                            'next_of_kin.relationship'           => $_POST['relationship'],                                        
                            'next_of_kin.NokSex'                 => $_POST['NokSex'],                            
                            'next_of_kin.NokContackAdd'          => $_POST['NokContackAdd'],                            
                            'next_of_kin.NokAddress'             => $_POST['NokAddress'],               
                            'next_of_kin.NokEmail'               => $_POST['NokEmail'],                                 
                            'next_of_kin.NokPhoneNumber'         => $_POST['NokPhoneNumber'], 
                              
                            'last_updated'       => $created,                                 
            
                        );
                        $profile='staff-profile';
                        // Insert member data in the database

                       

                       if (update_by_uniqueId($data,  $profile, $uniqueId)){
                                
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


