<?php
function upload($file, $filename){
   
 
    /* Location */
    $location = "../../login/uploads/" . $filename;
    $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);

    
       /* Upload file */
        if (move_uploaded_file($file, $location)) {
            return true;
        }
        //printf("Error %s. \n", $stmt->error);
        return false;
}