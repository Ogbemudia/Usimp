<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
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


include_once('../core/initialize.php');
require_once('logsfunc.php');

$dbname = 'unibendb';
$collection='staff-profile';


//DB connection
$db = new DbManager();
$conn = $db->getConnection();

// read all records
$filter = ['uniqueId'=>$loguniqueId];
$option = [];
$read = new MongoDB\Driver\Query($filter, $option);

//fetch records
$records = $conn->executeQuery("$dbname.$collection", $read)->toArray();
if (count($records) > 0) {
    unset($records[0]->registry->promotion);
}
echo json_encode($records);
//echo json_encode(iterator_to_array($records));
/* ****************************************Create logs ****************************************************** */
/* if($sex=='male'){
    $gender='his';
}elseif($sex=='female'){
    $gender='her';
}
$logEvent='logged into '.$gender.'profile page.';
logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail); */
                    
/* ****************************************end Create logs****************************************************** */