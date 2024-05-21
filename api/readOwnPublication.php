<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require_once('validation.php');
require_once('../validation/classes/session.php');
//require_once('functionSom.php');
//require_once('logsfunc.php');
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

$accRights=array('staff',);
$accPrivilege=array('view');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}

include_once('../core/initialize.php');

$dbname = 'unibendb';
$collection='publications';

//$uniqueId = isset($_GET['uniqueId'])? $_GET['uniqueId'] : die();

//DB connection
$db = new DbManager();
$conn = $db->getConnection();

// read all records
$filter = ['uniqueId'=>$loguniqueId];
$option = [];
$read = new MongoDB\Driver\Query($filter, $option);

//fetch records
$records = $conn->executeQuery("$dbname.$collection", $read);

echo json_encode(iterator_to_array($records));