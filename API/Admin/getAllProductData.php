<?php

require '../../API/Connection/BackEndPermission.php';

// Fetch products with Qty > 0 and Inventort_Updated = 'True'
$sql = "SELECT 
        p.Product_Id, 
        p.Product_Name,
        b.Brand_Name,
        c.Category_Name,
        s.Supplier_Name,
        SUM(pd.Qty) AS Qty
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
        pd.Inventort_Updated = 'True'
    GROUP BY
        p.Product_Id, p.Product_Name, b.Brand_Name, c.Category_Name, s.Supplier_Name
    ORDER BY 
        p.Product_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Product_Id" => $row["Product_Id"],
            "Product_Name" => $row["Product_Name"],
            "Brand_Name" => $row["Brand_Name"],
            "Category_Name" => $row["Category_Name"],
            "Qty" => number_format($row["Qty"]),
            "Supplier_Name" => $row["Supplier_Name"]
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>