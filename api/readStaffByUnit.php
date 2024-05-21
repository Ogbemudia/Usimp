<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require('validation.php');

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

$accRights=array('staff');
$accPrivilege=array('view');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}
require_once('../core/initialize.php');
require_once('logsfunc.php');

$dbname = 'unibendb';
$collection='staff-profile';

$unit = isset($_GET['unit'])? $_GET['unit'] : die();

//DB connection
$db = new DbManager();
$conn = $db->getConnection();

// read all records
$filter = ['registry.unit'=>$unit];
$option = [];
$read = new MongoDB\Driver\Query($filter, $option);

//fetch records
$records = $conn->executeQuery("$dbname.$collection", $read)->toArray();
if (count($records) > 0) {
    unset($records[0]->registry->promotion);
}
echo json_encode($records);

//echo json_encode(iterator_to_array($records));