<?php

require '../../API/Connection/BackEndPermission.php';

$Id = $_REQUEST["Id"];

// Fetch product details data
$sqlProductDetails = "
    SELECT 
        pd.Id,
        pd.Product_Id,
        p.Product_Name,
        b.Brand_Name,
        c.Category_Name,
        s.Supplier_Name,
        pd.Cost,
        pd.Landing_Cost,
        pd.Retail_Price,
        pd.Wholesale_Price,
        pd.Qty,
        pd.Received_Date,
        pd.Inventort_Updated
    FROM 
        tbl_product_details pd
    INNER JOIN 
        tbl_product p ON pd.Product_Id = p.Product_Id
    INNER JOIN 
        tbl_brand b ON p.Brand_Id = b.Brand_Id
    INNER JOIN 
        tbl_category c ON p.Category_Id = c.Category_Id
    INNER JOIN 
        tbl_suppliers s ON pd.Supplier_Id = s.Supplier_Id
    WHERE 
        pd.Id = '$Id'
";
$resultProductDetails = $conn->query($sqlProductDetails);

$productData = array();
$productQueued = array();

if ($resultProductDetails->num_rows > 0) {
    $rowProductData = $resultProductDetails->fetch_assoc();

    // Construct product data
    $productData = array(
        'Id' => $rowProductData['Id'],
        'Product_Id' => $rowProductData['Product_Id'],
        'Product_Name' => $rowProductData['Product_Name'],
        'Brand_Name' => $rowProductData['Brand_Name'],
        'Category_Name' => $rowProductData['Category_Name'],
        'Supplier_Name' => $rowProductData['Supplier_Name'],
        'Cost' => number_format($rowProductData['Cost'], 2),
        'Landing_Cost' => number_format($rowProductData['Landing_Cost'], 2),
        'Retail_Price' => number_format($rowProductData['Retail_Price'], 2),
        'Wholesale_Price' => number_format($rowProductData['Wholesale_Price'], 2),
        'Qty' => number_format($rowProductData['Qty']),
        'Received_Date' => $rowProductData['Received_Date'],
        'Inventort_Updated' => $rowProductData['Inventort_Updated']
    );

    // Fetch Product_Id from product details
    $Product_Id = $rowProductData['Product_Id'];

    // Fetch products queued where Inventort_Updated is true
    $sqlProductQueued = "
        SELECT 
            pd.Received_Date,
            pd.Qty,
            pd.Cost,
            pd.Landing_Cost,
            pd.Retail_Price,
            pd.Wholesale_Price
        FROM 
            tbl_product_details pd
        WHERE 
            pd.Product_Id = '$Product_Id' AND pd.Inventort_Updated = 'True'
        ORDER BY 
            pd.Received_Date DESC
    ";
    $resultProductQueued = $conn->query($sqlProductQueued);

    if ($resultProductQueued->num_rows > 0) {
        while ($rowProductQueued = $resultProductQueued->fetch_assoc()) {
            // Construct product queued data
            $productQueuedData = array(
                'Received_Date' => $rowProductQueued['Received_Date'],
                'Qty' => number_format($rowProductQueued['Qty']),
                'Cost' => number_format($rowProductQueued['Cost'], 2),
                'Landing_Cost' => number_format($rowProductQueued['Landing_Cost'], 2),
                'Retail_Price' => number_format($rowProductQueued['Retail_Price'], 2),
                'Wholesale_Price' => number_format($rowProductQueued['Wholesale_Price'], 2)
            );
            array_push($productQueued, $productQueuedData);
        }
    }
}

// Combine product data and queued products into a single response object
$response = array(
    'productData' => $productData,
    'productQueued' => $productQueued
);

// Encode response object as JSON and output it
echo json_encode($response);

mysqli_close($conn);

?>