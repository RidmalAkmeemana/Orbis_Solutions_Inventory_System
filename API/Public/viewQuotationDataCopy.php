<?php

header("Content-Type: application/json; charset=UTF-8");
require '../../API/Connection/config.php';

// Fetch the Quotation_Id from the GET parameters
$quotationId = isset($_GET['Quotation_Id']) ? mysqli_real_escape_string($conn, $_GET['Quotation_Id']) : '';

if (empty($quotationId)) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'Quotation_Id is required';
    echo json_encode($response);
    exit();
}

// Fetch the QuotationData from tbl_quotation and join with tbl_customers and tbl_user
$invoiceSQL = "SELECT 
    q.Quotation_Id AS Quotation_No,
    q.Customer_Id,
    c.Customer_Name,
    c.Customer_Address,
    c.Customer_Contact,
    c.Customer_Email,
    q.User_Id,
    u.First_Name,
    u.Last_Name,
    q.Sale_Type,
    q.Item_Count,
    FORMAT(q.Sub_Total, 2) AS Sub_Total,
    FORMAT(q.Discount_Total, 2) AS Discount_Total,
    q.ServiceCharge_IsPercentage,
    FORMAT(q.ServiceCharge, 2) AS ServiceCharge,
    q.Tax_IsPercentage,
    FORMAT(q.Tax, 2) AS Tax,
    q.Vat_IsPercentage,
    FORMAT(q.Vat, 2) AS Vat,
    q.Delivery_IsPercentage,
    FORMAT(q.Delivery, 2) AS Delivery,
    FORMAT(q.Profit_Total, 2) AS Profit_Total,
    FORMAT(q.Grand_Total, 2) AS Grand_Total,
    q.Description,
    q.Quotation_Date
FROM tbl_quotation q
JOIN tbl_customers c ON q.Customer_Id = c.Customer_Id
JOIN tbl_user u ON q.User_Id = u.Id
WHERE q.Quotation_Id = '$quotationId'";

// Execute the query
$invoiceResult = mysqli_query($conn, $invoiceSQL);

if (!$invoiceResult) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'SQL Error: ' . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

if (mysqli_num_rows($invoiceResult) == 0) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'Invoice not found';
    echo json_encode($response);
    exit();
}

$QuotationData = mysqli_fetch_assoc($invoiceResult);

// Fetch the products associated with the Quotation_Id
$productsSQL = "SELECT 
    Quotation_Id,
    Product_Id,
    Product_Name,
    FORMAT(Landing_Cost, 2) AS Landing_Cost,
    FORMAT(Unit_Price, 2) AS Unit_Price,
    FORMAT(Unit_Discount, 2) AS Unit_Discount,
    Qty,
    FORMAT(Total_Discount, 2) AS Product_Total_Discount,
    FORMAT(Total_Price, 2) AS Product_Total_Price,
    FORMAT(Total_Cost, 2) AS Total_Product_Cost,
    FORMAT(Total_Profit, 2) AS Product_Total_Profit
FROM tbl_quote_item 
WHERE Quotation_Id = '$quotationId'";

$productsResult = mysqli_query($conn, $productsSQL);

if (!$productsResult) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'SQL Error: ' . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

$products = [];
while ($row = mysqli_fetch_assoc($productsResult)) {
    $products[] = $row;
}

// Build the response object
$response = new \stdClass();
$response->success = true;
$response->QuotationData = $QuotationData;
$response->products = $products;

// Return the JSON response
echo json_encode($response);

?>
