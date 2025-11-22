<?php

require '../../API/Connection/BackEndPermission.php';
include '../Connection/uploadurl.php';

// Fetch the Expense_Id from the GET parameters
$expenseId = isset($_GET['Expense_Id']) ? mysqli_real_escape_string($conn, $_GET['Expense_Id']) : '';

if (empty($expenseId)) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'Expense_Id is required';
    echo json_encode($response);
    exit();
}

// Fetch the ExpenseData from tbl_expenses and join and tbl_user
$expenseSQL = "SELECT 
    e.Expense_Id,
    et.Expense_Type,
    e.User_Id,
    e.Expense_Title,
    u.First_Name,
    u.Last_Name,
    e.Doc,
    e.Status,
    FORMAT(e.Expense_Amount, 2) AS Expense_Amount,
    FORMAT(e.Paid_Amount, 2) AS Paid_Amount,
    FORMAT(e.Due_Amount, 2) AS Due_Amount,
    e.Payment_Type,
    e.Expense_Description,
    e.Expence_Date,
    e.Payment_Date
FROM tbl_expenses e
JOIN tbl_expenses_types et ON e.Expense_Type_Id = et.Expense_Type_Id
JOIN tbl_user u ON e.User_Id = u.Id
WHERE e.Expense_Id = '$expenseId'";

// Execute the query
$expenseResult = mysqli_query($conn, $expenseSQL);

if (!$expenseResult) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'SQL Error: ' . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

if (mysqli_num_rows($expenseResult) == 0) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'Expense not found';
    echo json_encode($response);
    exit();
}

$expenseData = mysqli_fetch_assoc($expenseResult);

// Fetch the payments associated with the Expense_Id
$paymentsSQL = "SELECT 
    p.Id,
    p.Expense_Id,
    p.Payment_Id,
    FORMAT(p.Paid_Amount, 2) AS Paid_Amount,
    FORMAT(p.Due_Amount, 2) AS Due_Amount,
    p.Payment_Type,
    p.Payment_Date,
    p.Expense_Description,
    p.Updated_By,
    u.First_Name,
    u.Last_Name
FROM tbl_expense_payments p
JOIN tbl_user u ON p.Updated_By = u.Id
WHERE p.Expense_Id = '$expenseId'
ORDER BY p.Payment_Id ASC";

$paymentsResult = mysqli_query($conn, $paymentsSQL);

if (!$paymentsResult) {
    $response = new \stdClass();
    $response->success = false;
    $response->error = 'SQL Error: ' . mysqli_error($conn);
    echo json_encode($response);
    exit();
}

if (!empty($expenseData['Doc'])) {
    $expenseData['Doc'] = $base_url . $expenseData['Doc'];
}

$payments = [];
while ($row = mysqli_fetch_assoc($paymentsResult)) {
    $payments[] = $row;
}

// Build the response object
$response = new \stdClass();
$response->success = true;
$response->ExpenseData = $expenseData;
$response->payments = $payments;

// Return the JSON response
echo json_encode($response);

?>
