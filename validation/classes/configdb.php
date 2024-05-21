<?php
//connect to our database.
/* class DbManager {
	

	//Database configuration
	
	private $conn;
	
	
	function __construct(){
        //Connecting to MongoDB
        try {
			//Establish database connection
			$this->conn = new MongoDB\Driver\Manager('mongodb://root:Q6CYgQJZAjyg7Bb@192.168.80.29:27022/?retryWrites=true&w=majority');
		   //$this->conn = new MongoDB\Driver\Manager('mongodb://dbadmin:Per1%24c0pe%401@192.168.80.29:27019/?retryWrites=true&w=majority');
		   //$this->conn = new MongoDB\Driver\Manager('mongodb://dbadmin:Per1%24c0pe%401@192.168.80.29:27019/?retryWrites=true&w=majority');
		   //$this->conn = new MongoDB\Driver\Manager('mongodb+srv://Webunit:Pa55w0rd1@cluster0.y3jbmdl.mongodb.net/?retryWrites=true&w=majority');
		  //$this->conn = new MongoDB\Driver\Manager('mongodb://appuser:Per1%24c0pe%401@192.168.80.29:27017/?retryWrites=true&w=majority');


			
        }catch (MongoDBDriverExceptionException $e) {
            echo $e->getMessage();
			echo nl2br("n");
			
        }
    }
	

	function getConnection() {
		return $this->conn;
	}

	

} */



// DbManager class for managing MongoDB connections
class DbManager {
    private $conn; // MongoDB connection object

    // Constructor to establish database connection
    function __construct(){
        // Connect to MongoDB
        try {
            // MongoDB connection string
            $uri = 'mongodb://root:Q6CYgQJZAjyg7Bb@192.168.80.29:27022/?retryWrites=true&w=majority';
            $this->conn = new MongoDB\Driver\Manager($uri);
        } catch (MongoDB\Driver\Exception\Exception $e) {
            // Handle MongoDB connection errors
            echo "Error connecting to MongoDB: " . $e->getMessage();
            echo "<br>";
            exit; // Terminate script execution upon connection failure
        }
    }

    // Method to get the MongoDB connection object
    function getConnection() {
        return $this->conn;
    }
}

?>
