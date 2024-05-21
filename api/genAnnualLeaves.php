<?php
header('Access-Control-Allow-Origin: localhost/staffprofile/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require_once('validation.php');
require_once('../validation/classes/session.php');
require_once('functionSom.php');
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

$accRights=array('admin', 'registry', 'gen_duties');
$accPrivilege=array('create');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}
require_once('logsfunc.php');



$date1 = date("F j, Y");
$year = date("Y");
$nextYear=date("Y", strtotime('+1 year'));

$month=date("m", strtotime('+1 month'));
//$month=date("M", strtotime('+1 month'));
$lMonth=date("m", strtotime('+1 month'));

require_once('logsfunc.php');   
include_once('../core/initialize.php');
include_once('sendverify.php');
require_once('create_profile.php');

$dbname = 'unibendb';
$collection='staff-profile';

/* getting singnature */
if($role4 =='gen_duties_signLeave'){
    $userLogin22='userlogin';
    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();

    $filter = ['uniqueId'=>$loguniqueId];
    $option = [];
    $read = new MongoDB\Driver\Query($filter, $option);

    //fetch records
    $records = $conn->executeQuery("$dbname.$userLogin22", $read)->toArray();
    if (count($records) > 0) {
        $staff=json_encode($records);
        $staffRec = json_decode($staff, true);
        foreach ($staffRec as $value) {
            $userid = $value['_id']['$oid'];
            $fullName = $value['fullName'];
            $designation = $value['designation'];
            $signature = $value['signature'];
        
        }
    }
}else{
    $fullName = '';
    $designation ='';
    $signature = '';
}



/* getting staff eligible for leave */
//DB connection
$db = new DbManager();
$conn = $db->getConnection();

$filter = [
    '$or'  => [
        ['registry.annualLeave.leaveMonth'=>$month,'registry.leaveStatus'=>'inactive'],
        ['registry.annualLeave.leaveMonth'=>$month,'registry.leaveType'=>'sick leave']
    ]
  ];
$option = [];
$read = new MongoDB\Driver\Query($filter, $option);

//fetch records
$records = $conn->executeQuery("$dbname.$collection", $read)->toArray();
if(count($records)>0){
    //$staffNo=count($records);
    //for ($i = 1; $i <= $staffNo; $i++) {

    $staff=json_encode($records);
    $staffRec = json_decode($staff, true);
    foreach ($staffRec as $value) {
        $userid = $value['_id']['$oid'];
        $uniqueId = $value['uniqueId'];
        $title = $value['title'];
        $first_name = $value['first_name'];
        $surname = $value['surname'];
        $currentLevel = $value['registry']['currentLevel'];
        $leaveMonth = $value['registry']['annualLeave']['leaveMonth'];
        $activeLeave = $value['registry']['activeLeave'];
        $leaveDay = $value['registry']['annualLeave']['leaveDay'];
        $currentLevel = $value['registry']['currentLevel'];
        $staffType = $value['registry']['staffType'];
        $mailSend = $value['contact']['email'];
        //$mailSend = 'ogbemudia.edoseghe@uniben.edu';
        $dept = $value['registry']['dept'];
        $bursaryNo = $value['bursary']['bursaryNo'];
        $bursaryNo1 = strToUpper($bursaryNo);

    if($staffType=='non-academic'){
        if($currentLevel>=6){
            $noLeaveDays=30;
            $startDate=$year.'-'.$leaveMonth.'-'.$leaveDay;
            $endDate=leaveExpire($startDate, $noLeaveDays);
            $resumptionDate=checkWeekend($endDate);
        }elseif($currentLevel > 3 &&  $currentLevel < 6){
            $noLeaveDays=21;
            $startDate=$year.'-'.$leaveMonth.'-'.$leaveDay;
            $endDate=leaveExpire($startDate, $noLeaveDays);
            $resumptionDate=checkWeekend($endDate);
        }elseif($currentLevel<=2){
            $noLeaveDays=15;
            $startDate=$year.'-'.$leaveMonth.'-'.$leaveDay;
            $endDate=leaveExpire($startDate, $noLeaveDays);
            $resumptionDate=checkWeekend($endDate);
        }
    }elseif($staffType=='academic'){
        $noLeaveDays=30;
        $startDate=$year.'-'.$leaveMonth.'-'.$leaveDay;
        $endDate=leaveExpire($startDate, $noLeaveDays);
        $resumptionDate=checkWeekend($endDate);
    }

    // Update staff records
    $leaveEffectDate=$leaveDay.' '.$leaveMonth.', '.$year;
    $data=array(
        'registry.annualLeave.status'     => 'available',  
        'registry.annualLeave.name'       => $year.' Annual Leave',  
        'registry.annualLeave.startDate'  => $leaveEffectDate,  
        'registry.annualLeave.endDate'     => $endDate,  
        'registry.annualLeave.leaveNoDays' => (int) $noLeaveDays,  
        'registry.annualLeave.resumptionDate' => $resumptionDate,  
        //'registry.annualLeave.leaveDay' => '',  
       // 'registry.annualLeave.leaveMonth' => '',  
    ); 
    
    $profile='staff-profile';
    update_by_uniqueId($data,  $profile, $uniqueId);
    // end Update staff records

   $mailS= 'RE: ANNUAL LEAVE';
    $fromName= 'Uniben Genral Duties';
    $mailB= 'Your '.$year.'/ '. $nextYear.' Annual Leave is attached below.';
   sendAuth($mailSend, $mailS, $mailB, $fromName, $bursaryNo1, $date1, $title, $surname, $first_name, $dept, $leaveDay, $leaveMonth, $year, $nextYear, $noLeaveDays, $endDate, $resumptionDate, $signature, $fullName, $designation); 
   
}
http_response_code(200);
 /* ****************************************Create logs ****************************************************** */
 $logEvent='Generated and signed annual leave letters for the month of '.$month.', '.$year.'.';
 logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
    
/* ****************************************end Create logs****************************************************** */

echo json_encode(
    array('success' => 1,
    'status' => 200,
    'message' => 'Leaves successfully signed and send.'));

}