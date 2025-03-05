<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch supplier details
$sql = "SELECT tbl_suppliers.*, 
           COUNT(CASE WHEN tbl_product_details.Inventort_Updated = 'False' THEN 1 END) AS product_count 
    FROM tbl_suppliers
    LEFT JOIN tbl_product_details ON tbl_suppliers.Supplier_Id = tbl_product_details.Supplier_Id 
    GROUP BY tbl_suppliers.Supplier_Id, tbl_suppliers.Supplier_Name, tbl_suppliers.Supplier_Address, tbl_suppliers.Supplier_Contact, tbl_suppliers.Supplier_Email 
    ORDER BY tbl_suppliers.Supplier_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $supplier_contact = $row["Supplier_Contact"];
        
        // Ensure Supplier_Contact is treated as a string
        if (is_numeric($supplier_contact)) {
            $supplier_contact = sprintf('%010d', $supplier_contact);
        }
        
        array_push($dataset, array(
            "Supplier_Id" => $row["Supplier_Id"],
            "Supplier_Name" => $row["Supplier_Name"],
            "Supplier_Address" => $row["Supplier_Address"],
            "Supplier_Contact" => $supplier_contact, // Ensure Supplier_Contact is formatted correctly
            "Supplier_Email" => $row["Supplier_Email"],
            "product_count" => number_format($row["product_count"])
        ));
    }
}

echo json_encode($dataset); // No JSON_NUMERIC_CHECK to avoid conversion of numeric strings
mysqli_close($conn);

?>
