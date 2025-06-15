<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch invoice payment data
$sql = "SELECT 
    e.Id,
    e.Expense_Id,
    e.Payment_Id,
    FORMAT(e.Expense_Amount, 2) AS Expense_Amount,
    FORMAT(e.Reverse_Amount, 2) AS Reverse_Amount,
    e.Payment_Date,
    e.Reverse_Reason,
    e.Reverse_Date,
    e.Reversed_By,
    u.First_Name,
    u.Last_Name
    FROM tbl_expense_payments_history e
    JOIN tbl_user u ON e.Reversed_By = u.Id
    ORDER BY e.Expense_Id, e.Reverse_Date DESC";

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
            "Expense_Id" => $row["Expense_Id"],
            "Payment_Id" => $row["Payment_Id"],
            "Expense_Amount" => $row["Expense_Amount"],
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
