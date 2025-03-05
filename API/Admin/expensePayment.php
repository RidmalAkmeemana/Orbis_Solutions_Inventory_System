<?php

require '../../API/Connection/BackEndPermission.php';

// Sanitize and escape POST data
$Expense_Id = mysqli_real_escape_string($conn, $_POST['Expense_Id']);
$Expense_Type = mysqli_real_escape_string($conn, $_POST['Expense_Type']);
$Expense_Amount = mysqli_real_escape_string($conn, $_POST['Expense_Amount']);
$Due_Amount = mysqli_real_escape_string($conn, $_POST['Due_Amount']);
$Last_Paid_Amount = mysqli_real_escape_string($conn, $_POST['Last_Paid_Amount']);
$Paid_Amount = mysqli_real_escape_string($conn, $_POST['Paid_Amount']);
$Payment_Type = mysqli_real_escape_string($conn, $_POST['Payment_Type']);
$Expense_Description = isset($_POST['Expense_Description']) ? mysqli_real_escape_string($conn, $_POST['Expense_Description']) : 'NULL';
$Updated_By = mysqli_real_escape_string($conn, $_POST['Updated_By']);

// If Expense_Description is empty, set it to NULL without quotes
if (empty($Expense_Description)) {
    $Expense_Description = 'NULL';
} else {
    $Expense_Description = "'$Expense_Description'";
}

$Final_Paid_Amount = $Last_Paid_Amount + $Paid_Amount;

// Get the current date and time
date_default_timezone_set("Asia/Colombo");
$currentDateTime = date('Y-m-d H:i:s');

// Determine the status based on the paid amount
if ($Due_Amount == 0) {
    $Status = "Fully Paid";
    $PaymentDate = "'$currentDateTime'";
} else {
    $Status = "Partially Paid";
    $PaymentDate = "'$currentDateTime'";
}

// Check for empty fields
if (empty($Expense_Id) || empty($Expense_Amount) || empty($Due_Amount) || empty($Paid_Amount) || empty($Payment_Type) || empty($Updated_By)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
} else {
    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Check if Expense_Id exists in the database
        $checkQuery = "SELECT * FROM `tbl_expenses` WHERE `Expense_Id` = '$Expense_Id';";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            // If Expense_Id exists, update the invoice details
            $updateQuery = "UPDATE `tbl_expenses` SET 
                                `Status` = '$Status',
                                `Paid_Amount` = '$Final_Paid_Amount',  
                                `Due_Amount` = '$Due_Amount', 
                                `Payment_Type` = '$Payment_Type', 
                                `Expense_Description` = $Expense_Description, 
                                `Payment_Date` = " . ($PaymentDate === 'NULL' ? 'NULL' : $PaymentDate) . " 
                                WHERE `Expense_Id` = '$Expense_Id';";
            
            if (!mysqli_query($conn, $updateQuery)) {
                throw new Exception("Failed to update invoice: " . mysqli_error($conn));
            }

            // Determine the Payment_Id by counting the number of previous payments for this Expense_Id
            $countPaymentSQL = "SELECT COUNT(*) AS paymentCount FROM `tbl_expense_payments` WHERE `Expense_Id` = '$Expense_Id';";
            $result = mysqli_query($conn, $countPaymentSQL);
            $paymentId = 1; // Default Payment_Id to 1 if no payments exist

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $paymentId = $row['paymentCount'] + 1; // Increment Payment_Id
            }

            // Insert the new payment record with the calculated Payment_Id
            $paymentInsertQuery = "INSERT INTO `tbl_expense_payments` 
                                    (`Expense_Id`, `Payment_Id`, `Expense_Amount`, `Paid_Amount`, `Due_Amount`, `Payment_Type`, `Payment_Date`, `Expense_Description`, `Updated_By`) 
                                    VALUES ('$Expense_Id', '$paymentId', '$Expense_Amount', '$Paid_Amount', '$Due_Amount', '$Payment_Type', '$currentDateTime', $Expense_Description, '$Updated_By');";

            if (!mysqli_query($conn, $paymentInsertQuery)) {
                throw new Exception("Failed to insert payment: " . mysqli_error($conn));
            }

            // Get the Id of the newly inserted payment record
            $expenseTransactionId = mysqli_insert_id($conn);

            // Determine the expense value based on the payment status
            $expenseValue = $Paid_Amount;

            // Insert into tbl_cash_flow
            $paymentDescription = $Expense_Type . " Payment_" . $paymentId . " - " . $Expense_Id;

            $insertCashFlowSQL = "INSERT INTO tbl_cash_flow 
                (Income_Transaction_Id, Expense_Transaction_Id, Description, Income, Expense, Payment_Type, Update_Date) 
                VALUES 
                ( NULL, '$expenseTransactionId', '$paymentDescription', NULL, '$expenseValue', '$Payment_Type', '$currentDateTime')";

            
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
