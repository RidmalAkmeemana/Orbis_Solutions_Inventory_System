<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT 
    q.Id,
    q.Quotation_Id,
    q.Customer_Id,
    c.Customer_Name, -- Fetch Customer Name
    c.Customer_Address,
    c.Customer_Contact,
    c.Customer_Email,
    q.User_Id,
    u.First_Name,  -- Fetch First Name of user
    u.Last_Name,   -- Fetch Last Name of user
    q.Sale_Type,
    q.Item_Count,
    FORMAT(q.Sub_Total, 2) AS Sub_Total,
    FORMAT(q.Discount_Total, 2) AS Discount_Total,
    FORMAT(q.Profit_Total, 2) AS Profit_Total,
    FORMAT(q.Grand_Total, 2) AS Grand_Total,
    q.Description,
    q.Quotation_Date
    FROM tbl_quotation q
    JOIN tbl_customers c ON q.Customer_Id = c.Customer_Id
    JOIN tbl_user u ON q.User_Id = u.Id 
    ORDER BY q.Quotation_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Id" => $row["Id"],
            "Quotation_Id" => $row["Quotation_Id"],
            "Customer_Name" => $row["Customer_Name"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"],
            "Sale_Type" => $row["Sale_Type"],
            "Item_Count" => $row["Item_Count"],
            'Grand_Total' => $row['Grand_Total'],
            'Quotation_Date' => $row['Quotation_Date']
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>