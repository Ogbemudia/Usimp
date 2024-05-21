<?php
header('Access-Control-Allow-Origin: localhost/giz/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require __DIR__.'validation.php';

require_once('../validation/classes/session.php');
require_once('functionSom.php');
require_once('logsfunc.php');
require_once('leaveApplicationFunc.php');
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

 
include_once('../core/initialize.php');
require_once('create_profile.php');
require_once('uploads.php');

$dbname = 'unibendb';

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "applyForLeave" && $_POST['uniqueId']){

    $uniqueId=$_POST['uniqueId'];
    $profile='staff-profile';
    $db = new DbManager();
    $conn = $db->getConnection();

    $filter = ['uniqueId'=>$uniqueId];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);

    //fetch records
    $records = $conn->executeQuery("$dbname.$profile", $read)->toArray();
    if (count($records) > 0) {
        $staff=json_encode($records);
        $staffRec = json_decode($staff, true);
        foreach ($staffRec as $value) {
            $userid = $value['_id']['$oid'];
            $uniqueId = $value['uniqueId'];
            $first_name = $value['first_name'];
            $surname = $value['surname'];
            $bursaryNo = $value['bursary']['bursaryNo'];
            $currentLevel = $value['registry']['currentLevel'];
            $active = $value['registry']['active'];
            $leaveType = $value['registry']['leaveType'];
            $leaveStatus = $value['registry']['leaveStatus'];
            $annualLeaveStatus = $value['registry']['annualLeave']['status'];
            $annualLeaveName = $value['registry']['annualLeave']['name'];
            /* $startDate = $value['registry']['annualLeave']['startDate'];
            $endDate = $value['registry']['annualLeave']['endDate'];
            $leaveNoDays = $value['registry']['annualLeave']['leaveNoDays'];
            $leaveDuration = $value['registry']['annualLeave']['leaveNoDays']; */
            //$resumptionDate = $value['registry']['annualLeave']['resumptionDate'];
        
        }
    }
    $currentLeaveYear = substr($annualLeaveName, 0, 4);
    $currentYear = date("Y");
    $fullName=$first_name.' '.$surname;
    //$reason=$_POST['comments'];

    if($leaveStatus==='active'){
        http_response_code(404);
        echo json_encode(
        array('success' => 0,
        'status' => 404,
        'message' => 'Hello '.$fullName.' you can not apply for any leave at this time, until you return from your '.$leaveType.'.'));
        die;
    }
    

    $datehist = date('d/m/y');
    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    
    /*  Get row data.
    proceed,
    defer,
    forfeit. */
   
    $action = $_POST['leaveApplication'];
    /* **************************************************
    Create maternity leave
    *****************************************************/
    if($action==="maternity leave"){
        $startDate=$_POST['startDate'];
        if(in_array($annualLeaveStatus,array('proceeded', 'forfeited')) && $currentLeaveYear===$currentYear){
            if($currentLevel>=6){
                $leaveNoDays=60;
            }elseif($currentLevel > 3 &&  $currentLevel < 6){
                $leaveNoDays=69;
            }elseif($currentLevel<=2){
                $leaveNoDays=75;
            }
            
        }else{
            $leaveNoDays=90;
        }
        $endDate=leaveExpire($startDate, $leaveNoDays);
        $resumptionDate=checkWeekend($endDate);
        $leaveDuration=$leaveNoDays.' days';
        $leaveName = 'maternity leave';
        $leaveAppType = 'maternity leave application';
        $leaveDetail=$_POST['application'];

        /* *******************upload doc report************************** */

        if(!empty($_FILES['docReport']['name'])){
            $filename = $_FILES['docReport']['name'];
            $file = $_FILES['docReport']['tmp_name'];
            $size = $_FILES['docReport']['size'];
            $location = "../uploads/" . $filename;
            $extension = pathinfo($location, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            $extension_arr = array("png","jpg","jpeg","pdf");
            if (!in_array($extension, $extension_arr)) {
                echo json_encode(
                    array('success' => 0,
                    'status' => 422,
                    'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                    die;
                } elseif (move_uploaded_file($file, $location)) {

                    $data = array(
                        'registry.leaveApplication.backupDoc'  =>$filename,
                    );
                    $profile = 'staff-profile';
                    $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                }
            }

        $maternityLeave=leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
        if($maternityLeave){
            /* ****************************************Create logs ****************************************************** */
            $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
            logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
            /* ****************************************end Create logs****************************************************** */
    
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leaves application successful.')); 
                    exit;
                }else{
                    http_response_code(400);
                    echo json_encode(
                    array('success' => 0,
                    'status' => 400,
                    'message' => 'Leave application failed'));
                };
           
    /* **************************************************
    Create application for sick leave
    *****************************************************/
    }elseif($action==="sick leave"){
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $leaveDuration=$_POST['leaveDuration'];
        $resumptionDate=checkWeekend($endDate);
        $leaveNoDays=0;
        $leaveName = 'sick leave';
        $leaveAppType = 'sick leave application';
        $leaveDetail=$_POST['application']; 

         /* *******************upload doc report************************** */

         if(!empty($_FILES['docReport']['name'])){
            $filename = $_FILES['docReport']['name'];
            $file = $_FILES['docReport']['tmp_name'];
            $size = $_FILES['docReport']['size'];
            $location = "../uploads/" . $filename;
            $extension = pathinfo($location, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            $extension_arr = array("png","jpg","jpeg","pdf");
            if (!in_array($extension, $extension_arr)) {
                echo json_encode(
                    array('success' => 0,
                    'status' => 422,
                    'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                    die;
                } elseif (move_uploaded_file($file, $location)) {

                    $data = array(
                        'registry.leaveApplication.backupDoc'  =>$filename,
                    );
                    $profile = 'staff-profile';
                    $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                }
            }

       
        $sickLeave=leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
        if($sickLeave){
            /* ****************************************Create logs ****************************************************** */
            $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
            logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
            /* ****************************************end Create logs****************************************************** */
           
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leave application successful.')); 
                    exit;
                }else{
                    http_response_code(400);
                    echo json_encode(
                    array('success' => 0,
                    'status' => 400,
                    'message' => 'Leaves application failed.'));
                    exit;
                };
            
    /* **************************************************
    create sabbatical leave 
    *****************************************************/
    }elseif($action==="sabbatical leave"){
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $leaveDuration=$_POST['leaveDuration'];
        $resumptionDate=checkWeekend($endDate);
        $leaveNoDays=0;
        $leaveName = 'sabbatical leave';
        $leaveAppType = 'sabbatical leave application';
        $leaveDetail=$_POST['application']; 
        
        /* *******************upload doc report************************** */

        if(!empty($_FILES['docReport']['name'])){
            $filename = $_FILES['docReport']['name'];
            $file = $_FILES['docReport']['tmp_name'];
            $size = $_FILES['docReport']['size'];
            $location = "../uploads/" . $filename;
            $extension = pathinfo($location, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
            $extension_arr = array("png","jpg","jpeg","pdf");
            if (!in_array($extension, $extension_arr)) {
                echo json_encode(
                    array('success' => 0,
                    'status' => 422,
                    'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                    die;
                } elseif (move_uploaded_file($file, $location)) {

                    $data = array(
                        'registry.leaveApplication.backupDoc'  =>$filename,
                    );
                    $profile = 'staff-profile';
                    $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                }
            }
            $sabbaticalLeave= leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
            if($sabbaticalLeave){
                /* ****************************************Create logs ****************************************************** */
                $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
                logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                /* ****************************************end Create logs****************************************************** */
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leave application successful.')); 
                    exit;
                }else{
                    http_response_code(400);
                    echo json_encode(
                    array('success' => 0,
                    'status' => 400,
                    'message' => 'Leaves application failed.'));
                    exit;
                };

            /* **************************************************
            create leave of absence
            *****************************************************/    
            }elseif($action==="leave of absence"){
                $startDate=$_POST['startDate'];
                $endDate=$_POST['endDate'];
                $leaveDuration=$_POST['leaveDuration'];
                $resumptionDate=checkWeekend($endDate);
                $leaveNoDays=0;
                $leaveName = 'leave of absence';
                $leaveAppType = 'leave absence application';
                $leaveDetail=$_POST['application']; 
                
                /* *******************upload doc report************************** */

                if(!empty($_FILES['docReport']['name'])){
                    $filename = $_FILES['docReport']['name'];
                    $file = $_FILES['docReport']['tmp_name'];
                    $size = $_FILES['docReport']['size'];
                    $location = "../uploads/" . $filename;
                    $extension = pathinfo($location, PATHINFO_EXTENSION);
                    $extension = strtolower($extension);
                    $extension_arr = array("png","jpg","jpeg","pdf");
                    if (!in_array($extension, $extension_arr)) {
                        echo json_encode(
                            array('success' => 0,
                            'status' => 422,
                            'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                            die;
                        } elseif (move_uploaded_file($file, $location)) {

                            $data = array(
                                'registry.leaveApplication.backupDoc'  =>$filename,
                            );
                            $profile = 'staff-profile';
                            $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                        }
                    }
                    $leaveofAbsence= leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
                    if($leaveofAbsence){
                        /* ****************************************Create logs ****************************************************** */
                        $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
                        logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                        /* ****************************************end Create logs****************************************************** */
                        http_response_code(200);
                        echo json_encode(
                            array('success' => 1,
                            'status' => 200,
                            'message' => 'Leave application successful.')); 
                            exit;
                        }else{
                            http_response_code(400);
                            echo json_encode(
                            array('success' => 0,
                            'status' => 400,
                            'message' => 'Leaves application failed.'));
                            exit;
                        };
                 
                  /* **************************************************
                    create training  leave with pay
                    *****************************************************/      
                 }elseif($action==="training leave with pay"){
                            $startDate=$_POST['startDate'];
                            $endDate=$_POST['endDate'];
                            $leaveDuration=$_POST['leaveDuration'];
                            $resumptionDate=checkWeekend($endDate);
                            $leaveNoDays=0;
                            $leaveName = 'training leave with pay';
                            $leaveAppType = 'application for training leave with pay';
                            $leaveDetail=$_POST['application']; 
                            
                            /* *******************upload doc report************************** */

                            if(!empty($_FILES['docReport']['name'])){
                                $filename = $_FILES['docReport']['name'];
                                $file = $_FILES['docReport']['tmp_name'];
                                $size = $_FILES['docReport']['size'];
                                $location = "../uploads/" . $filename;
                                $extension = pathinfo($location, PATHINFO_EXTENSION);
                                $extension = strtolower($extension);
                                $extension_arr = array("png","jpg","jpeg","pdf");
                                if (!in_array($extension, $extension_arr)) {
                                    echo json_encode(
                                        array('success' => 0,
                                        'status' => 422,
                                        'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                                        die;
                                    } elseif (move_uploaded_file($file, $location)) {

                                        $data = array(
                                            'registry.leaveApplication.backupDoc'  =>$filename,
                                        );
                                        $profile = 'staff-profile';
                                        $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                                    }
                                }
                                $trainLeavewithPay= leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
                                if($trainLeavewithPay){
                                    /* ****************************************Create logs ****************************************************** */
                                    $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
                                    logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                                    /* ****************************************end Create logs****************************************************** */
                                    http_response_code(200);
                                    echo json_encode(
                                        array('success' => 1,
                                        'status' => 200,
                                        'message' => 'Leave application successful.')); 
                                        exit;
                                    }else{
                                        http_response_code(400);
                                        echo json_encode(
                                        array('success' => 0,
                                        'status' => 400,
                                        'message' => 'Leaves application failed.'));
                                        exit;
                                    };
                                    /* **************************************************
                                    create training leave without pay
                                    *****************************************************/      
                                }elseif($action==="training leave without pay"){
                                            $startDate=$_POST['startDate'];
                                            $endDate=$_POST['endDate'];
                                            $leaveDuration=$_POST['leaveDuration'];
                                            $resumptionDate=checkWeekend($endDate);
                                            $leaveNoDays=0;
                                            $leaveName = 'training leave without pay';
                                            $leaveAppType = 'application for training leave without pay';
                                            $leaveDetail=$_POST['application']; 
                                            
                                            /* *******************upload doc report************************** */

                                            if(!empty($_FILES['docReport']['name'])){
                                                $filename = $_FILES['docReport']['name'];
                                                $file = $_FILES['docReport']['tmp_name'];
                                                $size = $_FILES['docReport']['size'];
                                                $location = "../uploads/" . $filename;
                                                $extension = pathinfo($location, PATHINFO_EXTENSION);
                                                $extension = strtolower($extension);
                                                $extension_arr = array("png","jpg","jpeg","pdf");
                                                if (!in_array($extension, $extension_arr)) {
                                                    echo json_encode(
                                                        array('success' => 0,
                                                        'status' => 422,
                                                        'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                                                        die;
                                                    } elseif (move_uploaded_file($file, $location)) {

                                                        $data = array(
                                                            'registry.leaveApplication.backupDoc'  =>$filename,
                                                        );
                                                        $profile = 'staff-profile';
                                                        $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                                                    }
                                                }
                                                $trainLeavewithPay= leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
                                                if($trainLeavewithPay){
                                                    /* ****************************************Create logs ****************************************************** */
                                                    $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
                                                    logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                                                    /* ****************************************end Create logs****************************************************** */
                                                    http_response_code(200);
                                                    echo json_encode(
                                                        array('success' => 1,
                                                        'status' => 200,
                                                        'message' => 'Leave application successful.')); 
                                                        exit;
                                                    }else{
                                                        http_response_code(400);
                                                        echo json_encode(
                                                        array('success' => 0,
                                                        'status' => 400,
                                                        'message' => 'Leaves application failed.'));
                                                        exit;
                                                    }; 
                                   /* **************************************************
                                    create study leave without pay
                                    *****************************************************/      
                                }elseif($action==="study leave without pay"){
                                            $startDate=$_POST['startDate'];
                                            $endDate=$_POST['endDate'];
                                            $leaveDuration=$_POST['leaveDuration'];
                                            $resumptionDate=checkWeekend($endDate);
                                            $leaveNoDays=0;
                                            $leaveName = 'study leave without pay';
                                            $leaveAppType = 'application for study leave without pay';
                                            $leaveDetail=$_POST['application']; 
                                            
                                            /* *******************upload doc report************************** */

                                            if(!empty($_FILES['docReport']['name'])){
                                                $filename = $_FILES['docReport']['name'];
                                                $file = $_FILES['docReport']['tmp_name'];
                                                $size = $_FILES['docReport']['size'];
                                                $location = "../uploads/" . $filename;
                                                $extension = pathinfo($location, PATHINFO_EXTENSION);
                                                $extension = strtolower($extension);
                                                $extension_arr = array("png","jpg","jpeg","pdf");
                                                if (!in_array($extension, $extension_arr)) {
                                                    echo json_encode(
                                                        array('success' => 0,
                                                        'status' => 422,
                                                        'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                                                        die;
                                                    } elseif (move_uploaded_file($file, $location)) {

                                                        $data = array(
                                                            'registry.leaveApplication.backupDoc'  =>$filename,
                                                        );
                                                        $profile = 'staff-profile';
                                                        $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                                                    }
                                                }
                                                $trainLeavewithPay= leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
                                                if($trainLeavewithPay){
                                                    /* ****************************************Create logs ****************************************************** */
                                                    $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
                                                    logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                                                    /* ****************************************end Create logs****************************************************** */
                                                    http_response_code(200);
                                                    echo json_encode(
                                                        array('success' => 1,
                                                        'status' => 200,
                                                        'message' => 'Leave application successful.')); 
                                                        exit;
                                                    }else{
                                                        http_response_code(400);
                                                        echo json_encode(
                                                        array('success' => 0,
                                                        'status' => 400,
                                                        'message' => 'Leaves application failed.'));
                                                        exit;
                                                    };
                                    /* **************************************************
                                    create study leave with pay
                                    *****************************************************/      
                                }elseif($action==="study leave with pay"){
                                            $startDate=$_POST['startDate'];
                                            $endDate=$_POST['endDate'];
                                            $leaveDuration=$_POST['leaveDuration'];
                                            $resumptionDate=checkWeekend($endDate);
                                            $leaveNoDays=0;
                                            $leaveName = 'study leave with pay';
                                            $leaveAppType = 'application for study leave with pay';
                                            $leaveDetail=$_POST['application']; 
                                            
                                            /* *******************upload doc report************************** */

                                            if(!empty($_FILES['docReport']['name'])){
                                                $filename = $_FILES['docReport']['name'];
                                                $file = $_FILES['docReport']['tmp_name'];
                                                $size = $_FILES['docReport']['size'];
                                                $location = "../uploads/" . $filename;
                                                $extension = pathinfo($location, PATHINFO_EXTENSION);
                                                $extension = strtolower($extension);
                                                $extension_arr = array("png","jpg","jpeg","pdf");
                                                if (!in_array($extension, $extension_arr)) {
                                                    echo json_encode(
                                                        array('success' => 0,
                                                        'status' => 422,
                                                        'message' => 'File must be in png, jpg, jpeg or pdf format.'));
                                                        die;
                                                    } elseif (move_uploaded_file($file, $location)) {

                                                        $data = array(
                                                            'registry.leaveApplication.backupDoc'  =>$filename,
                                                        );
                                                        $profile = 'staff-profile';
                                                        $uploadReport=update_by_uniqueId($data,  $profile, $uniqueId);                    
                                                    }
                                                }
                                                $trainLeavewithPay= leaveApplication($created, $leaveName, $leaveNoDays, $leaveAppType, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId);
                                                if($trainLeavewithPay){
                                                    /* ****************************************Create logs ****************************************************** */
                                                    $logEvent='Applied for '.$leaveName.' for: '.$fullName. '.';
                                                    logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                                                    /* ****************************************end Create logs****************************************************** */
                                                    http_response_code(200);
                                                    echo json_encode(
                                                        array('success' => 1,
                                                        'status' => 200,
                                                        'message' => 'Leave application successful.')); 
                                                        exit;
                                                    }else{
                                                        http_response_code(400);
                                                        echo json_encode(
                                                        array('success' => 0,
                                                        'status' => 400,
                                                        'message' => 'Leaves application failed.'));
                                                        exit;
                                                    };
    }else{

        http_response_code(403);
        echo json_encode(
        array('success' => 0,
        'status' => 403,
        'message' => 'This action is not permitted'));
        exit;
    }



}


