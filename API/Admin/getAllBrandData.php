<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT 
        tbl_brand.*, 
        COUNT(DISTINCT CASE WHEN tbl_product_details.Inventort_Updated = 'True' THEN tbl_product.Product_Id END) AS product_count 
    FROM 
        tbl_brand 
    LEFT JOIN 
        tbl_product ON tbl_brand.Brand_Id = tbl_product.Brand_Id 
    LEFT JOIN 
        tbl_product_details ON tbl_product.Product_Id = tbl_product_details.Product_Id 
    GROUP BY 
        tbl_brand.Id, tbl_brand.Brand_Id, tbl_brand.Brand_Name 
    ORDER BY 
        tbl_brand.Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Id" => $row["Id"],
            "Brand_Id" => $row["Brand_Id"],
            "Brand_Name" => $row["Brand_Name"],
            "product_count" => number_format($row["product_count"])
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>