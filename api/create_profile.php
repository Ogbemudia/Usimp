<?php

function create_profile($data,  $profile){
        
    $dbname = 'unibendb';
   // $collection = 'm_e_profile';

    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();

    // insert record
    $insert = new MongoDB\Driver\BulkWrite();
    $insert->insert($data);

    $result = $conn->executeBulkWrite("$dbname.$profile", $insert);

    // verify
    if ($result->getInsertedCount() == 1) {
        /* $reg_succ= "Registration successful.";
        $returnData = msg(1,201,$reg_succ); */
        return true;
    } else {
        return false;
        
       /*  $reg_err= "Error while saving record.";
        $returnData = msg(0,422,$reg_err); */
    }
   // echo json_encode($returnData);
        }


//function to update database
function update_profile($data,  $profile, $uniqueId){
    
    $dbname = 'unibendb';
   // $collection = 'm_e_profile';

    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    $uniqueId = $uniqueId;//->{'where'};


    // update database
    $update = new MongoDB\Driver\BulkWrite();
    $update->update(['uniqueId' => $uniqueId], ['$set' => $data], ['multi' => false, 'upsert' => false]);

    $result = $conn->executeBulkWrite("$dbname.$profile", $update);

    // verify
    if ($result->getModifiedCount() == 1) {
        return true;
        /* $reg_succ= "update successful.";
        $returnData = msg(1,201,$reg_succ); */
    } else {
        return false;
        
        $reg_err= "Error while saving record.";
        $returnData = msg(0,422,$reg_err);
    }
   // echo json_encode($returnData);
}

//function to update database
function update_profile_by_email($data,  $profile, $email){
    
    $dbname = 'unibendb';
   // $collection = 'm_e_profile';

    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    $email = $email;//->{'where'};


    // update database
    $update = new MongoDB\Driver\BulkWrite();
    $update->update(['email' => $email], ['$set' => $data], ['multi' => false, 'upsert' => false]);

    $result = $conn->executeBulkWrite("$dbname.$profile", $update);

    // verify
    if ($result->getModifiedCount() == 1) {
        return true;
        /* $reg_succ= "update successful.";
        $returnData = msg(1,201,$reg_succ); */
    } else {
        return false;
        
        $reg_err= "Error while saving record.";
        $returnData = msg(0,422,$reg_err);
    }
   // echo json_encode($returnData);
}


//function to update database with unique id
function update_by_uniqueId($data,  $profile, $uniqueId){
    
    $dbname = 'unibendb';
   // $collection = 'm_e_profile';

    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    $uniqueId = $uniqueId;//->{'where'};


    // update database
    $update = new MongoDB\Driver\BulkWrite();
    $update->update(['uniqueId' => $uniqueId], ['$set' => $data], ['multi' => false, 'upsert' => false]);

    $result = $conn->executeBulkWrite("$dbname.$profile", $update);

    // verify
    if ($result->getModifiedCount() == 1) {
        return true;
        /* $reg_succ= "update successful.";
        $returnData = msg(1,201,$reg_succ); */
    } else {
        return false;
        
        $reg_err= "Error while saving record.";
        $returnData = msg(0,422,$reg_err);
    }
   // echo json_encode($returnData);
}


//function to update database with unique id
function bulkupdate_by_ubsno($data,  $profile, $uniqueId){
    
    $dbname = 'unibendb';
   // $collection = 'm_e_profile';

    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    $uniqueId = $uniqueId;//->{'where'};


// bulkupdate database
$update = new MongoDB\Driver\BulkWrite();
$update->update(['uniqueId' => $uniqueId], ['$set' => $data], ['multi' => false, 'upsert' => false]);

$result = $conn->executeBulkWrite("$dbname.$profile", $update);

// verify
if ($result->getModifiedCount() == 1) {
    return true;
    /* $reg_succ= "update successful.";
    $returnData = msg(1,201,$reg_succ); */
} else {
    return false;
    
    $reg_err= "Error while saving record.";
    $returnData = msg(0,422,$reg_err);
}
// echo json_encode($returnData);
}



function chkemailP($email, $profile)
    {
        $dbname = 'unibendb';
       // $collection = 'm_e_profile';
        
       
      

        //DB connection
        $db = new DbManager();
        $conn = $db->getConnection();
        //$query = ['email' => $email];
        $query = ['contact.email'=>$email];
        $option = [];

        $queryDriver = new MongoDB\Driver\Query($query, $option);

            
            $users = $conn->executeQuery("$dbname.$profile", $queryDriver)->toArray(); 
            if (count($users) > 0){

            
                return false;
            } else {
                return true;
            }
    
    }


