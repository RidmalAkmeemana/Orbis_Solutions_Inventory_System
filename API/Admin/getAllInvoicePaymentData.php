<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch invoice payment data
$sql = "SELECT 
    i.Id,
    i.Invoice_Id,
    p.Payment_Id,
    FORMAT(p.Paid_Amount, 2) AS Paid_Amount,
    FORMAT(p.Balance_Total, 2) AS Balance_Total,
    FORMAT(p.Due_Total, 2) AS Due_Total,
    p.Payment_Type,
    p.Payment_Date,
    p.Description,
    p.Updated_By,
    u.First_Name,
    u.Last_Name
    FROM tbl_invoice i
    JOIN tbl_payments p ON i.Invoice_Id = p.Invoice_Id
    JOIN tbl_user u ON p.Updated_By = u.Id
    ORDER BY i.Invoice_Id, p.Payment_Id ASC";

// Execute the query and handle errors
$result = $conn->query($sql);

if (!$result) {
    // Log the error for debugging
    error_log("SQL Error: " . $conn->error);
    echo json_encode(["error" => "Failed to fetch data. Please check the logs."]);
    mysqli_close($conn);
    exit;
}

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Id" => $row["Id"],
            "Invoice_Id" => $row["Invoice_Id"],
            "Payment_Id" => $row["Payment_Id"],
            "Paid_Amount" => $row["Paid_Amount"],
            "Balance_Total" => $row["Balance_Total"],
            "Due_Total" => $row["Due_Total"],
            "Payment_Type" => $row["Payment_Type"],
            "Payment_Date" => $row["Payment_Date"],
            "Description" => $row["Description"],
            "Updated_By" => $row["Updated_By"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"]
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>
