<?php
header("Access-Control-Allow-Origin: localhost/Uniben/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/validation.php';

require_once('classes/session.php');
login();
$role4 =  $_SESSION["role"];
if($role4 !=='admin'){
    header("location: logout.php");
    exit;
} 
require __DIR__.'/classes/configdb.php';

function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

require_once 'library.php';





$dbname = 'unibendb';
// $collection = 'm_e_profile';

//DB connection
$db = new DbManager();
$conn = $db->getConnection();

                            //signup function
//function signup($link){
   



//$data = json_decode(file_get_contents("php://input", true));
    $date1 = date("F j, Y"); 
    $tim = date("g:i a");
    $created = $date1. " at ".$tim;
    

    //validate name.
    if(empty(trim($_POST["fullName"]))){
        $fullName_err = "Please enter your first name.";
        $returnData = msg(0,422,$fullName_err);
        echo json_encode($returnData);
        }else{
            $fullName = trim($_POST["fullName"]);
        }

        //validate phone number.
   
    //validating role
    if(empty(trim($_POST["role"]))){
        $role_err = "Please select role.";
        $returnData = msg(0,422,$role_err);
        echo json_encode($returnData);
        }else{
            $role = trim($_POST["role"]);
        }

        if(empty(trim($_POST["status"]))){
            $status_err = "Please select status.";
            $returnData = msg(0,422,$status_err);
            echo json_encode($returnData);
            }else{
                $status = trim($_POST["status"]);
            }

         //validate gender.
   if(empty(trim($_POST["gender"]))){
    $role_err = "Please select gender.";
    $returnData = msg(0,422,$gender_err);
    echo json_encode($returnData);
    }else{
        $gender = trim($_POST["gender"]);
    }
  
    //validate phone number
     if(empty(trim($_POST["phone"]))){
    $phone_err = "Please enter your phone number.";
    $returnData = msg(0,422,$phone_err);
    echo json_encode($returnData);
    }else{
        $phone = trim($_POST["phone"]);
    }
    
    //Validate email
    if(empty($_POST["email"])){
        $email_err = "Please enter your email.";
        $returnData = msg(0,422,$email_err);
        echo json_encode($returnData);
    }else{
        $email = trim($_POST["email"]);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $email_err= "Please enter a valid email.";
                        $returnData = msg(0,422,$email_err);
                        echo json_encode($returnData);
                    }
         }
           
         
        //Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter your password";
            $returnData = msg(0,422,$password_err);
            }elseif(strlen(trim($_POST["password"])) < 6){
                $password_err = "Password must be atleast 6 characters.";
                $returnData = msg(0,422,$password_err);
                echo json_encode($returnData);

            }else{
                $password = trim($_POST["password"]);
            }

            //validate confirm_password.
            if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm your password.";
            $returnData = msg(0,422,$confirm_password_err);
            echo json_encode($returnData);
            }else{
                $confirm_password = trim($_POST["confirm_password"]);
                if(empty($password_err) && ($password != $confirm_password)){
                    $confirm_password_err = "Password did not match.";
                    $returnData = msg(0,422,$confirm_password_err);
                    echo json_encode($returnData);
                }
            }

            
                
           
            // checking input errors before inserting in database
            if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($fullName_err) && empty($role_err) && empty($gender_err) && empty($phone_err) && empty($status_err)){
                $password     = password_hash($password, PASSWORD_DEFAULT); //this creates a hash password.
                $document = array(
            
                    "fullName"     => $fullName,
                    "email"        => $email,
                    "role"         => $role,
                    "gender"       => $gender,
                    "phone"        => $phone,
                    "password"     => $password,
                    "status"       => $status,
                    "created"      => $created
                
                );
                $collection = 'userlogin';

                $query = chkemail($email, $collection);
                    if($query){
                        register($document, $collection);
                        
                    // header("Location: login.php");
                        }
                    else{
                                $email_err= "This email has already been used.";
                                $returnData = msg(0,422,$email_err);
                                echo json_encode($returnData);
                        }
                }
               
      


    
//};





                    