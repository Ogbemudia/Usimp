<?php
   // session_start();
    function register($document,  $collection){
        
        $dbname = 'unibendb';
       // $collection = 'm_e_profile';

        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();

        // insert record
        $insert = new MongoDB\Driver\BulkWrite();
        $insert->insert($document);

        $result = $conn->executeBulkWrite("$dbname.$collection", $insert);

        // verify
        if ($result->getInsertedCount() == 1) {
           /*  $reg_succ= "Registration successful.";
            $returnData = msg(1,200,$reg_succ); */
            return true;
        } else {
            
           /*  $reg_err= "Error while saving record.";
            $returnData = msg(0,422,$reg_err); */
            return false;
        }
        //echo json_encode($returnData);
            }


    //function to update database
    function update($document,  $collection, $uniqueId){
        
        $dbname = 'unibendb';
       // $collection = 'm_e_profile';

        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        $id = $uniqueId;//->{'where'};


        // update database
        $update = new MongoDB\Driver\BulkWrite();
        $update->update(['uniqueId' => $uniqueId], ['$set' => $document], ['multi' => false, 'upsert' => false]);

        $result = $conn->executeBulkWrite("$dbname.$collection", $update);

        // verify
        if ($result->getModifiedCount() == 1) {
            return true;
            /* $reg_succ= "update successful.";
            $returnData = msg(1,201,$reg_succ); */
        } else {
            return false;
            
            /* $reg_err= "Error while saving record.";
            $returnData = msg(0,422,$reg_err); */
        }
       // echo json_encode($returnData);
    }

    /***********************************update with email********************************************** */
    //function to update database
    function updateWith($document,  $collection, $email){
        
        $dbname = 'unibendb';
       // $collection = 'm_e_profile';

        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        $email = $email;//->{'where'};


        // update database
        $update = new MongoDB\Driver\BulkWrite();
        $update->update(['email' => $email], ['$set' => $document], ['multi' => false, 'upsert' => false]);

        $result = $conn->executeBulkWrite("$dbname.$collection", $update);

        // verify
        if ($result->getModifiedCount() == 1) {
            return true;
            /* $reg_succ= "update successful.";
            $returnData = msg(1,201,$reg_succ); */
        } else {
            return false;
            
            /* $reg_err= "Error while saving record.";
            $returnData = msg(0,422,$reg_err); */
        }
       // echo json_encode($returnData);
    }

    /***********************************end update with email********************************************** */


    /***********************************update with bursaryNo********************************************** */
    //function to update database
    function updateWithuniqueId($document,  $collection, $uniqueId){
        
        $dbname = 'unibendb';
       // $collection = 'm_e_profile';

        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        $uniqueId = $uniqueId;//->{'where'};


        // update database
        $update = new MongoDB\Driver\BulkWrite();
        $update->update(['uniqueId' => $uniqueId], ['$set' => $document], ['multi' => false, 'upsert' => false]);

        $result = $conn->executeBulkWrite("$dbname.$collection", $update);

        // verify
        if ($result->getModifiedCount() == 1) {
            return true;
            /* $reg_succ= "update successful.";
            $returnData = msg(1,201,$reg_succ); */
        } else {
            return false;
            
           /*  $reg_err= "Error while saving record.";
            $returnData = msg(0,422,$reg_err); */
        }
       // echo json_encode($returnData);
    }

    /***********************************end update with email********************************************** */

    
    function chkemail($email, $collection)
    {
        $dbname = 'unibendb';
       // $collection = 'm_e_profile';
        
       
      

        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        $query = ['email' => $email];
        $option = [];

        $queryDriver = new MongoDB\Driver\Query($query, $option);

            
            $users = $conn->executeQuery("$dbname.$collection", $queryDriver)->toArray(); 
            if (count($users) > 0){

            //if (empty($records)) {
                return false;
            } else {
                return true;
            }
    
    }
    