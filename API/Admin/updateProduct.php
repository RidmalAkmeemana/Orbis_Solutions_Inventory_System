<?php

require '../../API/Connection/BackEndPermission.php';

$Product_Id = $_POST['Product_Id'];
$Product_Name = $_POST['Product_Name'];
$Brand_Id = $_POST['Brand_Id'];
$Category_Id = $_POST['Category_Id'];

if (empty($Product_Id) || empty($Brand_Id) || empty($Category_Id) || empty($Product_Name)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} 
else {
    
    // Check if Product_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_product` WHERE `tbl_product`.`Product_Id` = '$Product_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Product_Id exists, perform the update
        $updateQuery = "UPDATE `tbl_product` SET `Product_Name` = '$Product_Name', `Brand_Id` = '$Brand_Id', `Category_Id` = '$Category_Id' WHERE `tbl_product`.`Product_Id` = '$Product_Id';";
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