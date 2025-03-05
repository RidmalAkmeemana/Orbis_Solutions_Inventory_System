<?php

require '../../API/Connection/BackEndPermission.php';

$Category_Id = $_REQUEST["Category_Id"];

// Fetch category data
$sqlCategory = "SELECT * FROM tbl_category WHERE `Category_Id` = '$Category_Id'";
$resultCategory = $conn->query($sqlCategory);

$categoryData = array();

if ($resultCategory->num_rows > 0) {
    $rowCategory = $resultCategory->fetch_assoc();

    // Construct category data
    $categoryData = array(
        'Id' => $rowCategory["Id"],
        'Category_Id' => $rowCategory["Category_Id"],
        'Category_Name' => $rowCategory["Category_Name"]
    );
}

// Fetch products under the category
$sqlEnrollment = "
    SELECT p.Product_Id, p.Product_Name, SUM(pd.Qty) as Qty
    FROM tbl_category c 
    INNER JOIN tbl_product p ON c.Category_Id = p.Category_Id 
    INNER JOIN tbl_product_details pd ON p.Product_Id = pd.Product_Id
    WHERE c.Category_Id = '$Category_Id' AND pd.Inventort_Updated = 'True'
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

// Combine category and product data into a single response object
$response = array(
    'categoryData' => $categoryData,
    'product' => $product
);

// Encode response object as JSON and output it
echo json_encode($response);
?>