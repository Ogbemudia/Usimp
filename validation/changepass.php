<?php
header("Access-Control-Allow-Origin: localhost/giz/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require __DIR__.'/classes/configdb.php';

require_once 'library.php';



// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

session_start();
$email=$_SESSION['userlogin'];
// $_SESSION['category'] = $category;
$userid=$_SESSION["id"];

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['crud_req'] == "changePass"){

	$oldPass = $_POST['old_password'];
    $newPass = $_POST['new_password'];
   // $password = password_hash($password, PASSWORD_DEFAULT);
                        
    $date1 = date("F j, Y"); 
    $tim = date("g:i a");
    $succ = $date1. " at ".$tim;
    $dbname = 'unibendb';
    $collection = 'userlogin';

    $hashed_password=$oldPass;
        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        $query = ['_id' => new MongoDB\BSON\ObjectId($userid)];
        $option = [];

        $queryDriver = new MongoDB\Driver\Query($query, $option);

        $users = $conn->executeQuery("$dbname.$collection", $queryDriver)->toArray();
       
            if (count($users) > 0){ 

                $user=json_encode($users);
                $result = json_decode($user, true);
                foreach ($result as $value) {
                   /* $userid = $value['_id']['$oid'];
                   $fullName = $value['fullName'];
                   $role = $value['role'];
                   $email = $value['email'];
                   $status = $value['status']; */

                    $passw=$value['password'];
                }
                   
                    
                    if (password_verify($hashed_password,$passw)) {
                        if(!password_verify($newPass,$passw)){
                            //update database
                            $document = array( 
                                'password_update'=> $succ,
                                'password'=> $newPass
                                                        
                            );
                            $update= update($document,  $collection, $userid);
                            if($update){
                            
                                $passOk = "Password successfully changed";
                                echo json_encode(
                                array('success' => 1,
                                'status' => 201,
                                'message' => $passOk));
                                }
                        
                        }else{
                            
                                $passOk = "You cannot reset to the old password";
                                echo json_encode(
                                array('success' => 0,
                                'status' => 422,
                                'message' => $passOk));
                            }

                    }else{
                        echo json_encode(
                            array('success' => 0,
                            'status' => 422,
                            'message' => 'Old password is incorrect.'));
                       }
                    }
                }             
 