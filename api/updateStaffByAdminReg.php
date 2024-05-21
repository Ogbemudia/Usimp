<?php
header('Access-Control-Allow-Origin: localhost/giz/');
header('Content-Type: application/json, charset=UTF-8');
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
include_once('sendverify.php');
require_once('../validation/library.php');
require_once('create_profile.php');
require_once('uploads.php');



$dbname = 'unibendb';

$uniqueId= isset($_POST['uniqueId'])? $_POST['uniqueId'] : die();
//if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update"){
    
    $dbname = 'unibendb';
    $collection = 'userlogin';
    $date1 = date("F j, Y"); 
    $tim = date("g:i a");
    $created = $date1. " at ".$tim;
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
    /* *******************upload cv************************** */
    if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "update_cv") {
        $uploadedFile = $_FILES['file'];
        
        $originalName = $uploadedFile['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        
        // Generate a new unique filename
        $newFilename = uniqid() . '_cv_' . $loguniqueId . '.' . $extension;
        
        // Specify the directory where the file will be uploaded
        $uploadDirectory = '../uploads/cv/';
        $extension = strtolower($extension);
        $extension_arr = array("pdf", "doc", "docx");
        
        if (!in_array($extension, $extension_arr)) {
            echo json_encode(array(
                'success' => 0,
                'status' => 422,
                'message' => 'File must be in PDF or Word format.'
            ));
        } elseif (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $newFilename)) {
            // File upload successful
            $data = array(
                'staff_profile.cv' => $newFilename,
                'last_updated'                  => $created,                                

            );
            
            $profile = 'staff-profile';
            $updateProfile = update_by_uniqueId($data, $profile, $uniqueId);

            /* ****************************************Create logs ****************************************************** */
            $logEvent='Updated '.$fullName1.' Curriculum Vitae.';
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
        
        $originalName = $uploadedFile['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        
        // Generate a new unique filename
        $newFilename = uniqid() . '_profile_pic_' . $loguniqueId . '.' . $extension;
        
        // Specify the directory where the file will be uploaded
        $uploadDirectory = '../uploads/cv/';
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
                'staff_profile.picName' => $newFilename,
                'last_updated'          => $created,                                

            );
            
            $profile = 'staff-profile';
            $updateProfile = update_by_uniqueId($data, $profile, $uniqueId);
            
            /* ****************************************Create logs ****************************************************** */
            $logEvent='Updated '.$fullName1.' profile picture.';
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
                                    'title'                         => $_POST['title'],                                                
                                    'first_name'                    => $_POST['firstName'],                                               
                                    'middleName'                    => $_POST['middleName'],
                                    'surname'                       => $_POST['surname'], 
                                    'last_updated'                  => $created,                                
                                    
                                    'staff_profile.sex'                 => $_POST['sex'],                             
                                    'staff_profile.dOb'                 => $_POST['dOb'],                             
                                    'staff_profile.placeOfBirth'        => $_POST['placeOfBirth'],                                                  
                                    'staff_profile.marritalStatus'      => $_POST['marritalStatus'],                                                  
                                    'staff_profile.staffCat'            => strtolower($_POST['staffCat']),                                            
                                    /* 'staff_profile.picName'             => '',//$picName,                                        
                                    'staff_profile.cv'                  => '',//$picName, */                                        
                                    //'staff_profile.biography'           => '',//$picName,                                        
                                    'staff_profile.awards'              => [$_POST['awards']],//$picName,                                        
                                    'staff_profile.professional_qual'   => [$_POST['professional_qual']],//$picName,                                        
                                    'staff_profile.staffIDNo'           => $_POST['staffIDNo'],                                        
                                    'staff_profile.orcid_no'            => $_POST['orcid_no'],                                        
                                    'staff_profile.nationality'         => $_POST['nationality'],                                        
                                    
                                    'contact.LGAofOrigin'               => $_POST['LGAofOrigin'],                                        
                                    'contact.stateOfOrigin'             => $_POST['stateOfOrigin'],                                        
                                    'contact.geoPoliticalZone'          => $_POST['geoPoliticalZone'],
                                    'contact.wardOfOrigin'              => $_POST['wardOfOrigin'],  
                                    'contact.stateOfRes'                => $_POST['stateOfRes'],                    
                                    'contact.LGAOfRes'                  => $_POST['LGAOfRes'],                            
                                    'contact.PAddress'                  => $_POST['PAddress'],               
                                    'contact.Caddress'                  => $_POST['Caddress'],                                 
                                    'contact.phone'                     => $_POST['phone'],               
                                    'contact.email'                     => $_POST['email'],
                                    'spouse.spouseFirstName'           => $_POST['spouseFirstName'],                                        
                                    'spouse.spouseMiddleName'          => $_POST['spouseMiddleName'],                                        
                                    'spouse.spouseLastName'            => $_POST['spouseLastName'],                    
                                    'spouse.spouseOccupation'          => $_POST['spouseOccupation'],                            
                                    'spouse.spousePlaceOfWork'         => $_POST['spousePlaceOfWork'],               
                                    'spouse.spouseResedentialAddress'  => $_POST['spouseResedentialAddress'],                                 
                                    'spouse.spousePhone'               => $_POST['spousePhone'],               
                                
                                    'children.child1.childName'           => $_POST['childName1'],                                        
                                    'children.child1.date_of_Birth'       => $_POST['childBirth1'],                                        
                                    'children.child1.childSex'            => $_POST['childSex1'],                    
                                    
                                    'children.child2.childName'           => $_POST['childName2'],                                        
                                    'children.child2.date_of_Birth'       => $_POST['childBirth2'],                                        
                                    'children.child2.childSex'            => $_POST['childSex2'],                    
                                        
                                    'children.child3.childName'           => $_POST['childName3'],                                        
                                    'children.child3.date_of_Birth'       => $_POST['childBirth3'],                                        
                                    'children.child3.childSex'            => $_POST['childSex3'],                    
                                        
                                    'children.child4.childName'           => $_POST['childName4'],                                        
                                    'children.child4.date_of_Birth'       => $_POST['childBirth4'],                                        
                                    'children.child4.childSex'            => $_POST['childSex4'],                    
                                        
                                
                                    'next_of_kin.NokFirstName'           => $_POST['NokFirstName'],                                        
                                    'next_of_kin.NokMiddleName'          => $_POST['NokMiddleName'],                                        
                                    'next_of_kin.NokLastName'            => $_POST['NokLastName'],                                        
                                    'next_of_kin.relationship'           => $_POST['relationship'],                                        
                                    'next_of_kin.NokSex'                 => $_POST['NokSex'],                            
                                    'next_of_kin.NokContackAdd'          => $_POST['NokContackAdd'],                            
                                    'next_of_kin.NokAddress'             => $_POST['NokAddress'],               
                                    'next_of_kin.NokEmail'               => $_POST['NokEmail'],                                 
                                    'next_of_kin.NokPhoneNumber'         => $_POST['NokPhoneNumber'],                
                                    //'registry'=>[
                                        'registry.PFileNo'               => $_POST['PFileNo'],                                        
                                        'registry.faculty'               => strtolower($_POST['faculty']),                                        
                                        'registry.dept'                  => strtolower($_POST['dept']),                    
                                        'registry.unit'                  => strtolower($_POST['unit']),                            
                                        'registry.designation'           => strtolower($_POST['designation']), 
                                        'registry.designationOnFirstApp' => strtolower($_POST['designationOnFirstApp']),               
                                        'registry.postion'               => $_POST['postion'],                                 
                                        'registry.areaOfSpecialization'  => $_POST['areaOfSpecialization'],
                                        'registry.acadQuatnsWithDate'    => $_POST['acadQuatnsWithDate'], 
                                        'registry.certification'         => $_POST['certification'],                                        
                                        'registry.PHYCHAL'               => $_POST['PHYCHAL'],                                        
                                        'registry.union'                 => $_POST['union'],                    
                                        'registry.date_employed'         => $_POST['date_employed'],                            
                                        'registry.date_confirmed'        => $_POST['date_confirmed'],               
                                        'registry.staff_Status'          => $_POST['staff_Status'],                                 
                                        'registry.dateOfAssumptionOfDuty'=> $_POST['dateOfAssumptionOfDuty'],
                                        'registry.cadreOn1stApp'         => strtolower($_POST['cadreOn1stApp']),                                        
                                        'registry.levelOn1stApp'         => $_POST['levelOn1stApp'],                                        
                                        'registry.stepOn1stApp'          => $_POST['stepOn1stApp'],                    
                                        'registry.currentLevel'          => $_POST['currentLevel'],                            
                                        'registry.currentStep'           => $_POST['currentStep'],               
                                        'registry.currentCadre'          => $_POST['currentCadre'],                                 
                                        'registry.incStep'               => $_POST['incStep'],
                                        'registry.dateOfRetirementByAge' => $_POST['dateOfRetirementByAge'],                                        
                                        'registry.dateOfRetirementByEmpl'=> $_POST['dateOfRetirementByEmpl'],                                        
                                        'registry.dateOfRetirement'      => $_POST['dateOfRetirement'],                    
                                        'registry.exitDates'             => $_POST['exitDates'],                    
                                        'registry.dateOfResignation'     => $_POST['dateOfResignation'],
                                        'registry.employmentHist'        => $_POST['employmentHist'],               
                                        'registry.promotion.lastPromDate'=> $_POST['lastPromDate'],               
                            
                                        //'registry.leaveStatus'           => $leaveStatus,               
                                        //'registry.leaveType'             => $leaveType,                                 
                                        'registry.leaveMonth'            => $_POST['leaveMonth'],                                 
                                        'registry.leaveDay'              => $_POST['leaveDay'],                                 
                                        //'registry.leaveEffectDate'       => $leaveEffectDate,
                                        //'registry.leaveResumeDate'       => $leaveResumeDate,
                                        //'registry.leaveNoDays'           => $leaveNoDays,                                        
                                        //'registry.leaveExpireDate'       => $leaveExpireDate,                                        
                                        'registry.groupCadre'            => $_POST['groupCadre'],                    
                                        'registry.staffType'             => strtolower($_POST['staffType']),                    
                                        'registry.initial'               => $_POST['initial'],                            
                                        'registry.CONTSRTDATE'           => $_POST['CONTSRTDATE'],               
                                        'registry.CONTEXPDATE'           => $_POST['CONTEXPDATE'],                                 
                                        'registry.NoOfRenewedContract'   => $_POST['NoOfRenewedContract'],               
                                    //],
                                        'bursary.bursaryNo'             => $_POST['bursaryNo'],                                        
                                        'bursary.IPPIS_NO'              => $_POST['IPPIS_NO'],                                        
                                        'bursary.staff_category'        => strtolower($_POST['staff_category']),                    
                                        'bursary.bankName'              => $_POST['bankName'],                            
                                        'bursary.accountNumber'         => $_POST['accountNumber'],               
                                        'bursary.PFANAme'               => $_POST['PFANAme'],                                 
                                        'bursary.PENSION_PIN'           => $_POST['PENSION_PIN'],               
                                        
                                        'NHIS.NHIS_code'                => $_POST['NHIS_code'],               
                                        'NHIS.HMO'                      => $_POST['HMO'],               
                                        'NHIS.primary_health_care'      => $_POST['primary_health_care'],
                                        
                                        'cooperative.cooperative_name'  => $_POST['cooperative_name'],               
                                        'cooperative.cooperative_id'    => $_POST['cooperative_id'],               
                            );
                        $profile='staff-profile';
                        // Insert member data in the database
                       if (update_by_uniqueId($data,  $profile, $uniqueId)){
                         /*************************update login ********************************/
                         $fullName= $_POST['firstName'].' '.$_POST['middleName'].' '.$_POST['surname'];
                         $document = array(

                            //"uniqueId"                 => $uniqueId,
                            "fullName"                 => $fullName,
                            "email"                    => $_POST['email'],
                            //"password"                 => $password,
                            "bursaryNo"                => $_POST['bursaryNo'],
                            "staffIDNo"                => $_POST['staffIDNo'],
                            "designation"              => $_POST['designation'],
                            "postion"                  => $_POST['postion'],
                            //"signature"                => '',
                            "status"                   => 'active',
                            //"email_v_code"             => $verifyCode,
                            //"role"                     => $role,
                            "sex"                      => $_POST['sex'],
                            //"created"                  => $created,
                            "last_update"              => $created
                         );
                         
                         updateWithuniqueId($document, $collection, $uniqueId);

                        /*************************update publication********************************/
                        $collPublication= 'publication';
                        //DB connection
                        $db = new DbManager();
                        $conn = $db->getConnection();
                        $query = ['uniqueId'=>$uniqueId];
                        $option = [];
                        $queryDriver = new MongoDB\Driver\Query($query, $option);
                        $pubResult = $conn->executeQuery("$dbname.$collPublication", $queryDriver)->toArray();
                    
                        if (count($pubResult) > 0) {
                            $pubData=array(

                                //'uniqueId'                      => $uniqueId,
                                'ubsno'                         => $_POST['ubsno'],                             
                                'staffIDNo'                     => $_POST['staffIDNo'],
                                'title'                         => $_POST['title'],                                                
                                'fullName'                      => $fullName,                             
                                'biography'                     => $_POST['biography'],                                                  
                                'email'                         => $_POST['email'],                                                  
                                //'orcid_no'                      => '',                                                  
                                'faculty'                       => strtoupper($_POST['faculty']),                                        
                                'dept'                          => strtoupper($_POST['dept']),                                         
                                'staff_category'                => strtoupper($_POST['staff_category']),
                                'last_update'                       => $created,   
                            );  
                            $update = new MongoDB\Driver\BulkWrite();
                            $update->update(['uniqueId' => $uniqueId], ['$set' => $pubData], ['multi' => false, 'upsert' => false]);
                        }

                        /*************************end update publication********************************/

                        /*************************end update login********************************/
                        /* ****************************************Create logs ****************************************************** */
                        $logEvent='Updated '.$fullName1.' profile picture.';
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
                }else{
                    echo json_encode(
                        array('success' => 0,
                              'status' => 400,
                              'message' => 'This staff does not exist.'));
                }
    
}