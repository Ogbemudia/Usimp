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
$collPublications = 'publications';



    $uniqueId= $loguniqueId;
    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;

    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "deleteart") {
        $articleId= isset($_POST['articleId'])? $_POST['articleId'] : die();

           // DB connection
        $db = new DbManager();
        $conn = $db->getConnection();

         // Update database
         $update = new MongoDB\Driver\BulkWrite();
         $update->update(['uniqueId' => $uniqueId, "articles.articleId" => $articleId], ['$pull' => ['articles' => ['articleId' => $articleId]]], ['multi' => false, 'upsert' => false]);
                         
         $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
         // verify

        
        if ($result->getModifiedCount() > 0) {
   
            /* ****************************************Create logs ****************************************************** */
            if($sex=='male'){
                $gender='his';
            }elseif($sex=='female'){
                $gender='her';
            }
            $logEvent='Deleted '.$gender.' article.';
            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                
            /* ****************************************end Create logs****************************************************** */
            
            echo json_encode(array(
                'success' => 1,
                'status' => 200,
                'message' => 'Successful deleted.'
            ));
        } else {
            // File upload failed
            echo json_encode(array(
                'success' => 0,
                'status' => 422,
                'message' => 'Deleting failed. Please try again later.'
            ));
        }
    }
    
    

