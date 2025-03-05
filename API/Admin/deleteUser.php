<?php

require '../../API/Connection/BackEndPermission.php';

$Id = $_POST['Id'];

if (empty($Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_user` WHERE `tbl_user`.`Id` = '$Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Id exists, perform the delete
        $updateQuery = "DELETE FROM tbl_user WHERE Id = '$Id';";
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
        $myObj->error = 'no_user_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>