<?php
header("Access-Control-Allow-Origin: localhost/uniben/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once('../core/initialize.php');

require_once('../validation/library.php');
require_once('create_profile.php');


/* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
} 
 */


// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

    
$verifyCode = trim($_POST["emailCode"]);
$date1 = date("F j, Y"); 
$tim = date("g:i a");
$createdPassword = $date1. " at ".$tim;
$password = trim($_POST['password']);


 //verify credential

       $dbname = 'unibendb';
       $collection = 'userlogin';
       //$profile = 'm_e_profile';
        
       
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
                   $role = $value['role'];
                  /* $login_status = $value['login_status']; */
                  
                }
               
            //if(($email_status=='unverified') && ($login_status=='notInUse')){
                
                $password = password_hash($password, PASSWORD_DEFAULT);

                $document = array( 
                    'email_status'        => 'verified',
                    'status'              => 'active',
                    'email_v_code'        => 'codeConsumed',
                    'password_created_at' => $createdPassword,
                    'password'            => $password
                    
                );
               $update= update($document,  $collection, $userid);
               if($update){
               
                    echo json_encode(
                    array('success' => 1,
                    'status' => 200,
                    'message' => 'Password set successful.')); 
               }else{
                echo json_encode(
                    array('success' => 0,
                    'status' => 422,
                    'message' => 'Password set failed..')); 
            }

               
   
           // }

           
}else{
    echo json_encode(
        array('success' => 0,
        'status' => 422,
        'message' => 'This link does not exist.')); 
}