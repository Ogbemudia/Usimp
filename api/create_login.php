<?php
header('Access-Control-Allow-Origin: localhost/Uniben/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require __DIR__.'/validation.php';

require_once('../validation/classes/session.php');
login();
$username = $_SESSION['userlogin'];  
//Include required phpmailer files



/* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
} */
function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

//initializing api
include_once('../core/initialize.php');
include_once('sendverify.php');
require_once('../validation/library.php');
require_once('create_profile.php');

$dbname = 'unibendb';

//$dbname = 'unibendb';
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "signUp"){
    $role = $_POST['role'];
    $password     = trim($_POST["ubsno"]);
    $password     = strtolower($password);
    $password     = password_hash($password, PASSWORD_DEFAULT); //this creates a hash password.
    $login_role=$role;
    
    
        $date1 = date("F j, Y"); 
        $tim = date("g:i a");
        $created = $date1. " at ".$tim;
        $fullName = $_POST['fullName'];
        $gender = $_POST['gender'];
        $ubsno = strtolower($_POST['ubsno']);
        $email = trim($_POST["email"]);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $email_err= "Please enter a valid email.";
                        $returnData = msg(0,422,$email_err);
                        echo json_encode($returnData);
                    };
    
        if(empty($email_err)){
            $collection = 'userlogin';
            $query = chkemail($email, $collection);
            if($query){
                /*************************verify email********************************/
                /* $name = $fullName;     
                $mailSend= $email;
                $mailS= "Email Verification"; 
                $numgen = rand(0,99);
                $timeReg = time();
                $codegen = $numgen. $timeReg;
                $verifyCode = substr(sha1($codegen), 0, 14); 
                $createPass = 'http://staffprofile.uniben.edu/login/create_password.html?'.$verifyCode;
                $mailB ="Please click on this link: $createPass to complete your registration:";

                
                $verify=sendAuth($verifyCode, $mailSend, $name, $mailS, $mailB);
                if($verify){ */
                    /*************************create suspended login ********************************/

                   
                        $document = array(

                            "fullName"                 => $fullName,
                            "email"                    => $email,
                            "password"                 => $password,
                            "bursaryNo"                => $bursaryNo,
                            "status"                   => 'suspended',
                            //"email_v_code"             => $verifyCode,
                            "role"                     => $role,
                            "gender"                   => $gender,
                            "created"                  => $created
                        
                        );
                        
                         register($document, $collection);
                   
                   
                /* }else{
                    echo json_encode(
                        array('success' => 0,
                        'status' => 422,
                        'message' => 'Registation not successful, verification not sent.'));
                        die;
                }; */
                /*************************end verify email********************************/

        }else{
             echo json_encode(
                array('success' => 0,
                'status' => 422,
                'message' => 'This email has already been used.'));        
            
            };           
                        
        }else{
            echo json_encode(
                array('success' => 0,
                'status' => 422,
                'message' => 'Registation not successful, please enter a valid email.'));
                die;
           };

        
    
}