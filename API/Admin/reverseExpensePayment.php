<?php

require '../../API/Connection/BackEndPermission.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $Expense_Id = mysqli_real_escape_string($conn, $_POST['Expense_Id']);
    $Reverse_Reason = mysqli_real_escape_string($conn, $_POST['Reverse_Reason']);
    $Reversed_By = mysqli_real_escape_string($conn, $_POST['Reverse_By']);

    // Get the current date and time
    date_default_timezone_set("Asia/Colombo");
    $currentDateTime = date('Y-m-d H:i:s');

    if (empty($Expense_Id || $Reverse_Reason || $Reversed_By)) {
        echo json_encode(['success' => false, 'error' => 'Expense_Id, Reverse_Reason & Reversed_By is required.']);
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // Fetch the last payment record
        $getLastPaymentSQL = "SELECT * FROM tbl_expense_payments WHERE Expense_Id = '$Expense_Id' ORDER BY Payment_Id DESC LIMIT 1";
        $result = mysqli_query($conn, $getLastPaymentSQL);

        if (!$result || mysqli_num_rows($result) === 0) {
            throw new Exception("No payments found for Expense_Id: $Expense_Id.");
        }

        $lastPayment = mysqli_fetch_assoc($result);
        $lastPaidAmount = $lastPayment['Paid_Amount'];
        $lastPaymentId = $lastPayment['Payment_Id'];
        $expenseAmount = $lastPayment['Expense_Amount'];
        $paymentDate = $lastPayment['Payment_Date'];
        $reverseDate = $currentDateTime;

        // Insert into history table
        $insertHistorySQL = "
            INSERT INTO tbl_expense_payments_history 
                (Expense_Id, Payment_Id, Expense_Amount, Reverse_Amount, Reverse_Reason, Payment_Date, Reverse_Date, Reversed_By)
            VALUES 
                ('$Expense_Id', '$lastPaymentId', '$expenseAmount', '$lastPaidAmount', '$Reverse_Reason', 
                '$paymentDate', '$reverseDate','$Reversed_By')
        ";
        if (!mysqli_query($conn, $insertHistorySQL)) {
            throw new Exception("Failed to insert into payment history: " . mysqli_error($conn));
        }

        // Delete the payment
        $deletePaymentSQL = "DELETE FROM tbl_expense_payments WHERE Expense_Id = '$Expense_Id' AND Payment_Id = '$lastPaymentId'";
        if (!mysqli_query($conn, $deletePaymentSQL)) {
            throw new Exception("Failed to delete payment: " . mysqli_error($conn));
        }

        // Delete related cash flow
        $deleteCashFlowSQL = "DELETE FROM tbl_cash_flow WHERE Expense_Transaction_Id = '$lastPaymentId'";
        if (!mysqli_query($conn, $deleteCashFlowSQL)) {
            throw new Exception("Failed to delete cash flow: " . mysqli_error($conn));
        }

        // Check if any payments remain
        $remainingPaymentsSQL = "SELECT * FROM tbl_expense_payments WHERE Expense_Id = '$Expense_Id' ORDER BY Payment_Id DESC LIMIT 1";
        $remainingResult = mysqli_query($conn, $remainingPaymentsSQL);

        $newDescription = "NULL";
        $newPaymentDate = "NULL";

        if ($remainingResult && mysqli_num_rows($remainingResult) > 0) {
            $lastRemaining = mysqli_fetch_assoc($remainingResult);
            $newDescription = "'" . mysqli_real_escape_string($conn, $lastRemaining['Expense_Description']) . "'";
            $newPaymentDate = "'" . mysqli_real_escape_string($conn, $lastRemaining['Payment_Date']) . "'";
        }

        // Recalculate amounts and status
        $getExpenseSQL = "
            SELECT Expense_Amount, Paid_Amount - '$lastPaidAmount' AS New_Paid_Amount 
            FROM tbl_expenses 
            WHERE Expense_Id = '$Expense_Id'
        ";
        $expenseResult = mysqli_query($conn, $getExpenseSQL);
        if (!$expenseResult || mysqli_num_rows($expenseResult) === 0) {
            throw new Exception("Failed to fetch expense details: " . mysqli_error($conn));
        }

        $expense = mysqli_fetch_assoc($expenseResult);
        $newPaid = $expense['New_Paid_Amount'];
        $totalExpense = $expense['Expense_Amount'];
        $newDue = $totalExpense - $newPaid;

        $newStatus = (number_format($newDue, 2, '.', '') == $totalExpense) ? 'Unpaid' : 'Partially Paid';

        // Update tbl_expenses
        $updateExpenseSQL = "
            UPDATE tbl_expenses 
            SET 
                Paid_Amount = '$newPaid',
                Due_Amount = '$newDue',
                Status = '$newStatus',
                Expense_Description = $newDescription,
                Payment_Date = $newPaymentDate
            WHERE Expense_Id = '$Expense_Id'
        ";
        if (!mysqli_query($conn, $updateExpenseSQL)) {
            throw new Exception("Failed to update expense: " . mysqli_error($conn));
        }

        mysqli_commit($conn);

        echo json_encode([
            'success' => true,
            'message' => 'Last payment reversed successfully. Status updated accordingly.',
        ]);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method.',
    ]);
}
?>
