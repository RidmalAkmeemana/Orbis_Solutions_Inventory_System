<?php

require '../../API/Connection/BackEndPermission.php';

$Role_Id = $_POST['Role_Id'];

if (empty($Role_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Role_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_roles` WHERE `tbl_roles`.`Role_Id` = '$Role_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Role_Id exists, perform the delete
        $updateQuery = "DELETE FROM tbl_roles WHERE Role_Id = '$Role_Id';";
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
        // If Category_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_category_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>