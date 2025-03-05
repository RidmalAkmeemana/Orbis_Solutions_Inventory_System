<?php

require '../../API/Connection/BackEndPermission.php';

$Customer_Id = $_REQUEST["Customer_Id"];

// Fetch customer data
$sqlCustomer = "SELECT * FROM tbl_customers WHERE `Customer_Id` = '$Customer_Id'";
$resultCustomer = $conn->query($sqlCustomer);

$customerData = array();

if ($resultCustomer->num_rows > 0) {
    $rowCustomer = $resultCustomer->fetch_assoc();

    // Construct Customer data
    $customerData = array(
        'Customer_Id' => $rowCustomer["Customer_Id"],
        'Customer_Name' => $rowCustomer["Customer_Name"],
        'Customer_Address' => $rowCustomer["Customer_Address"],
        'Customer_Contact' => $rowCustomer["Customer_Contact"],
        'Customer_Email' => $rowCustomer["Customer_Email"]
    );
}

// Fetch invoices for the Customer
$sqlInvoices = "SELECT DISTINCT i.Id, i.Invoice_Id, i.Customer_Id, i.Sale_Type, i.Item_Count, i.Status, i.Grand_Total, i.Paid_Amount, i.Balance_Total, i.Due_Total, i.Payment_Type, i.Invoice_Date, i.Payment_Date 
                FROM tbl_invoice i
                WHERE i.Customer_Id = '$Customer_Id'";
                
$resultInvoices = $conn->query($sqlInvoices);

// Check if the query executed successfully
if (!$resultInvoices) {
    // If the query fails, output the error message
    die("Error in SQL query: " . $conn->error);
}

$invoices = array();

if ($resultInvoices->num_rows > 0) {
    while ($rowInvoice = $resultInvoices->fetch_assoc()) {
        // Construct invoices data
        $invoicesData = array(
            'Id' => $rowInvoice['Id'],
            'Invoice_Id' => $rowInvoice['Invoice_Id'],
            'Sale_Type' => $rowInvoice['Sale_Type'],
            'Item_Count' => $rowInvoice['Item_Count'],
            'Status' => $rowInvoice['Status'],
            'Grand_Total' => number_format($rowInvoice['Grand_Total'], 2),
            'Paid_Amount' => number_format($rowInvoice['Paid_Amount'], 2),
            'Balance_Total' => number_format($rowInvoice['Balance_Total'], 2),
            'Due_Total' => number_format($rowInvoice['Due_Total'], 2),
            'Payment_Type' => $rowInvoice['Payment_Type'],
            'Invoice_Date' => $rowInvoice['Invoice_Date'],
            'Payment_Date' => $rowInvoice['Payment_Date']
        );
        array_push($invoices, $invoicesData);
    }
}

// Combine Customer and invoice data into a single response object
$response = array(
    'customerData' => $customerData,
    'invoices' => $invoices
);

// Encode response object as JSON and output it
echo json_encode($response);

?>
