<?php

require '../../API/Connection/BackEndPermission.php';

// Sanitize and escape POST data
$Supplier_Name = mysqli_real_escape_string($conn, $_POST['Supplier_Name']);
$Supplier_Contact = mysqli_real_escape_string($conn, $_POST['Supplier_Contact']);
$Supplier_Email = mysqli_real_escape_string($conn, $_POST['Supplier_Email']);
$Supplier_Address = isset($_POST['Supplier_Address']) ? mysqli_real_escape_string($conn, $_POST['Supplier_Address']) : 'NULL';

// If Supplier_Address is empty, set it to NULL without quotes
if (empty($Supplier_Address)) {
    $Supplier_Address = 'NULL';
} else {
    // Wrap address in quotes if not empty
    $Supplier_Address = "'$Supplier_Address'";
}

if (empty($Supplier_Name) || empty($Supplier_Contact) || empty($Supplier_Email)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Supplier_Name already exists in the database
    $checkQuery = "SELECT * FROM `tbl_suppliers` WHERE `Supplier_Name`='$Supplier_Name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Supplier_Name already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {
        // Get the maximum Supplier_Id in the database
        $maxSupIDQuery = "SELECT MAX(Supplier_Id) AS max_supplier_id FROM tbl_suppliers";
        $maxSupIDResult = mysqli_query($conn, $maxSupIDQuery);
        $maxSupIDRow = mysqli_fetch_assoc($maxSupIDResult);
        $maxSupID = $maxSupIDRow['max_supplier_id'];

        // If there are no existing suppliers, start with SUP0001
        if (!$maxSupID) {
            $newSupplierId = 'SUP0001';
        } else {
            // Extract the numeric part of the Supplier_Id and increment it
            $maxSupNum = intval(substr($maxSupID, 3));
            $newSupNum = str_pad($maxSupNum + 1, 4, '0', STR_PAD_LEFT);
            $newSupplierId = 'SUP' . $newSupNum;
        }

        // Perform the insertion
        $sql = "INSERT INTO `tbl_suppliers` (Supplier_Id, Supplier_Name, Supplier_Address, Supplier_Contact, Supplier_Email)
                VALUES ('$newSupplierId', '$Supplier_Name', $Supplier_Address, '$Supplier_Contact', '$Supplier_Email');";

        if (mysqli_query($conn, $sql)) {
            $myObj = new \stdClass();
            $myObj->success = 'true';
            $myJSON = json_encode($myObj);
            echo $myJSON;
        } else {
            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myObj->message = mysqli_error($conn);
            $myJSON = json_encode($myObj);
            echo $myJSON;
        }
    }
}
?>