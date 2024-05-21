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

require_once('authfunc.php');

$accRights=array('admin', 'registry');
$accPrivilege=array('create');
$RoleZ = role($privilege, $logRight, $accRights, $accPrivilege);
if (!$RoleZ) {
    //echo 'you dont have the right to access this api';
    header("location: ../validation/logout.php");
    exit;
}



/* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}   */

//initializing api
include_once('../core/initialize.php');
include_once('sendverify.php');
require_once('../validation/library.php');
require_once('create_profile.php');
require_once('logsfunc.php');


$dbname = 'unibendb';


//if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "signup"){
    
    $date1 = date("F j, Y"); 
	$tim = date("g:i a");
	$created = $date1. " at ".$tim;
    
                // Get row data
                $title                        = $_POST['title'];
                $firstName                    = $_POST['firstName'];
                $middleName                   = $_POST['middleName'];
                $surname                      = $_POST['surname'];
                $sex                          = $_POST['sex'];
                $dOb                          = $_POST['dOb'];
                $placeOfBirth                 = $_POST['placeOfBirth'];
                $marritalStatus               = $_POST['marritalStatus'];
                $staffCat                     = $_POST['staffCat'];
                $picName                      = $_POST['picName'];
                $staffIDNo                    = $_POST['staffIDNo'];
                $nationality                  = $_POST['nationality'];
                //contact
                $LGAofOrigin                  = $_POST['LGAofOrigin'];
                $stateOfOrigin                = $_POST['stateOfOrigin'];
                $geoPoliticalZone             = $_POST['geoPoliticalZone'];
                $wardOfOrigin                 = $_POST['wardOfOrigin'];
                $stateOfRes                   = $_POST['stateOfRes'];
                $LGAOfRes                     = $_POST['LGAOfRes'];
                $PAddress                     = $_POST['PAddress'];
                $Caddress                     = $_POST['Caddress'];
                $phone                        = $_POST['phone'];
                $email                        = $_POST['email'];
                //spouse
                $spouseFirstName              = $_POST['spouseFirstName'];
                $spouseMiddleName             = $_POST['spouseMiddleName'];
                $spouseLastName               = $_POST['spouseLastName']; 
                $spouseOccupation             = $_POST['spouseOccupation'];
                $spousePlaceOfWork            = $_POST['spousePlaceOfWork']; 
                $spouseResedentialAddress     = $_POST['spouseResedentialAddress']; 
                $spousePhone                  = $_POST['spousePhone'];
                //children 
                $childName1                   = $_POST['childName1']; 
                $childBirth1                  = $_POST['childBirth1']; 
                $childSex1                    = $_POST['childSex1']; 
                $childName2                   = $_POST['childName2']; 
                $childBirth2                  = $_POST['childBirth2']; 
                $childSex2                    = $_POST['childSex2']; 
                $childName3                   = $_POST['childName3']; 
                $childBirth3                  = $_POST['childBirth3']; 
                $childSex3                    = $_POST['childSex3']; 
                $childName4                   = $_POST['childName4']; 
                $childBirth4                  = $_POST['childBirth4']; 
                $childSex4                    = $_POST['childSex4'];
                //next of kin
                $NokFirstName                 = $_POST['NokFirstName']; 
                $NokMiddleName                = $_POST['NokMiddleName']; 
                $NokLastName                  = $_POST['NokLastName']; 
                $relationship                 = $_POST['relationship']; 
                $NokSex                       = $_POST['NokSex']; 
                $NokContackAdd                = $_POST['NokContackAdd']; 
                $NokAddress                   = $_POST['NokAddress']; 
                $NokEmail                     = $_POST['NokEmail']; 
                $NokPhoneNumber               = $_POST['NokPhoneNumber'];
                //registry info 
                $PFileNo                     = $_POST['PFileNo']; 
                $faculty                     = $_POST['faculty']; 
                $dept                        = $_POST['dept']; 
                $unit                        = $_POST['unit']; 
                $designation                 = $_POST['designation']; 
                $designationOnFirstApp       = $_POST['designationOnFirstApp']; 
                $postion                     = $_POST['postion']; 
                $areaOfSpecialization        = $_POST['areaOfSpecialization']; 
                $acadQuatnsWithDate          = $_POST['acadQuatnsWithDate']; 
                $PHYCHAL                     = $_POST['PHYCHAL']; 
                $union                       = $_POST['union']; 
                $date_employed               = $_POST['date_employed']; 
                $date_confirmed              = $_POST['date_confirmed']; 
                $staff_Status                = $_POST['staff_Status']; 
                $dateOfAssumptionOfDuty      = $_POST['dateOfAssumptionOfDuty']; 
                $cadreOn1stApp               = $_POST['cadreOn1stApp']; 
                $levelOn1stApp               = $_POST['levelOn1stApp']; 
                $stepOn1stApp                = $_POST['stepOn1stApp']; 
                $currentLevel                = $_POST['currentLevel']; 
                $currentStep                 = $_POST['currentStep']; 
                $currentCadre                = $_POST['currentCadre']; 
                $incStep                     = $_POST['incStep']; 
                $dateOfRetirementByAge       = $_POST['dateOfRetirementByAge']; 
                $exitDates                   = $_POST['exitDates']; 
                $dateOfRetirementByEmpl      = $_POST['dateOfRetirementByEmpl']; 
                $dateOfRetirement            = $_POST['dateOfRetirement']; 
                $dateOfResignation           = $_POST['dateOfResignation']; 
                $employmentHist              = $_POST['employmentHist']; 
                $leaveStatus                 = $_POST['leaveStatus']; 
                $leaveType                   = $_POST['leaveType']; 
                $lastPromDate                = $_POST['lastPromDate']; 
                //$activeLeave                 = $_POST['activeLeave']; 
                $leaveEffectDate             = $_POST['leaveEffectDate']; 
                $leaveResumeDate             = $_POST['leaveResumeDate']; 
                $leaveNoDays                 = $_POST['leaveNoDays']; 
                $leaveExpireDate             = $_POST['leaveExpireDate'];  
                $groupCadre                  = $_POST['groupCadre']; 
                $staffType                   = strtolower($_POST['staffType']); 
                $initial                     = $_POST['initial']; 
                $CONTSRTDATE                 = $_POST['CONTSRTDATE']; 
                $CONTEXPDATE                 = $_POST['CONTEXPDATE']; 
                $NoOfRenewedContract         = $_POST['NoOfRenewedContract']; 
                //bursary
                $bursaryNo                   = $_POST['bursaryNo']; 
                $IPPIS_NO                    = $_POST['IPPIS_NO']; 
                //$staff_category              = $_POST['staff_category']; 
                $SalaryCategory              = $_POST['SalaryCategory']; 
                $bankName                    = $_POST['bankName']; 
                $accountNumber               = $_POST['accountNumber']; 
                $PFANAme                     = $_POST['PFANAme']; 
                $PENSION_PIN                 = $_POST['PENSION_PIN'];
                $certification               = $_POST['certification']; 

                              
                if(!empty($staffIDNo)){

                    // Check whether member already exists in the database with the same email
                    $collection = 'userlogin';
                    $dbname = 'unibendb';
                    
                    //DB connection
                    $db = new DbManager();
                    $conn = $db->getConnection();
                    $query = ['staffIDNo' => $staffIDNo];
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
                    end generate unique Id
                    ************************************* */
                    if (!empty($date_confirmed)) {
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
                                'awards'                    => [],                                        
                                'staffIDNo'                 => $staffIDNo,                                        
                                'orcid_no'                 => '',                                        
                                'nationality'               => $nationality                                        
                             ],
                             'contact'=>[
                                'LGAofOrigin'               => $LGAofOrigin,                                        
                                'stateOfOrigin'             => $stateOfOrigin,                                        
                                'geoPoliticalZone'          => $geoPoliticalZone,                                        
                                'wardOfOrigin'              => $wardOfOrigin,                                        
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
                                'relationship'           => $relationship,                                        
                                'NokSex'                 => $NokSex,                            
                                'NokContackAdd'          => $NokContackAdd,                            
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
                                'designationOnFirstApp' => strtolower($designationOnFirstApp),               
                                'postion'               => $postion,                                 
                                'areaOfSpecialization'  => $areaOfSpecialization,
                                'acadQuatnsWithDate'    => $acadQuatnsWithDate,                                        
                                'certification'         => $certification,                                        
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
                                'exitDates'             => $exitDates,                    
                                'dateOfResignation'     => $dateOfResignation,
                                'employmentHist'        => $employmentHist,
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
                                    'periodOfReport'=>'',
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
                                            'central_A_and_PB_NA'=>[
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

                                            

                                            'central_A_and_PB_NA'=>[
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

                                                'external_assessor'=>[
                                                    'status'=>'',
                                                    'Name'=>'',
                                                    'comment'=>'',
                                                    'assessor_signature'=>'',
                                                    'date'=>'',
                                                  ],
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
                                          
                                          'A_and_PB_sub_committee_A'=>[
                                            'status'=>'',
                                            'recommendation'=>'',
                                            'to_be_promoted_to'=>'',
                                            'chairman_signature'=>'',
                                            'date'=>'',
                                          ],
                                          
                                          

                                          'central_A_and_PB_A'=>[
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
                                'groupCadre'            => strtolower($groupCadre),
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
                                "dept"     => [],
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
                            $query = ['staff_category' => strtolower($staffCat),];
                            $option = [];
                
                            $queryDriver = new MongoDB\Driver\Query($query, $option);
                            $bGroup = $conn->executeQuery("$dbname.$collstaff_category", $queryDriver);
                            $bGroup = $bGroup->toArray(); 
                            $nGroup=count($bGroup);
                            if ($nGroup <= 0){
                                $hOrg=array(
                                    'staff_category'  => strtolower($staffCat),
                                );
            
                                // insert record
                                $insert = new MongoDB\Driver\BulkWrite();
                                $insert->insert($hOrg);

                                $result = $conn->executeBulkWrite("$dbname.$collstaff_category", $insert);
                                
                                
                                }; 
                    /* ****************************************end Create collstaff_category****************************************************** */
                    /* ****************************************Create groupCadre ****************************************************** */
                    $collgroupCadre= 'group-cadre';
                    //DB connection
                    $db = new DbManager();
                    $conn = $db->getConnection();
                    //if(!empty($host_organisation)){
                        // Check whether group host already exists in the database
                        $query = ['groupCadre' => strtolower($groupCadre),
                                    'staff_category'  => strtolower($staffCat)];
                        $option = [];

                        $queryDriver = new MongoDB\Driver\Query($query, $option);
                        $bGroup = $conn->executeQuery("$dbname.$collgroupCadre", $queryDriver);
                        $bGroup = $bGroup->toArray(); 
                        $nGroup=count($bGroup);
                        if ($nGroup <= 0){
                            $hOrg=array(
                                'staff_category'  => strtolower($staffCat),
                                'groupCadre'  => strtolower($groupCadre),
                            );

                            // insert record
                            $insert = new MongoDB\Driver\BulkWrite();
                            $insert->insert($hOrg);

                            $result = $conn->executeBulkWrite("$dbname.$collgroupCadre", $insert);
                            
                            
                            }; 
                /* ****************************************end Create groupCadre****************************************************** */
                /*************************create publication********************************/
                        $collPublication= 'publications';
                        //DB connection
                        
                            $pubData=array(

                                'uniqueId'                      => $uniqueId,
                                'bursaryNo'                     => $bursaryNo,                             
                                'staffIDNo'                     => $staffIDNo,
                                'title'                         => $title,                                                
                                'fullName'                      => $firstName.' '.$middleName.' '.$surname,                             
                                'biography'                     => '',                                                  
                                'email'                         => $email,                                                  
                                'orcid_no'                      => '',                                                  
                                'faculty'                       => strtoupper($faculty),                                        
                                'dept'                          => strtoupper($dept),                                         
                                'staff_category'                => strtoupper($staffCat),
                                'last_update'                   => $created,   
                            );  
                            // insert record
                            create_profile($pubData, $collPublication);
                        

            /*************************end create publication********************************/


                /* ****************************************end Create Lists****************************************************** */


            /* ****************************************Create logs ****************************************************** */
            $logEvent='Added '.$fullName.' with staff Id: '.$staffIDNo. ' to the staff record.';
            logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                            
            /* ****************************************end Create logs****************************************************** */
            echo json_encode(
                array('success' => 1,
                    'status' => 201,
                    "message" => " Record successfully created. " ));
           
        }else{
            echo json_encode(
                array('success' => 0,
                    'status' => 401,
                    "message" => "  Record could not be created try again later."));
           
        }
    }elseif($existusers > 0){
        $staff=json_encode($users);
        $staffRec = json_decode($staff, true);

        
        foreach ($staffRec as $value) {
            //$userid = $value['_id']['$oid'];
            $uniqueId = $value['uniqueId'];
        }

        if (!empty($date_confirmed)) {
            $appointmentStatus='confirmed';
        } else {
            $appointmentStatus='';
        }

        $leaveDate2   = $dateOfAssumptionOfDuty;
        $leaveDate2   = explode('/', $leaveDate2);
        $leaveDay     = $leaveDate2[0];
        $leaveMonth   = $leaveDate2[1];
        $leaveYear    = $leaveDate2[2];
        // Update staff data in the database


        $data=array(

            'title'                         => $title,                                                
            'first_name'                    => $firstName,                                               
            'middleName'                    => $middleName,
            'surname'                       => $surname, 
            'last_updated'                  => $created,                                
            
            'staff_profile.sex'                 => $sex,                             
            'staff_profile.dOb'                 => $dOb,                             
            'staff_profile.placeOfBirth'        => $placeOfBirth,                                                  
            'staff_profile.marritalStatus'      => $marritalStatus,                                                  
            'staff_profile.staffCat'            => strtolower($staffCat),                                            
            //'staff_profile.picName'             => '',//$picName,                                        
            //'staff_profile.cv'                  => '',//$picName,                                        
            //'staff_profile.biography'           => '',//$picName,                                        
            'staff_profile.awards'              => [],//$picName,                                        
            'staff_profile.professional_qual'   => [],//$picName,                                        
            'staff_profile.staffIDNo'           => $staffIDNo,                                        
            'staff_profile.nationality'         => $nationality,                                        
            
            'contact.LGAofOrigin'               => $LGAofOrigin,                                        
            'contact.stateOfOrigin'             => $stateOfOrigin,  
            'contact.geoPoliticalZone'          => $geoPoliticalZone,                                        
            'contact.wardOfOrigin'              => $wardOfOrigin,                                       
            'contact.stateOfRes'                => $stateOfRes,                    
            'contact.LGAOfRes'                  => $LGAOfRes,                            
            'contact.PAddress'                  => $PAddress,               
            'contact.Caddress'                  => $Caddress,                                 
            'contact.phone'                     => $phone,               
            'contact.email'                     => $email,
            'spouse.spouseFirstName'           => $spouseFirstName,                                        
            'spouse.spouseMiddleName'          => $spouseMiddleName,                                        
            'spouse.spouseLastName'            => $spouseLastName,                    
            'spouse.spouseOccupation'          => $spouseOccupation,                            
            'spouse.spousePlaceOfWork'         => $spousePlaceOfWork,               
            'spouse.spouseResedentialAddress'  => $spouseResedentialAddress,                                 
            'spouse.spousePhone'               => $spousePhone,               
        
            'children.child1.childName'           => $childName1,                                        
            'children.child1.date_of_Birth'       => $childBirth1,                                        
            'children.child1.childSex'            => $childSex1,                    
               
            'children.child2.childName'           => $childName2,                                        
            'children.child2.date_of_Birth'       => $childBirth2,                                        
            'children.child2.childSex'            => $childSex2,                    
                
            'children.child3.childName'           => $childName3,                                        
            'children.child3.date_of_Birth'       => $childBirth3,                                        
            'children.child3.childSex'            => $childSex3,                    
                
            'children.child4.childName'           => $childName4,                                        
            'children.child4.date_of_Birth'       => $childBirth4,                                        
            'children.child4.childSex'            => $childSex4,                    
                
           
                /* 'next_of_kin.NokFirstName'        => $NokFirstName,                                        
                'next_of_kin.NokMiddleName'       => $NokMiddleName,                                        
                'next_of_kin.NokLastName'         => $NokLastName,
                'next_of_kin.relationship'        => $relationship,         
                'next_of_kin.NokSex'              => $NokSex,                            
                'next_of_kin.NokContackAdd'       => $NokContackAdd,               
                'next_of_kin.NokAddress'          => $NokAddress,               
                'next_of_kin.NokEmail'            => $NokEmail,                                 
                'next_of_kin.NokPhoneNumber'      => $NokPhoneNumber,   */             
             //'registry'=>[
                'registry.PFileNo'               => $PFileNo,                                        
                'registry.faculty'               => strtolower($faculty),                                        
                'registry.dept'                  => strtolower($dept),                    
                'registry.unit'                  => strtolower($unit),                            
                'registry.designation'           => strtolower($designation),               
                'registry.designationOnFirstApp' => strtolower($designationOnFirstApp),               
                'registry.postion'               => $postion,                                 
                'registry.areaOfSpecialization'  => $areaOfSpecialization,
                'registry.acadQuatnsWithDate'    => $acadQuatnsWithDate,                                        
                'registry.certification'         => [$certification],                                        
                'registry.PHYCHAL'               => $PHYCHAL,                                        
                'registry.union'                 => $union,                    
                'registry.date_employed'         => $date_employed,                            
                'registry.date_confirmed'        => $date_confirmed,               
                'registry.staff_Status'          => $staff_Status,                                 
                'registry.dateOfAssumptionOfDuty'=> $dateOfAssumptionOfDuty,
                'registry.cadreOn1stApp'         => strtolower($cadreOn1stApp),                                        
                'registry.levelOn1stApp'         => $levelOn1stApp,                                        
                'registry.stepOn1stApp'          => $stepOn1stApp,                    
                'registry.currentLevel'          => $currentLevel,                            
                'registry.currentStep'           => $currentStep,               
                'registry.currentCadre'          => $currentCadre,                                 
                'registry.incStep'               => $incStep,
                'registry.dateOfRetirementByAge' => $dateOfRetirementByAge,                                        
                'registry.dateOfRetirementByEmpl'=> $dateOfRetirementByEmpl,                                        
                'registry.dateOfRetirement'      => $dateOfRetirement,                    
                'registry.exitDates'             => $exitDates,                    
                'registry.dateOfResignation'     => $dateOfResignation,                            
                'registry.employmentHist'        => $employmentHist,               
                //'registry.leaveStatus'           => $leaveStatus,               
                //'registry.leaveType'             => $leaveType,                                 
                'registry.leaveMonth'            => $leaveMonth,                                 
                'registry.leaveDay'              => $leaveDay,                                 
                //'registry.leaveEffectDate'       => $leaveEffectDate,
                //'registry.leaveResumeDate'       => $leaveResumeDate,
                //'registry.leaveNoDays'           => $leaveNoDays,                                        
                //'registry.leaveExpireDate'       => $leaveExpireDate,                                        
                'registry.groupCadre'            => strtolower($groupCadre),                    
                'registry.staffType'             => strtolower($staffType),                    
                'registry.initial'               => $initial,                            
                'registry.CONTSRTDATE'           => $CONTSRTDATE,               
                'registry.CONTEXPDATE'           => $CONTEXPDATE,                                 
                'registry.NoOfRenewedContract'   => $NoOfRenewedContract,               
                'registry.promotion.lastPromDate'   => $lastPromDate,               
                'registry.promotion.staff_under_review.date_and_grade_on_first_appointment'   => $date_employed.' grade level '.$levelOn1stApp.' step '.$stepOn1stApp.'.',               
                'registry.promotion.staff_under_review.appointment_confirmation.appointmentStatus'   => $appointmentStatus,               
                'registry.promotion.staff_under_review.appointment_confirmation.date_confirmed'   => $date_confirmed,               
             //],
                'bursary.bursaryNo'             => $bursaryNo,                                        
                'bursary.IPPIS_NO'              => $IPPIS_NO,                                        
                'bursary.SalaryCategory'        => strtolower($SalaryCategory),                    
                'bursary.bankName'              => $bankName,                            
                'bursary.accountNumber'         => $accountNumber,               
                'bursary.PFANAme'               => $PFANAme,                                 
                'bursary.PENSION_PIN'           => $PENSION_PIN,               
                         
                        
     );
     
     
     $profile='staff-profile';
     $staffUpdate =update_by_uniqueId($data,  $profile, $uniqueId);
     if($staffUpdate){
          /*************************update login ********************************/

          $fullName = $title.' '.$firstName.' '.$middleName.' '.$surname;     
          $document = array(

            //"uniqueId"                 => $uniqueId,
            "fullName"                 => $fullName,
            "email"                    => $email,
            //"password"                 => $password,
            "bursaryNo"                => $bursaryNo,
            "staffIDNo"                => $staffIDNo,
            "designation"              => $designation,
            "postion"                  => $postion,
            //"signature"                => '',
            "status"                   => 'active',
            //"email_v_code"             => $verifyCode,
            //"role"                     => $role,
            "sex"                      => $sex,
            //"created"                  => $created,
            "last_update"              => $created
        
        );
        
        update($document,  $collection, $uniqueId);
   
   
            /*************************end create login********************************/
            /*************************update publication********************************/
            $collPublication= 'publications';
            //DB connection
            $db = new DbManager();
            $conn = $db->getConnection();
            $query = ['uniqueId'=>$uniqueId];
            $option = [];
            $queryDriver = new MongoDB\Driver\Query($query, $option);
            $pubResult = $conn->executeQuery("$dbname.$collPublication", $queryDriver)->toArray();
        
            if (count($pubResult) > 0) {
                $pubData=array(

                    //'uniqueId'                      => $uniqueId,
                    'bursaryNo'                     => $bursaryNo,                             
                    'staffIDNo'                     => $staffIDNo,
                    'title'                         => $title,                                                
                    'fullName'                      => $firstName.' '.$middleName.' '.$surname,                             
                    'biography'                     => '',                                                  
                    'email'                         => $email,                                                  
                    //'orcid_no'                      => '',                                                  
                    'faculty'                       => strtoupper($faculty),                                        
                    'dept'                          => strtoupper($dept),                                         
                    'staff_category'                => strtoupper($staffCat),
                    'last_update'                   => $created,   
                );  
                $update = new MongoDB\Driver\BulkWrite();
                $update->update(['uniqueId' => $uniqueId], ['$set' => $pubData], ['multi' => false, 'upsert' => false]);
            }

            /*************************end update publication********************************/

                /* ****************************************Create logs ****************************************************** */
                $logEvent='Updated the staff record of '.$fullName.' with staff Id: '.$staffIDNo. '.';
                logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                    
                /* ****************************************end Create logs****************************************************** */
                echo json_encode(
                    array('success' => 1,
                        'status' => 201,
                        "message" => " Record successfully updated. " ));
            }else{
                echo json_encode(
                    array('success' => 0,
                        'status' => 401,
                        "message" => "  Record could not be updated try again later."));
            }

        //}
    }
    /* echo json_encode(
        array('success' => 1,
            'status' => 201,
            "message" => $noCreated." records where successfully created and ".$noFailedCreated." records could not be created. While ".$noUpdated." records where successfully updated and ".$noFailedUpdate." records could not be updated." ));
    */
    
}else{
    echo json_encode(
        array('success' => 0,
        'status' => 400,
        'message' => 'Error! staffIDNo is empty.'));
}