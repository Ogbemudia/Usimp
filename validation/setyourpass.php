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


   
   
    //validate password.
    if (empty(trim($_POST["newpassword"]))) {
        $password_err = "Please enter your password.";
        $returnData = msg(0, 422, $password_err);
    } else {
        $password = trim($_POST["newpassword"]);
    }
   // $returnData = msg(0, 422, $email);
 

    //Validating inputs
    if (empty($password_err)) {

                        session_start();
                        $email=$_SESSION['userlogin'];
                        // $_SESSION['category'] = $category;
                        $userid=$_SESSION["id"];

                        $password = password_hash($password, PASSWORD_DEFAULT);
                        
                        $date1 = date("F j, Y"); 
                        $tim = date("g:i a");
                        $succ = $date1. " at ".$tim;
                        $dbname = 'unibendb';
                        $collection = 'userlogin';
                        //update database
                        $document = array( 
                            'password_update'=> $succ,
                            'password'=> $password
                            
                        );
                       $update= update($document,  $collection, $userid);
                       if($update){
                        //destroy all session and cookies
                        session_destroy();
                        unset($_SESSION['userlogin']);  //Destroy This Session
                        unset($_SESSION['id']);

                        //$token = $jwt;
                    
                        $name="";
                        header ("Set-Cookie: username=$name; time()-60*60; path=/");
                        
                        unset($_COOKIE["username"]);

                          $passOk = "Password reset successful!";
                          echo json_encode(
                          array('success' => 1,
                          'status' => 422,
                          'message' => $passOk));
                       }else{
                        echo json_encode(
                            array('success' => 0,
                            'status' => 422,
                            'message' => 'Somthing went wrong! try again later'));
                       }
                    
                    } 
                    
                        
                        
    
                   
        
           
