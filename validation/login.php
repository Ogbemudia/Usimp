<?php
header('Access-Control-Allow-Origin: localhost');
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
require_once '../api/logsfunc.php';

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
       $collection = 'userlogin';
        
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
                   $fullName = $value['fullName'];
                   $role = $value['role'];
                   $right = $value['role']['right'];
                   $dept = $value['role']['dept'];
                   $privilege = $value['role']['privilege'];
                   $email = $value['email'];
                   $status = $value['status'];
                   $bursaryNo = $value['bursaryNo'];
                   $sex = $value['sex'];
                   $uniqueId = $value['uniqueId'];
                  

                
                 
                    $passw=$value['password'];
                }
                    $collection = 'userlogin';
                    
                    if (password_verify($hashed_password,$passw)) {
                        
                        
                        $date1 = date("F j, Y"); 
                        $tim = date("g:i a");
                        $succ = $date1. " at ".$tim;
                        $dbname = 'unibendb';
                        $collection = 'userlogin';
                        //update database
                        $document = array( 
                            'login_history'=> $succ
                            
                        );
                        update($document,  $collection, $uniqueId);
                          //set cookies
                       $name =$fullName;
                       /* $exptim = time()+60*10;
                       $expTime = time()+60*60;
                       $path = 'path=/';
                       header ("Set-Cookie: username=$name; $expTime; $path");
                       setcookie ("idlog", $exptim, $path); */
                       
                      // $auth2 = substr(sha1(time()), 0, 6);
                      /*  session_start();
                       $_SESSION['userlogin'] = $email;
                       $_SESSION['name'] = $name;
                       $_SESSION['role'] = $role;
                       $_SESSION["id"] = $userid;
                       $_SESSION["bursaryNo"] = $bursaryNo; */
                      
                    
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
                       header ("Set-Cookie: token=$token; $expire; $httponly; $path");

                       session_start();
                       $_SESSION['userlogin'] = $email;
                       $_SESSION['name'] = $name;
                       $_SESSION['role'] = $role;
                       $_SESSION['right'] = $right;
                       $_SESSION['dept'] = $dept;
                       $_SESSION['privilege'] = $privilege;
                       $_SESSION["id"] = $userid;
                       $_SESSION["bursaryNo"] = $bursaryNo;
                       $_SESSION["uniqueId"] = $uniqueId;
                       $_SESSION["sex"] = $sex;
                       
                       $returnData = [
                           'success' => 1,
                           'status' => 201,
                           'message' => 'You have successfully logged in.',
                           //'token' => $token,
                           'role' => $role,
                           //'privilege' => $privilege,
                           'name' => $name,
                           'uniqueId' => $uniqueId,
                       ];
                       echo json_encode($returnData);

                       /* ****************************************Create logs ****************************************************** */
                       $executorsFullName=$fullName;
                       $logRight=$right;
                       $loguniqueId=$uniqueId;
                       $logEmail=$email;
                       $sex = trim($sex);
                       $sex = strtolower($sex);
                        if($sex=='male'){
                            $gender='his';
                        }elseif($sex=='female'){
                            $gender='her';
                        }
                        $logEvent='logged into '.$gender.' profile page.';
                        logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail);
                                            
                        /* ****************************************end Create logs****************************************************** */
                                            
                        
                        
    
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
}else {
    $email_err = "You have not signin.";
    echo json_encode(
        array('success' => 0,
        'status' => 422,
        'message' => $email_err)
    );
    /* $returnData = msg(0, 422, $email_err);
    echo json_encode($returnData); */
} 