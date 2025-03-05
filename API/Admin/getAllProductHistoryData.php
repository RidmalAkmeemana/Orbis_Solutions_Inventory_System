<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT 
        p.Product_Id, 
        p.Product_Name,
        c.Category_Name,
        s.Supplier_Name,
        pd.Id,
        pd.Qty,
        pd.Received_Date,
        pd.Inventort_Updated
    FROM 
        tbl_product p
    JOIN 
        tbl_product_details pd ON p.Product_Id = pd.Product_Id
    JOIN
        tbl_category c ON p.Category_Id = c.Category_Id
    JOIN
        tbl_suppliers s ON pd.Supplier_Id = s.Supplier_Id
    ORDER BY 
        pd.Received_Date DESC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Id" => $row["Id"],
            "Product_Id" => $row["Product_Id"],
            "Product_Name" => $row["Product_Name"],
            "Received_Date" => $row["Received_Date"],
            "Qty" => number_format($row["Qty"]),
            "Supplier_Name" => $row["Supplier_Name"],
            "Inventort_Updated" => $row["Inventort_Updated"]
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>