<?php

require '../../API/Connection/BackEndPermission.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and escape POST data
    $Invoice_Id = mysqli_real_escape_string($conn, $_POST['Invoice_Id']);

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
        // Check if payments exist for the Invoice_Id
        $checkPaymentsSQL = "SELECT COUNT(*) AS PaymentCount FROM tbl_payments WHERE Invoice_Id = '$Invoice_Id'";
        $paymentResult = mysqli_query($conn, $checkPaymentsSQL);
        $paymentData = mysqli_fetch_assoc($paymentResult);

        if ($paymentData['PaymentCount'] > 0) {
            throw new Exception("Payments exist for Invoice_Id: $Invoice_Id. Cannot proceed with return.");
        }

        // Check if cash flow entries exist for the Invoice_Id
        $checkCashFlowSQL = "SELECT COUNT(*) AS CashFlowCount FROM tbl_cash_flow WHERE Income_Transaction_Id IN (SELECT Payment_Id FROM tbl_payments WHERE Invoice_Id = '$Invoice_Id')";
        $cashFlowResult = mysqli_query($conn, $checkCashFlowSQL);
        $cashFlowData = mysqli_fetch_assoc($cashFlowResult);

        if ($cashFlowData['CashFlowCount'] > 0) {
            throw new Exception("Cash flow entries exist for Invoice_Id: $Invoice_Id. Cannot proceed with return.");
        }

        // Retrieve items under the Invoice_Id
        $getInvoiceItemsSQL = "SELECT Product_Id, Qty FROM tbl_item WHERE Invoice_Id = '$Invoice_Id'";
        $itemResults = mysqli_query($conn, $getInvoiceItemsSQL);

        if ($itemResults && mysqli_num_rows($itemResults) > 0) {
            while ($item = mysqli_fetch_assoc($itemResults)) {
                $productId = $item['Product_Id'];
                $qty = $item['Qty'];

                // Update the product quantity in the latest record in tbl_product_details based on Received_Date
                $updateProductSQL = "UPDATE tbl_product_details 
                                     SET Qty = Qty + $qty 
                                     WHERE Product_Id = '$productId' 
                                     AND Received_Date = (
                                         SELECT MAX(Received_Date) 
                                         FROM tbl_product_details 
                                         WHERE Product_Id = '$productId'
                                     )";
                if (!mysqli_query($conn, $updateProductSQL)) {
                    throw new Exception("Failed to update product quantity: " . mysqli_error($conn));
                }
            }

            // Delete the items from tbl_item after updating product quantities
            $deleteItemsSQL = "DELETE FROM tbl_item WHERE Invoice_Id = '$Invoice_Id'";
            if (!mysqli_query($conn, $deleteItemsSQL)) {
                throw new Exception("Failed to delete items: " . mysqli_error($conn));
            }

            // Delete the invoice
            $deleteInvoiceSQ = "DELETE FROM tbl_invoice WHERE Invoice_Id = '$Invoice_Id'";
            if (!mysqli_query($conn, $deleteInvoiceSQ)) {
                throw new Exception("Failed to update invoice status: " . mysqli_error($conn));
            }
        } else {
            throw new Exception("No items found under Invoice_Id: $Invoice_Id.");
        }

        // Commit the transaction
        mysqli_commit($conn);

        $response = [
            'success' => true,
            'message' => 'Invoice returned successfully. Product quantities updated, and items deleted.',
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        mysqli_rollback($conn);

        $response = [
            'success' => false,
            'message' => $e->getMessage(),
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        'success' => false,
        'error' => 'Invalid request method.',
    ];
    echo json_encode($response);
}

?>
