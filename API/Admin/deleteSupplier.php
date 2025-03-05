<?php

require '../../API/Connection/BackEndPermission.php';

$Supplier_Id = $_POST['Supplier_Id'];

if (empty($Supplier_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Supplier_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_suppliers` WHERE `tbl_suppliers`.`Supplier_Id` = '$Supplier_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Supplier_Id exists, perform the delete
        $updateQuery = "DELETE FROM tbl_suppliers WHERE Supplier_Id = '$Supplier_Id';";
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
        // If Supplier_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_supplier_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>