<?php

require '../../API/Connection/BackEndPermission.php';

$Product_Id = $_POST['Product_Id'];

if (empty($Product_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Product_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_product` WHERE `tbl_product`.`Product_Id` = '$Product_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Product_Id exists, perform the delete
        $updateQuery = "DELETE FROM tbl_product WHERE Product_Id = '$Product_Id';";
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
        // If Product_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_product_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>