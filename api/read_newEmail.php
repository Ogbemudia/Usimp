<?php
header("Access-Control-Allow-Origin: localhost/giz/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once('../core/initialize.php');
//require_once 'library.php';


/* function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
} 
 */


// GET DATA FORM REQUEST
/* $data = json_decode(file_get_contents("php://input"));
$returnData = []; */

    
//$verifyCode = $_POST["emailCode"];
$verifyCode=isset($_GET['emailCode'])? $_GET['emailCode'] : die();


 //verify credential

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
                   $postUpdateCode = $value['email_v_code'];
                   $fullName = $value['fullName'];
                   
                  // $login_status = $value['login_status'];
                  
                }
                echo json_encode(
                    array('success' => 1,
                    'status' => 201,
                    'messageN' => $fullName, 
                    'messageID' => $postUpdateCode)); 
                //echo json_encode($fullName);
}else{
    echo json_encode(
        array('success' => 0,
        'status' => 422,
        'message' => 'This link does not exist.')); 
}