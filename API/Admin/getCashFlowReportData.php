<?php
require '../../API/Connection/BackEndPermission.php';

// Get filter values
$dateFrom = $_POST['DateFrom'];  // Expecting format: 'YYYY-MM-DD'
$dateTo = $_POST['DateTo'];      // Expecting format: 'YYYY-MM-DD'

// Validate Date Inputs
if (!empty($dateFrom) && !empty($dateTo)) {
    $dateFrom .= ' 00:00:00';
    $dateTo .= ' 23:59:59';
} else {
    echo json_encode(array(
        "success" => "false",
        "message" => "DateFrom and DateTo are required"
    ));
    exit;
}

// Fetch filtered Cash Flow data
$sql = "
    SELECT 
        cf.Id,
        cf.Income_Transaction_Id,
        cf.Expense_Transaction_Id,
        cf.Description,
        COALESCE(cf.Income, 0) AS Income,
        COALESCE(cf.Expense, 0) AS Expense,
        cf.Payment_Type,
        cf.Update_Date
    FROM tbl_cash_flow cf
    WHERE cf.Update_Date BETWEEN '$dateFrom' AND '$dateTo'
    ORDER BY cf.Update_Date ASC
";

$result = $conn->query($sql);

$transactions = array();
$totalIncome = 0.00;
$totalExpense = 0.00;
$totalBalance = 0.00; // Running Balance

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $income = (float)$row["Income"];
        $expense = (float)$row["Expense"];

        // Update totals
        $totalIncome += $income;
        $totalExpense += $expense;

        // Running Balance
        $totalBalance += ($income - $expense);

        $transactions[] = array(
            "Id" => $row["Id"],
            "Income_Transaction_Id" => $row["Income_Transaction_Id"],
            "Expense_Transaction_Id" => $row["Expense_Transaction_Id"],
            "Description" => $row["Description"],
            "Income" => number_format($income, 2),
            "Expense" => number_format($expense, 2),
            "Balance" => number_format($totalBalance, 2),
            "Payment_Type" => $row["Payment_Type"],
            "Update_Date" => $row["Update_Date"]
        );
    }

    // Reverse to latest-first (optional based on UI)
    $transactions = array_reverse($transactions);

    $response = array(
        "success" => "true",
        "cashflow" => $transactions,
        "totals" => array(
            "Total_Income" => number_format($totalIncome, 2),
            "Total_Expense" => number_format($totalExpense, 2),
            "Total_Balance" => number_format($totalBalance, 2)
        )
    );

} else {
    $response = array(
        "success" => "false",
        "message" => "No Cash Flow Data Found for Selected Date Range"
    );
}

echo json_encode($response);
mysqli_close($conn);
?>
