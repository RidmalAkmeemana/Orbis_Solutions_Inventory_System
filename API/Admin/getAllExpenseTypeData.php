<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch expenses types
$sql = "SELECT 
    et.Expense_Type_Id, 
    et.Expense_Type, 
    et.Expense_Category_Id, 
    ec.Expense_Category_Name,
    COUNT(e.Expense_Id) AS expense_count
FROM 
    tbl_expenses_types et
JOIN 
    tbl_expenses_categories ec 
    ON et.Expense_Category_Id = ec.Expense_Category_Id
LEFT JOIN 
    tbl_expenses e 
    ON et.Expense_Type_Id = e.Expense_Type_Id
GROUP BY 
    et.Expense_Type_Id, 
    et.Expense_Type, 
    ec.Expense_Category_Name
ORDER BY 
    et.Expense_Type_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        array_push($dataset, array(
            "Expense_Type_Id" => $row["Expense_Type_Id"],
            "Expense_Type" => $row["Expense_Type"],
            "Expense_Category" => $row["Expense_Category_Name"],
            "expense_count" => number_format($row["expense_count"])
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);

?>
