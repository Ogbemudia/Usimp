<?php
header('Access-Control-Allow-Origin: localhost/giz/');
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
require_once('logsfunc.php');

$dbname = 'unibendb';

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "leaveAction" && $_POST['uniqueId']){

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
            $annualLeaveStatus = $value['registry']['annualLeave']['status'];
            $leaveName = $value['registry']['annualLeave']['name'];
            $startDate = $value['registry']['annualLeave']['startDate'];
            $endDate = $value['registry']['annualLeave']['endDate'];
            $leaveNoDays = $value['registry']['annualLeave']['leaveNoDays'];
            $leaveDuration = $value['registry']['annualLeave']['leaveNoDays'];
            $resumptionDate = $value['registry']['annualLeave']['resumptionDate'];
            $accuLeaveDuration = $value['registry']['accumulativeLeave']['leaveNoDays'];
        
        }
    }else{
        http_response_code(404);
        echo json_encode(
        array('success' => 0,
        'status' => 404,
        'message' => 'Record not found.'));
        die; 
    }
    //$accuLeaveDuration1=$accuLeaveDuration+$leaveDuration;
    $fullName=$first_name.' '.$surname;
    //$reason=$_POST['comments'];

    //if($annualLeaveStatus!=='available')
    if(in_array($annualLeaveStatus,array('forfeited', 'proceeded', 'defered', 'unavailable', ''))){
        http_response_code(404);
        echo json_encode(
        array('success' => 0,
        'status' => 404,
        'message' => 'Annual Leave is not available to '.$fullName.' at this time. Tray again later.'));
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
    $action = $_POST['action'];
    /* **************************************************
    deferment of Annual leave
    *****************************************************/
    if($action==="defer"){
        $deferReason=$_POST['deferReason'];
        $status=$_POST['status'];
        $data=array(
            'registry.leaveApplication.leaveStatus'                   =>'pending',
            'registry.leaveApplication.leaveName'                     =>$leaveName,
            'leaveAppType'                                            =>'defer',
            'registry.leaveApplication.leaveDuration'                 =>$leaveDuration,
            'registry.leaveApplication.startDate'                     =>$startDate,
            'registry.leaveApplication.endDate'                       =>$endDate,
            'registry.leaveApplication.leaveNoDays'                   => (int) $leaveNoDays,
            'registry.leaveApplication.resumptionDate'                =>$resumptionDate,
            'registry.leaveApplication.leaveDetail'                   =>$leaveName,
            'registry.leaveApplication.applicationDate'               =>$created,
            'registry.leaveApplication.hodResponse'                   =>$status,
            'registry.leaveApplication.hodComments'                           =>[
                                                                            ['comment'=>$deferReason,
                                                                             'date'=>$created,
                                                                            ],
                                                                        ],
            'registry.leaveApplication.dean_directorResponse'         =>'',
            'registry.leaveApplication.dean_directorComments'          =>[],
            'registry.leaveApplication.registrarResponse'             =>'',
            'registry.leaveApplication.registrarComment'              =>[],
        );
        $profile='staff-profile';
        $leaveAppl=update_by_uniqueId($data,  $profile, $uniqueId);
        if($leaveAppl){
        
           
            
                 /* ****************************************Create logs ****************************************************** */
                 $logEvent='Defered '.$leaveName.' for '.$fullName.' with bursary Id: '.$bursaryNo. '.';
                 logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                        
                /* ****************************************end Create logs****************************************************** */
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leaves successfully defered.')); 
                    exit;
                }else{
                    http_response_code(400);
                    echo json_encode(
                    array('success' => 0,
                    'status' => 400,
                    'message' => 'Leaves deferment failed try again later. Staff profile not updated'));
                };
            

    /* **************************************************
    update leave profile for proceeding on Annual leave
    *****************************************************/
    }elseif($action==="proceed"){
        $data=array(
            'registry.leaveStatus'           => 'active',               
            'registry.leaveType'             => $leaveName,  
            'registry.leaveEffectDate'       => $startDate,
            'registry.leaveResumeDate'       => $resumptionDate,
            'leaveNoDays'                    =>(int) $leaveDuration,
            'leaveDuration'                  =>$leaveDuration.' Days',                                        
            'registry.leaveExpireDate'       => $endDate,   
        );
        $profile='staff-profile';
        $accumUpdated=update_by_uniqueId($data,  $profile, $uniqueId);
        if($accumUpdated){
        /* **************************************************
        create histry for deferment
        *****************************************************/
        $data=array(
            'notification'          =>'unviewed',
            'leaveType'             =>$leaveName,
            'leaveStatus'           =>'proceeded',
            'uniqueId'              =>$uniqueId,
            'fullName'              =>$fullName,
            'bursaryNo'             =>$bursaryNo,
            'leaveName'             =>$leaveName,
            'leaveNoDays'           =>(int) $leaveDuration,
            'leaveDuration'         =>$leaveDuration.' Days',
            'startDate'             =>$startDate,
            'endDate'               =>$endDate,
            'leaveDetail'           =>$_POST['comments'],
            'applicationDate'               =>'',
            'hodResponse'                   =>'',
            'hodComments'                    =>[
                                                    [
                                                        'comment'=>$_POST['comments'],
                                                        'date'=>$created
                                                    ],
                                                ],
            'dean_directorResponse'         =>'',
            'dean_directorComments'          =>[],
            'registrarResponse'             =>'',
            'registrarComments'              =>[],
            'created'                       => $created,
        );
        $profile='leave-history';
        $histryUpdated=create_profile($data, $profile);
        if ($histryUpdated) {
            $data=array(
                'registry.annualLeave.status'     => 'proceeded',  
                /* 'registry.annualLeave.name'       => '',  
                'registry.annualLeave.startDate'  => '',  
                'registry.annualLeave.endDate'     => '',  
                'registry.annualLeave.leaveNoDays' => (int) 0,  
                'registry.annualLeave.resumptionDate' => '',  */   
            ); 
            
            $profile='staff-profile';
            $profileUpdated= update_by_uniqueId($data,  $profile, $uniqueId);
            if ($profileUpdated) {

            /* ****************************************Create logs ****************************************************** */
                $logEvent='Proceeded '.$leaveName.' for '.$fullName.' with bursary Id: '.$bursaryNo. '.';
                logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail);
                   
            /* ****************************************end Create logs****************************************************** */
           
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leaves successfully proceeded on.')); 
                    exit;
                }else{
                    http_response_code(400);
                    echo json_encode(
                    array('success' => 0,
                    'status' => 400,
                    'message' => 'Leaves deferment failed try again later. Staff profile not updated'));
                };
            }else{
                http_response_code(400);
                echo json_encode(
                array('success' => 0,
                'status' => 400,
                'message' => 'Leaves deferment failed try again later. Leave history not updated'));
            };
        }else{
            http_response_code(400);
            echo json_encode(
            array('success' => 0,
            'status' => 400,
            'message' => 'Leaves deferment failed try again later. staff prfile (leave) not updated'));
            exit;
        };




    /* **************************************************
    update leave profile for forfeiture of Annual leave
    *****************************************************/
    }elseif($action==="forfeit"){
        $forfietReason=$_POST['Reason'];
        $data=array(
            'registry.leaveApplication.leaveStatus'                   =>'pending',
            'registry.leaveApplication.leaveName'                     =>$leaveName,
            'leaveAppType'                                            =>'forfeit',
            'registry.leaveApplication.leaveDuration'                 =>$leaveDuration,
            'registry.leaveApplication.startDate'                     =>$startDate,
            'registry.leaveApplication.endDate'                       =>$endDate,
            'registry.leaveApplication.leaveNoDays'                   => (int) $leaveNoDays,
            'registry.leaveApplication.resumptionDate'                =>$resumptionDate,
            'registry.leaveApplication.leaveDetail'                   =>$leaveName,
            'registry.leaveApplication.applicationDate'               =>$created,
            'registry.leaveApplication.hodResponse'                   =>'approved',
            'registry.leaveApplication.hodComments'                           =>[
                                                                                    ['comment'=>$deferReason,
                                                                                        'date'=>$created,
                                                                                    ],
                                                                                ],
            'registry.leaveApplication.dean_directorResponse'         =>'',
            'registry.leaveApplication.dean_directorComments'          =>[],
            'registry.leaveApplication.registrarResponse'             =>'',
            'registry.leaveApplication.registrarComment'              =>[],
           
        );
        $profile='staff-profile';
        $leaveAppl=update_by_uniqueId($data,  $profile, $uniqueId);
        if($leaveAppl){
            /* ****************************************Create logs ****************************************************** */
            $logEvent='Forfeited on  '.$leaveName.' for '.$fullName.' with bursary Id: '.$bursaryNo. '.';
            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                   
            /* ****************************************end Create logs****************************************************** */

           
                
                http_response_code(200);
                echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Leaves successfully forfeited.')); 
                    exit;
                
           
        }else{
            http_response_code(400);
            echo json_encode(
            array('success' => 0,
            'status' => 400,
            'message' => 'Leaves deferment failed try again later. staff prfile (leave) not updated'));
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


