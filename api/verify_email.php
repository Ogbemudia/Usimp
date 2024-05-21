<?php
header('Access-Control-Allow-Origin: localhost/giz/');
header('Content-Type: application/json, charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');
require __DIR__.'validation.php';

require_once('../validation/classes/session.php');
login();
//$username = $_SESSION['userlogin'];
//Include required phpmailer files



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
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "verify"){

    $verifyCode = $_POST['emailVeriCode'];

    $dbname = 'unibendb';
    $collection = 'userlogin';
     
     //DB connection
     $db = new DbManager();
     $conn = $db->getConnection();
     $query = ['email_v_code' => $verifyCode];
     $option = [];

     $queryDriver = new MongoDB\Driver\Query($query, $option);

     $users = $conn->executeQuery("$dbname.$collection", $queryDriver)->toArray();
    
         if (count($users) > 0){ 

             $user=json_encode($users);
             $result = json_decode($user, true);
             foreach ($result as $value) {
                
                $userid = $value['_id']['$oid'];
                
                $fullName = $value['fullName'];
                $email = $value['email'];
                
                
             }

             $document = array(

                "email_status" => 'Verified',
                "email_v_code" => 'This email has been verified'
               
            );
            update($document,  $collection, $userid);
            $data = array(
    
                "email_status" => 'Verified'
            );
            $profile = 'p-o-profile';
            update_profile_by_email($data, $profile, $email);

            echo json_encode(
                array('success' => 1,
                'status' => 201,
                'message1' => 'Congratulations'.' ' .$fullName.'.',
                'message2' => 'Your registration was successful, an email will be send to you if your is approved'));
         }else{
            echo json_encode(
                array('success' => 0,
                'status' => 403,
                'message' => 'You have entered the wrong email verification code.'));
                die;
         }

         
        

}