<?php
header('Access-Control-Allow-Origin: localhost');
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
$sessionId = session_id();

require_once('authfunc.php');

$accRights=array('admin','library');
$accPrivilege=array('approve');
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
require_once('logsfunc.php');


$dbname = 'unibendb';
//$collection = 'staff-profile';



    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    

    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "authenticatePublication") {
        $staffUniqueId= isset($_POST['staffUniqueId'])? $_POST['staffUniqueId'] : die();
        $articleId= isset($_POST['articleId'])? $_POST['articleId'] : die();

                $collPublications= 'publications';
                //DB connection
                $db = new DbManager();
                $conn = $db->getConnection();
                $query = [ "uniqueId" => $staffUniqueId,
                           "articles.articleId" => $articleId];
                $option = [];

                $queryDriver = new MongoDB\Driver\Query($query, $option);

                $articles = $conn->executeQuery("$dbname.$collPublications", $queryDriver)->toArray();
            
                if (count($articles) > 0) {
                    //$existingArticles = $articles[0]->articles;
                    $fullName = $articles[0]->fullName;
                            /* ****************************************update publication ****************************************************** */
                             
                                        $articles =     [
                                                              
                                            'articles.$.library_authentication.authentication_status' => $_POST['authentication_status'],
                                            'articles.$.library_authentication.authenticator'         => $executorsFullName,
                                            'articles.$.library_authentication.date'                  =>$created,
                                            
                                        ];
                                                        
                                       
                                        // Update database
                                        $update = new MongoDB\Driver\BulkWrite();
                                        $update->update(['uniqueId' => $staffUniqueId, "articles.articleId" => $articleId], ['$set' => $articles], ['multi' => false, 'upsert' => false]);
                                                        
                                        $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                                        // verify
                                        if ($result->getModifiedCount() == 1) {
                                            /* ****************************************Create logs ****************************************************** */
                                            $logEvent='Evaluated '.$fullName.' publications.';
                                            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                                                
                                            /* ****************************************end Create logs****************************************************** */
                                
                                            echo json_encode(
                                                array('success' => 1,
                                                'status' => 200,
                                                'message' => 'Publication successfully updated.'));
                                        } else {
                                            echo json_encode(
                                                array('success' => 0,
                                                'status' => 400,
                                                'message' => 'Some problem occurred, please try again.'));
                                            }
                }else{
                    echo json_encode(
                        array('success' => 0,
                        'status' => 400,
                        'message' => 'Publication not found.'));
                    
                }
    }