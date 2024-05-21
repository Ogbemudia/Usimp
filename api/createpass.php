<?php
header("Access-Control-Allow-Origin: localhost/uniben/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require __DIR__.'/classes/configdb.php';

require_once 'library.php';


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

    
$email = trim($_POST["email"]);


 //verify credential

       $dbname = 'unibendb';
       $collection = 'userlogin';
        
       
        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        $query = ['email' => $email];
        $option = [];

        $queryDriver = new MongoDB\Driver\Query($query, $option);

        $users = $conn->executeQuery("$dbname.$collection", $queryDriver)->toArray();
       
            if (count($users) > 0){ 

                $user=json_encode($users);
                $result = json_decode($user, true);
                foreach ($result as $value) {
                   $userid = $value['_id']['$oid'];
                   $email_status = $value['email_status'];
                   $login_status = $value['login_status'];
                  
                }
               
            if(($email_status=='unverified') && ($login_status=='notInUse')){
                $password = $_POST['password'];
                $password = password_hash($password, PASSWORD_DEFAULT);

                $document = array( 
                    'email_status'=> 'verified',
                    'status'=> 'active',
                    'password'=> $password
                    
                );
               $update= update($document,  $collection, $userid);

            }
           
}else{
    echo json_encode(
        array('success' => 0,
        'status' => 422,
        'message' => 'This email is invalid')); 
}