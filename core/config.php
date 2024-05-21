<?php
class DbManager {
	

	//Database configuration
	/* private $dbhost = 'localhost';
	private $dbport = '27017'; */
	private $conn;
	
	
	function __construct(){
        //Connecting to MongoDB
        try {
			//Establish database connection
			//$this->conn = new MongoDB\Driver\Manager('mongodb+srv://Webunit:Pa55w0rd1@cluster0.y3jbmdl.mongodb.net/?retryWrites=true&w=majority');
			$this->conn = new MongoDB\Driver\Manager('mongodb://root:Q6CYgQJZAjyg7Bb@192.168.80.29:27022/?retryWrites=true&w=majority');
			//$this->conn = new MongoDB\Driver\Manager('mongodb://appuser:Per1%24c0pe%401@192.168.80.29:27017/?retryWrites=true&w=majority');
			//$this->conn = new MongoDB\Driver\Manager('mongodb://appuser:Per1%24c0pe%401@192.168.80.29:27017/?authMechanism=DEFAULT');

        }catch (MongoDBDriverExceptionException $e) {
            echo $e->getMessage();
			echo nl2br("n");
			
        }
    }
	

	function getConnection() {
		return $this->conn;
	}

	

}

/* $client = new MongoDB\Client(
    'mongodb+srv://<username>:<password>@cluster0.gi7bs.mongodb.net/?retryWrites=true&w=majority');
$db = $client->test; */



?>