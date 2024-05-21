<?php
function leaveExpire($startDate, $noLeaveDays)
{
    $date = strtotime($startDate);
    $i = 0;

    while($i < $noLeaveDays)
    {
        //get number of week day (1-7)
        $day = date('N',$date);
        //get just Y-m-d date
        $dateYmd = date("Y-M-d",$date);

        if($day < 6 && $dateYmd){
            $i++;
        }       
        $date = strtotime($dateYmd . ' +1 day');
        
        
    }  
        $actDate=date('Y-M-d',$date);
        $date=date_create($actDate);
        date_sub($date,date_interval_create_from_date_string("1 days"));
        $endDate=date_format($date,"Y-M-d");     

    return $endDate;

}

/* $startDate='2023-1-23';
$businessDays=30;


$endDate=leaveExpire($startDate, $businessDays);

echo $endDate; */

function checkWeekend($endDate)
{
$dt=$endDate;
        $dt1 = strtotime($dt);
        $dt2 = date("l", $dt1);
        $dt3 = strtolower($dt2);
    if($dt3 == "friday" )
		{
            $date=date_create($dt);
            date_add($date,date_interval_create_from_date_string("3 days"));
            $resumptionDate=date_format($date,"Y-M-d");     

            return $resumptionDate;
            //echo $dt3.' is weekend'."\n";
        }else{
            $date=date_create($dt);
            date_add($date,date_interval_create_from_date_string("1 days"));
            $resumptionDate=date_format($date,"Y-M-d");     

            return $resumptionDate;
        } 
    
    }
   /*  $resumptionDate=checkWeekend($endDate);
    echo $resumptionDate; */