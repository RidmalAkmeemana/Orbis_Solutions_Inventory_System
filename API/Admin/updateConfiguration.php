<?php

require '../../API/Connection/BackEndPermission.php';

$Configuration_Id = $_POST['Configuration_Id'];
$Service_Charge_Type = $_POST['Service_Charge_Type'];
$serviceCharge = $_POST['serviceCharge'];
$Tax_Charge_Type = $_POST['Tax_Charge_Type'];
$taxCharge = $_POST['taxCharge'];
$Vat_Charge_Type = $_POST['Vat_Charge_Type'];
$vatCharge = $_POST['vatCharge'];

// Validate required fields
if (empty($Configuration_Id) || !isset($serviceCharge) || !isset($taxCharge) || !isset($vatCharge)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
    exit;
}

// Check if Configuration_Id exists to perform the update
$updateCheckQuery = "SELECT * FROM `tbl_system_configuration` WHERE `Id` = '$Configuration_Id';";
$updateResult = mysqli_query($conn, $updateCheckQuery);

if (mysqli_num_rows($updateResult) > 0) {
    // Configuration_Id exists, perform the update
    $updateQuery = "UPDATE `tbl_system_configuration` SET 
                    `ServiceCharge_IsPercentage` = '$Service_Charge_Type', 
                    `ServiceCharge` = '$serviceCharge', 
                    `Tax_IsPercentage` = '$Tax_Charge_Type', 
                    `Tax` = '$taxCharge',
                    `Vat_IsPercentage` = $Vat_Charge_Type,
                    `Vat` = $vatCharge 
                    WHERE `Id` = '$Configuration_Id';";

    if (mysqli_query($conn, $updateQuery)) {
        $myObj = new \stdClass();
        $myObj->success = 'true';
        echo json_encode($myObj);
    } else {
        // Debugging error message
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'update_failed';
        $myObj->sql_error = mysqli_error($conn); // Shows actual SQL error
        echo json_encode($myObj);
    }
} else {
    // If Configuration_Id doesn't exist, send appropriate response
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'no_configuration_data';
    echo json_encode($myObj);
}

?>
