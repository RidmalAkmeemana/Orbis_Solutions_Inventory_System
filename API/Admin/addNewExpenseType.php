<?php

require '../../API/Connection/BackEndPermission.php';

// Sanitize and escape POST data
$Expense_Category_Id = mysqli_real_escape_string($conn, $_POST['Expense_Category_Id']);
$Expense_Type = mysqli_real_escape_string($conn, $_POST['Expense_Type']);

if (empty($Expense_Category_Id) || empty($Expense_Type)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Expense_Type already exists in the database
    $checkQuery = "SELECT * FROM `tbl_expenses_types` WHERE `Expense_Type`='$Expense_Type'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Supplier_Name already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {
        // Get the maximum Expense_Type_Id in the database
        $maxEtpIDQuery = "SELECT MAX(Expense_Type_Id) AS max_expense_Type_id FROM tbl_expenses_types";
        $maxEtpIDResult = mysqli_query($conn, $maxEtpIDQuery);
        $maxEtpIDRow = mysqli_fetch_assoc($maxEtpIDResult);
        $maxEtpID = $maxEtpIDRow['max_expense_Type_id'];

        // If there are no existing expense type, start with ETP0001
        if (!$maxEtpID) {
            $newExpenseTypeId = 'ETP0001';
        } else {
            // Extract the numeric part of the Expense_Type_Id and increment it
            $maxEtpNum = intval(substr($maxEtpID, 3));
            $newEtpNum = str_pad($maxEtpNum + 1, 4, '0', STR_PAD_LEFT);
            $newExpenseTypeId = 'ETP' . $newEtpNum;
        }

        // Perform the insertion
        $sql = "INSERT INTO `tbl_expenses_types` (Expense_Type_Id, Expense_Category_Id, Expense_Type)
                VALUES ('$newExpenseTypeId', '$Expense_Category_Id', '$Expense_Type');";

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