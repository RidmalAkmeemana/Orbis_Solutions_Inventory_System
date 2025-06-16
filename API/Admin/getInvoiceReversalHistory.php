<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch invoice payment data
$sql = "SELECT 
    p.Id,
    p.Invoice_Id,
    p.Payment_Id,
    FORMAT(p.Grand_Total, 2) AS Grand_Total,
    FORMAT(p.Reverse_Amount, 2) AS Reverse_Amount,
    p.Payment_Date,
    p.Reverse_Reason,
    p.Reverse_Date,
    p.Reversed_By,
    u.First_Name,
    u.Last_Name
    FROM tbl_payments_history p
    JOIN tbl_user u ON p.Reversed_By = u.Id
    ORDER BY p.Invoice_Id, p.Reverse_Date DESC";

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
            "Grand_Total" => $row["Grand_Total"],
            "Reverse_Amount" => $row["Reverse_Amount"],
            "Payment_Date" => $row["Payment_Date"],
            "Reverse_Reason" => $row["Reverse_Reason"],
            "Reverse_Date" => $row["Reverse_Date"],
            "Reversed_By" => $row["Reversed_By"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"]
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>
