<?php

require '../../API/Connection/BackEndPermission.php';

// Fetch the Invoice_Id from the GET parameters
$invoiceId = isset($_GET['Invoice_Id']) ? $_GET['Invoice_Id'] : '';

if (empty($invoiceId)) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'Invoice_Id is required';
    echo json_encode($response);
    exit();
}

// Fetch the InvoiceData from tbl_invoice and join with tbl_customers and tbl_user
$invoiceSQL = "SELECT 
    i.Invoice_Id AS Invoice_No,
    i.Customer_Id,
    c.Customer_Name, -- Fetch Customer Name
    c.Customer_Address,
    c.Customer_Contact,
    c.Customer_Email,
    i.User_Id,
    u.First_Name,  -- Fetch First Name of user
    u.Last_Name,   -- Fetch Last Name of user
    i.Sale_Type,
    i.Item_Count,
    i.Status,
    FORMAT(i.Sub_Total, 2) AS Sub_Total,
    FORMAT(i.Discount_Total, 2) AS Discount_Total,
    i.ServiceCharge_IsPercentage,
    FORMAT(i.ServiceCharge, 2) AS ServiceCharge,
    i.Tax_IsPercentage,
    FORMAT(i.Tax, 2) AS Tax,
    i.Vat_IsPercentage,
    FORMAT(i.Vat, 2) AS Vat,
    i.Delivery_IsPercentage,
    FORMAT(i.Delivery, 2) AS Delivery,
    FORMAT(i.Profit_Total, 2) AS Profit_Total,
    FORMAT(i.Grand_Total, 2) AS Grand_Total,
    FORMAT(i.Paid_Amount, 2) AS Paid_Amount,
    FORMAT(i.Balance_Total, 2) AS Balance_Total,
    FORMAT(i.Due_Total, 2) AS Due_Total,
    i.Payment_Type,
    i.Description,
    i.Invoice_Date,
    i.Payment_Date
    FROM tbl_invoice i
    JOIN tbl_customers c ON i.Customer_Id = c.Customer_Id
    JOIN tbl_user u ON i.User_Id = u.Id
    WHERE i.Invoice_Id = '$invoiceId'";

// Execute the query
$invoiceResult = mysqli_query($conn, $invoiceSQL);

// Check for query execution errors
if (!$invoiceResult) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'SQL Error: ' . mysqli_error($conn); // Capture SQL error
    echo json_encode($response);
    exit();
}

// Check if the invoice exists
if (mysqli_num_rows($invoiceResult) == 0) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'Invoice not found';
    echo json_encode($response);
    exit();
}

// Fetch the invoice data
$invoiceData = mysqli_fetch_assoc($invoiceResult);

// Fetch the products associated with the Invoice_Id from tbl_item
$productsSQL = "SELECT 
    Invoice_Id,
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
    FROM tbl_item WHERE Invoice_Id = '$invoiceId'";

$productsResult = mysqli_query($conn, $productsSQL);

// Check for product query execution errors
if (!$productsResult) {
    $response = new \stdClass();
    $response->success = 'false';
    $response->error = 'SQL Error: ' . mysqli_error($conn); // Capture SQL error
    echo json_encode($response);
    exit();
}

$products = [];
while ($row = mysqli_fetch_assoc($productsResult)) {
    $products[] = $row;
}

// Build the response object
$response = new \stdClass();
$response->InvoiceData = $invoiceData;
$response->products = $products;

// Return the JSON response
echo json_encode($response);

?>
