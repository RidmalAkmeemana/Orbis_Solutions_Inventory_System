<?php

require '../../API/Connection/BackEndPermission.php';

// Sanitize and escape POST data
$Invoice_Id = mysqli_real_escape_string($conn, $_POST['Invoice_Id']);
$Grand_Total = mysqli_real_escape_string($conn, $_POST['Grand_Total']);
$Balance_Total = mysqli_real_escape_string($conn, $_POST['Balance_Total']);
$Due_Total = mysqli_real_escape_string($conn, $_POST['Due_Total']);
$Last_Paid_Amount = mysqli_real_escape_string($conn, $_POST['Last_Paid_Amount']);
$Paid_Amount = mysqli_real_escape_string($conn, $_POST['Paid_Amount']);
$Payment_Type = mysqli_real_escape_string($conn, $_POST['Payment_Type']);
$Description = isset($_POST['Description']) ? mysqli_real_escape_string($conn, $_POST['Description']) : 'NULL';
$Updated_By = mysqli_real_escape_string($conn, $_POST['Updated_By']);

// If Description is empty, set it to NULL without quotes
if (empty($Description)) {
    $Description = 'NULL';
} else {
    $Description = "'$Description'";
}

$Final_Paid_Amount = $Last_Paid_Amount + $Paid_Amount;

// Get the current date and time
date_default_timezone_set("Asia/Colombo");
$currentDateTime = date('Y-m-d H:i:s');

// Determine the status based on the paid amount
if ($Due_Total == 0) {
    $Status = "Fully Paid";
    $PaymentDate = "'$currentDateTime'";
} else {
    $Status = "Partially Paid";
    $PaymentDate = "'$currentDateTime'";
}

// Check for empty fields
if (empty($Invoice_Id) || empty($Grand_Total) || empty($Balance_Total) || empty($Due_Total) || empty($Paid_Amount) || empty($Payment_Type) || empty($Updated_By)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
} else {
    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Check if Invoice_Id exists in the database
        $checkQuery = "SELECT * FROM `tbl_invoice` WHERE `Invoice_Id` = '$Invoice_Id';";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            // If Invoice_Id exists, update the invoice details
            $updateQuery = "UPDATE `tbl_invoice` SET 
                                `Status` = '$Status',
                                `Paid_Amount` = '$Final_Paid_Amount', 
                                `Balance_Total` = '$Balance_Total', 
                                `Due_Total` = '$Due_Total', 
                                `Payment_Type` = '$Payment_Type', 
                                `Description` = $Description, 
                                `Payment_Date` = " . ($PaymentDate === 'NULL' ? 'NULL' : $PaymentDate) . " 
                                WHERE `Invoice_Id` = '$Invoice_Id';";
            
            if (!mysqli_query($conn, $updateQuery)) {
                throw new Exception("Failed to update invoice: " . mysqli_error($conn));
            }

            // Determine the Payment_Id by counting the number of previous payments for this Invoice_Id
            $countPaymentSQL = "SELECT COUNT(*) AS paymentCount FROM `tbl_payments` WHERE `Invoice_Id` = '$Invoice_Id';";
            $result = mysqli_query($conn, $countPaymentSQL);
            $paymentId = 1; // Default Payment_Id to 1 if no payments exist

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $paymentId = $row['paymentCount'] + 1; // Increment Payment_Id
            }

            // Insert the new payment record with the calculated Payment_Id
            $paymentInsertQuery = "INSERT INTO `tbl_payments` 
                                    (`Invoice_Id`, `Payment_Id`, `Grand_Total`, `Balance_Total`, `Due_Total`, `Paid_Amount`, `Payment_Type`, `Payment_Date`, `Description`, `Updated_By`) 
                                    VALUES ('$Invoice_Id', '$paymentId', '$Grand_Total', '$Balance_Total', '$Due_Total', '$Paid_Amount', '$Payment_Type', '$currentDateTime', $Description, '$Updated_By');";

            if (!mysqli_query($conn, $paymentInsertQuery)) {
                throw new Exception("Failed to insert payment: " . mysqli_error($conn));
            }

            // Get the Id of the newly inserted payment record
            $incomeTransactionId = mysqli_insert_id($conn);

            // Determine the income value based on the payment status
            $incomeValue = $Paid_Amount - $Balance_Total;

            // Insert into tbl_cash_flow
            $paymentDescription = "Invoice Payment_" . $paymentId . " - " . $Invoice_Id;

            $insertCashFlowSQL = "INSERT INTO tbl_cash_flow 
                (Income_Transaction_Id, Expense_Transaction_Id, Description, Income, Expense, Payment_Type, Update_Date) 
                VALUES 
                ('$incomeTransactionId', NULL, '$paymentDescription', '$incomeValue', NULL, '$Payment_Type', '$currentDateTime')";

            
            if (!mysqli_query($conn, $insertCashFlowSQL)) {
                throw new Exception("Failed to update cash flow: " . mysqli_error($conn));
            }

            // Commit the transaction if both queries succeeded
            mysqli_commit($conn);

            // Return success response
            $myObj = new \stdClass();
            $myObj->success = 'true';
            echo json_encode($myObj);
        } else {
            // If Invoice_Id doesn't exist, send appropriate response
            throw new Exception("No invoice data found for Invoice_Id: $Invoice_Id");
        }
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($conn);

        // Return error response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->message = $e->getMessage();
        echo json_encode($myObj);
    }
}
?>
