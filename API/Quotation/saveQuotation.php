<?php

require '../../API/Connection/BackEndPermission.php';

// Fetch product data from POST
$quotationNos = $_POST['Quotation_No'];
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

// Quotation data
$quotationId = $_POST['Quotation_Id'];
$customerId = $_POST['Customer_Id'];
$userId = $_POST['User_Id'];
$itemCount = $_POST['Item_Count'];
$subTotal = str_replace(',', '', $_POST['Sub_Total']);
$discountTotal = str_replace(',', '', $_POST['Discount_Total']);
$serviceChargeType = $_POST['Service_Charge_Type'];
$serviceCharge = str_replace(',', '', $_POST['Service_Charge']);
$taxChargeType = $_POST['Tax_Charge_Type'];
$taxCharge = str_replace(',', '', $_POST['Tax_Charge']);
$vatChargeType = $_POST['Vat_Charge_Type'];
$vatCharge = str_replace(',', '', $_POST['Vat_Charge']);
$deliveryChargeType = $_POST['Delivery_Charge_Type'];
$deliveryCharge = str_replace(',', '', $_POST['Delivery_Charge']);
$profitTotal = str_replace(',', '', $_POST['Profit_Total']);
$grandTotal = str_replace(',', '', $_POST['Grand_Total']);
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

// Check if any required field is empty
if (empty($quotationId) || empty($customerId) || empty($userId) || empty($grandTotal)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'empty';
    echo json_encode($response);
    exit();
}

// Check if the Quotation_No already exists in the database
$checkQuotationSQL = "SELECT COUNT(*) AS count FROM tbl_invoice WHERE Invoice_Id='$quotationId'";
$checkQuotationResult = mysqli_query($conn, $checkQuotationSQL);
$quotationRow = mysqli_fetch_assoc($checkQuotationResult);

if ($quotationRow['count'] > 0) {
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
    $key = $quotationNos[$i] . '-' . $productIds[$i];
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

// Insert invoice data into tbl_quotation
$insertInvoiceSQL = "INSERT INTO tbl_quotation 
    (Quotation_Id, Customer_Id, User_Id, Sale_Type, Item_Count, Sub_Total, Discount_Total, ServiceCharge_IsPercentage, ServiceCharge, Tax_IsPercentage, Tax, Vat_IsPercentage, Vat, Delivery_IsPercentage, Delivery, Profit_Total, Grand_Total, Description, Quotation_Date)
    VALUES 
    ('$quotationId', '$customerId', '$userId', '$saleType', '$itemCount', '$subTotal', '$discountTotal', '$serviceChargeType', '$serviceCharge', '$taxChargeType', '$taxCharge', '$vatChargeType', '$vatCharge', '$deliveryChargeType', '$deliveryCharge', '$profitTotal', '$grandTotal', $description, '$currentDateTime')";

if (!mysqli_query($conn, $insertInvoiceSQL)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'database_error_invoice';
    $response->errorDetails = mysqli_error($conn);
    echo json_encode($response);
    exit();
}

// Loop through each product and insert into tbl_item
for ($i = 0; $i < count($productIds); $i++) {
    $quotationNo = $quotationNos[$i];
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
    $insertItemSQL = "INSERT INTO tbl_quote_item 
        (Quotation_Id, Product_Id, Product_Detail_Id, Product_Name, Landing_Cost, Unit_Price, Qty, Total_Price, Total_Cost, Total_Profit, Unit_Discount, Total_Discount) 
        VALUES 
        ('$quotationNo', '$productId', '$detailId', '$productName', '$landingCost', '$unitPrice', '$qty', '$totalPrice', '$totalCost', '$totalProfit', '$unitDiscount', '$totalDiscount')";

    if (!mysqli_query($conn, $insertItemSQL)) {
        $response = new \stdClass();
        $response->success = 'false';
        $response->error = 'database_error_item_quote';
        $response->errorDetails = mysqli_error($conn);
        echo json_encode($response);
        exit();
    }
}

// Update the tbl_temp_quotation 'Value' column to increment the current value
$updateTempQuotationSQL = "UPDATE tbl_temp_quotation SET Value = Value + 1 WHERE Id = 1";
if (!mysqli_query($conn, $updateTempQuotationSQL)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'database_error_quotation';
    echo json_encode($response);
    exit();
}

// If all goes well, return a success response
$response = new \stdClass();
$response->success = 'true';
$response->message = 'Quotation saved successfully, and quantity updated';
echo json_encode($response);

?>
