<?php

require '../../API/Connection/BackEndPermission.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and escape POST data
    $Invoice_Id = mysqli_real_escape_string($conn, $_POST['Invoice_Id']);

    // Check if Invoice_Id is empty
    if (empty($Invoice_Id)) {
        $response = [
            'success' => false,
            'error' => 'Invoice_Id is required.',
        ];
        echo json_encode($response);
        exit;
    }

    // Start a transaction
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

            // Extract payment details
            $lastPaidAmount = $lastPayment['Paid_Amount'];
            $lastPaymentId = $lastPayment['Payment_Id'];

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
            $grandTotal = $invoice['Grand_Total'];
            $newDueTotal = $grandTotal - ($invoice['New_Paid_Amount'] ?? 0);

            // Determine the new Status
            $newStatus = (number_format($newDueTotal, 2, '.', '') == $grandTotal) ? 'Unpaid' : 'Partially Paid';

            // Update the invoice totals and status
            $updateInvoiceSQL = "UPDATE tbl_invoice 
                                 SET 
                                     Paid_Amount = Paid_Amount - '$lastPaidAmount',
                                     Balance_Total = 0,
                                     Due_Total = '$newDueTotal',
                                     Status = '$newStatus',
                                     Description = $newDescription,
                                     Payment_Date = $newPaymentDate
                                 WHERE Invoice_Id = '$Invoice_Id';";

            if (!mysqli_query($conn, $updateInvoiceSQL)) {
                throw new Exception("Failed to update invoice: " . mysqli_error($conn));
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
            throw new Exception("No payments found for Invoice_Id: $Invoice_Id.");
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
