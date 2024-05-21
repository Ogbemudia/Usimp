<?php
/* *****************************************************
Fuction for to apply for leave
****************************************************** */
function leaveApplication($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId){
    $data=array(
        'registry.leaveApplication.leaveStatus'                   =>'pending',
        'registry.leaveApplication.leaveName'                     =>$leaveName,
        'registry.leaveApplication.leaveAppType'                  =>$leaveAppType,
        'registry.leaveApplication.leaveDuration'                 =>$leaveDuration,
        'registry.leaveApplication.startDate'                     =>$startDate,
        'registry.leaveApplication.endDate'                       =>$endDate,
        'registry.leaveApplication.leaveNoDays'                   => (int) $leaveNoDays,
        'registry.leaveApplication.resumptionDate'                =>$resumptionDate,
        'registry.leaveApplication.leaveDetail'                   =>$leaveDetail,
        'registry.leaveApplication.applicationDate'               =>$created,
        'registry.leaveApplication.hodResponse'                   =>'',
        'registry.leaveApplication.hodComment'                    =>'',
        'registry.leaveApplication.hodResponse_date'              =>'',
        'registry.leaveApplication.dean_directorResponse'         =>'',
        'registry.leaveApplication.dean_directorComment'          =>'',
        'registry.leaveApplication.dean_directorResponse_date'    =>'',
        'registry.leaveApplication.registrarResponse'             =>'',
        'registry.leaveApplication.registrarComment'              =>'',
        'registry.leaveApplication.registrarResponse_date'        =>'',
       
    );
    $profile='staff-profile';
    $leaveAppl=update_by_uniqueId($data,  $profile, $uniqueId);
    if($leaveAppl){
         
            return true;
    }else{
        return false;
    }

}



/* *****************************************************
Fuction for leave application action by hod.
****************************************************** */
function leaveApplicationActionByHOD($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId, $hodResponse, $hodComment, $hodResponse_date, $fullName, $bursaryNo, $applicationDate){
    
    $data=array(
        'registry.leaveApplication.leaveStatus'                   =>'pending',
        //'registry.leaveApplication.leaveName'                     =>$leaveName,
        //'registry.leaveApplication.leaveAppType'                  =>$leaveAppType,
        'registry.leaveApplication.leaveDuration'                 =>$leaveDuration,
        'registry.leaveApplication.startDate'                     =>$startDate,
        'registry.leaveApplication.endDate'                       =>$endDate,
        //'registry.leaveApplication.leaveNoDays'                   => (int) $leaveNoDays,
        'registry.leaveApplication.resumptionDate'                =>$resumptionDate,
        //'registry.leaveApplication.leaveDetail'                   =>$leaveDetail,
        //'registry.leaveApplication.applicationDate'               =>$created,
        'registry.leaveApplication.hodResponse'                   =>$hodResponse,
        'registry.leaveApplication.hodComment'                    =>$hodComment,
        'registry.leaveApplication.hodResponse_date'              =>$hodResponse_date
        /* 'registry.leaveApplication.dean_directorResponse'         =>'',
        'registry.leaveApplication.dean_directorComment'          =>'',
        'registry.leaveApplication.dean_directorResponse_date'    =>'',
        'registry.leaveApplication.registrarResponse'             =>'',
        'registry.leaveApplication.registrarComment'              =>'',
        'registry.leaveApplication.registrarResponse_date'        =>'',
        */
    );
    $profile='staff-profile';
    $hodUpdateOnLeave=update_by_uniqueId($data,  $profile, $uniqueId);
    if($hodUpdateOnLeave){
        
     
    if($hodResponse==='declined'){
     /* **************************************************
    create histry for hod decline
    *****************************************************/
    $data=array(
        'notification'          =>'unviewed',
        'uniqueId'              =>$uniqueId,
        'fullName'              =>$fullName,
        'bursaryNo'             =>$bursaryNo,
        'leaveStatus'           =>'declined',
        'leaveName'             =>$leaveName,
        'leaveAppType'          =>$leaveAppType,
        'leaveNoDays'           =>(int) $leaveNoDays,
        'leaveDuration'         =>$leaveDuration,
        'startDate'             =>$startDate,
        'endDate'               =>$endDate,
        'leaveDetail'           =>$leaveDetail,
        'applicationDate'               =>$applicationDate,
        'hodResponse'                   =>$hodResponse_date,
        'hodComment'                    =>$hodComment,
        'hodResponse_date'              =>$created,
        'dean_directorResponse'         =>'',
        'dean_directorComment'          =>'',
        'dean_directorResponse_date'    =>'',
        'registrarResponse'             =>'',
        'registrarComment'              =>'',
        'registrarResponse_date'        =>'',
        'created'                       => $created,
    );
    $profile='leave-history';
    $histryUpdated=create_profile($data, $profile);
    if ($histryUpdated) {
       /*  $data=array(
            'registry.leaveApplication.leaveStatus'                   =>'',
            'registry.leaveApplication.leaveName'                     =>'',
            'registry.leaveApplication.leaveDuration'                 =>'',
            'registry.leaveApplication.startDate'                     =>'',
            'registry.leaveApplication.endDate'                       =>'',
            'registry.leaveApplication.leaveNoDays'                   => (int) 0,
            'registry.leaveApplication.resumptionDate'                =>'',
            'registry.leaveApplication.leaveDetail'                   =>'',
            'registry.leaveApplication.applicationDate'               =>'',
            'registry.leaveApplication.hodResponse'                   =>'',
            'registry.leaveApplication.hodComment'                    =>'',
            'registry.leaveApplication.hodResponse_date'              =>'',
            'registry.leaveApplication.dean_directorResponse'         =>'',
            'registry.leaveApplication.dean_directorComment'          =>'',
            'registry.leaveApplication.dean_directorResponse_date'    =>'',
            'registry.leaveApplication.registrarResponse'             =>'',
            'registry.leaveApplication.registrarComment'              =>'',
            'registry.leaveApplication.registrarResponse_date'        =>'',
           
        );
        $profile='staff-profile';
        $leaveAppl=update_by_uniqueId($data,  $profile, $uniqueId);
        if($leaveAppl){ */
             
                return true;
        }else{
            return false;
        }
    //}
}else{
    return true;
}
}else{
    return false;
}

}


/* *****************************************************
Fuction for to leave application action by Dean, Director, Dvc.
****************************************************** */
function leaveApplicationActionByDirector($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId, $hodResponse, $hodComment, $hodResponse_date, $directorResponse, $directorComment, $directorResponse_date, $fullName, $bursaryNo, $applicationDate){
    //if($hodResponse==='approved'){
        $data=array(
            'registry.leaveApplication.leaveStatus'                   =>'pending',
            //'registry.leaveApplication.leaveName'                     =>$leaveName,
            'registry.leaveApplication.leaveDuration'                 =>$leaveDuration,
            'registry.leaveApplication.startDate'                     =>$startDate,
            'registry.leaveApplication.endDate'                       =>$endDate,
            //'registry.leaveApplication.leaveNoDays'                   => (int) $leaveNoDays,
            'registry.leaveApplication.resumptionDate'                =>$resumptionDate,
            //'registry.leaveApplication.leaveDetail'                   =>$leaveDetail,
            //'registry.leaveApplication.applicationDate'               =>$created,
           /*  'registry.leaveApplication.hodResponse'                   =>$hodResponse,
            'registry.leaveApplication.hodComment'                    =>$hodComment,
            'registry.leaveApplication.hodResponse_date'              =>$hodResponse_date */
            'registry.leaveApplication.dean_directorResponse'         =>$directorResponse,
            'registry.leaveApplication.dean_directorComment'          =>$directorComment,
            'registry.leaveApplication.dean_directorResponse_date'    =>$directorResponse_date,
            /* 'registry.leaveApplication.registrarResponse'             =>'',
            'registry.leaveApplication.registrarComment'              =>'',
            'registry.leaveApplication.registrarResponse_date'        =>'',
            */
        );
        $profile='staff-profile';
        $dirUpdateOnLeave=update_by_uniqueId($data,  $profile, $uniqueId);
        if($dirUpdateOnLeave){
        /*     
                return true;
        }else{
            return false;
        } */
    //}elseif($hodResponse==='declined'){
  if($directorResponse==='declined'){
         /* **************************************************
        create histry for decline by director
        *****************************************************/
        $data=array(
            'notification'          =>'unviewed',
            'uniqueId'              =>$uniqueId,
            'fullName'              =>$fullName,
            'bursaryNo'             =>$bursaryNo,
            'leaveStatus'           =>'declined',
            'leaveName'             =>$leaveName,
            'leaveAppType'          =>$leaveAppType,
            'leaveNoDays'           =>(int) $leaveNoDays,
            'leaveDuration'         =>$leaveDuration,
            'startDate'             =>$startDate,
            'endDate'               =>$endDate,
            'leaveDetail'           =>$leaveDetail,
            'applicationDate'               =>$applicationDate,
            'hodResponse'                   =>$hodResponse_date,
            'hodComment'                    =>$hodComment,
            'hodResponse_date'              =>$created,
            'dean_directorResponse'         =>$directorResponse,
            'dean_directorComment'          =>$directorComment,
            'dean_directorResponse_date'    =>$directorResponse_date,
            'registrarResponse'             =>'',
            'registrarComment'              =>'',
            'registrarResponse_date'        =>'',
            'created'                       => $created,
        );
        $profile='leave-history';
        $histryUpdated=create_profile($data, $profile);
        if ($histryUpdated) {
            /* $data=array(
                'registry.leaveApplication.leaveStatus'                   =>'',
                'registry.leaveApplication.leaveName'                     =>'',
                'registry.leaveApplication.leaveDuration'                 =>'',
                'registry.leaveApplication.startDate'                     =>'',
                'registry.leaveApplication.endDate'                       =>'',
                'registry.leaveApplication.leaveNoDays'                   => (int) 0,
                'registry.leaveApplication.resumptionDate'                =>'',
                'registry.leaveApplication.leaveDetail'                   =>'',
                'registry.leaveApplication.applicationDate'               =>'',
                'registry.leaveApplication.hodResponse'                   =>'',
                'registry.leaveApplication.hodComment'                    =>'',
                'registry.leaveApplication.hodResponse_date'              =>'',
                'registry.leaveApplication.dean_directorResponse'         =>'',
                'registry.leaveApplication.dean_directorComment'          =>'',
                'registry.leaveApplication.dean_directorResponse_date'    =>'',
                'registry.leaveApplication.registrarResponse'             =>'',
                'registry.leaveApplication.registrarComment'              =>'',
                'registry.leaveApplication.registrarResponse_date'        =>'',
               
            );
            $profile='staff-profile';
            $leaveAppl=update_by_uniqueId($data,  $profile, $uniqueId);
            if($leaveAppl){ */
                 
                return true;
            }else{
                return false;
            }
        //}
    }else{
        return true;
    }
    }else{
        return false;
    }
}


/* *****************************************************
Fuction for to leave application action by Registrar.
****************************************************** */
function leaveApplicationActionByRegistrar($created, $leaveName, $leaveAppType, $leaveNoDays, $leaveDuration, $startDate, $endDate, $leaveDetail, $resumptionDate, $uniqueId, $hodResponse, $hodComment, $hodResponse_date, $directorResponse, $directorComment, $directorResponse_date, $registrarResponse, $registrarComment, $registrarResponse_date, $fullName, $bursaryNo, $applicationDate){
    //if($hodResponse==='approved'){
        $data=array(
            'registry.leaveApplication.leaveStatus'                   =>$registrarResponse,
            //'registry.leaveApplication.leaveName'                     =>$leaveName,
            'registry.leaveApplication.leaveDuration'                 =>$leaveDuration,
            'registry.leaveApplication.startDate'                     =>$startDate,
            'registry.leaveApplication.endDate'                       =>$endDate,
            //'registry.leaveApplication.leaveNoDays'                   => (int) $leaveNoDays,
            'registry.leaveApplication.resumptionDate'                =>$resumptionDate,
            //'registry.leaveApplication.leaveDetail'                   =>$leaveDetail,
            //'registry.leaveApplication.applicationDate'               =>$created,
           /*  'registry.leaveApplication.hodResponse'                   =>$hodResponse,
            'registry.leaveApplication.hodComment'                    =>$hodComment,
            'registry.leaveApplication.hodResponse_date'              =>$hodResponse_date */
           /*  'registry.leaveApplication.dean_directorResponse'         =>$directorResponse,
            'registry.leaveApplication.dean_directorComment'          =>$directorComment,
            'registry.leaveApplication.dean_directorResponse_date'    =>$directorResponse_date, */
            'registry.leaveApplication.registrarResponse'             =>$registrarResponse,
            'registry.leaveApplication.registrarComment'              =>$registrarComment,
            'registry.leaveApplication.registrarResponse_date'        =>$registrarResponse_date,
        );
        $profile='staff-profile';
        $regUpdateOnLeave=update_by_uniqueId($data,  $profile, $uniqueId);
        if($regUpdateOnLeave){
        
    //}elseif($hodResponse==='declined'){
    if($registrarResponse==='declined'){
         /* **************************************************
        create histry for for registrars decline
        *****************************************************/
        $data=array(
            'notification'          =>'unviewed',
            'uniqueId'              =>$uniqueId,
            'fullName'              =>$fullName,
            'bursaryNo'             =>$bursaryNo,
            'leaveStatus'           =>'declined',
            'leaveName'             =>$leaveName,
            'leaveAppType'          =>$leaveAppType,
            'leaveNoDays'           =>(int) $leaveNoDays,
            'leaveDuration'         =>$leaveDuration,
            'startDate'             =>$startDate,
            'endDate'               =>$endDate,
            'leaveDetail'           =>$leaveDetail,
            'applicationDate'               =>$applicationDate,
            'hodResponse'                   =>$hodResponse_date,
            'hodComment'                    =>$hodComment,
            'hodResponse_date'              =>$created,
            'dean_directorResponse'         =>$directorResponse,
            'dean_directorComment'          =>$directorComment,
            'dean_directorResponse_date'    =>$directorResponse_date,
            'registrarResponse'             =>$registrarResponse,
            'registrarComment'              =>$registrarComment,
            'registrarResponse_date'        =>$registrarResponse_date,
            'created'                       => $created,
        );
        $profile='leave-history';
        $histryUpdated=create_profile($data, $profile);
        if ($histryUpdated) {
            /* $data=array(
                'registry.leaveApplication.leaveStatus'                   =>'',
                'registry.leaveApplication.leaveName'                     =>'',
                'registry.leaveApplication.leaveDuration'                 =>'',
                'registry.leaveApplication.startDate'                     =>'',
                'registry.leaveApplication.endDate'                       =>'',
                'registry.leaveApplication.leaveNoDays'                   => (int) 0,
                'registry.leaveApplication.resumptionDate'                =>'',
                'registry.leaveApplication.leaveDetail'                   =>'',
                'registry.leaveApplication.applicationDate'               =>'',
                'registry.leaveApplication.hodResponse'                   =>'',
                'registry.leaveApplication.hodComment'                    =>'',
                'registry.leaveApplication.hodResponse_date'              =>'',
                'registry.leaveApplication.dean_directorResponse'         =>'',
                'registry.leaveApplication.dean_directorComment'          =>'',
                'registry.leaveApplication.dean_directorResponse_date'    =>'',
                'registry.leaveApplication.registrarResponse'             =>'',
                'registry.leaveApplication.registrarComment'              =>'',
                'registry.leaveApplication.registrarResponse_date'        =>'',
               
            );
            $profile='staff-profile';
            $leaveAppl=update_by_uniqueId($data,  $profile, $uniqueId);
            if($leaveAppl){ */
                 
                    return true;
        }else{
            return false;
        }
    //}
}else{
    return true;
}
}else{
    return false;
}
}