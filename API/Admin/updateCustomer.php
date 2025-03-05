<?php

require '../../API/Connection/BackEndPermission.php';

$Customer_Id = $_POST['Customer_Id'];
$Customer_Name = $_POST['Customer_Name'];
$Customer_Contact = $_POST['Customer_Contact'];
$Customer_Email = !empty($_POST['Customer_Email']) ? "'" . mysqli_real_escape_string($conn, $_POST['Customer_Email']) . "'" : 'NULL';
$Customer_Address = !empty($_POST['Customer_Address']) ? "'" . mysqli_real_escape_string($conn, $_POST['Customer_Address']) . "'" : 'NULL';

// Validate required fields
if (empty($Customer_Id) || empty($Customer_Name) || empty($Customer_Contact)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
    exit;
}

// Check if Customer_Id exists in the database
$checkQuery = "SELECT * FROM `tbl_customers` WHERE `Customer_Id` = '$Customer_Id';";
$result = mysqli_query($conn, $checkQuery);

if ($result && mysqli_num_rows($result) > 0) {
    // If Customer_Id exists, perform the update
    $updateQuery = "
        UPDATE `tbl_customers`
        SET 
            `Customer_Name` = '$Customer_Name', 
            `Customer_Address` = $Customer_Address, 
            `Customer_Contact` = '$Customer_Contact', 
            `Customer_Email` = $Customer_Email
        WHERE `Customer_Id` = '$Customer_Id';
    ";

    if (mysqli_query($conn, $updateQuery)) {
        $myObj = new \stdClass();
        $myObj->success = 'true';
        echo json_encode($myObj);
    } else {
        $myObj = new \stdClass();
        $myObj->success = 'false';
        echo json_encode($myObj);
    }
} else {
    // If Customer_Id doesn't exist
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'no_customer_data';
    echo json_encode($myObj);
}
?>
