<?php

require '../../API/Connection/BackEndPermission.php';

$Id = $_POST['Id'];
$Cost = $_POST['Cost'];
$Landing_Cost = $_POST['Landing_Cost'];
$Retail_Price = $_POST['Retail_Price'];
$Wholesale_Price = $_POST['Wholesale_Price'];
$Qty = $_POST['Qty'];
$Supplier_Id = $_POST['Supplier_Id'];
$Inventort_Updated = "False";

if (empty($Id) || empty($Cost) || empty($Landing_Cost) || empty($Retail_Price) || empty($Wholesale_Price) || empty($Qty) || empty($Supplier_Id)) {
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
        $updateQuery = "UPDATE `tbl_product_details` SET `Cost` = '$Cost', `Landing_Cost` = '$Landing_Cost', `Retail_Price` = '$Retail_Price', `Wholesale_Price` = '$Wholesale_Price', `Qty` = '$Qty', `Supplier_Id` = '$Supplier_Id', `Inventort_Updated` = '$Inventort_Updated' WHERE `tbl_product_details`.`Id` = '$Id';";
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
        $myObj->error = 'no_product_details_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>