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


//$username = $_SESSION['userlogin'];
$user_id =  $_SESSION["id"]; 


//initializing api
include_once('../core/initialize.php');

$id = $user_id ? $user_id : die();
$dbname = 'unibendb';
$collection = 'userlogin';


//DB connection
$db = new DbManager();
$conn = $db->getConnection();

// read all records
//['projection' => ['_id' => 0]];
$filter = ['_id' => new MongoDB\BSON\ObjectId($id)];
$option = ['password' => 0];
$read = new MongoDB\Driver\Query($filter, $option);

//fetch records
$records = $conn->executeQuery("$dbname.$collection", $read);


echo json_encode(iterator_to_array($records));
