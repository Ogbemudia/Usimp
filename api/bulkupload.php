<?php
header('Access-Control-Allow-Origin: localhost/uniben/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require __DIR__.'/validation.php';

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

$accRights=array('admin');
$accPrivilege=array('create');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
} 
//Include required phpmailer files


 /* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
};    */

//initializing api
include_once('../core/initialize.php');
//include_once('sendverify.php');
require_once('../validation/library.php');
require_once('create_profile.php');



$dbname = 'unibendb';


if(isset($_POST['importSubmit'])){
    
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
           // $errorEmail=array();
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                //staf profile
                $title                        = $line[0];
                $firstName                    = $line[1];
                $middleName                   = $line[2];
                $surname                      = $line[3];
                $sex                          = $line[4];
                $dOb                          = $line[5];
                $placeOfBirth                 = $line[6];
                $marritalStatus               = $line[7];
                $staffCat                     = $line[8];
                $picName                      = $line[9];
                $staffIDNo                    = $line[10];
                $nationality                  = $line[11];
                //contact
                $LGAofOrigin                  = $line[12];
                $stateOfOrigin                = $line[13];
                $stateOfRes                   = $line[14];
                $LGAOfRes                     = $line[15];
                $PAddress                     = $line[16];
                $Caddress                     = $line[17];
                $phone                        = $line[18];
                $email                        = $line[19];
                //spouse
                $spouseFirstName              = $line[20];
                $spouseMiddleName             = $line[21];
                $spouseLastName               = $line[22]; 
                $spouseOccupation             = $line[23];
                $spousePlaceOfWork            = $line[24]; 
                $spouseResedentialAddress     = $line[25]; 
                $spousePhone                  = $line[26];
                //children 
                $childName1                   = $line[27]; 
                $childBirth1                  = $line[28]; 
                $childSex1                    = $line[29]; 
                $childName2                   = $line[30]; 
                $childBirth2                  = $line[31]; 
                $childSex2                    = $line[32]; 
                $childName3                   = $line[33]; 
                $childBirth3                  = $line[34]; 
                $childSex3                    = $line[35]; 
                $childName4                   = $line[36]; 
                $childBirth4                  = $line[37]; 
                $childSex4                    = $line[38];
                //next of kin
                $NokFirstName                 = $line[39]; 
                $NokMiddleName                = $line[40]; 
                $NokLastName                  = $line[41]; 
                $NokSex                       = $line[42]; 
                $NokAddress                   = $line[43]; 
                $NokEmail                     = $line[44]; 
                $NokPhoneNumber               = $line[45];
                //registry info 
                $PFileNo                     = $line[46]; 
                $faculty                     = $line[47]; 
                $dept                        = $line[48]; 
                $unit                        = $line[49]; 
                $designation                 = $line[50]; 
                $postion                     = $line[51]; 
                $areaOfSpecialization        = $line[52]; 
                $acadQuatnsWithDate          = $line[53]; 
                $PHYCHAL                     = $line[54]; 
                $union                       = $line[55]; 
                $date_employed               = $line[56]; 
                $date_confirmed              = $line[57]; 
                $staff_Status                = $line[58]; 
                $dateOfAssumptionOfDuty      = $line[59]; 
                $cadreOn1stApp               = $line[60]; 
                $levelOn1stApp               = $line[61]; 
                $stepOn1stApp                = $line[62]; 
                $currentLevel                = $line[63]; 
                $currentStep                 = $line[64]; 
                $currentCadre                = $line[65]; 
                $incStep                     = $line[66]; 
                $dateOfRetirementByAge       = $line[67]; 
                $dateOfRetirementByEmpl      = $line[68]; 
                $dateOfRetirement            = $line[69]; 
                $dateOfResignation           = $line[70]; 
                $leaveStatus                 = $line[71]; 
                $leaveType                   = $line[72]; 
                $lastPromDate                = $line[73]; 
                $leaveEffectDate             = $line[74]; 
                $leaveResumeDate             = $line[75]; 
                $leaveNoDays                 = $line[76]; 
                $leaveExpireDate             = $line[77]; 
                $groupCadre                  = $line[78]; 
                $staffType                   = $line[79]; 
                $initial                     = $line[80]; 
                $CONTSRTDATE                 = $line[81]; 
                $CONTEXPDATE                 = $line[82]; 
                $NoOfRenewedContract         = $line[83]; 
                //bursary
                $bursaryNo                   = $line[84]; 
                $IPPIS_NO                    = $line[85]; 
                $SalaryCategory              = $line[86]; 
                $bankName                    = $line[87]; 
                $accountNumber               = $line[88]; 
                $PFANAme                     = $line[89]; 
                $PENSION_PIN                 = $line[90]; 

                
                
                /* $Date_employed               = $line[53]; 
                $Date_confirmed              = $line[54]; 
                $Staff_Status                = $line[55]; 
                $DateOfAssumptionOfDuty      = $line[56]; 
                $CadreOn1stApp               = $line[57]; 
                $LevelOn1stApp               = $line[58]; 
                $StepOn1stApp                = $line[59]; 
                $CurrentLevel                = $line[60]; 
                $CurrentStep                 = $line[61]; 
                $CurrentCadre                = $line[62]; 
                $IncStep                     = $line[63]; 
                $DateOfRetirementByAge       = $line[64]; 
                $DateOfRetirementByEmployment= $line[65]; 
                $DateOfRetirement            = $line[66]; 
                $DateOfResignation           = $line[67]; 
                $LeaveStatus                 = $line[68]; 
                $LeaveType                   = $line[69]; 
                $LeaveEffectDate             = $line[70]; 
                $LeaveResumeDate             = $line[71]; 
                $LeaveNoDays                 = $line[72]; 
                $LeaveExpireDate             = $line[73]; 
                $GroupCadre                  = $line[74]; 
                $Initial                     = $line[75]; 
                $CONTSRTDATE                 = $line[76]; 
                $CONTEXPDATE                 = $line[77]; 
                $NoOfRenewedContract         = $line[78];  */
                
               
                if(!empty($staffIDNo)){
                   /*  $fullName = $title.' '.$firstName.' '.$middleName.' '.$surname;
                    $password     = strtolower($bursaryNo);
                    $password     = trim($password);
                    $role         ='staff';
                    $password     = password_hash($password, PASSWORD_DEFAULT); //this creates a hash password.
                    $date1 = date("F j, Y"); 
                    $tim = date("g:i a");
                    $created = $date1. " at ".$tim;
                    $leaveDate2   = $dateOfAssumptionOfDuty;
                    $leaveDate2   = explode('/', $leaveDate2);
                    $leaveDay     = $leaveDate2[0];
                    $monthNum   = $leaveDate2[1];
                    $leaveMonth = date('F', mktime(0, 0, 0, $monthNum, 10)); */
                    //$leaveYear    = $leaveDate2[2];

                    $date1 = date("F j, Y"); 
                    $tim = date("g:i a");
                    $created = $date1. " at ".$tim;

                // Check whether member already exists in the database with the same email
                $collection = 'userlogin';
                $dbname = 'unibendb';
                 
                 //DB connection
                 $db = new DbManager();
                 $conn = $db->getConnection();
                 $query = ['bursaryNo' => $bursaryNo];
                 $option = [];
         
                 $queryDriver = new MongoDB\Driver\Query($query, $option);
         
                     
                     $users = $conn->executeQuery("$dbname.$collection", $queryDriver);
                     $users = $users->toArray(); 
                     $existusers=count($users);
                     if ($existusers <= 0){
    
                      
                        
                        $fullName = $title.' '.$firstName.' '.$middleName.' '.$surname;
                        $password2    = substr($staffIDNo, strrpos($staffIDNo, '.') + 1);
                        $password     = strtolower($password2);
                        $password     = trim($password);
                        $role         ='staff';
                        $password     = password_hash($password, PASSWORD_DEFAULT); //this creates a hash password.
    
                        $leaveDate2   = $dateOfAssumptionOfDuty;
                        $leaveDate2   = explode('/', $leaveDate2);
                        $leaveDay     = $leaveDate2[0];
                        $leaveMonth   = $leaveDate2[1];
                        $leaveYear    = $leaveDate2[2];
    
                        /* ***********************************
                        generate unique Id
                        ************************************* */
                        $numgen = rand(0,999);
                        $timeReg = time();
                        $codegen = $numgen. $timeReg;
                        $verifyCode = substr(sha1($codegen), 0, 6);
                        $uniqueId=$verifyCode.$password2;
                         /* ***********************************
                        generate unique Id
                        ************************************* */
                        if (empty($date_confirmed)) {
                            $appointmentStatus='confirmed';
                        } else {
                            $appointmentStatus='';
                        }
                   
                        $data=array(

                            'uniqueId'                      => $uniqueId,                                                
                            'title'                         => $title,                                                
                            'first_name'                    => $firstName,                                               
                            'middleName'                    => $middleName,
                            'surname'                       => $surname, 
                            'created'                       => $created,                                
                            'last_updated'                  => '',                                
                            'staff_profile'=>[
                                'sex'                       => $sex,                             
                                'dOb'                       => $dOb,                             
                                'placeOfBirth'              => $placeOfBirth,                                                  
                                'marritalStatus'            => $marritalStatus,                                                  
                                'staffCat'                  => strtolower($staffCat),                                            
                                'picName'                   => $picName,                                        
                                'cv'                        => '',                                        
                                'biography'                 => '',                                        
                                'awards'                    => '',                                        
                                'staffIDNo'                 => $staffIDNo,                                        
                                'orcid_no'                  => '',                                        
                                'nationality'               => $nationality                                        
                             ],
                             'contact'=>[
                                'LGAofOrigin'               => $LGAofOrigin,                                        
                                'stateOfOrigin'             => $stateOfOrigin,                                        
                                'stateOfRes'                => $stateOfRes,                    
                                'LGAOfRes'                  => $LGAOfRes,                            
                                'PAddress'                  => $PAddress,               
                                'Caddress'                  => $Caddress,                                 
                                'phone'                     => $phone,               
                                'email'                     => $email,
                             ],
                            'spouse'=>[
                                'spouseFirstName'           => $spouseFirstName,                                        
                                'spouseMiddleName'          => $spouseMiddleName,                                        
                                'spouseLastName'            => $spouseLastName,                    
                                'spouseOccupation'          => $spouseOccupation,                            
                                'spousePlaceOfWork'         => $spousePlaceOfWork,               
                                'spouseResedentialAddress'  => $spouseResedentialAddress,                                 
                                'spousePhone'               => $spousePhone,               
                             ],
                            'children'=>[
                                'child1'=>[
                                    'childName'           => $childName1,                                        
                                    'date_of_Birth'       => $childBirth1,                                        
                                    'childSex'            => $childSex1,                    
                                ],
                                'child2'=>[
                                    'childName'           => $childName2,                                        
                                    'date_of_Birth'       => $childBirth2,                                        
                                    'childSex'            => $childSex2,                    
                                ],
                                'child3'=>[
                                    'childName'           => $childName3,                                        
                                    'date_of_Birth'       => $childBirth3,                                        
                                    'childSex'            => $childSex3,                    
                                ],
                                'child4'=>[
                                    'childName'           => $childName4,                                        
                                    'date_of_Birth'       => $childBirth4,                                        
                                    'childSex'            => $childSex4,                    
                                ],
                                            
                             ],
                            'next_of_kin'=>[
                                'NokFirstName'           => $NokFirstName,                                        
                                'NokMiddleName'          => $NokMiddleName,                                        
                                'NokLastName'            => $NokLastName,                    
                                'NokSex'                 => $NokSex,                            
                                'NokAddress'             => $NokAddress,               
                                'NokEmail'               => $NokEmail,                                 
                                'NokPhoneNumber'         => $NokPhoneNumber,               
                             ],
                             'registry'=>[
                                'PFileNo'               => $PFileNo,                                        
                                'faculty'               => strtolower($faculty),                                        
                                'dept'                  => strtolower($dept),                    
                                'unit'                  => strtolower($unit),                            
                                'designation'           => strtolower($designation),               
                                'postion'               => $postion,                                 
                                'areaOfSpecialization'  => $areaOfSpecialization,
                                'acadQuatnsWithDate'    => $acadQuatnsWithDate,                                        
                                'PHYCHAL'               => $PHYCHAL,                                        
                                'union'                 => $union,                    
                                'date_employed'         => $date_employed,                            
                                'date_confirmed'        => $date_confirmed,               
                                'staff_Status'          => $staff_Status,                                 
                                'dateOfAssumptionOfDuty'=> $dateOfAssumptionOfDuty,
                                'cadreOn1stApp'         => strtolower($cadreOn1stApp),                                        
                                'levelOn1stApp'         => $levelOn1stApp,                                        
                                'stepOn1stApp'          => $stepOn1stApp,                    
                                'currentLevel'          => $currentLevel,                            
                                'currentStep'           => $currentStep,               
                                'currentCadre'          => $currentCadre,                                 
                                'incStep'               => $incStep,
                                'dateOfRetirementByAge' => $dateOfRetirementByAge,                                        
                                'dateOfRetirementByEmpl'=> $dateOfRetirementByEmpl,                                        
                                'dateOfRetirement'      => $dateOfRetirement,                    
                                'dateOfResignation'     => $dateOfResignation,
                                'annualLeave'=>[
                                    'status'            => 'unavailable',  
                                    'name'              => '',  
                                    'startDate'         => '',  
                                    'endDate'           => '',  
                                    'leaveNoDays'       => (int) 0,
                                    'resumptionDate'    => '',
                                    'leaveDay'          => $leaveDay,
                                    'leaveMonth'        => $leaveMonth,
                                ],  
                                'leaveApplication'=>[
                                    'leaveStatus'                   =>'',
                                    'leaveAppType'                  =>'',
                                    'leaveName'                     =>'',
                                    'leaveDuration'                 =>'',
                                    'startDate'                     =>'',
                                    'endDate'                       =>'',
                                    'leaveNoDays'                   => (int) 0,
                                    'resumptionDate'                => '',
                                    'leaveDetail'                   =>'',
                                    'backupDoc            '         =>'',
                                    'applicationDate'               =>'',
                                    'hodResponse'                   =>'',
                                    'hodComments'                    =>[],
                                    //'hodResponse_date'              =>'',
                                    'dean_directorResponse'         =>'',
                                    'dean_directorComment'          =>[],
                                    //'dean_directorResponse_date'    =>'',
                                    'registrarResponse'             =>'',
                                    'registrarComment'              =>[],
                                    //'registrarResponse_date'        =>'',
                                ],  
                                'accumulativeLeave'=>[
                                    'leaveNoDays'        => (int) 0,  
                                    'accumulatedLeaves'  =>[],  
                                ],
                                /* **************************************************************************
                                                                staff promotion
                                *************************************************************************** */
                                'promotion'=>[
                                    'lastPromDate'=>$lastPromDate,
                                    'status'=>'',
                                    'staff_under_review'=>[
                                        'date_and_grade_on_first_appointment' => $date_employed.' grade level '.$levelOn1stApp.' step '.$stepOn1stApp.'.',
                                        'date_and_grade_of_last_promotion' =>'',
                                        'date_and_grade_of_current_appointment' =>'',
                                        'appointment_confirmation'=>[
                                            'appointmentStatus'=>$appointmentStatus,
                                            'date_confirmed'=>$date_confirmed,
                                        ],
                                        'salary'=>[
                                            'present_salary'=>'',
                                                'present_annual_salary'=>'',
                                                'grade_level'=>'',
                                                'step'=>'',
                                            ],
                                        //'academic_qualification_WithDate' =>$acadQuatnsWithDate,
                                        //'professional_qualification_WithDate' =>'',
                                        //'faculty/department/unit'=>$faculty.'/'.$dept.'/'.$unit,
                                        'indicate_any_changes_in_status_or_emolument_during_thePeriod_under_review'=>[],
                                        'record_of_service_the_university'=>[],
                                        'courses_or_conferences_undertaken_during_periodof_report'=>[],
                                        'in_service_courses_todate'=>[],
                                        'statetypeof_in_service_training_required'=>'',
                                        'main_duties_during_periodunder_review'=>'',
                                        'major_difficulties_encountered_onduties/possible_solutions'=>[],
                                        'other_useful_info_peculiar_to_your_duties'=>'',
                                        'experience_outside_the_university_profesional'=>'',
                                        'experience_within_the_university'=>'',
                                        'noncontinous_adhoc_duties_performed'=>[],
                                        'other_activities_within_the_university'=>[],
                                        'other_activities_outside_regular_university_duty'=>[],
                                        
                                        'breakthrough_or_significant_contribution_to_knowledge'=>[],
                                        'unpublished_papers_read_at_conferences'=>[],
                                        'staff_signature'=>'',
                                        'date_submitted'=>'',
                                    ],
                                     /* **************************************************************************
                                            staff promotion assessment
                                    *************************************************************************** */
                                    'assessment'=>[

                                         /* **************************************************************************
                                            non_academic senior staff assessment
                                         *************************************************************************** */
                                        'non_academic_senior_staff'=>[
                                            'queries'=>[
                                                /* array */
                                                /* [
                                                    'query' => '',
                                                    'date_issued' => '',
                                                    'query_upload' => '',
                                                ], */
                                            ],
                                            'warnings'=>[
                                                 /* array */
                                                /* [
                                                    'warning' => '',
                                                    'date_issued' => '',
                                                    'warning_upload' => '',
                                                ], */
                                            ],
                                            'commendations'=>[
                                                 /* array */
                                                /* [
                                                    'commendation' => '',
                                                    'date_issued' => '',
                                                    'query_commendation' => '',
                                                ], */
                                            ],
                                            'was_there_agreement_on_job_description'=>'',
                                            'if_no_discuss_the_changes_with_candidate_and_record_unresolved_deferences'=>'',
                                            
                                            'aspects_of_performance'=>[
                                                
                                                'cognate_experience'=>[
                                                    'performance_of_assigned_schedule_adhoc_outside_duties'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'oral_written_expression'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'computer_literacy'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                ],
                                                
                                                'job_knowledge'=>[
                                                    'administrative_professional_technical_competence'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'relaibility_under_preasure'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'work_output'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'work_output2'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'confidence_in_discharge_of_duties'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                ], 
                                                
                                                'attitude_to_work'=>[
                                                    'punctuality_regularity_to_work'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'acceptance_of_responsibility'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'relationship_with_staff_public'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'response_to_criticism'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'drive_and_determination'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                ],
                                                
                                                'leadership_qualities'=>[
                                                    'organisational_ability'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'foresight'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'sense_of_judgement'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'motivation'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    
                                                ],
                                                
                                                'integrity'=>[
                                                    'self_control'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'honesty'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    'loyalty'=>[
                                                        'score'=>'',
                                                        'documentary_evidence'=>'',
                                                    ],
                                                    
                                                    
                                                ],
                                            ],

                                            'overall_assessment_percentage_score'=>[
                                                'hod_comment'=>'',
                                                'percentage_score'=>'',
                                            ],
                                            'comments_of_officer_reported_on'=>[
                                                'staff_comment'=>'',
                                                'staff_signature'=>'',
                                                'HATISS'=>'',
                                                'job_title'=>'',
                                                'date'=>'',
                                            ],

                                            'training_needs_for_staff_under_review'=>[
                                                'specify_training_needs_for_improvement'=>'',
                                                'if_training_on_the_job_is_enough_suggest_possible_to_train_the_staff'=>'',
                                            ],
                                            
                                            'next_job_at_same_level'=>[
                                                'a_different_job_in_same_grade'=>'',
                                                'transfer_to_a_job_at_similar_level_in_another_group_or_cadre'=>'',
                                                'if_yes_to_the_above_state_the_job_and_reason'=>'',
                                            ], 
                                            
                                            'promotability'=>[
                                                'normal_promotion'=>[
                                                    'staff_under_review_is_presently'=>'',
                                                    'comment_on_your_recommendation'=>'',
                                                ],
                                                
                                                'special_recommendation'=>[
                                                    'staff_should_be_specially_considered_for_promotion_to'=>'',
                                                    'reasons_on_your_recommendation'=>'',
                                                ],
                                            ],
                                            'hod_director_signature'=>[
                                                'the_staff_under_review_has_served_under_me_for'=>'',
                                                'signature'=>'',
                                                'grade'=>'',
                                                'date'=>'',
                                            ],
                                            
                                            'recommendation_of_a_and_p_non_acad_sub_committee'=>[
                                                'status'=>'',
                                                'recommendation'=>'',
                                                'to_be_promoted_to'=>'',
                                                'chairman_signature'=>'',
                                                'date'=>'',
                                               
                                            ],
                                            'A_and_PB_NA'=>[
                                                'decision_of_A_and_PB_NA'=>'',
                                                'additional_info'=>'',
                                                'status'=>'',
                                            ],

                                        ],



                                        /* **************************************************************************
                                            non-academic junior staff assessment
                                         *************************************************************************** */
                                        'non_academic_junior_staff'=>[

                                            /* **************************************************************************
                                                section C
                                                (particulars to be supplied by the supervisor)
                                            *************************************************************************** */

                                            'sickleave_days_during_period_under_review'=>[
                                                'with_medical_cert'=>[],
                                                'without_medical_cert'=>[],
                                            ],
                                            'frequency_of_attedance_athealth_centre'=>'',
                                            'cassual_leave_number_of_days_during_period_under_review'=>'',
                                            'sanctions_disciplinary_action_during_period_under_review'=>[
                                                /* array */
                                                /*  ['sanctions_disciplinary'=>'', 
                                               'date'=>'', 
                                               'signature'=>''],  */
                                            ],

                                            /* **************************************************************************
                                            part II
                                            evaluation of performance
                                            (to be completed by immediate supervisor or hod)
                                            *************************************************************************** */
                                            'evaluation_of_performance'=>[
                                                'main_work_performed_by_employee_during_period_under_review'=>'',
                                                'recommended_training_to_improve_employee_under_review'=>'',
                                                'other_useful_info_about_staff_under_review'=>'',

                                                'assessment'=>[
                                                    'conduct'=>'',
                                                    'relations_with_colleagues'=>'',
                                                    'level_of_responsibility'=>'',
                                                    'quality_of_work'=>'',
                                                    'quantity_of_output'=>'',
                                                    'initiative'=>'',
                                                    'adaptibility'=>'',
                                                    'ability_to_work_with_minimum_supervision'=>'',
                                                    'expresion_on_paper'=>'',
                                                    'oral_expression'=>'',
                                                    'punctuality'=>'',
                                                    'management_of_subordinates/numerical_ability'=>'',
                                                    'organisation_of_work'=>'',
                                                    'self_improvement_effort'=>'',
                                                    'regularity_at_work'=>'',
                                                    'attitude_to_work'=>'',
                                                    'human_relations'=>'',
                                                    'degree_of_relaibility'=>'',
                                                    'knowledge_of_departmental_rules'=>'',
                                                    'personality'=>'',
                                                    'total'=>'',
                                                    
                                                    'reporting_officer'=>[
                                                        'name'=>'',
                                                        'signature'=>'',
                                                        'status'=>'',
                                                        'date'=>'',
                                                    ],
                                                    
                                                    'staff_under_review'=>[
                                                        'comment'=>'',
                                                        'signature'=>'',
                                                        'date'=>'',
                                                    ],
                                                    
                                                ],
                                            ],

                                            /* **************************************************************************
                                            part III
                                            Overall assessment by hod or department committee
                                            *************************************************************************** */  
                                            'overall_assessment'=>[
                                                'ripe_for_promotion'=>'',
                                                'ripe_for_confirmation'=>'',
                                                'satisfactory_performance'=>'',
                                                'recommended_for_increment_only'=>'',
                                                'recently_appointed/promoted'=>'',
                                                'to_obtain_more_qualification/experience_before_next_promotion'=>'',
                                                'recommended_for_promotion_next_year'=>'',
                                                'has_reached_the_end0f_present_career_structure_otherwise_a_good_candidate_for_promotion'=>'',
                                                'to_be_transfer_to_a_different_job_after_training'=>'',
                                                'unsatisfactory'=>'',
                                                'to_be_advised'=>'',
                                                'to_be_reprimanded'=>'',
                                                'to_lose_annual_increment'=>'',
                                                'grossly_unsatisfactory'=>'',
                                                'to_be_reduced_in_rank'=>'',
                                                'appointment_to_be_terminated'=>'',
                                                'to_be_dismissed_from_service'=>'',
                                                'general_remarks/observation'=>'',
                                                'hod_signature'=>'',
                                                'date'=>'',
                                            ],

                                             /* **************************************************************************
                                            part IV
                                            *************************************************************************** */
                                            'recommendation_of_a_and_p_non_acad_sub_committee'=>[
                                                'status'=>'',
                                                'recommendation'=>'',
                                                'to_be_promoted_to'=>'',
                                                'chairman_signature'=>'',
                                                'date'=>'',
                                               
                                            ],
                                            'A_and_PB_NA'=>[
                                            'decision_of_A_and_PB_NA'=>'',
                                            'additional_info'=>'',
                                            'status'=>'',
                                            ],
                                        ],  

                                        //],
                                        
                                        
                                        
                                         /* **************************************************************************
                                            academic staff assessment
                                         *************************************************************************** */
                                        'academic'=>[

                                            /* **************************
                                            part B
                                            comments by heads of departments
                                           ******************************/
                                            'hod_assessment'=>[
                                                'quality_of_teaching'=>'',
                                                'teaching_load'=>'',
                                                'quality_of_research'=>'',
                                                'quality_of_publication'=>'',
                                                'postgraduate_supervision'=>'',
                                                'participation_in_department/faculty_university_activities'=>'',
                                                'other_remarks'=>'',
                                                'hod_signature'=>'',
                                                'staff_signature'=>'',
                                                'staff_under_review'=>[
                                                    'comments_by_staff_under_review'=>'',
                                                    'hod_signature'=>'',
                                                    'staff_signature'=>'',
                                                ],
                                            ],

                                            /* **************************
                                            part c
                                            detailed scoring 
                                            (only in respect for candidate being recommended for promotion)
                                           ******************************/
                                            'detailed_scoring_for_candidate_recommended_for_promotion'=>[
                                                'academic/professional_qualifications'=>[],
                                                'teaching'=>[
                                                    'length'=>'',
                                                    'load'=>'',
                                                    'quality'=>'',
                                                ],
                                                'current_research'=>[],
                                                'recognised_publications'=>[],

                                                /* **************************
                                                (to be assessed by at least two internal and/or external assessor)
                                                minimum points in respect of publications.
                                                senior lectural     --17 points
                                                associate prof.     --20 points
                                                prof.     --24 points
                                                ******************************/
                                                'references'=>[],
                                                'interview_performance'=>[
                                                    'associate_prof/prof'=>'',
                                                    'others'=>'',
                                                ],
                                                'contribution_to_university_or_country'=>'',
                                                'administrative_experience'=>'',
                                                'academic_distinction'=>'',
                                                'total'=>'',
                                                'percentage'=>'',
                                            ],
                                            
                                            /* **************************
                                            for candidate for promotion to the post of prof/associate prof
                                           ******************************/
                                          'remarks'=>'',
                                          'recommendation'=>'',
                                          'signature_hod/dean/provost'=>'',
                                          'date'=>'',


                                           /* **************************
                                           part d
                                           recommendation of the college/faculty sub-committee of A&PB
                                           ******************************/
                                          'recommendation_of_college/faculty_sub_committee_of_A_and_PB_A'=>[
                                            'status'=>'',
                                            'recommendation'=>'',
                                            'to_be_promoted_to'=>'',
                                            'chairman_signature'=>'',
                                            'date'=>'',
                                          ],
                                          'A_and_PB_A'=>[
                                                'decision_of_A_and_PB_A'=>'',
                                                'additional_info'=>'',
                                                'status'=>'',
                                          ],
                                          
                                        ],
                                    ],
                             
                                ],  
                                /* **************************************************************************
                                            end staff promotion
                                *************************************************************************** */
                                
                                'leaveStatus'           => $leaveStatus,               
                                'leaveType'             => $leaveType,  
                                //'leaveMonth'            => $leaveMonth,                                 
                                //'leaveDay'              => $leaveDay,                                
                                'leaveEffectDate'       => $leaveEffectDate,
                                'leaveResumeDate'       => $leaveResumeDate,
                                'leaveDuration'         => '',
                                'leaveNoDays'           => (int) 0,//$leaveNoDays,                                        
                                'leaveExpireDate'       => '',//$leaveExpireDate,                                        
                                'groupCadre'            => $groupCadre,
                                'staffType'             => $staffType,                    
                                'initial'               => $initial,                            
                                'CONTSRTDATE'           => $CONTSRTDATE,               
                                'CONTEXPDATE'           => $CONTEXPDATE,                                 
                                'NoOfRenewedContract'   => $NoOfRenewedContract,               
                             ],
                             'bursary'=>[
                                'bursaryNo'             => $bursaryNo,                                        
                                'IPPIS_NO'              => $IPPIS_NO,                                        
                                'SalaryCategory'        => strtolower($SalaryCategory),                    
                                'bankName'              => $bankName,                            
                                'accountNumber'         => $accountNumber,               
                                'PFANAme'               => $PFANAme,                                 
                                'PENSION_PIN'           => $PENSION_PIN,               
                             ],
                                         
                             'NHIS'=>[
                                'NHIS_code'           =>'',                                        
                                'HMO'                 =>'',                                        
                                'primary_health_care' =>'',                    
                             ],

                             'cooperative'=>[
                                'cooperative_name'           =>'',                                        
                                'cooperative_id'             =>'',                                        
                             ],
                                         
                                        
                     );
                            $noCreated=0;
                            $noFailedCreated=0;
                            $profile='staff-profile';
                            // Insert member data in the database
                            $createProfile=create_profile($data, $profile);
                            if($createProfile){
    
                             /*************************create login ********************************/
    
                       
                              $document = array(
    
                                "uniqueId"                 => $uniqueId,
                                "fullName"                 => $fullName,
                                "email"                    => $email,
                                "password"                 => $password,
                                "bursaryNo"                => $bursaryNo,
                                "staffIDNo"                => $staffIDNo,
                                "designation"              => $designation,
                                "postion"                  => $postion,
                                "signature"                => '',
                                "status"                   => 'active',
                                //"email_v_code"             => $verifyCode,
                                "role"                     => [
                                    "dept"     => ['dept'],
                                    "right"     => ['staff'],
                                    "privilege" => ['view', 'update', 'save'],
                                ],
                                
                                "sex"                      => $sex,
                                "created"                  => $created
                            
                            );
                            
                             register($document, $collection); 
                       
                       
                    /*************************end create login********************************/
                            
                            
    /* ****************************************Create Lists****************************************************** */
    /* ****************************************Create faculties****************************************************** */
                            $collFaculties = 'faculties';
                            //DB connection
                            $db = new DbManager();
                            $conn = $db->getConnection();
                            //if(!empty($host_organisation)){
                                // Check whether group host already exists in the database
                                $query = ['faculty' => strtolower($faculty),];
                                $option = [];
                    
                                $queryDriver = new MongoDB\Driver\Query($query, $option);
                                $bGroup = $conn->executeQuery("$dbname.$collFaculties", $queryDriver);
                                $bGroup = $bGroup->toArray(); 
                                $nGroup=count($bGroup);
                                if ($nGroup <= 0){
                                    $hOrg=array(
                                        'faculty'  => strtolower($faculty),
                                    );
                
                                    // insert record
                                    $insert = new MongoDB\Driver\BulkWrite();
                                    $insert->insert($hOrg);
    
                                    $result = $conn->executeBulkWrite("$dbname.$collFaculties", $insert);
                                    
                                    
                                    }; 
    /* ****************************************end Create faculties****************************************************** */
    /* ****************************************Create dept ****************************************************** */
                            $colldept = 'dept';
                            //DB connection
                            $db = new DbManager();
                            $conn = $db->getConnection();
                            //if(!empty($host_organisation)){
                                // Check whether group host already exists in the database
                                $query = ['dept' => strtolower($dept),
                                            'faculty' => strtolower($faculty)];
                                $option = [];
                    
                                $queryDriver = new MongoDB\Driver\Query($query, $option);
                                $bGroup = $conn->executeQuery("$dbname.$colldept", $queryDriver);
                                $bGroup = $bGroup->toArray(); 
                                $nGroup=count($bGroup);
                                if ($nGroup <= 0){
                                    $hOrg=array(
                                        'dept'  => strtolower($dept),
                                        'faculty'  => strtolower($faculty),
                                    );
                
                                    // insert record
                                    $insert = new MongoDB\Driver\BulkWrite();
                                    $insert->insert($hOrg);
    
                                    $result = $conn->executeBulkWrite("$dbname.$colldept", $insert);
                                    
                                    
                                    }; 
    /* ****************************************end Create dept****************************************************** */
    /* ****************************************Create unit ****************************************************** */
                            $collunit = 'unit';
                            //DB connection
                            $db = new DbManager();
                            $conn = $db->getConnection();
                            //if(!empty($host_organisation)){
                                // Check whether group host already exists in the database
                                $query = ['unit' => strtolower($unit),
                                            'dept' => strtolower($dept),
                                            'faculty' => strtolower($faculty)];
                                $option = [];
                    
                                $queryDriver = new MongoDB\Driver\Query($query, $option);
                                $bGroup = $conn->executeQuery("$dbname.$collunit", $queryDriver);
                                $bGroup = $bGroup->toArray(); 
                                $nGroup=count($bGroup);
                                if ($nGroup <= 0){
                                    $hOrg=array(
                                        'unit'  => strtolower($unit),
                                        'dept'  => strtolower($dept),
                                        'faculty'  => strtolower($faculty),
                                    );
                
                                    // insert record
                                    $insert = new MongoDB\Driver\BulkWrite();
                                    $insert->insert($hOrg);
    
                                    $result = $conn->executeBulkWrite("$dbname.$collunit", $insert);
                                    
                                    
                                    }; 
    /* ****************************************end Create unit****************************************************** */
    /* ****************************************Create nationality ****************************************************** */
                            $collnationality= 'nationality';
                            //DB connection
                            $db = new DbManager();
                            $conn = $db->getConnection();
                            //if(!empty($host_organisation)){
                                // Check whether group host already exists in the database
                                $query = ['nationality' => strtolower($nationality),];
                                $option = [];
                    
                                $queryDriver = new MongoDB\Driver\Query($query, $option);
                                $bGroup = $conn->executeQuery("$dbname.$collnationality", $queryDriver);
                                $bGroup = $bGroup->toArray(); 
                                $nGroup=count($bGroup);
                                if ($nGroup <= 0){
                                    $hOrg=array(
                                        'nationality'  => strtolower($nationality),
                                    );
                
                                    // insert record
                                    $insert = new MongoDB\Driver\BulkWrite();
                                    $insert->insert($hOrg);
    
                                    $result = $conn->executeBulkWrite("$dbname.$collnationality", $insert);
                                    
                                    
                                    }; 
    /* ****************************************end Create nationality****************************************************** */
    /* ****************************************Create staff_category ****************************************************** */
    $collstaff_category= 'staff-category';
    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    //if(!empty($host_organisation)){
        // Check whether group host already exists in the database
        $query = ['staff_category' => strtolower($StaffCat),];
        $option = [];

        $queryDriver = new MongoDB\Driver\Query($query, $option);
        $bGroup = $conn->executeQuery("$dbname.$collstaff_category", $queryDriver);
        $bGroup = $bGroup->toArray(); 
        $nGroup=count($bGroup);
        if ($nGroup <= 0){
            $hOrg=array(
                'staff_category'  => strtolower($StaffCat),
            );

            // insert record
            $insert = new MongoDB\Driver\BulkWrite();
            $insert->insert($hOrg);

            $result = $conn->executeBulkWrite("$dbname.$collstaff_category", $insert);
            
            
            }; 
/* ****************************************end Create collstaff_category****************************************************** */


    /* ****************************************end Create Lists****************************************************** */
    
    
                /* ****************************************Create logs ****************************************************** */
               /*  $logEvent='Added '.$fullName.' with staff Id: '.$staffIDNo. ' to the staff record.';
                logs($logEvent, $executorsFullName, $logRole, $loguniqueId, $logEmail); */
                                    
                /* ****************************************end Create logs****************************************************** */
             $noCreated++;
            }else{
                $noFailedCreated++;
            }  

                    
                    };
            };
        }
            // Close opened CSV file
            fclose($csvFile);
            
            echo json_encode(
            array('success' => 1,
            'status' => 201,
            "message" => $noCreated." records where successfully created and ".$noFailedCreated." records could not be created." ));
   
/* ****************************************Create logs ****************************************************** */
                        /* $collLogs= 'logs';
                        //DB connection
                        $db = new DbManager();
                        $conn = $db->getConnection();
                            $log=array(
                                'event_date'  => $created,
                                'fullname'  => $executorsFullName,
                                'event'  =>'Uploaded excel file to update and or create new record',
                                'email'  => $logEmail,
                                'bursaryNo'  => $logUBS,
                                'role'  => $logRole,
                            );
                            // insert record
                            $insert = new MongoDB\Driver\BulkWrite();
                            $insert->insert($log);

                            $result = $conn->executeBulkWrite("$dbname.$collLogs", $insert); */
 /* ****************************************end Create logs****************************************************** */
           // }
        }else{
            echo json_encode(
                array('success' => 0,
                'status' => 400,
                'message' => 'Some problem occurred, please try again.'));
        }
    }else{
        echo json_encode(
            array('success' => 0,
            'status' => 400,
            'message' => 'Please upload a valid CSV file.'));
    }
}else{
    echo json_encode(
        array('success' => 0,
        'status' => 400,
        'message' => 'failed importSubmit.'));
}