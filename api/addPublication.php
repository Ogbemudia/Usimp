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
    /* ****************************************articleId ****************************************************** */
    $numgen = rand(0,999);
    $timeReg = time();
    $codegen = $numgen. $timeReg;
    $vCode = substr(sha1($codegen), 0, 6); 
    $articleId=uniqid().$vCode;

    $uniqueId= $loguniqueId;
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "addPublication") {
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
                   $orcid_no = $value['staff_profile']['orcid_no'];
                   $email = $value['contact']['email'];
                  
                }
            }



                $collPublications= 'publications';
                //DB connection
                $db = new DbManager();
                $conn = $db->getConnection();
                $query = ['uniqueId'=>$loguniqueId];
                $option = [];

                $queryDriver = new MongoDB\Driver\Query($query, $option);

                $articles = $conn->executeQuery("$dbname.$collPublications", $queryDriver)->toArray();
            
                if (count($articles) > 0) {
                    $existingArticles = $articles[0]->articles;
                    // $uniqueId = $articles[0]->uniqueId;
                            /* ****************************************add publication ****************************************************** */
                             /* *******************upload article************************** */
                            if (empty($_FILES['file']['tmp_name'])) {
                                        /* *******************upload article without file************************** */

                                        $newArticles =     [
                                            'articleId'                     =>$articleId,
                                            'library_authentication'        => [
                                                                                'authentication_status'=>$libraryAuth,
                                                                                'authenticator'=>'',
                                                                                'date'=>'',
                                                                                ],                             
                                            'article_title'                 => $_POST['article_title'],
                                            'category'                      => $_POST['category'],
                                            'status'                        => $_POST['status'],
                                            'publish_status'                => $_POST['publish_status'],
                                            'abstract'                      => $_POST['abstract'],
                                            'authors'                       => $_POST['authors'],
                                            'keywords'                      => $_POST['keywords'],
                                            'name_of_journal'               => $_POST['name_of_journal'],
                                            'publisher_name'                => $_POST['publisher_name'],
                                            'date_published'                => $_POST['date_published'],
                                            'journal_link'                  => $_POST['journal_link'],
                                            'article_link'                  => $_POST['article_link'],
                                            'doi'                           => $_POST['doi'],
                                            'copy_right_access'             => $_POST['copy_right_access'],
                                            'upload'                        => "",
                                            'created'                       => $created, 
                                        ];
                                                        
                                        $existingArticles[] = $newArticles;
                                                        
                                        // Update database
                                        $update = new MongoDB\Driver\BulkWrite();
                                        $update->update(['uniqueId' => $uniqueId], ['$set' => ['articles' => $existingArticles]], ['multi' => false, 'upsert' => false]);
                                                        
                                        $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                                        // verify
                                        if ($result->getModifiedCount() == 1) {
                                            /* ****************************************Create logs ****************************************************** */
                                            if($sex=='male'){
                                                $gender='his';
                                            }elseif($sex=='female'){
                                                $gender='her';
                                            }
                                            $logEvent='Added '.$_POST['category'].' to '.$gender.' list of publications.';
                                            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                                                
                                            /* ****************************************end Create logs****************************************************** */
                                
                                            echo json_encode(
                                                array('success' => 1,
                                                'status' => 200,
                                                'message' => 'Publication successfully Added.'));
                                        } else {
                                            echo json_encode(
                                                array('success' => 0,
                                                'status' => 400,
                                                'message' => 'Some problem occurred, please try again.'));
                                            }
                                /* *******************end upload article without file************************** */

                            }else{
                                /* *******************upload article with file************************** */

                               
                                    $uploadedFile = $_FILES['file'];
                                
                                    $originalName = $uploadedFile['name'];
                                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                
                                    // Generate a new unique filename
                                    $newFilename = $loguniqueId .  $_POST['article_title'].'.'.$extension;
                                
                                    // Specify the directory where the file will be uploaded
                                    $uploadDirectory = '../uploads/articles/';
                                    $extension = strtolower($extension);
                                    $allowedExtensions = array("pdf", "doc", "docx");
                                
                                    if (!in_array($extension, $allowedExtensions)) {
                                        echo json_encode(array(
                                            'success' => 0,
                                            'status' => 422,
                                            'message' => 'File must be in PDF or Word format.'
                                        ));
                                    } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . '/' . $newFilename)) {
                                        
                                        $newArticles =     [
                                            'articleId'                     =>$articleId,
                                            'library_authentication'        => [
                                                                                'authentication_status'=>$libraryAuth,
                                                                                'authenticator'=>'',
                                                                                'date'=>'',
                                                                                ],                             
                                            'article_title'                 => $_POST['article_title'],
                                            'category'                      => $_POST['category'],
                                            'status'                        => $_POST['status'],
                                            'publish_status'                => $_POST['publish_status'],
                                            'abstract'                      => $_POST['abstract'],
                                            'authors'                       => [$_POST['authors']],
                                            'keywords'                      => $_POST['keywords'],
                                            'name_of_journal'               => $_POST['name_of_journal'],
                                            'publisher_name'                => $_POST['publisher_name'],
                                            'date_published'                => $_POST['date_published'],
                                            'journal_link'                  => $_POST['journal_link'],
                                            'article_link'                  => $_POST['article_link'],
                                            'doi'                           => $_POST['doi'],
                                            'copy_right_access'             => $_POST['copy_right_access'],
                                            'upload'                        => $newFilename,
                                            'created'                       => $created, 
                                        ];
                                                        
                                        $existingArticles[] = $newArticles;
                                                        
                                        // Update database
                                        $update = new MongoDB\Driver\BulkWrite();
                                        $update->update(['uniqueId' => $uniqueId], ['$set' => ['articles' => $existingArticles]], ['multi' => false, 'upsert' => false]);
                                                        
                                        $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                                        // verify
                                        if ($result->getModifiedCount() == 1) {
                                            /* ****************************************Create logs ****************************************************** */
                                            if($sex=='male'){
                                                $gender='his';
                                            }elseif($sex=='female'){
                                                $gender='her';
                                            }
                                            $logEvent='Added '.$_POST['category'].' to '.$gender.' list of publications.';
                                            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                                                
                                            /* ****************************************end Create logs****************************************************** */
                                
                                            echo json_encode(
                                                array('success' => 1,
                                                'status' => 200,
                                                'message' => 'Publication successfully Added.'));
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
                                /* *******************upload article************************** */
                                if (empty($_FILES['file']['tmp_name'])) {
                                    /* *******************upload article without file************************** */
                                    $data=array(

                                        'uniqueId'                      => $uniqueId,
                                        'ubsno'                         => $ubsno,                             
                                        'staffIDNo'                     => $staffIDNo,
                                        'title'                         => $title,                                                
                                        'fullName'                      => $first_name.' '.$middleName.' '.$surname,                             
                                        'biography'                     => $biography,                                                  
                                        'email'                         => $email,                                                  
                                        'orcid_no'                      => $orcid_no,                                                  
                                        'faculty'                       => strtoupper($faculty),                                        
                                        'dept'                          => strtoupper($dept),                                         
                                        'staff_category'                => strtoupper($staff_category),
                                        'created'                       => $created,                                 

                                        'articles'=>    [
                                                            [
                                            'articleId'                     =>$articleId,
                                            'library_authentication'        => [
                                                                                'authentication_status'=>$libraryAuth,
                                                                                'authenticator'=>'',
                                                                                'date'=>'',
                                                                                ],                             
                                            'article_title'                 => $_POST['article_title'],
                                            'category'                      => $_POST['category'],
                                            'status'                        => $_POST['status'],
                                            'publish_status'                => $_POST['publish_status'],
                                            'abstract'                      => $_POST['abstract'],
                                            'authors'                       => [$_POST['authors']],
                                            'keywords'                      => $_POST['keywords'],
                                            'name_of_journal'               => $_POST['name_of_journal'],
                                            'publisher_name'                => $_POST['publisher_name'],
                                            'date_published'                => $_POST['date_published'],
                                            'journal_link'                  => $_POST['journal_link'],
                                            'article_link'                  => $_POST['article_link'],
                                            'doi'                           => $_POST['doi'],
                                            'copy_right_access'             => $_POST['copy_right_access'],
                                            'upload'                        => "",
                                            'created'                       => $created, 
                                        ], 
                                    ],
                                                    
                                    );
                                    $profile='publications';
                                    // Insert member data in the database
                                    if(create_profile($data, $profile)){

                                        echo json_encode(
                                            array('success' => 1,
                                            'status' => 200,
                                            'message' => 'Publication successfully Added.'));

                                            /* ****************************************Create logs ****************************************************** */
                                            if($sex=='male'){
                                                $gender='his';
                                            }elseif($sex=='female'){
                                                $gender='her';
                                            }
                                            $logEvent='Added '.$_POST['category'].' to '.$gender.' list of publications.';
                                            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                                                
                                            /* ****************************************end Create logs****************************************************** */
                                    // }
                                    }else{
                                        echo json_encode(
                                            array('success' => 0,
                                            'status' => 400,
                                            'message' => 'Some problem occurred, please try again.'));
                                    }
                                    /* *******************end upload article without file************************** */

                                }else{
                                   /* *******************upload article with file************************** */
                                        $uploadedFile = $_FILES['file'];
                                    
                                        $originalName = $uploadedFile['name'];
                                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                    
                                        // Generate a new unique filename
                                        $newFilename =$loguniqueId .  $_POST['article_title'].'.'.$extension;
                                    
                                        // Specify the directory where the file will be uploaded
                                        $uploadDirectory = '../uploads/articles/';
                                        $extension = strtolower($extension);
                                        $allowedExtensions = array("pdf", "doc", "docx");
                                    
                                        if (!in_array($extension, $allowedExtensions)) {
                                            echo json_encode(array(
                                                'success' => 0,
                                                'status' => 422,
                                                'message' => 'File must be in PDF or Word format.'
                                            ));
                                        } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . '/' . $newFilename)) {
                                        
                                            $data=array(

                                                'uniqueId'                      => $uniqueId,
                                                'ubsno'                         => $ubsno,                             
                                                'staffIDNo'                     => $staffIDNo,
                                                'title'                         => $title,                                                
                                                'fullName'                      => $first_name.' '.$middleName.' '.$surname,                             
                                                'biography'                     => $biography,                                                  
                                                'email'                         => $email,                                                  
                                                'orcid_no'                      => $orcid_no,                                                  
                                                'faculty'                       => strtoupper($faculty),                                        
                                                'dept'                          => strtoupper($dept),                                         
                                                'staff_category'                => strtoupper($staff_category),
                                                'created'                       => $created,                                 

                                                'articles'=>[
                                                    [
                                                                [
                                                                    'articleId'                     =>$articleId,
                                                                    'library_authentication'        => [
                                                                                                        'authentication_status'=>$libraryAuth,
                                                                                                        'authenticator'=>'',
                                                                                                        'date'=>'',
                                                                                                        ],                             
                                                                    'article_title'                 => $_POST['article_title'],
                                                                    'category'                      => $_POST['category'],
                                                                    'status'                        => $_POST['status'],
                                                                    'publish_status'                => $_POST['publish_status'],
                                                                    'abstract'                      => $_POST['abstract'],
                                                                    'authors'                       => [$_POST['authors']],
                                                                    'keywords'                      => $_POST['keywords'],
                                                                    'name_of_journal'               => $_POST['name_of_journal'],
                                                                    'publisher_name'                     => $_POST['publisher_name'],
                                                                    'date_published'                => $_POST['date_published'],
                                                                    'journal_link'                  => $_POST['journal_link'],
                                                                    'article_link'                  => $_POST['article_link'],
                                                                    'doi'                           => $_POST['doi'],
                                                                    'copy_right_access'             => $_POST['copy_right_access'],
                                                                    'upload'                        => $newFilename,
                                                                    'created'                       => $created, 
                                                                ],
                                                            ],
                                                        ],                                
                                                            
                                            );
                                            $profile='publications';
                                            // Insert member data in the database
                                            if(create_profile($data, $profile)){

                                                echo json_encode(
                                                    array('success' => 1,
                                                    'status' => 200,
                                                    'message' => 'Publication successfully Added.'));

                                                    /* ****************************************Create logs ****************************************************** */
                                                    if($sex=='male'){
                                                        $gender='his';
                                                    }elseif($sex=='female'){
                                                        $gender='her';
                                                    }
                                                    $logEvent='Added '.$_POST['category'].' to '.$gender.' list of publications.';
                                                    logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                                                        
                                                    /* ****************************************end Create logs****************************************************** */
                                            // }
                                            }else{
                                                echo json_encode(
                                                    array('success' => 0,
                                                    'status' => 400,
                                                    'message' => 'Some problem occurred, please try again.'));
                                            }
                                        }
                                        /* ******************* upload article with file************************** */
                             }
                    }
    }
                








            /* ****************************************upload article****************************************************** */
            if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "uploadArticle") {
                
                    /* *******************upload article************************** */
                    $uploadedFile = $_FILES['file'];
                
                    $originalName = $uploadedFile['name'];
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                
                    // Generate a new unique filename
                    $newFilename =uniqid() . $loguniqueId .  $originalName;
                
                    // Specify the directory where the file will be uploaded
                    $uploadDirectory = '../uploads/articles';
                    $extension = strtolower($extension);
                    $allowedExtensions = array("pdf", "doc", "docx");
                
                    if (!in_array($extension, $allowedExtensions)) {
                        echo json_encode(array(
                            'success' => 0,
                            'status' => 422,
                            'message' => 'File must be in PDF or Word format.'
                        ));
                    } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . '/' . $newFilename)) {
                        // File upload successful
                
                        /* **************************update publication with article upload***************************************** */
                        //$dbname = 'unibendb';
                        $collPublications = 'publications';
                        $db = new DbManager();
                        $conn = $db->getConnection();
                        $query = ['uniqueId' => $uniqueId, "articles.tempId" =>$sessionId];
                        $option = [];
                
                        $queryDriver = new MongoDB\Driver\Query($query, $option);
                
                        $publications = $conn->executeQuery("$dbname.$collPublications", $queryDriver)->toArray();
                
                        if (count($publications) > 0) {
                            $existingArticles = $publications[0]->articles;
                
                            $filter = [
                                "uniqueId" => $uniqueId,
                                "articles.tempId" => $sessionId
                            ];
                
                            // Updated article upload filename
                            $articleUpload = $newFilename;
                
                            $update = new MongoDB\Driver\BulkWrite();
                            $update->update($filter, ['$set' => ['articles.$.upload' => $articleUpload]], ['multi' => false, 'upsert' => false]);
                
                            $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                
                            // Check if the update was successful
                            if ($result->getModifiedCount() > 0) {
                                echo json_encode(
                                    array(
                                        'success' => 1,
                                        'status' => 200,
                                        'message' => 'Publication successfully added.'
                                    )
                                );
                                $filter = [
                                    "uniqueId" => $uniqueId,
                                    "articles.tempId" => $sessionId
                                ];
                    
                                // Updated article upload filename
                                $articleUpload = $newFilename;
                    
                                $update = new MongoDB\Driver\BulkWrite();
                                $update->update($filter, ['$set' => ['articles.$.tempId' => '']], ['multi' => false, 'upsert' => false]);
                    
                                $result = $conn->executeBulkWrite("$dbname.$collPublications", $update);
                            }else{
                                echo json_encode(
                                    array('success' => 0,
                                    'status' => 400,
                                    'message' => 'Upload failed, please try again.')
                                );
                            }
                        }else{
                            echo json_encode(
                                array('success' => 0,
                                'status' => 400,
                                'message' => 'article not found.')
                            );
                        }
                    }
                }
                
                

    
