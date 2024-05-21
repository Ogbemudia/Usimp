<?php

function logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail){
    $dbname = 'unibendb';
    $collLogs= 'logs';
    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    $query = ['uniqueId'=>$loguniqueId];
    $option = [];

    $queryDriver = new MongoDB\Driver\Query($query, $option);

    $logs = $conn->executeQuery("$dbname.$collLogs", $queryDriver)->toArray();
   
        if (count($logs) > 0){ 

            $log=json_encode($logs);
            $result = json_decode($log, true);
            foreach ($result as $value) {
               $existingEvents = $value['events'];
               $uniqueId = $value['uniqueId'];
            }

            $date1 = date("F j, Y"); 
            $tim = date("g:i a");
            $created = $date1. " at ".$tim;
   
                /* ****************************************update logs ****************************************************** */
                $dbname = 'unibendb';
                $collLogs= 'logs';
                //DB connection
                $db = new DbManager();
                $conn = $db->getConnection();
                    /* $log=array(
                        'events' => array(
                            array(
                                'event' => $logEvent,
                                'event_date' => $created,
                            ),
                        ),
                    ); */
                    $newEvent=array(
                        'event' => $logEvent,
                        'event_date' => $created,
                    );
                    $existingEvents[]=$newEvent;
                    $log=array('events'=>$existingEvents);
                   // update database
                $update = new MongoDB\Driver\BulkWrite();
                $update->update(['uniqueId' => $uniqueId], ['$set' => $log], ['multi' => false, 'upsert' => false]);

                $result = $conn->executeBulkWrite("$dbname.$collLogs", $update);
                /* ****************************************end update logs****************************************************** */
                // verify
            if ($result->getModifiedCount() == 1) {
                        return true;
                }else{
                    return false;
                }
        }else{
            $date1 = date("F j, Y"); 
            $tim = date("g:i a");
            $created = $date1. " at ".$tim;
        
                /* ****************************************Create logs ****************************************************** */
                /* $dbname = 'unibendb';
                $collLogs= 'logs';
                //DB connection
                $db = new DbManager();
                $conn = $db->getConnection(); */
                    $log=array(
                        'fullname'  => $executorsFullName,
                        'email'  => $logEmail,
                        'uniqueId'  => $loguniqueId,
                        'right'  => $logRight,
                        'events' => array(
                            array(
                                'event' => $logEvent,
                                'event_date' => $created,
                            ),
                        ),
                    );
                    // insert record
                    $insert = new MongoDB\Driver\BulkWrite();
                    $insert->insert($log);

                    $result = $conn->executeBulkWrite("$dbname.$collLogs", $insert);
                /* ****************************************end Create logs****************************************************** */
                // verify
            if ($result->getInsertedCount() == 1) {
                        return true;
                }else{
                    return false;
            }
        }

}