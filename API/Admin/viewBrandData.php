<?php

require '../../API/Connection/BackEndPermission.php';

$Brand_Id = $_REQUEST["Brand_Id"];

// Fetch brand data
$sqlBrand = "SELECT * FROM tbl_brand WHERE `Brand_Id` = '$Brand_Id'";
$resultBrand = $conn->query($sqlBrand);

$brandData = array();

if ($resultBrand->num_rows > 0) {
    $rowBrand= $resultBrand->fetch_assoc();

    // Construct brand data
    $brandData = array(
        'Id' => $rowBrand["Id"],
        'Brand_Id' => $rowBrand["Brand_Id"],
        'Brand_Name' => $rowBrand["Brand_Name"]
    );
}

// Fetch products under the brand
$sqlEnrollment = "
    SELECT p.Product_Id, p.Product_Name, SUM(pd.Qty) as Qty
    FROM tbl_brand b 
    INNER JOIN tbl_product p ON b.Brand_Id = p.Brand_Id 
    INNER JOIN tbl_product_details pd ON p.Product_Id = pd.Product_Id
    WHERE b.Brand_Id = '$Brand_Id' AND pd.Inventort_Updated = 'True'
    GROUP BY p.Product_Id, p.Product_Name
";
$resultEnrollment = $conn->query($sqlEnrollment);

$product = array();

if ($resultEnrollment->num_rows > 0) {
    while ($rowEnrollment = $resultEnrollment->fetch_assoc()) {
        // Construct product data
        $productData = array(
            'Product_Id' => $rowEnrollment['Product_Id'],
            'Product_Name' => $rowEnrollment['Product_Name'],
            'Qty' => number_format($rowEnrollment['Qty'])
        );
        array_push($product, $productData);
    }
}

// Combine brand and product data into a single response object
$response = array(
    'brandData' => $brandData,
    'product' => $product
);

// Encode response object as JSON and output it
echo json_encode($response);
?>