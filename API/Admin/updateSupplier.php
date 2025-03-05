<?php

require '../../API/Connection/BackEndPermission.php';

$Supplier_Id = $_POST['Supplier_Id'];
$Supplier_Name = $_POST['Supplier_Name'];
$Supplier_Contact = $_POST['Supplier_Contact'];
$Supplier_Email = $_POST['Supplier_Email'];
$Supplier_Address = !empty($_POST['Supplier_Address']) ? "'" . mysqli_real_escape_string($conn, $_POST['Supplier_Address']) . "'" : 'NULL';

// Validate required fields
if (empty($Supplier_Id) || empty($Supplier_Name) || empty($Supplier_Contact) || empty($Supplier_Email)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
    exit; // Stop further execution
}

// Check if Supplier_Name already exists for a different Supplier_Id
$checkQuery = "SELECT * FROM `tbl_suppliers` WHERE `Supplier_Name` = '$Supplier_Name' AND `Supplier_Id` != '$Supplier_Id';";
$result = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    // If the Supplier_Name already exists for another Supplier_Id, return 'duplicate' error
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'duplicate';
    $myJSON = json_encode($myObj);
    echo $myJSON;
    exit; // Stop further execution
}

// Check if Supplier_Id exists to perform the update
$updateCheckQuery = "SELECT * FROM `tbl_suppliers` WHERE `Supplier_Id` = '$Supplier_Id';";
$updateResult = mysqli_query($conn, $updateCheckQuery);

if (mysqli_num_rows($updateResult) > 0) {
    // Supplier_Id exists, perform the update
    $updateQuery = "UPDATE `tbl_suppliers` SET 
                    `Supplier_Name` = '$Supplier_Name', 
                    `Supplier_Address` = $Supplier_Address, 
                    `Supplier_Contact` = '$Supplier_Contact', 
                    `Supplier_Email` = '$Supplier_Email' 
                    WHERE `Supplier_Id` = '$Supplier_Id';";

    if (mysqli_query($conn, $updateQuery)) {
        $myObj = new \stdClass();
        $myObj->success = 'true';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {
        // Update query failed
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'update_failed';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
} else {
    // If Supplier_Id doesn't exist, send appropriate response
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'no_supplier_data';
    $myJSON = json_encode($myObj);
    echo $myJSON;
}

?>
