<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch invoice payment data
$sql = "SELECT 
    e.Id,
    e.Expense_Id,
    ep.Payment_Id,
    FORMAT(ep.Paid_Amount, 2) AS Paid_Amount,
    FORMAT(ep.Due_Amount, 2) AS Due_Amount,
    ep.Payment_Type,
    ep.Payment_Date,
    ep.Expense_Description,
    ep.Updated_By,
    u.First_Name,
    u.Last_Name
    FROM tbl_expenses e
    JOIN tbl_expense_payments ep ON e.Expense_Id = ep.Expense_Id
    JOIN tbl_user u ON ep.Updated_By = u.Id
    ORDER BY e.Expense_Id, ep.Payment_Id ASC";

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
            "Paid_Amount" => $row["Paid_Amount"],
            "Due_Amount" => $row["Due_Amount"],
            "Payment_Type" => $row["Payment_Type"],
            "Payment_Date" => $row["Payment_Date"],
            "Expense_Description" => $row["Expense_Description"],
            "Updated_By" => $row["Updated_By"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"]
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>
