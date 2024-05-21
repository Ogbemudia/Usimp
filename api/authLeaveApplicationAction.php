<?php
header('Access-Control-Allow-Origin: localhost/giz/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require_once('validation.php');
require_once('../validation/classes/session.php');
require_once('functionSom.php');
require_once('logsfunc.php');
require_once('leaveApplicationAction.php');
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

$accRights=array('admin', 'hod', 'acting_hod', 'dean', 'acting_dean', 'director', 'acting_director', 'registrar', 'acting_registrar', 'dvc_admin', 'acting_dvc_admin', 'dvc_acad', 'acting_dvc_acad', 'provost', 'acting_provost');
$accPrivilege=array('approve');
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

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "actOnLeave" && $_POST['uniqueId']){

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
            $leaveDetail = $value['registry']['leaveApplication']['leaveDetail'];
            $applicationDate = $value['registry']['leaveApplication']['applicationDate'];
            $leaveName = $value['registry']['leaveApplication']['leaveName'];
            $leaveAppType = $value['registry']['leaveApplication']['leaveAppType'];
            $hodResponse = $value['registry']['leaveApplication']['hodResponse'];
            $hodComment = $value['registry']['leaveApplication']['hodComment'];
            $hodResponse_date = $value['registry']['leaveApplication']['hodResponse_date'];
            $directorResponse = $value['registry']['leaveApplication']['directorResponse'];
            $directorComment = $value['registry']['leaveApplication']['directorComment'];
            $directorResponse_date = $value['registry']['leaveApplication']['directorResponse_date'];
        }
    }
    /* $currentLeaveYear = substr($leaveName, 0, 4);
    $currentYear = date("Y");
    $accuLeaveDuration1=$accuLeaveDuration+$leaveDuration; */
    $fullName=$first_name.' '.$surname;
    //$reason=$_POST['comments'];

    if($leaveStatus!=='pending'){
        http_response_code(404);
        echo json_encode(
        array('success' => 0,
        'status' => 404,
        'message' =>$fullName.' has not apply for any leave.'));
        die;
    }
    

    $datehist = date('d/m/y');
    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    
    /* **************************************************
    Hod action on leave
    *****************************************************/
    //if($role4==="hod"){
    if(in_array("hod",array($logRight))){
        $startDate=$_POST['startDate'];
        $endDate=$_POST['endDate'];
        $leaveDuration=$_POST['leaveDuration'];
        $resumptionDate=checkWeekend($endDate);
        $leaveNoDays=0;
        $hodResponse=$_POST['hodResponse'];
        $hodComment=$_POST['hodComment'];
        $hodResponse_date=$created;

        $maternityLeave=leaveApplicationActionByHOD($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId, $hodResponse, $hodComment, $hodResponse_date, $fullName, $bursaryNo, $applicationDate);
        if($maternityLeave){
             /* ****************************************Create logs ****************************************************** */
             $logEvent= $hodResponse.' '.$fullName.' '.$leaveName. ' application.';
             logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
             /* ****************************************end Create logs****************************************************** */
     
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leaves application was successfully'.$hodResponse.'.')); 
                    exit;
                }else{
                    http_response_code(400);
                    echo json_encode(
                    array('success' => 0,
                    'status' => 400,
                    'message' => 'An error has occured try again later.'));
                };
                
                
                /* **************************************************
                director action on leave
                *****************************************************/
            }elseif(in_array($role4,array('dean', 'director', 'dvc_admin', 'dvc_acad'))){
                    $startDate=$_POST['startDate'];
                    $endDate=$_POST['endDate'];
                    $leaveDuration=$_POST['leaveDuration'];
                    $resumptionDate=checkWeekend($endDate);
                    $leaveNoDays=0;
                    $directorResponse=$_POST['directorResponse'];
                    $directorComment=$_POST['directorComment'];
                    $directorResponse_date=$created;

                    $maternityLeave=leaveApplicationActionByDirector($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId, $hodResponse, $hodComment, $hodResponse_date, $directorResponse, $directorComment, $directorResponse_date, $fullName, $bursaryNo, $applicationDate);
                    if($maternityLeave){
                        /* ****************************************Create logs ****************************************************** */
                        $logEvent= $directorResponse.' '.$fullName.' '.$leaveName. ' application.';
                        logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                        /* ****************************************end Create logs****************************************************** */
                
                            http_response_code(200);
                            echo json_encode(
                                array('success' => 1,
                                'status' => 200,
                                'message' => 'Leaves application was successfully'.$dirResponse.'.')); 
                                exit;
                            }else{
                                http_response_code(400);
                                echo json_encode(
                                array('success' => 0,
                                'status' => 400,
                                'message' => 'An error has occured try again later.'));
                            };
                       
                        
                        /* **************************************************
                        Registrar action on leave
                        *****************************************************/
                    }elseif($role4==="registrar"){
                            $startDate=$_POST['startDate'];
                            $endDate=$_POST['endDate'];
                            $leaveDuration=$_POST['leaveDuration'];
                            $resumptionDate=checkWeekend($endDate);
                            $leaveNoDays=0;
                            $registrarResponse=$_POST['registrarResponse'];
                            $registrarComment=$_POST['registrarComment'];
                            $registrarResponse_date=$created;

                            $maternityLeave=leaveApplicationActionByRegistrar($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId, $hodResponse, $hodComment, $hodResponse_date, $directorResponse, $directorComment, $directorResponse_date, $registrarResponse, $registrarComment, $registrarResponse_date, $fullName, $bursaryNo, $applicationDate);
                            if($maternityLeave){
                                /* ****************************************Create logs ****************************************************** */
                                $logEvent= $registrarResponse.' '.$fullName.' '.$leaveName. ' application.';
                                logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                /* ****************************************end Create logs****************************************************** */
                        
                                    http_response_code(200);
                                    echo json_encode(
                                        array('success' => 1,
                                        'status' => 200,
                                        'message' => 'Leaves application was successfully'.$dirResponse.'.')); 
                                        exit;
                                    }else{
                                        http_response_code(400);
                                        echo json_encode(
                                        array('success' => 0,
                                        'status' => 400,
                                        'message' => 'An error has occured try again later.'));
                                    };
                                }
                                
           
    }else{

        http_response_code(403);
        echo json_encode(
        array('success' => 0,
        'status' => 403,
        'message' => 'This action is not permitted'));
        exit;
    }



//}


