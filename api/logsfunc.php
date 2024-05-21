<?php

function logs($logEvent, $executorsFullName, $logRight, $loguniqueId, $logEmail){
    $date1 = date("F j, Y"); 
    $tim = date("g:i a");
    $created = $date1 . " at " . $tim;
    $dbname = 'unibendb';
    $collLogs= 'logs';
    //DB connection
    $db = new DbManager();
    $conn = $db->getConnection();
    $query = ['uniqueId'=>$loguniqueId];
    $option = [];

    $queryDriver = new MongoDB\Driver\Query($query, $option);

    $logs = $conn->executeQuery("$dbname.$collLogs", $queryDriver)->toArray();
   
    if (count($logs) > 0) {
        $existingEvents = $logs[0]->events;
        $uniqueId = $logs[0]->uniqueId;
    
                /* ****************************************update logs ****************************************************** */
                $newEvent = [
                    'event' => $logEvent,
                    'event_date' => $created,
                ];
                
                $existingEvents[] = $newEvent;
                
                // Update database
                $update = new MongoDB\Driver\BulkWrite();
                $update->update(['uniqueId' => $uniqueId], ['$set' => ['events' => $existingEvents]], ['multi' => false, 'upsert' => false]);
                
                $result = $conn->executeBulkWrite("$dbname.$collLogs", $update);
        
                /* ****************************************end update logs****************************************************** */
                // verify
                if ($result->getModifiedCount() == 1) {
                    return true;
                } else {
                    return false;
                }
        }else{
        
                /* ****************************************Create logs ****************************************************** */
                $log = [
                    'fullname'  => $executorsFullName,
                    'email'  => $logEmail,
                    'uniqueId'  => $loguniqueId,
                    'right'  => $logRight,
                    'events' => [
                        [
                            'event' => $logEvent,
                            'event_date' => $created,
                        ],
                    ],
                ];
                
                // Insert record
                $insert = new MongoDB\Driver\BulkWrite();
                $insert->insert($log);
                
                $result = $conn->executeBulkWrite("$dbname.$collLogs", $insert);
        
                /* ****************************************end Create logs****************************************************** */
                // verify
                if ($result->getInsertedCount() == 1) {
                    return true;
                } else {
                    return false;
                }
        }

}