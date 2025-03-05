<?php

require '../../API/Connection/BackEndPermission.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and escape POST data
    $Expense_Id = mysqli_real_escape_string($conn, $_POST['Expense_Id']);

    // Check if Expense_Id is empty
    if (empty($Expense_Id)) {
        $response = [
            'success' => false,
            'error' => 'Expense_Id is required.',
        ];
        echo json_encode($response);
        exit;
    }

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch the last payment record for the given Expense_Id
        $getLastPaymentSQL = "SELECT * 
                              FROM tbl_expense_payments 
                              WHERE Expense_Id = '$Expense_Id' 
                              ORDER BY Payment_Id DESC LIMIT 1;";
        $result = mysqli_query($conn, $getLastPaymentSQL);

        if ($result && mysqli_num_rows($result) > 0) {
            $lastPayment = mysqli_fetch_assoc($result);

            // Extract payment details
            $lastPaidAmount = $lastPayment['Paid_Amount'];
            $lastPaymentId = $lastPayment['Payment_Id'];

            // Delete the last payment record
            $deletePaymentSQL = "DELETE FROM tbl_expense_payments 
                                 WHERE Expense_Id = '$Expense_Id' 
                                 AND Payment_Id = '$lastPaymentId';";
            if (!mysqli_query($conn, $deletePaymentSQL)) {
                throw new Exception("Failed to delete payment: " . mysqli_error($conn));
            }

            // Remove the corresponding entry from tbl_cash_flow
            $deleteCashFlowSQL = "DELETE FROM tbl_cash_flow 
                                  WHERE Expense_Transaction_Id = '$lastPaymentId';";
            if (!mysqli_query($conn, $deleteCashFlowSQL)) {
                throw new Exception("Failed to delete cash flow: " . mysqli_error($conn));
            }

            // Check if any payments remain for the expense
            $getRemainingPaymentsSQL = "SELECT * 
                                        FROM tbl_expense_payments 
                                        WHERE Expense_Id = '$Expense_Id' 
                                        ORDER BY Payment_Id DESC LIMIT 1;";
            $remainingPaymentsResult = mysqli_query($conn, $getRemainingPaymentsSQL);

            $newDescription = "NULL";
            $newPaymentDate = "NULL";

            if ($remainingPaymentsResult && mysqli_num_rows($remainingPaymentsResult) > 0) {
                $lastRemainingPayment = mysqli_fetch_assoc($remainingPaymentsResult);
                $newDescription = "'" . mysqli_real_escape_string($conn, $lastRemainingPayment['Expense_Description']) . "'";
                $newPaymentDate = "'" . mysqli_real_escape_string($conn, $lastRemainingPayment['Payment_Date']) . "'";
            }

            // Calculate Due_Amount and determine the new Status
            $getExpenseDetailsSQL = "SELECT Expense_Amount, Paid_Amount - '$lastPaidAmount' AS New_Paid_Amount 
                                     FROM tbl_expenses 
                                     WHERE Expense_Id = '$Expense_Id';";
            $expenseResult = mysqli_query($conn, $getExpenseDetailsSQL);

            if (!$expenseResult || mysqli_num_rows($expenseResult) === 0) {
                throw new Exception("Failed to fetch expense details: " . mysqli_error($conn));
            }

            $expense = mysqli_fetch_assoc($expenseResult);
            $expenseAmount = $expense['Expense_Amount'];
            $newDueAmount = $expenseAmount - ($expense['New_Paid_Amount'] ?? 0);

            // Determine the new Status
            $newStatus = (number_format($newDueAmount, 2, '.', '') == $expenseAmount) ? 'Unpaid' : 'Partially Paid';

            // Update the expense totals and status
            $updateExpenseSQL = "UPDATE tbl_expenses 
                                 SET 
                                     Paid_Amount = Paid_Amount - '$lastPaidAmount',
                                     Due_Amount = '$newDueAmount',
                                     Status = '$newStatus',
                                     Expense_Description = $newDescription,
                                     Payment_Date = $newPaymentDate
                                 WHERE Expense_Id = '$Expense_Id';";

            if (!mysqli_query($conn, $updateExpenseSQL)) {
                throw new Exception("Failed to update expense: " . mysqli_error($conn));
            }

            // Commit the transaction
            mysqli_commit($conn);

            // Return success response
            $response = [
                'success' => true,
                'message' => 'Last payment reversed successfully. Status updated accordingly.',
            ];
            echo json_encode($response);
        } else {
            throw new Exception("No payments found for Expense_Id: $Expense_Id.");
        }
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($conn);

        // Return error response
        $response = [
            'success' => false,
            'message' => $e->getMessage(),
        ];
        echo json_encode($response);
    }
} else {
    // Handle invalid request method
    $response = [
        'success' => false,
        'error' => 'Invalid request method.',
    ];
    echo json_encode($response);
}
?>
