<?php

require '../../API/Connection/BackEndPermission.php';

$Expense_Type_Id = $_REQUEST["Expense_Type_Id"];

// Fetch expense type data
$sqlExpenseType = "
    SELECT 
        et.Id, 
        et.Expense_Type_Id, 
        ec.Expense_Category_Name, 
        et.Expense_Type 
    FROM 
        tbl_expenses_types et 
    INNER JOIN 
        tbl_expenses_categories ec 
    ON 
        et.Expense_Category_Id = ec.Expense_Category_Id 
    WHERE 
        et.Expense_Type_Id = '$Expense_Type_Id'
";
$resultExpenseType = $conn->query($sqlExpenseType);

$expenseTypeData = array();

if ($resultExpenseType->num_rows > 0) {
    $rowExpenseType = $resultExpenseType->fetch_assoc();

    // Construct expense type data
    $expenseTypeData = array(
        'Id' => $rowExpenseType["Id"],
        'Expense_Type_Id' => $rowExpenseType["Expense_Type_Id"],
        'Expense_Category_Name' => $rowExpenseType["Expense_Category_Name"],
        'Expense_Type' => $rowExpenseType["Expense_Type"]
    );
}

// Fetch expenses under the expense type
$sqlExpenses = "
    SELECT 
        e.Id, 
        e.Expense_Id, 
        e.Expense_Title, 
        e.Expense_Amount, 
        e.Expence_Date 
    FROM 
        tbl_expenses e 
    INNER JOIN 
        tbl_expenses_types et 
    ON 
        e.Expense_Type_Id = et.Expense_Type_Id 
    WHERE 
        et.Expense_Type_Id = '$Expense_Type_Id'
";
$resultExpenses = $conn->query($sqlExpenses);

$expenses = array();

if ($resultExpenses->num_rows > 0) {
    while ($rowExpense = $resultExpenses->fetch_assoc()) {
        // Construct expenses data
        $expenseData = array(
            'Expense_Id' => $rowExpense['Expense_Id'],
            'Expense_Title' => $rowExpense['Expense_Title'],
            'Expense_Amount' => number_format($rowExpense['Expense_Amount'], 2),
            'Expence_Date' => $rowExpense['Expence_Date']
        );
        array_push($expenses, $expenseData);
    }
}

// Combine expense type and expenses data into a single response object
$response = array(
    'expenseTypeData' => $expenseTypeData,
    'expenses' => $expenses
);

// Encode response object as JSON and output it
echo json_encode($response);

?>
