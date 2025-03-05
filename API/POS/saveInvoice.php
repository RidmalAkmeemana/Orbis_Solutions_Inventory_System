<?php

require '../../API/Connection/BackEndPermission.php';

// Fetch product data from POST
$invoiceNos = $_POST['Invoice_No'];
$productIds = $_POST['Product_Id'];
$Ids = $_POST['Id'];  // This is Product_Detail_Id
$productNames = $_POST['Product_Name'];
$landingCosts = $_POST['Landing_Cost'];
$unitPrices = $_POST['Unit_Price'];
$qtys = $_POST['Qty'];
$totalPrices = $_POST['Total_Price'];
$totalCosts = $_POST['Total_Cost'];
$totalProfits = $_POST['Total_Profit'];
$unitDiscounts = $_POST['unit-discount-input'];
$totalDiscounts = $_POST['total-discount-input'];

// Invoice data
$invoiceId = $_POST['Invoice_Id'];
$customerId = $_POST['Customer_Id'];
$userId = $_POST['User_Id'];
$itemCount = $_POST['Item_Count'];
$subTotal = str_replace(',', '', $_POST['Sub_Total']);
$discountTotal = str_replace(',', '', $_POST['Discount_Total']);
$profitTotal = str_replace(',', '', $_POST['Profit_Total']);
$grandTotal = str_replace(',', '', $_POST['Grand_Total']);
$paidAmount = str_replace(',', '', $_POST['Paid_Amount']);
$balanceTotal = str_replace(',', '', $_POST['Balance_Total']);
$dueTotal = str_replace(',', '', $_POST['Due_Total']);
$paymentMethod = isset($_POST['Payment_Type']) ? $_POST['Payment_Type'] : '';
$saleType = $_POST['Sale_Type'];
$description = isset($_POST['Description']) ? mysqli_real_escape_string($conn, $_POST['Description']) : 'NULL';

if (empty($description)) {
    $description = 'NULL';
} else {
    $description = "'$description'";
}

// Get the current date and time
date_default_timezone_set("Asia/Colombo");
$currentDateTime = date('Y-m-d H:i:s');

// Determine the status based on the paid amount
if ($paidAmount >= $grandTotal) {
    $status = "Fully Paid";
    $paymentDate = "'$currentDateTime'";
} elseif ($paidAmount < $grandTotal && $paidAmount > 0) {
    $status = "Partially Paid";
    $paymentDate = "'$currentDateTime'";
} else {
    $status = "Unpaid";
    $paymentDate = 'NULL';
}

// Check if any required field is empty
if (empty($invoiceId) || empty($customerId) || empty($userId) || empty($grandTotal)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'empty';
    echo json_encode($response);
    exit();
}

// Check if the Invoice_No already exists in the database
$checkInvoiceSQL = "SELECT COUNT(*) AS count FROM tbl_invoice WHERE Invoice_Id='$invoiceId'";
$checkInvoiceResult = mysqli_query($conn, $checkInvoiceSQL);
$invoiceRow = mysqli_fetch_assoc($checkInvoiceResult);

if ($invoiceRow['count'] > 0) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'duplicate';
    echo json_encode($response);
    exit();
}

// Check if the Customer_Id exists
$checkCustomerSQL = "SELECT COUNT(*) AS count FROM tbl_customers WHERE Customer_Id='$customerId'";
$checkCustomerResult = mysqli_query($conn, $checkCustomerSQL);
$customerRow = mysqli_fetch_assoc($checkCustomerResult);

if ($customerRow['count'] == 0) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'invalid_customer';
    echo json_encode($response);
    exit();
}

// Check for duplicate product entries in tbl_item
$duplicateCheck = [];
for ($i = 0; $i < count($productIds); $i++) {
    $key = $invoiceNos[$i] . '-' . $productIds[$i];
    if (isset($duplicateCheck[$key])) {
        $response = new \stdClass();
        $response->success = 'false';
        $response->error = 'duplicate_product';
        $response->message = "Duplicate Product Id: {$productIds[$i]} !";
        echo json_encode($response);
        exit();
    }
    $duplicateCheck[$key] = true;
}

// Insert invoice data into tbl_invoice
$insertInvoiceSQL = "INSERT INTO tbl_invoice 
    (Invoice_Id, Customer_Id, User_Id, Sale_Type, Item_Count, Status, Sub_Total, Discount_Total, Profit_Total, Grand_Total, Paid_Amount, Balance_Total, Due_Total, Payment_Type, Description, Invoice_Date, Payment_Date)
    VALUES 
    ('$invoiceId', '$customerId', '$userId', '$saleType', '$itemCount', '$status', '$subTotal', '$discountTotal', '$profitTotal', '$grandTotal', '$paidAmount', '$balanceTotal', '$dueTotal', '$paymentMethod', $description, '$currentDateTime', $paymentDate)";

if (!mysqli_query($conn, $insertInvoiceSQL)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'database_error_invoice';
    $response->errorDetails = mysqli_error($conn);
    echo json_encode($response);
    exit();
}

// Insert payment data into tbl_payments only if status is not "Unpaid"
if ($status !== "Unpaid") {
    // Determine the Payment_Id by counting the number of previous payments for this Invoice_Id
    $countPaymentSQL = "SELECT COUNT(*) AS paymentCount FROM tbl_payments WHERE Invoice_Id = '$invoiceId'";
    $result = mysqli_query($conn, $countPaymentSQL);
    $paymentId = 1; // Default Payment_Id to 1 if no payments exist

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $paymentId = $row['paymentCount'] + 1; // Increment Payment_Id
    }

    // Insert the new payment record
    $insertPaymentSQL = "INSERT INTO tbl_payments 
        (Invoice_Id, Payment_Id, Grand_Total, Paid_Amount, Balance_Total, Due_Total, Payment_Type, Description, Payment_Date, Updated_By)
        VALUES 
        ('$invoiceId', '$paymentId', '$grandTotal', '$paidAmount', '$balanceTotal', '$dueTotal', '$paymentMethod', $description, '$currentDateTime', '$userId')";

    if (!mysqli_query($conn, $insertPaymentSQL)) {
        $response = new \stdClass();
        $response->success = 'false';
        $response->error = 'database_error_payment';
        $response->errorDetails = mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    // Get the Id of the newly inserted payment record
    $incomeTransactionId = mysqli_insert_id($conn);

    // Determine the income value based on the payment status
    $incomeValue = ($status === "Fully Paid") ? $grandTotal : $paidAmount;

    // Insert into tbl_cash_flow
    $paymentDescription = "Invoice Payment_" . $paymentId . " - " . $invoiceId;

    $insertCashFlowSQL = "INSERT INTO tbl_cash_flow 
        (Income_Transaction_Id, Expense_Transaction_Id, Description, Income, Expense, Payment_Type, Update_Date) 
        VALUES 
        ('$incomeTransactionId', NULL, '$paymentDescription', '$incomeValue', NULL, '$paymentMethod', '$currentDateTime')";

    if (!mysqli_query($conn, $insertCashFlowSQL)) {
        $response = new \stdClass();
        $response->success = 'false';
        $response->error = 'database_error_cash_flow';
        $response->errorDetails = mysqli_error($conn);
        echo json_encode($response);
        exit();
    }
}

// Loop through each product and insert into tbl_item
for ($i = 0; $i < count($productIds); $i++) {
    $invoiceNo = $invoiceNos[$i];
    $productId = $productIds[$i];
    $detailId = $Ids[$i];
    $productName = $productNames[$i];
    $landingCost = str_replace(',', '', $landingCosts[$i]);
    $unitPrice = str_replace(',', '', $unitPrices[$i]);
    $qty = str_replace(',', '', $qtys[$i]);
    $totalPrice = str_replace(',', '', $totalPrices[$i]);
    $totalCost = str_replace(',', '', $totalCosts[$i]);
    $totalProfit = str_replace(',', '', $totalProfits[$i]);
    $unitDiscount = str_replace(',', '', $unitDiscounts[$i]);
    $totalDiscount = str_replace(',', '', $totalDiscounts[$i]);

    // Insert product data into tbl_item
    $insertItemSQL = "INSERT INTO tbl_item 
        (Invoice_Id, Product_Id, Product_Detail_Id, Product_Name, Landing_Cost, Unit_Price, Qty, Total_Price, Total_Cost, Total_Profit, Unit_Discount, Total_Discount) 
        VALUES 
        ('$invoiceNo', '$productId', '$detailId', '$productName', '$landingCost', '$unitPrice', '$qty', '$totalPrice', '$totalCost', '$totalProfit', '$unitDiscount', '$totalDiscount')";

    if (!mysqli_query($conn, $insertItemSQL)) {
        $response = new \stdClass();
        $response->success = 'false';
        $response->error = 'database_error_item';
        $response->errorDetails = mysqli_error($conn);
        echo json_encode($response);
        exit();
    }

    // Update the qty in tbl_product_details
    $updateQtySQL = "UPDATE tbl_product_details 
                    SET Qty = GREATEST(Qty - '$qty', 0) 
                    WHERE Id = '$detailId'";

    if (!mysqli_query($conn, $updateQtySQL)) {
        $response = new \stdClass();
        $response->success = 'false';
        $response->error = 'database_error_qty_update';
        $response->errorDetails = mysqli_error($conn);
        echo json_encode($response);
        exit();
    }
}

// Update the tbl_temp_invoice 'Value' column to increment the current value
$updateTempInvoiceSQL = "UPDATE tbl_temp_invoice SET Value = Value + 1 WHERE Id = 1";
if (!mysqli_query($conn, $updateTempInvoiceSQL)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'database_error_temp_invoice';
    echo json_encode($response);
    exit();
}

// If all goes well, return a success response
$response = new \stdClass();
$response->success = 'true';
$response->message = 'Invoice and payment saved successfully, and quantity updated';
echo json_encode($response);

?>
