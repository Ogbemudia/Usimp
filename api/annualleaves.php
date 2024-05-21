<?php
header('Access-Control-Allow-Origin: localhost/staffprofile/');
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


http_response_code(200);

/* $currentDay = idate("d");
if($currentDay==01){ */
    //$month=date("M", strtotime('+1 month'));
    //$month='Jul';
    //$month = str_pad($month1, 2, "0", STR_PAD_LEFT);

    //$month = sprintf('%02d', $month1);
    //echo $month;
    $month=date("m", strtotime('+1 month'));

//}

include_once('../core/initialize.php');

$dbname = 'unibendb';
$collection='staff-profile';


//DB connection
$db = new DbManager();
$conn = $db->getConnection();

// read all records
//$filter = ['registry.leaveMonth'=>$month,'registry.leaveStatus'=>'inactive'];
$filter = [
    '$or'  => [
        ['registry.annualLeave.leaveMonth'=>$month,'registry.leaveStatus'=>'inactive'],
        ['registry.annualLeave.leaveMonth'=>$month,'registry.leaveType'=>'sick leave']
    ]
  ];
//$option = ['registry.leaveMonth'=>$month,'registry.leaveType'=>'sick leave'];
$option = [];
$read = new MongoDB\Driver\Query($filter, $option);

//fetch records
$records = $conn->executeQuery("$dbname.$collection", $read);

echo json_encode(iterator_to_array($records));
 