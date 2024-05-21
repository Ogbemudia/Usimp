<?php
function role($privilege, $logRight, $accRights, $accPrivilege) {
    /* $privilege1 = str_replace(['privilege', '"', ' '], '', $privilege);
    $privileges = explode(',', $privilege1);
    */

    $right = false;
    $privilege2 = false;

    if (count(array_intersect($logRight, $accRights)) > 0) {
        $right = true;
    }

    if (count(array_intersect($privilege, $accPrivilege)) > 0) {
        $privilege2 = true;
    }

    if ($right && $privilege2) {
        return true;
        // header("location: ../validation/logout.php");
        // echo 'you don\'t have the right to access this API';
        // exit;
    } else {
        return false;
    }
}
