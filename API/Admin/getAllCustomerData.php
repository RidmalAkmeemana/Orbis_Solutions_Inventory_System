<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT tbl_customers.*, 
           COUNT(tbl_invoice.Invoice_Id) AS invoice_count 
    FROM tbl_customers
    LEFT JOIN tbl_invoice ON tbl_customers.Customer_Id = tbl_invoice.Customer_Id 
    GROUP BY tbl_customers.Customer_Id, tbl_customers.Customer_Name, tbl_customers.Customer_Address, tbl_customers.Customer_Contact, tbl_customers.Customer_Email 
    ORDER BY tbl_customers.Customer_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customer_contact = $row["Customer_Contact"];
        
        // Ensure Customer_Contact is treated as a string
        if (is_numeric($customer_contact)) {
            $customer_contact = sprintf('%010d', $customer_contact);
        }
        
        array_push($dataset, array(
            "Customer_Id" => $row["Customer_Id"],
            "Customer_Name" => $row["Customer_Name"],
            "Customer_Address" => $row["Customer_Address"],
            "Customer_Contact" => $customer_contact, // Ensure Customer_Contact is formatted correctly
            "Customer_Email" => $row["Customer_Email"],
            "invoice_count" => number_format($row["invoice_count"])
        ));
    }
}

echo json_encode($dataset); // No JSON_NUMERIC_CHECK to avoid conversion of numeric strings
mysqli_close($conn);

?>