<?php

require '../../API/Connection/BackEndPermission.php';

$Supplier_Id = $_REQUEST["Supplier_Id"];

// Fetch supplier data
$sqlSupplier = "SELECT * FROM tbl_suppliers WHERE `Supplier_Id` = '$Supplier_Id'";
$resultSupplier = $conn->query($sqlSupplier);

$supplierData = array();

if ($resultSupplier->num_rows > 0) {
    $rowSupplier = $resultSupplier->fetch_assoc();

    // Construct Supplier data
    $supplierData = array(
        'Supplier_Id' => $rowSupplier["Supplier_Id"],
        'Supplier_Name' => $rowSupplier["Supplier_Name"],
        'Supplier_Address' => $rowSupplier["Supplier_Address"],
        'Supplier_Contact' => $rowSupplier["Supplier_Contact"],
        'Supplier_Email' => $rowSupplier["Supplier_Email"]
    );
}

// Fetch products received from the Supplier where Inventort_Updated is false
$sqlProducts = "SELECT DISTINCT pd.Id, pd.Product_Id, pd.Qty, pd.Cost, pd.Received_Date, pd.Inventort_Updated, p.Product_Name
                FROM tbl_suppliers s
                INNER JOIN tbl_product_details pd ON pd.Supplier_Id = s.Supplier_Id
                INNER JOIN tbl_product p ON pd.Product_Id = p.Product_Id
                WHERE s.Supplier_Id = '$Supplier_Id' AND pd.Inventort_Updated = 'False'";
$resultProducts = $conn->query($sqlProducts);

$products = array();

if ($resultProducts->num_rows > 0) {
    while ($rowProduct = $resultProducts->fetch_assoc()) {
        // Construct products data
        $productsData = array(
            'Id' => $rowProduct['Id'],
            'Product_Id' => $rowProduct['Product_Id'],
            'Product_Name' => $rowProduct['Product_Name'],
            'Qty' => number_format($rowProduct['Qty']),
            'Cost' => number_format($rowProduct['Cost'], 2),
            'Received_Date' => $rowProduct['Received_Date'],
            'Inventort_Updated' => $rowProduct['Inventort_Updated']
        );
        array_push($products, $productsData);
    }
}

// Combine Supplier and product data into a single response object
$response = array(
    'supplierData' => $supplierData,
    'products' => $products
);

// Encode response object as JSON and output it
echo json_encode($response);

?>