<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require __DIR__.'validation.php';

require_once('../validation/classes/session.php');
login();


include_once('../core/initialize.php');



$dbname = 'unibendb';
$collection = 'staff-profile';
$staff_category=isset($_GET['staff_category'])? $_GET['staff_category'] : die();
//$role = 'admin';
//DB connection
$db = new DbManager();
$conn = $db->getConnection();


// read active records
$filter = ['staff_category'=>$staff_category];
$option = [];
$readActive = new MongoDB\Driver\Query($filter, $option);
//fetch records
$activeRecords = $conn->executeQuery("$dbname.$collection", $readActive)->toArray();
$activeRecords = count($activeRecords);

$all=array(
    'Total Number Of'.$staff_category.' Staff'=>$activeRecords, 
    
); 



echo json_encode($all);