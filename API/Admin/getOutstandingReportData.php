<?php
require '../../API/Connection/BackEndPermission.php';

// Get filter values
$amountFrom = floatval($_POST['AmountFrom']);
$amountTo = floatval($_POST['AmountTo']);

// Base SQL
$sql = "
    SELECT
        i.Customer_Id,
        c.Customer_Name,
        c.Customer_Contact,
        COUNT(i.Invoice_Id) AS invoice_count,
        SUM(i.Grand_Total) AS Total_Grand_Total,
        SUM(i.Paid_Amount) AS Total_Paid_Amount,
        SUM(i.Balance_Total) AS Total_Balance_Total,
        SUM(i.Due_Total) AS Total_Due_Total
    FROM 
        tbl_invoice i
    JOIN 
        tbl_customers c ON i.Customer_Id = c.Customer_Id
    GROUP BY c.Customer_Id
";

// Apply Outstanding Filters Using HAVING Clause
if ($amountFrom == 0 && $amountTo == 0) {
    // Fetch only customers with exactly zero outstanding
    $sql .= " HAVING Total_Due_Total = 0";
} else {
    // Fetch customers with outstanding in between given range
    $sql .= " HAVING Total_Due_Total BETWEEN $amountFrom AND $amountTo";
}

$sql .= " ORDER BY c.Customer_Id ASC";

// Execute query
$result = $conn->query($sql);

if (!$result) {
    echo "SQL Error: " . $conn->error;
    exit;
}

$customerData = array();
$totalGrandTotal = 0;
$totalPaidTotal = 0;
$totalDueTotal = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $totalPaidAmount = $row["Total_Paid_Amount"] - $row["Total_Balance_Total"];

        $customerData[] = array(
            "Customer_Id" => $row["Customer_Id"],
            "Customer_Name" => $row["Customer_Name"],
            "Customer_Contact" => $row["Customer_Contact"],
            "Invoice_Count" => number_format($row["invoice_count"]),
            "Grand_Total" => number_format($row["Total_Grand_Total"], 2),
            "Paid_Amount" => number_format($totalPaidAmount, 2),
            "Due_Total" => number_format($row["Total_Due_Total"], 2)
        );

        // Page Totals
        $totalGrandTotal += $row["Total_Grand_Total"];
        $totalPaidTotal += $totalPaidAmount;
        $totalDueTotal += $row["Total_Due_Total"];
    }

    $response = array(
        "success" => "true",
        "pageData" => array(
            "Total_Sales" => number_format($totalGrandTotal, 2),
            "Total_Paid" => number_format($totalPaidTotal, 2),
            "Total_Outstandings" => number_format($totalDueTotal, 2)
        ),
        "customerData" => $customerData
    );
} else {
    $response = array(
        "success" => "false",
        "message" => "No data found for the selected filters"
    );
}

echo json_encode($response);
mysqli_close($conn);
?>
