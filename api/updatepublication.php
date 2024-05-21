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
require_once('logsfunc.php');


$dbname = 'unibendb';
$collection = 'staff-profile';



    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    

    $uniqueId= $loguniqueId;
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "updatePublication") {
        $articleId= isset($_POST['articleId'])? $_POST['articleId'] : die();

        if($_POST['status']=="published"){
            $libraryAuth="pending";
        }else{
            $libraryAuth="";
        }

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
                   
                   //$userid = $value['_id']['$oid'];
                   $first_name = $value['first_name'];
                   $middleName = $value['middleName'];
                   $surname = $value['surname'];
                   //$role = $value['role'];
                   $faculty = $value['registry']['faculty'];
                   $dept = $value['registry']['dept'];
                   $staff_category = $value['bursary']['staff_category'];
                   $ubsno = $value['bursary']['bursaryNo'];
                   $staffIDNo = $value['staff_profile']['staffIDNo'];
                   $biography = $value['staff_profile']['biography'];
                   $uniqueId = $value['uniqueId'];
                   $title = $value['title'];
                   $email = $value['contact']['email'];
                  
                }
            }



                $collPublications= 'publications';
                //DB connection
                $db = new DbManager();
                $conn = $db->getConnection();
                $query = [ "uniqueId" => $uniqueId,
                           "articles.articleId" => $articleId];
                $option = [];

                $queryDriver = new MongoDB\Driver\Query($query, $option);

                $articles = $conn->executeQuery("$dbname.$collPublications", $queryDriver)->toArray();
            
                if (count($articles) > 0) {
                    //$existingArticles = $articles[0]->articles;
                    // $uniqueId = $articles[0]->uniqueId;
                            /* ****************************************update publication ****************************************************** */
                             /* *******************upload article************************** */
                            if (empty($_FILES['file']['tmp_name'])) {
                                        /* *******************update article without file************************** */

                                        $articles =     [
                                                              
                                            'articles.$.article_title'                 => $_POST['article_title'],
                                            'articles.$.category'                      => $_POST['category'],
                                            'articles.$.status'                        => $_POST['status'],
                                            'articles.$.publish_status'                => $_POST['publish_status'],
                                            'articles.$.abstract'                      => $_POST['abstract'],
                                            'articles.$.authors'                       => $_POST['authors'],
                                            'articles.$.keywords'                      => $_POST['keywords'],
                                            'articles.$.name_of_journal'               => $_POST['name_of_journal'],
                                            'articles.$.publisher_name'                => $_POST['publisher_name'],
                                            'articles.$.date_published'                => $_POST['date_published'],
                                            'articles.$.journal_link'                  => $_POST['journal_link'],
                                            'articles.$.article_link'                  => $_POST['article_link'],
                                            'articles.$.doi'                           => $_POST['doi'],
                                            'articles.$.copy_right_access'             => $_POST['copy_right_access'],
                                            //'upload'                        => "",
                                            'articles.$.updated'                       => $created, 
                                        ];
                                                        
                                       
                                        // Update database
                                        $update = new MongoDB\Driver\BulkWrite();
                                        $update->update(['uniqueId' => $uniqueId, "articles.articleId" => $articleId], ['$set' => $articles], ['multi' => false, 'upsert' => false]);
                                                        
                                        $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                                        // verify
                                        if ($result->getModifiedCount() == 1) {
                                            /* ****************************************Create logs ****************************************************** */
                                            if($sex=='male'){
                                                $gender='his';
                                            }elseif($sex=='female'){
                                                $gender='her';
                                            }
                                            $logEvent='Updated '.$gender.' publications.';
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
                                /* *******************end upload article without file************************** */

                            }else{
                                /* *******************update article with file************************** */

                               
                                    $uploadedFile = $_FILES['file'];
                                
                                    $originalName = $uploadedFile['name'];
                                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                
                                    // Generate a new unique filename
                                    $newFilename = $loguniqueId .  $_POST['article_title'].'.'.$extension;
                                
                                    // Specify the directory where the file will be uploaded
                                    $uploadDirectory = '../uploads/articles/';

                                    // Check if the file exists
                                    if (file_exists($uploadDirectory . $newFilename)) {
                                        // Delete the existing file
                                        unlink($uploadDirectory . $newFilename);
                                    }

                                    $extension = strtolower($extension);
                                    $allowedExtensions = array("pdf", "doc", "docx");
                                
                                    if (!in_array($extension, $allowedExtensions)) {
                                        echo json_encode(array(
                                            'success' => 0,
                                            'status' => 422,
                                            'message' => 'File must be in PDF or Word format.'
                                        ));
                                    } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . '/' . $newFilename)) {
                                        
                                        $articles =     [
                                                              
                                            'articles.$.article_title'                 => $_POST['article_title'],
                                            'articles.$.category'                      => $_POST['category'],
                                            'articles.$.status'                        => $_POST['status'],
                                            'articles.$.publish_status'                => $_POST['publish_status'],
                                            'articles.$.abstract'                      => $_POST['abstract'],
                                            'articles.$.authors'                       => $_POST['authors'],
                                            'articles.$.keywords'                      => $_POST['keywords'],
                                            'articles.$.name_of_journal'               => $_POST['name_of_journal'],
                                            'articles.$.publisher_name'                => $_POST['publisher_name'],
                                            'articles.$.date_published'                => $_POST['date_published'],
                                            'articles.$.journal_link'                  => $_POST['journal_link'],
                                            'articles.$.article_link'                  => $_POST['article_link'],
                                            'articles.$.doi'                           => $_POST['doi'],
                                            'articles.$.copy_right_access'             => $_POST['copy_right_access'],
                                            'articles.$.upload'                        => $newFilename,
                                            'articles.$.updated'                       => $created, 
                                        ];
                                                        
                                       
                                        // Update database
                                        $update = new MongoDB\Driver\BulkWrite();
                                        $update->update(['uniqueId' => $uniqueId, "articles.articleId" => $articleId], ['$set' => $articles], ['multi' => false, 'upsert' => false]);
                                                        
                                        $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                                        // verify
                                        if ($result->getModifiedCount() == 1) {
                                            /* ****************************************Create logs ****************************************************** */
                                            if($sex=='male'){
                                                $gender='his';
                                            }elseif($sex=='female'){
                                                $gender='her';
                                            }
                                            $logEvent='Updated '.$gender.' publications.';
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

                                     /* *******************end upload article with file************************** */

                                    }
                            }
                                        
                            /* ****************************************end add publication****************************************************** */
                            
                }else{
                    echo json_encode(
                        array('success' => 0,
                        'status' => 400,
                        'message' => $articleId.'Record not found.'));         
                    }
    }
                







