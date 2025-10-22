<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT 
    i.Id,
    i.Invoice_Id,
    i.Customer_Id,
    c.Customer_Name, -- Fetch Customer Name
    c.Customer_Address,
    c.Customer_Contact,
    c.Customer_Email,
    i.User_Id,
    u.First_Name,  -- Fetch First Name of user
    u.Last_Name,   -- Fetch Last Name of user
    i.Sale_Type,
    i.Item_Count,
    i.Status,
    FORMAT(i.Sub_Total, 2) AS Sub_Total,
    FORMAT(i.Discount_Total, 2) AS Discount_Total,
    FORMAT(i.Profit_Total, 2) AS Profit_Total,
    FORMAT(i.Grand_Total, 2) AS Grand_Total,
    FORMAT(i.Paid_Amount, 2) AS Paid_Amount,
    FORMAT(i.Balance_Total, 2) AS Balance_Total,
    FORMAT(i.Due_Total, 2) AS Due_Total,
    i.Payment_Type,
    i.Description,
    i.Invoice_Date,
    i.Payment_Date
    FROM tbl_invoice i
    JOIN tbl_customers c ON i.Customer_Id = c.Customer_Id
    JOIN tbl_user u ON i.User_Id = u.Id 
    ORDER BY i.Invoice_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Id" => $row["Id"],
            "Invoice_Id" => $row["Invoice_Id"],
            "Customer_Name" => $row["Customer_Name"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"],
            "Sale_Type" => $row["Sale_Type"],
            "Item_Count" => $row["Item_Count"],
            'Status' => $row['Status'],
            'Grand_Total' => $row['Grand_Total'],
            'Paid_Amount' => $row['Paid_Amount'],
            'Balance_Total' => $row['Balance_Total'],
            'Due_Total' => $row['Due_Total'],
            'Payment_Type' => $row['Payment_Type'],
            'Invoice_Date' => $row['Invoice_Date'],
            'Payment_Date' => $row['Payment_Date']
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>