<?php

require '../../API/Connection/BackEndPermission.php';

// Initialize the response array
$response = [
    "success" => false,
    "data" => []
];

if (isset($_POST['Product_Id'])) {
    $productId = $conn->real_escape_string($_POST['Product_Id']); // Secure input

    // Fetch product details with inventory quantity
    $sql = "
        SELECT 
            p.Product_Id, 
            p.Product_Name,
            b.Brand_Id,
            c.Category_Id,
            s.Supplier_Id,
            SUM(pd.Qty) AS Qty,
            pd.Landing_Cost,
            pd.Wholesale_Price,
            pd.Retail_Price,
            pd.Cost
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
            p.Product_Id = ?
        GROUP BY
            p.Product_Id, p.Product_Name, b.Brand_Name, c.Category_Name, s.Supplier_Name, pd.Landing_Cost, pd.Wholesale_Price, pd.Retail_Price, pd.Cost
        LIMIT 1
    ";

    // Use a prepared statement to execute the query securely
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $productId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response["success"] = true;
            $response["data"] = [
                "Product_Id" => $row["Product_Id"],
                "Product_Name" => $row["Product_Name"],
                "Brand_Id" => $row["Brand_Id"],
                "Category_Id" => $row["Category_Id"],
                "Supplier_Id" => $row["Supplier_Id"],
                "Qty" => $row["Qty"],
                "Landing_Cost" => $row["Landing_Cost"],
                "Wholesale_Price" => $row["Wholesale_Price"],
                "Retail_Price" => $row["Retail_Price"],
                "Cost" => $row["Cost"]
            ];
        } else {
            $response["message"] = "No product found with the given Product_Id.";
        }
    } else {
        $response["message"] = "Error executing the query.";
    }

    $stmt->close();
} else {
    $response["message"] = "Product_Id is required.";
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response, JSON_NUMERIC_CHECK);

// Close the database connection
mysqli_close($conn);

?>