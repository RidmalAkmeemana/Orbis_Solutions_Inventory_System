<?php

require '../../API/Connection/BackEndPermission.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Invoice_Id = mysqli_real_escape_string($conn, $_POST['Invoice_Id']);
    $Reverse_Reason = mysqli_real_escape_string($conn, $_POST['Reverse_Reason']);
    $Reversed_By = mysqli_real_escape_string($conn, $_POST['Reverse_By']);

    date_default_timezone_set("Asia/Colombo");
    $currentDateTime = date('Y-m-d H:i:s');

    if (empty($Invoice_Id) || empty($Reverse_Reason) || empty($Reversed_By)) {
        echo json_encode([
            'success' => false,
            'error' => 'Invoice_Id, Reverse_Reason, and Reversed_By are required.'
        ]);
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // Fetch the last payment record for the given Invoice_Id
        $getLastPaymentSQL = "SELECT * 
                              FROM tbl_payments 
                              WHERE Invoice_Id = '$Invoice_Id' 
                              ORDER BY Payment_Id DESC LIMIT 1;";
        $result = mysqli_query($conn, $getLastPaymentSQL);

        if ($result && mysqli_num_rows($result) > 0) {
            $lastPayment = mysqli_fetch_assoc($result);
            $lastPaidAmount = $lastPayment['Paid_Amount'];
            $lastBalanceTotal = $lastPayment['Balance_Total'];
            $lastTotalPaid = $lastPaidAmount - $lastBalanceTotal;
            $lastPaymentId = $lastPayment['Payment_Id'];
            $grandTotal = $lastPayment['Grand_Total'];
            $paymentDate = $lastPayment['Payment_Date'];

            // Insert into tbl_payments_history before deletion
            $insertHistorySQL = "
                INSERT INTO tbl_payments_history (
                    Invoice_Id,
                    Payment_Id,
                    Grand_Total,
                    Reverse_Amount,
                    Reverse_Reason,
                    Payment_Date,
                    Reverse_Date,
                    Reversed_By
                ) VALUES (
                    '$Invoice_Id',
                    '$lastPaymentId',
                    '$grandTotal',
                    '$lastTotalPaid',
                    '$Reverse_Reason',
                    '$paymentDate',
                    '$currentDateTime',
                    '$Reversed_By'
                );";

            if (!mysqli_query($conn, $insertHistorySQL)) {
                throw new Exception("Failed to insert into payment history: " . mysqli_error($conn));
            }

            // Delete the last payment record
            $deletePaymentSQL = "DELETE FROM tbl_payments 
                                 WHERE Invoice_Id = '$Invoice_Id' 
                                 AND Payment_Id = '$lastPaymentId';";
            if (!mysqli_query($conn, $deletePaymentSQL)) {
                throw new Exception("Failed to delete payment: " . mysqli_error($conn));
            }

            // Remove the corresponding entry from tbl_cash_flow
            $deleteCashFlowSQL = "DELETE FROM tbl_cash_flow 
                                  WHERE Income_Transaction_Id = '$lastPaymentId';";
            if (!mysqli_query($conn, $deleteCashFlowSQL)) {
                throw new Exception("Failed to delete cash flow: " . mysqli_error($conn));
            }

            // Check if any payments remain for the invoice
            $getRemainingPaymentsSQL = "SELECT * 
                                        FROM tbl_payments 
                                        WHERE Invoice_Id = '$Invoice_Id' 
                                        ORDER BY Payment_Id DESC LIMIT 1;";
            $remainingPaymentsResult = mysqli_query($conn, $getRemainingPaymentsSQL);

            $newDescription = "NULL";
            $newPaymentDate = "NULL";

            if ($remainingPaymentsResult && mysqli_num_rows($remainingPaymentsResult) > 0) {
                $lastRemainingPayment = mysqli_fetch_assoc($remainingPaymentsResult);
                $newDescription = "'" . mysqli_real_escape_string($conn, $lastRemainingPayment['Description']) . "'";
                $newPaymentDate = "'" . mysqli_real_escape_string($conn, $lastRemainingPayment['Payment_Date']) . "'";
            }

            // Calculate Due_Total and determine the new Status
            $getInvoiceDetailsSQL = "SELECT Grand_Total, Paid_Amount - '$lastPaidAmount' AS New_Paid_Amount 
                                     FROM tbl_invoice 
                                     WHERE Invoice_Id = '$Invoice_Id';";
            $invoiceResult = mysqli_query($conn, $getInvoiceDetailsSQL);

            if (!$invoiceResult || mysqli_num_rows($invoiceResult) === 0) {
                throw new Exception("Failed to fetch invoice details: " . mysqli_error($conn));
            }

            $invoice = mysqli_fetch_assoc($invoiceResult);
            $newPaidAmount = $invoice['New_Paid_Amount'];
            $grandTotal = $invoice['Grand_Total'];
            $newDueTotal = $grandTotal - $newPaidAmount;

            $newStatus = (number_format($newDueTotal, 2, '.', '') == $grandTotal) ? 'Unpaid' : 'Partially Paid';

            // Update the invoice totals and status
            $updateInvoiceSQL = "UPDATE tbl_invoice 
                                 SET 
                                     Paid_Amount = '$newPaidAmount',
                                     Balance_Total = 0,
                                     Due_Total = '$newDueTotal',
                                     Status = '$newStatus',
                                     Description = $newDescription,
                                     Payment_Date = $newPaymentDate
                                 WHERE Invoice_Id = '$Invoice_Id';";

            if (!mysqli_query($conn, $updateInvoiceSQL)) {
                throw new Exception("Failed to update invoice: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            echo json_encode([
                'success' => true,
                'message' => 'Last payment reversed successfully. Status updated accordingly.'
            ]);
        } else {
            throw new Exception("No payments found for Invoice_Id: $Invoice_Id.");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method.'
    ]);
}
?>
