<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files for decoding jwt will be here
// required to decode jwt
require_once('vendor/autoload.php');

use \Firebase\JWT\JWT; 

 
// retrieve gieve jwt here
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
//$jwt=isset($data->token) ? $data->token : "";
if(isset($_COOKIE['token']));
$jwt = $_COOKIE['token'];



 
// decode jwt here
// if jwt is not empty
if($jwt){
 $key = 'GiZkey%visions6689#king%';
    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS512'));
        $decoded_array = (array)$decoded;

 
        // set response code
        http_response_code(200);
        
 
        // show user details
    
      echo json_encode(array(
            'success' => 1,
            'status' => 201,
            "message" => "Access granted."
          
        )); 
    
    }
 
    // catch will be here
    // if decode fails, it means jwt is invalid
catch (Exception $e){
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied  & show error message
    echo json_encode(array(
        'success' => 0,
        'status' => 422,
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
    header("location: logout.php");
    exit;
}
}
 
// error if jwt is empty will be here
// show error message if jwt is empty
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied.", 'success' => 0,
    'status' => 422));
    header("location: logout.php");
    exit;
}
?>
