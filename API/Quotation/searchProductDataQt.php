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
$sum_qty = $sum_qty_row['SUMQty'] ?? 0; // fallback to 0 if null

// Fetch the product details where Qty > 0
$sql = "
    SELECT 
        p.Product_Id, 
        p.Product_Name,
        c.Category_Name,
        s.Supplier_Id,
        s.Supplier_Name,
        pd.Id,
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

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dataset[] = array(
            "Id" => $row["Id"],
            "Product_Id" => $row["Product_Id"],
            "Product_Name" => $row["Product_Name"],
            "Category_Name" => $row["Category_Name"],
            "Cost" => number_format($row["Cost"], 2),
            "Landing_Cost" => number_format($row["Landing_Cost"], 2),
            "Retail_Price" => number_format($row["Retail_Price"], 2),
            "Wholesale_Price" => number_format($row["Wholesale_Price"], 2),
            "Qty" => $row["Qty"],
            "SumQty" => $sum_qty,
            "Supplier_Id" => $row["Supplier_Id"],
            "Supplier_Name" => $row["Supplier_Name"],
            "Received_Date" => $row["Received_Date"]
        );
    }
} else {
    // No records with Qty > 0, fallback to latest record (even if Qty = 0)
    $sql_fallback = "
        SELECT 
            p.Product_Id,
            p.Product_Name,
            c.Category_Name,
            s.Supplier_Id,
            s.Supplier_Name,
            pd.Id,
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
        while ($row = $result_fallback->fetch_assoc()) {
            $dataset[] = array(
                "Id" => $row["Id"],
                "Product_Id" => $row["Product_Id"],
                "Product_Name" => $row["Product_Name"],
                "Category_Name" => $row["Category_Name"],
                "Cost" => number_format($row["Cost"], 2),
                "Landing_Cost" => number_format($row["Landing_Cost"], 2),
                "Retail_Price" => number_format($row["Retail_Price"], 2),
                "Wholesale_Price" => number_format($row["Wholesale_Price"], 2),
                "Qty" => number_format($row["Qty"]),
                "SumQty" => number_format($sum_qty),
                "Supplier_Id" => $row["Supplier_Id"],
                "Supplier_Name" => $row["Supplier_Name"],
                "Received_Date" => $row["Received_Date"]
            );
        }
    }
}

// Return JSON response
echo json_encode($dataset);

// Close connection
mysqli_close($conn);

?>