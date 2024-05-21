<?php

//headers
header("Access-Control-Allow-Origin: localhost/giz/");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require __DIR__.'/validation.php';

require_once('classes/session.php');
login();
$email = $_SESSION['userlogin'];
$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

$post_arr = array(
    
    'role'      => $role   

);

//make a json
print_r(json_encode($post_arr));