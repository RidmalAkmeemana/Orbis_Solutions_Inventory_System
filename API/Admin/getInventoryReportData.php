<?php
require '../../API/Connection/BackEndPermission.php';

// Get filter values
$productId = $_POST['Product_Id'] ?? 'ALL';
$brandId = $_POST['Brand_Id'] ?? 'ALL';
$categoryId = $_POST['Category_Id'] ?? 'ALL';
$supplierId = $_POST['Supplier_Id'] ?? 'ALL';
$qtyFrom = $_POST['QtyFrom'];
$qtyTo = $_POST['QtyTo'];

// Initialize SQL with required conditions
$sql = "SELECT 
        p.Product_Id, 
        p.Product_Name,
        b.Brand_Name,
        c.Category_Name,
        s.Supplier_Name,
        SUM(pd.Qty) AS Qty,
        SUM(pd.Landing_Cost * pd.Qty) AS Landing_Cost
    FROM 
        tbl_product p
    JOIN 
        tbl_product_details pd ON p.Product_Id = pd.Product_Id
    JOIN
        tbl_brand b ON p.Brand_Id = b.Brand_Id
    JOIN
        tbl_category c ON p.Category_Id = c.Category_Id
    JOIN
        tbl_suppliers s ON pd.Supplier_Id = s.Supplier_Id
    WHERE 
        pd.Inventort_Updated = 'True'";

// Apply filters dynamically SUM(pd.Landing_Cost) AS Landing_Cost
if ($productId !== 'ALL') {
    $sql .= " AND p.Product_Id = '$productId'";
}

if ($brandId !== 'ALL') {
    $sql .= " AND p.Brand_Id = '$brandId'";
}

if ($categoryId !== 'ALL') {
    $sql .= " AND p.Category_Id = '$categoryId'";
}

if ($supplierId !== 'ALL') {
    $sql .= " AND pd.Supplier_Id = '$supplierId'";
}

$sql .= "
    GROUP BY
        p.Product_Id, p.Product_Name, b.Brand_Name, c.Category_Name, s.Supplier_Name
";

// Apply the Qty filter using HAVING clause (after GROUP BY)
if ($qtyFrom != 0 || $qtyTo != 0) {
    if ($qtyTo == 0) {
        $sql .= " HAVING SUM(pd.Qty) >= $qtyFrom";
    } elseif ($qtyFrom == 0) {
        $sql .= " HAVING SUM(pd.Qty) <= $qtyTo";
    } else {
        $sql .= " HAVING SUM(pd.Qty) BETWEEN $qtyFrom AND $qtyTo";
    }
}

else{
    $sql .= " HAVING SUM(pd.Qty) BETWEEN $qtyFrom AND $qtyTo";
}

$sql .= "
    ORDER BY 
        p.Product_Id ASC
";

// Execute the query
$result = $conn->query($sql);

// Error handling: Check if the query execution was successful
if (!$result) {
    // Log MySQL error
    echo "SQL Error: " . $conn->error;
    exit;
}

$productData = array();
$totalQty = 0;
$totalLandingCost = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedQty = number_format($row["Qty"]);
        $formattedLandingCost = number_format($row["Landing_Cost"], 2);

        // Add individual product data to the productData array
        array_push($productData, array(
            "Product_Id" => $row["Product_Id"],
            "Product_Name" => $row["Product_Name"],
            "Brand_Name" => $row["Brand_Name"],
            "Category_Name" => $row["Category_Name"],
            "Qty" => $formattedQty,
            "Landing_Cost" => $formattedLandingCost,
            "Supplier_Name" => $row["Supplier_Name"]
        ));

        // Sum the total quantity and landing cost
        $totalQty += $row["Qty"];
        $totalLandingCost += $row["Landing_Cost"];
    }

    // Prepare the response structure with total values and product data
    $response = array(
        "success" => "true",
        "pageData" => array(
            "Total_Qty" => number_format($totalQty),
            "Total_Cost" => number_format($totalLandingCost, 2)
        ),
        "productData" => $productData
    );
}
else {
    // If no data is found, return an error response
    $response = array(
        "success" => "false",
        "message" => "No data found for the selected filters"
    );
}



// Return the JSON response
echo json_encode($response);

mysqli_close($conn);
?>
