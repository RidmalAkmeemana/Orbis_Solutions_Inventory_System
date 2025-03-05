<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT 
        tbl_category.*, 
        COUNT(DISTINCT CASE WHEN tbl_product_details.Inventort_Updated = 'True' THEN tbl_product.Product_Id END) AS product_count 
    FROM 
        tbl_category 
    LEFT JOIN 
        tbl_product ON tbl_category.Category_Id = tbl_product.Category_Id 
    LEFT JOIN 
        tbl_product_details ON tbl_product.Product_Id = tbl_product_details.Product_Id 
    GROUP BY 
        tbl_category.Id, tbl_category.Category_Id, tbl_category.Category_Name 
    ORDER BY 
        tbl_category.Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Id" => $row["Id"],
            "Category_Id" => $row["Category_Id"],
            "Category_Name" => $row["Category_Name"],
            "product_count" => number_format($row["product_count"])
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>