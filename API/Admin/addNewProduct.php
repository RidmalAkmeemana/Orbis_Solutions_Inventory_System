<?php

require '../../API/Connection/BackEndPermission.php';

$Product_Id = $_POST['Product_Id'];
$Category_Id = $_POST['Category_Id'];
$Brand_Id = $_POST['Brand_Id'];
$Product_Name = $_POST['Product_Name'];
$Cost = $_POST['Cost'];
$Landing_Cost = $_POST['Landing_Cost'];
$Retail_Price = $_POST['Retail_Price'];
$Wholesale_Price = $_POST['Wholesale_Price'];
$Qty = $_POST['Qty'];
$Supplier_Id = $_POST['Supplier_Id'];

if (empty($Product_Id) || empty($Brand_Id) || empty($Category_Id) || empty($Product_Name) || empty($Cost) || empty($Landing_Cost) || empty($Retail_Price) || empty($Wholesale_Price) || empty($Qty) || empty($Supplier_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
    exit();
} else {
    // Check if the product_id already exists in the database
    $checkQuery = "SELECT * FROM `tbl_product` WHERE `Product_Id`='$Product_Id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If product_id already exists, update the tbl_product_details
        $sql = "UPDATE `tbl_product_details` SET `Cost` = '$Cost', `Landing_Cost` = '$Landing_Cost', `Retail_Price` = '$Retail_Price', `Wholesale_Price` = '$Wholesale_Price', `Qty` = `QTY` + '$Qty', `Supplier_Id` = '$Supplier_Id', `Received_Date` = current_timestamp(), `Inventort_Updated` = 'True' WHERE `tbl_product_details`.`Product_Id` = '$Product_Id';";

        if (mysqli_query($conn, $sql)) {
            $myObj = new \stdClass();
            $myObj->success = 'true';
            echo json_encode($myObj);
        } else {
            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myObj->error = 'update_failed_details';
            echo json_encode($myObj);
        }
    } else {
        // If product_id doesn't exist, insert into tbl_product first
        $sql1 = "INSERT INTO `tbl_product` (`Product_Id`, `Brand_Id`, `Category_Id`, `Product_Name`)
                 VALUES ('$Product_Id', '$Brand_Id', '$Category_Id', '$Product_Name')";

        if (mysqli_query($conn, $sql1)) {
            // Then insert into tbl_product_details
            $sql2 = "INSERT INTO `tbl_product_details` (`Product_Id`, `Cost`, `Landing_Cost`, `Retail_Price`, `Wholesale_Price`, `Qty`, `Supplier_Id`, `Received_Date`, `Inventort_Updated`)
                VALUES ('$Product_Id', '$Cost', '$Landing_Cost', '$Retail_Price', '$Wholesale_Price', '$Qty', '$Supplier_Id', current_timestamp(), 'True')";

            if (mysqli_query($conn, $sql2)) {
                $myObj = new \stdClass();
                $myObj->success = 'true';
                echo json_encode($myObj);
            } else {
                $myObj = new \stdClass();
                $myObj->success = 'false';
                $myObj->error = 'insert_failed_details';
                echo json_encode($myObj);
            }
        } else {
            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myObj->error = 'insert_failed_product';
            echo json_encode($myObj);
        }
    }
}

mysqli_close($conn);
?>