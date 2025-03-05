<?php

require '../../API/Connection/BackEndPermission.php';

$Brand_Id = $_POST['Brand_Id'];
$Brand_Name = $_POST['Brand_Name'];

if (empty($Brand_Id) || empty($Brand_Name)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} 
else {
    
    // Check if Brand_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_brand` WHERE `tbl_brand`.`Brand_Id` = '$Brand_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Brand_Id exists, perform the update
        $updateQuery = "UPDATE `tbl_brand` SET `Brand_Name` = '$Brand_Name' WHERE `tbl_brand`.`Brand_Id` = '$Brand_Id';";
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
        // If Brand_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_brand_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>