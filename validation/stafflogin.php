<?php
header("Access-Control-Allow-Origin: localhost/uniben/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('vendor/autoload.php');
use \Firebase\JWT\JWT;
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

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "login"){



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

    //validate password.
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
        $returnData = msg(0, 422, $password_err);
    } else {
        $password = trim($_POST["password"]);
    }
   // $returnData = msg(0, 422, $email);
 

    //Validating inputs
    if (empty($email_err) && empty($password_err)) {

 //verify credential

       $dbname = 'unibendb';
       $collection = 'staff-profile';
        
       $hashed_password=$password;
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
                   $first_name = $value['first_name'];
                   $last_name = $value['last_name'];
                   $ubsno = $value['ubsno'];
                   $email = $value['email'];
                   $status = $value['status'];
                  

                
                 
                }
                    $collection = 'staff-profile';
                    
                    if ($ubsno==$password) {
                        
                        $fullName = $first_name.' '.$last_name;
                        $role='staff';
                        $date1 = date("F j, Y"); 
                        $tim = date("g:i a");
                        $succ = $date1. " at ".$tim;
                        $dbname = 'unibendb';
                        $collection = 'staff-profile';
                        //update database
                        $document = array( 
                            'login_history'=> $succ
                            
                        );
                        update($document,  $collection, $userid);
                          //set cookies
                       $name =$fullName;
                      /*  $exptim = time()+60*10;
                       $expTime = time()+60*60;
                       $path = 'path=/';
                       header ("Set-Cookie: username=$name; $expTime; $path");
                       setcookie ("idlog", $exptim, $path); */
                       
                      // $auth2 = substr(sha1(time()), 0, 6);
                       session_start();
                       $_SESSION['stafflogin'] = $email;
                       $_SESSION['name'] = $name;
                       $_SESSION['role'] = $role;
                       $_SESSION["id"] = $userid;
                       $_SESSION["ubsno"] = $ubsno;
                       /* $_SESSION["auth"] = $auth2; 
                       $mailSend = $email;
                       $mailS= "Authorize Log In Attempt"; 
                       $mailB ="Please copy the following authentication code to authenticate your login:"; */

                       /* $sendEmail=sendAuth($auth2, $mailSend, $name, $mailS, $mailB);
                       if($sendEmail){
                        $passOk = "Authentication code sent!";
                        echo json_encode(
                          array('success' => 1,
                          'status' => 201,
                          'message' => $passOk));

                       // require_once('vendor/auth2.php');
                       }else{
                        echo json_encode(
                            array('success' => 0,
                            'status' => 422,
                            'message' => 'Authentication failed, check your internet'));
                       } */
                    
                       $issuedAt = time();
                       $expirationTime = $issuedAt + 1800*5;  // jwt valid for 60 seconds from the issued time
                       $payload = array(
                          // 'userid' => $userid,
                           'iat' => $issuedAt,
                           'exp' => $expirationTime,
                           'iss' => 'localhost/unben/validation',
                           'aud' => 'localhost/unben/'
                       );
                       $key = 'GiZkey%visions6689#king%';
                       $alg = 'HS512';
                       $jwt = JWT::encode($payload, $key, $alg);
                       $token = $jwt;
                       $expire=time()+1800*5;
                       $httponly='HttpOnly';
                       $path = 'path=/';
                       header ("Set-Cookie: token=$token; $expire; $path; $httponly");
                       
                       $returnData = [
                           'success' => 1,
                           'status' => 201,
                           'message' => 'You have successfully logged in.',
                           //'token' => $token,
                           'role' => $role
                       ];
                       echo json_encode($returnData);
                      
                        
                        
    
                    }else{
                        $email_err = "You have entered wrong password or email.";
                        echo json_encode(
                            array('success' => 0,
                            'status' => 422,
                            'message' => $email_err)
                        );
                       /*  $returnData = msg(0, 422, $email_err);
                        echo json_encode($returnData); */
                        $date1 = date("F j, Y"); 
                        $tim = date("g:i a");
                        $attempt = $date1. " at ".$tim;
                            //update database
                        $document = array(
                            "failed login"      => $attempt
                                );
                          update($document,  $collection, $userid);
                    }
        
                }else {
                $email_err = "You have entered wrong password or email.";
                echo json_encode(
                    array('success' => 0,
                    'status' => 422,
                    'message' => $email_err)
                );
                /* $returnData = msg(0, 422, $email_err);
                echo json_encode($returnData); */
            } 
        
           
}
}