<?php
header("Access-Control-Allow-Origin: localhost/giz/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require __DIR__.'/classes/configdb.php';

require_once 'library.php';
require_once 'sendcode.php';

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


    $email = "";
    $password = "";
    $email_err = "";
    $password_err = "";
 

    //$returnData = msg(1,201,'You have successfully loggedin.');

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
        $returnData = msg(0, 422, $email_err);
    } else {
        $email = trim($_POST["email"]);
    }

   

    //Validating inputs
    if (empty($email_err)) {

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
                   $fullName = $value['fullName'];
                   
                   $category = $value['role'];
                   $useremail = $value['email'];
                   $status = $value['status'];

                
                }
                $dbname = 'unibendb';
                $collection = 'userlogin';
                $resetCode = substr(sha1(time()), 0, 10);
                $exptim = time()+60*10;
                //update database
                $document = array( 
                    'reset_code'=> $resetCode,
                    'reset_code_exp'=>$exptim
                );
                $updateReset=update($document,  $collection, $userid);
                if( $updateReset){

                  //set cookies
                  $name =$fullName;
                        $path = 'path=/';
                        setcookie ("emailR", $useremail, $path);
                        
                        $auth2= $resetCode;
                        $mailSend = $email;
                        $mailS= "Password reset"; 
                        $mailB ="Please copy the following code to reset your password:";
                        $sendEmail=sendAuth($auth2, $mailSend, $name, $mailS, $mailB);
                        if($sendEmail){
                            $passOk = "OK!";
                            echo json_encode(
                            array('success' => 1,
                            'status' => 422,
                            'message' => $passOk));
                
                    }else{
                        $email_err = "Code not sent try again later.";
                        echo json_encode(
                            array('success' => 0,
                            'status' => 422,
                            'message' => $email_err)
                        );
                       
                    }
            }else{
                $email_err = "Error has occoured try again later.";
                echo json_encode(
                    array('success' => 0,
                    'status' => 422,
                    'message' => $email_err)
                );  
            }
        
                }else {
                $email_err = "You have entered wrong email.";
                echo json_encode(
                    array('success' => 0,
                    'status' => 201,
                    'message' => $email_err)
                );
                /* $returnData = msg(0, 422, $email_err);
                echo json_encode($returnData); */
            } 
        
           
}