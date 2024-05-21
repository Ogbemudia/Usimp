<?php
header('Access-Control-Allow-Origin: localhost/uniben/');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require __DIR__.'validation.php';

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

$accRights=array('admin', 'registry');
$accPrivilege=array('edit');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}


//initializing api
include_once('../core/initialize.php');
//require_once('../create_profile.php');
require_once('../validation/library.php');



$dbname = 'unibendb';
$collection = 'userlogin';
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update_role"){
    $uniqueId= isset($_POST['uniqueId'])? $_POST['uniqueId'] : die();
    $privilege = $_POST['privilege'];
    $right = $_POST['right'];
    $dept = $_POST['dept'];
    $date1 = date("F j, Y"); 
    $tim = date("g:i a");
    $last_update = $date1. " at ".$tim;
    $db = new DbManager();
    $conn = $db->getConnection();
    $query = ['uniqueId' => $uniqueId];
    $option = [];

    $queryDriver = new MongoDB\Driver\Query($query, $option);

    $users = $conn->executeQuery("$dbname.$collection", $queryDriver)->toArray();
   
        if (count($users) > 0){ 
            $user=json_encode($users);
            $result = json_decode($user, true);
            foreach ($result as $value) {
               $userid = $value['_id']['$oid'];
               $fullName1 = $value['fullName'];
               $role0 = $value['role'];
               $bursaryNo0 = $value['bursaryNo'];
            }

   
                    $document=array(
                    
                    //"email"         => $email,
                    "role.dept"         => $dept,
                    "role.right"         => $right,
                    "role.privilege"     => $privilege,
                    "last_update"       => $last_update
                );
                    $collection = 'userlogin';
                    if(updateWithuniqueId($document,  $collection, $uniqueId)){
                        /* ****************************************Create logs ****************************************************** */
                        $logEvent='Updated '.$fullName1.' profile picture.';
                        logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                            
                        /* ****************************************end Create logs****************************************************** */
                        echo json_encode(
                            array('success' => 1,
                            'status' => 201,
                            'message' => 'Update successful.'));
                    }else{

                        
                        echo json_encode(
                            array('success' => 0,
                            'status' => 400,
                            'message' => 'Update failed.'));
                            die;
                    }
                

           
            }else{
            echo json_encode(
                array('success' => 0,
                'status' => 400,
                'message' => 'This Staff does not exist.'));        
            }
        }









