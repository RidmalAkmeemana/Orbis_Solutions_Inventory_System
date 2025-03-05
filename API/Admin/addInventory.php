<?php

require '../../API/Connection/BackEndPermission.php';

$Id = $_REQUEST['Id'];
$Inventort_Updated = "True";

if (empty($Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} 
else {
    
    // Check if Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_product_details` WHERE `tbl_product_details`.`Id` = '$Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Id exists, perform the update
        $updateQuery = "UPDATE `tbl_product_details` SET `Inventort_Updated` = '$Inventort_Updated' WHERE `tbl_product_details`.`Id` = '$Id';";
        if (mysqli_query($conn, $updateQuery)) {
            $myObj = new \stdClass();
            $myObj->success = 'true';
            $myJSON = json_encode($myObj);
            echo $myJSON;
        } else {
            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myJSON = json_encode($myObj);
            echo $myJSON;
        }
    } else {
        // If Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_product_history_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>