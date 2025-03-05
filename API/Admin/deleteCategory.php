<?php

require '../../API/Connection/BackEndPermission.php';

$Category_Id = $_POST['Category_Id'];

if (empty($Category_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Category_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_category` WHERE `tbl_category`.`Category_Id` = '$Category_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Category_Id exists, perform the delete
        $updateQuery = "DELETE FROM tbl_category WHERE Category_Id = '$Category_Id';";
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