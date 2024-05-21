<?php
require __DIR__.'/classes/configdb.php';
$authResult;
 
//$auth2='300ad';


//$sendEmail=sendAuth($auth2, $mailSend, $name);

$nowtime= time();

 
    
if (isset($_POST["resetcode"])) {

  $email =$_COOKIE['emailR'];

  /**************************Read db ****************************/
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
                   $resetCode = $value['reset_code'];
                   $exptim = $value['reset_code_exp'];
                   $fullName = $value['fullName'];
                  
                   /* $category = $value['role'];
                   $email = $value['email'];
                   $status = $value['status']; */

                
                 
                   
                }
            }
    /**************************Read db ****************************/    
      if (empty(!($_POST['resetcode']))){ 

        $data = $_POST["resetcode"];
              
        if ($data === $resetCode) {
            if($exptim > $nowtime){ 
                //set cookie
                $name =$fullName;
                $expTime = time()+60*60;
                $path = 'path=/';
                header ("Set-Cookie: username=$name; $expTime; $path");
                
                 $name1="";
                 $timm=time()-60*60;
               
                setcookie ("emailR", $name1, time()-60*30, 'path=/'); 
                unset($_COOKIE["emailR"]);
                setcookie('emailR', null, -1, '/');  
               
                $authInfo = "Email verified.";
                echo json_encode(
                  array('success' => 2,
                  'status' => 201,
                  'message' => $authInfo)
              );

              session_start();
              $_SESSION['userlogin'] = $email;
             // $_SESSION['category'] = $category;
              $_SESSION["id"] = $userid;
              


              
            }else{
                $authResult =1;   
                $authInfo = "This reset code has expired.";
                echo json_encode(
                    array('success' => 1,
                    'status' => 422,
                    'message' => $authInfo)
                );
               
            }
        }else{
            $authResult = 0;   
            $authInfo = "Invalid reset code.";
            echo json_encode(
                array('success' => 0,
                'status' => 422,
                'message' => $authInfo)
            );
            return  $authResult;

        }
    }
               
      }

     