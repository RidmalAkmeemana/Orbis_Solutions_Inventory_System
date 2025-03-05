<?php

require '../../API/Connection/BackEndPermission.php';

$Customer_Id = $_POST['Customer_Id'];

if (empty($Customer_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Customer_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_customers` WHERE `tbl_customers`.`Customer_Id` = '$Customer_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Customer_Id exists, perform the delete
        $updateQuery = "DELETE FROM tbl_customers WHERE Customer_Id = '$Customer_Id';";
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
        // If Customer_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_customer_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>