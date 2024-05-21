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

$accRights=array('admin', 'registry', 'registrar', 'vc', 'central_a&p');
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
$collection = 'staff-profile';

// DB connection
$db = new DbManager();
$conn = $db->getConnection();

// Read all records
$filter = [];
$option = [];
$queryDriver = new MongoDB\Driver\Query($filter, $option);

$resultStaff = $conn->executeQuery("$dbname.$collection", $queryDriver)->toArray();

if (count($resultStaff) > 0) {
    $eligibleStaff = [];
    foreach ($resultStaff as $value) {
        $lastPromDate = $value->registry->promotion->lastPromDate;
       
            unset($eligibleStaff[0]->registry->promotion);
        

        /// Split the lastPromDate to get the year
        $lastPromYear = explode('/', $lastPromDate)[2];

        // Get the current year
        $currentYear = date('Y');

        // Compare only the year with the current year
        if ($lastPromYear <= $currentYear - 3)  {
                // Add eligible staff to the array
                $eligibleStaff[] = $value;
        }
    }
}

    // Echo the eligible staff records as JSON
    echo json_encode($eligibleStaff);



/* ****************************************Create logs ****************************************************** */
/* if($sex=='male'){
    $gender='his';
}elseif($sex=='female'){
    $gender='her';
}
$logEvent='logged into '.$gender.'profile page.';
logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail); */
                    
/* ****************************************end Create logs****************************************************** */