<?php

require '../../API/Connection/BackEndPermission.php';

$sql = "SELECT 
    cf.Id,
    cf.Income_Transaction_Id,
    cf.Expense_Transaction_Id,
    cf.Description,
    COALESCE(cf.Income, 0) AS Income,
    COALESCE(cf.Expense, 0) AS Expense,
    cf.Payment_Type,
    cf.Update_Date
    FROM tbl_cash_flow cf
    ORDER BY cf.Update_Date ASC"; // Sort in ascending order

$result = $conn->query($sql);

$transactions = array();
$totalIncome = 0.00;
$totalExpense = 0.00;
$totalBalance = 0.00; // Start from 0

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert to float
        $income = (float) $row["Income"];
        $expense = (float) $row["Expense"];

        // Update total income & expense
        $totalIncome += $income;
        $totalExpense += $expense;

        // Calculate running balance
        $totalBalance += $income - $expense;

        // Store transaction with correct balance
        array_push($transactions, array(
            "Id" => $row["Id"],
            "Income_Transaction_Id" => $row["Income_Transaction_Id"],
            "Expense_Transaction_Id" => $row["Expense_Transaction_Id"],
            "Description" => $row["Description"],
            "Income" => number_format($income, 2),
            "Expense" => number_format($expense, 2),
            "Balance" => number_format($totalBalance, 2), // Correct cumulative balance
            "Payment_Type" => $row["Payment_Type"],
            "Update_Date" => $row["Update_Date"]
        ));
    }
}

// Reverse the array to return it in descending order
$transactions = array_reverse($transactions);

// Prepare the response
$response = array(
    "cashflow" => $transactions,
    "totals" => array(
        "Total_Income" => number_format($totalIncome, 2),
        "Total_Expense" => number_format($totalExpense, 2),
        "Total_Balance" => number_format($totalBalance, 2)
    )
);

echo json_encode($response);
mysqli_close($conn);

?>
