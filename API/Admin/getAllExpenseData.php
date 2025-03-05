<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch expenses
$sql = "SELECT 
        e.Expense_Id,
        e.Expense_Title, 
        et.Expense_Type,
        e.Expense_Description,
        e.Status,
        e.Expense_Amount,
        e.Paid_Amount,
        e.Payment_Type,
        e.Due_Amount,
        e.Expence_Date,
        e.Payment_Date, 
        et.Expense_Category_Id, 
        ec.Expense_Category_Name,
        e.User_Id,
        u.First_Name,
        u.Last_Name
    FROM 
        tbl_expenses e
    LEFT JOIN 
        tbl_expenses_types et 
        ON e.Expense_Type_Id = et.Expense_Type_Id
    LEFT JOIN 
        tbl_expenses_categories ec 
        ON et.Expense_Category_Id = ec.Expense_Category_Id
    LEFT JOIN 
        tbl_user u 
        ON e.User_Id = u.Id
    GROUP BY 
        e.Expense_Id, 
        et.Expense_Type, 
        ec.Expense_Category_Name
    ORDER BY 
        e.Expense_Id ASC";

// Execute query
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

// Prepare dataset
$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Expense_Id" => $row["Expense_Id"],
            "Expense_Title" => $row["Expense_Title"],
            "Expense_Type" => $row["Expense_Type"],
            "Expense_Category" => $row["Expense_Category_Name"],
            "Status" => $row["Status"],
            "Expense_Amount" => number_format($row["Expense_Amount"],2),
            "Paid_Amount" => number_format($row["Paid_Amount"],2),
            "Payment_Type" => $row["Payment_Type"],
            "Due_Amount" => number_format($row["Due_Amount"],2),
            "Expence_Date" => $row["Expence_Date"],
            "Payment_Date" => $row["Payment_Date"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"],
        ));
    }
}

// Output the dataset as JSON
echo json_encode($dataset);
mysqli_close($conn);
