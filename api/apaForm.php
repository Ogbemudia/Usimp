<?php
header('Access-Control-Allow-Origin: localhost/giz/');
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
$sessionId = session_id();

require_once('authfunc.php');

$accRights=array('staff');
$accPrivilege=array('update');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}

//initializing api
include_once('../core/initialize.php');
include_once('sendverify.php');
require_once('../validation/library.php');
require_once('create_profile.php');
require_once('logsfunc.php');


$dbname = 'unibendb';
$profile = 'staff-profile';



    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    

    $uniqueId= $loguniqueId;
   
        
        $db = new DbManager();
        $conn = $db->getConnection();
        $query = ['uniqueId' => $uniqueId];
        $option = [];

        $queryDriver = new MongoDB\Driver\Query($query, $option);

        $users = $conn->executeQuery("$dbname.$profile", $queryDriver)->toArray();
       
            if (count($users) > 0){ 

               /*  $user=json_encode($users);
                $result = json_decode($user, true);
                foreach ($result as $value) {
                   
                   //$userid = $value['_id']['$oid'];
                   $first_name = $value['first_name'];
                   $middleName = $value['middleName'];
                   $surname = $value['surname'];
                   //$role = $value['role'];
                   $faculty = $value['registry']['faculty'];
                   $dept = $value['registry']['dept'];
                   $staff_category = $value['bursary']['staff_category'];
                   $ubsno = $value['bursary']['bursaryNo'];
                   $staffIDNo = $value['staff_profile']['staffIDNo'];
                   $biography = $value['staff_profile']['biography'];
                   $uniqueId = $value['uniqueId'];
                   $title = $value['title'];
                   $orcid_no = $value['staff_profile']['orcid_no'];
                   $email = $value['contact']['email'];
                  
                } */

                /* *********************apa form for Senior non acad************************** */
                if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "apaFormSenior") {
                    $data = array(
                        //'promotion'=>[
                            //'lastPromDate'=>$lastPromDate,
                            'promotion.status'=>'pending',
                            //'staff_under_review'=>[
                                'promotion.periodOfReport'=>'',
                                'promotion.staff_under_review.date_and_grade_on_first_appointment' => '',
                                'promotion.staff_under_review.date_and_grade_of_last_promotion' =>'',
                                'promotion.staff_under_review.date_and_grade_of_current_appointment' =>'',
                                
                                //'appointment_confirmation'=>[
                                    'promotion.staff_under_review.appointment_confirmation.appointmentStatus'=>'',
                                    'promotion.staff_under_review.appointment_confirmation.date_confirmed'=>'',
                                
                                //'salary'=>[
                                    //'promotion.staff_under_review.salary.present_salary'=>'',
                                    'promotion.staff_under_review.salary.present_annual_salary'=>'',
                                    'promotion.staff_under_review.salary.grade_level'=>'',
                                    'promotion.staff_under_review.salary.step'=>'',
                                    
                                //'academic_qualification_WithDate' =>$acadQuatnsWithDate,
                                //'professional_qualification_WithDate' =>'',
                                //'faculty/department/unit'=>$faculty.'/'.$dept.'/'.$unit,
                                //'promotion.indicate_any_changes_in_status_or_emolument_during_thePeriod_under_review'=>[],
                                //'promotion.record_of_service_the_university'=>[],
                                'promotion.courses_or_conferences_undertaken_during_periodof_report'=>[],
                                //'promotion.in_service_courses_todate'=>[],
                                //'promotion.statetypeof_in_service_training_required'=>'',
                                //'promotion.major_difficulties_encountered_onduties/possible_solutions'=>[],
                                //'promotion.other_useful_info_peculiar_to_your_duties'=>'',
                                'promotion.experience_outside_the_university_profesional'=>'',

                                /* ******************positions held and duties performed prior to report************************** */
                                'promotion.experience_within_the_university'=>'',
                                
                                'promotion.main_duties_during_periodunder_review'=>'',

                                'promotion.noncontinous_adhoc_duties_performed'=>[],
                                'promotion.other_activities_within_the_university'=>[],
                                'promotion.other_activities_outside_regular_university_duty'=>[],
                                
                                //'promotion.breakthrough_or_significant_contribution_to_knowledge'=>[],
                                //'promotion.unpublished_papers_read_at_conferences'=>[],
                                'promotion.staff_signature'=>'',
                                'promotion.date_submitted'=>'',
                            //],
                    );

                    /* *********************apa form for junior staff************************** */
                }elseif($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "apaFormJunior"){
                    $data = array(
                        //'promotion'=>[
                            //'lastPromDate'=>$lastPromDate,
                            'promotion.status'=>'pending',
                            'promotion.periodOfReport'=>'',
                            //'staff_under_review'=>[
                                
                                //'promotion.staff_under_review.date_and_grade_on_first_appointment' => '',
                                'promotion.staff_under_review.date_and_grade_of_last_promotion' =>'',
                                //'promotion.staff_under_review.date_and_grade_of_current_appointment' =>'',
                                
                                
                                
                                //'salary'=>[
                                    //'promotion.staff_under_review.salary.present_salary'=>'',
                                    'promotion.staff_under_review.salary.present_annual_salary'=>'',
                                    'promotion.staff_under_review.salary.grade_level'=>'',
                                    'promotion.staff_under_review.salary.step'=>'',
                                    
                                //'academic_qualification_WithDate' =>$acadQuatnsWithDate,
                                //'professional_qualification_WithDate' =>'',
                                //'faculty/department/unit'=>$faculty.'/'.$dept.'/'.$unit,
                                //'promotion.indicate_any_changes_in_status_or_emolument_during_thePeriod_under_review'=>[],
                                'promotion.record_of_service_the_university'=>[],
                                'promotion.courses_or_conferences_undertaken_during_periodof_report'=>[],
                                'promotion.in_service_courses_todate'=>[],
                                'promotion.statetypeof_in_service_training_required'=>'',
                                //'appointment_confirmation'=>[
                                    'promotion.staff_under_review.appointment_confirmation.appointmentStatus'=>'',
                                    'promotion.staff_under_review.appointment_confirmation.date_confirmed'=>'',
                                //'promotion.other_useful_info_peculiar_to_your_duties'=>'',
                                //'promotion.experience_outside_the_university_profesional'=>'',

                                /* ******************positions held and duties performed prior to report************************** */
                                //'promotion.experience_within_the_university'=>'',
                                
                                'promotion.main_duties_during_periodunder_review'=>'',
                                'promotion.major_difficulties_encountered_onduties/possible_solutions'=>[],
                                

                                'promotion.noncontinous_adhoc_duties_performed'=>[],
                                'promotion.other_activities_within_the_university'=>[],
                                'promotion.other_activities_outside_regular_university_duty'=>[],
                                'promotion.other_useful_info_peculiar_to_your_duties'=>'',
                                
                                //'promotion.breakthrough_or_significant_contribution_to_knowledge'=>[],
                                //'promotion.unpublished_papers_read_at_conferences'=>[],
                                'promotion.staff_signature'=>'',
                                'promotion.date_submitted'=>'',
                            //],
                    );
                }
                    $staffUpdate =update_by_uniqueId($data,  $profile, $uniqueId);
                    if($staffUpdate){
                        /* ****************************************Create logs ****************************************************** */
                        if($sex=='male'){
                            $gender='his';
                        }elseif($sex=='female'){
                            $gender='her';
                        }
                        $logEvent='Submitted ' .$gender.' APA form.';
                        logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                            
                        /* ****************************************end Create logs****************************************************** */
            
                        echo json_encode(
                            array('success' => 1,
                            'status' => 200,
                            'message' => 'APA form submitted successfully.'));
                    } else {
                        echo json_encode(
                            array('success' => 0,
                            'status' => 400,
                            'message' => 'Submition failed, please try again.'));
                        }
                    //}
    
                }           
            
  
