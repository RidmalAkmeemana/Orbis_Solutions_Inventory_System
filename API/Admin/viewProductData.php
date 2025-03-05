<?php

require '../../API/Connection/BackEndPermission.php';

$Product_Id = $_REQUEST['Product_Id'];

// Calculate the SUMQty separately
$sql_sum_qty = "
    SELECT SUM(pd.Qty) AS SUMQty
    FROM tbl_product_details pd
    WHERE pd.Product_Id = ? AND pd.Inventort_Updated = 'True'
";

$stmt_sum_qty = $conn->prepare($sql_sum_qty);
$stmt_sum_qty->bind_param("s", $Product_Id);
$stmt_sum_qty->execute();
$result_sum_qty = $stmt_sum_qty->get_result();
$sum_qty_row = $result_sum_qty->fetch_assoc();
$sum_qty = $sum_qty_row['SUMQty'];

// Fetch the product details where Qty > 0
$sql = "
    SELECT 
        p.Product_Id, 
        p.Product_Name,
        b.Brand_Name,
        c.Category_Name,
        s.Supplier_Name,
        pd.Cost, 
        pd.Landing_Cost, 
        pd.Retail_Price, 
        pd.Wholesale_Price, 
        pd.Qty,
        pd.Received_Date
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
    AND 
        pd.Qty > 0 AND pd.Inventort_Updated = 'True'
    ORDER BY 
        pd.Received_Date ASC
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $Product_Id);
$stmt->execute();
$result = $stmt->get_result();

$productData = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $productData = array(
        "Product_Id" => $row["Product_Id"],
        "Product_Name" => $row["Product_Name"],
        "Brand_Name" => $row["Brand_Name"],
        "Category_Name" => $row["Category_Name"],
        "Cost" => number_format($row["Cost"], 2),
        "Landing_Cost" => number_format($row["Landing_Cost"], 2),
        "Retail_Price" => number_format($row["Retail_Price"], 2),
        "Wholesale_Price" => number_format($row["Wholesale_Price"], 2),
        "Qty" => number_format($row["Qty"]),
        "SumQty" => number_format($sum_qty),
        "Supplier_Name" => $row["Supplier_Name"],
        "Received_Date" => $row["Received_Date"]
    );
} else {
    // In case no records with Qty > 0, fetch the latest record
    $sql_fallback = "
        SELECT 
            p.Product_Id,
            p.Product_Name,
            b.Brand_Name,
            c.Category_Name,
            s.Supplier_Name,
            pd.Cost, 
            pd.Landing_Cost, 
            pd.Retail_Price, 
            pd.Wholesale_Price, 
            pd.Qty,
            pd.Received_Date
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
            p.Product_Id = ? AND pd.Inventort_Updated = 'True'
        ORDER BY 
            pd.Received_Date DESC
        LIMIT 1
    ";

    $stmt_fallback = $conn->prepare($sql_fallback);
    $stmt_fallback->bind_param("s", $Product_Id);
    $stmt_fallback->execute();
    $result_fallback = $stmt_fallback->get_result();

    if ($result_fallback->num_rows > 0) {
        $row = $result_fallback->fetch_assoc();
        $productData = array(
            "Product_Id" => $row["Product_Id"],
            "Product_Name" => $row["Product_Name"],
            "Brand_Name" => $row["Brand_Name"],
            "Category_Name" => $row["Category_Name"],
            "Cost" => number_format($row["Cost"], 2),
            "Landing_Cost" => number_format($row["Landing_Cost"], 2),
            "Retail_Price" => number_format($row["Retail_Price"], 2),
            "Wholesale_Price" => number_format($row["Wholesale_Price"], 2),
            "Qty" => number_format($row["Qty"]),
            "SumQty" => number_format($sum_qty),
            "Supplier_Name" => $row["Supplier_Name"],
            "Received_Date" => $row["Received_Date"]
        );
    }
}

// Fetch customers who purchased the product
$sql_customers = "
    SELECT 
        DISTINCT i.Customer_Id, 
        c.Customer_Name, 
        itm.Qty AS Qty_Purchased
    FROM 
        tbl_invoice i
    JOIN 
        tbl_item itm ON i.Invoice_Id = itm.Invoice_Id
    JOIN
        tbl_customers c ON i.Customer_Id = c.Customer_Id
    WHERE 
        itm.Product_Id = ?
";

$stmt_customers = $conn->prepare($sql_customers);
$stmt_customers->bind_param("s", $Product_Id);
$stmt_customers->execute();
$result_customers = $stmt_customers->get_result();

$customers = array();
while ($row = $result_customers->fetch_assoc()) {
    array_push($customers, array(
        "Customer_Id" => $row["Customer_Id"],
        "Customer_Name" => $row["Customer_Name"],
        "Qty_Purchased" => number_format($row["Qty_Purchased"])
    ));
}

$response = array(
    "productData" => $productData,
    "customers" => $customers
);

echo json_encode($response);
mysqli_close($conn);

?>
