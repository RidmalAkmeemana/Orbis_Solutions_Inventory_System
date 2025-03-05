<?php
require '../../API/Connection/BackEndPermission.php';

// Get filter values
$invoiceId = $_POST['Invoice_Id'] ?? 'ALL';
$customerId = $_POST['Customer_Id'] ?? 'ALL';
$Id = $_POST['Id'] ?? 'ALL';
$saleType = $_POST['Sale_Type'] ?? 'ALL';
$Status = $_POST['Status'] ?? 'ALL';
$paymentType = $_POST['Payment_Type'] ?? 'ALL';
$dateFrom = $_POST['DateFrom'];  // Expecting format: '2024-09-27'
$dateTo = $_POST['DateTo'];      // Expecting format: '2024-09-27'

// Convert date inputs to full datetime format
if ($dateFrom && $dateTo) {
    $dateFrom .= ' 00:00:00';  // Set the start of the day for DateFrom
    $dateTo .= ' 23:59:59';    // Set the end of the day for DateTo
} else {
    // Handle the case where dates are missing or invalid (Optional)
    echo json_encode(array(
        "success" => "false",
        "message" => "DateFrom and DateTo are required and must be in the format YYYY-MM-DD"
    ));
    exit;
}

// Initialize SQL with required conditions
$sql = "
    SELECT
        i.Invoice_Id,
        i.Customer_Id,
        i.User_Id,
        i.Sale_Type,
        i.Item_Count,
        i.Status,
        i.Sub_Total,
        i.Discount_Total,
        i.Profit_Total,
        i.Grand_Total,
        i.Paid_Amount,
        i.Balance_Total,
        i.Due_Total,
        i.Payment_Type,
        i.Invoice_Date,
        c.Customer_Name,
        u.First_Name,
        u.Last_Name,
        SUM(i.Item_Count) AS Total_Item_Count,
        SUM(i.Discount_Total) AS Total_Discount_Total,
        SUM(i.Profit_Total) AS Total_Profit_Total,
        SUM(i.Grand_Total) AS Total_Grand_Total,
        SUM(i.Paid_Amount) AS Total_Paid_Amount,
        SUM(i.Due_Total) AS Total_Due_Total
    FROM 
        tbl_invoice i
    JOIN 
        tbl_customers c ON i.Customer_Id = c.Customer_Id
    JOIN
        tbl_user u ON u.Id = i.User_Id
    WHERE 1=1
";

// Apply filters dynamically based on inputs
if ($invoiceId !== 'ALL') {
    $sql .= " AND i.Invoice_Id = '$invoiceId'";
}

if ($customerId !== 'ALL') {
    $sql .= " AND i.Customer_Id = '$customerId'";
}

if ($Id !== 'ALL') {
    $sql .= " AND i.User_Id = '$Id'";
}

if ($saleType !== 'ALL') {
    $sql .= " AND i.Sale_Type = '$saleType'";
}

if ($Status !== 'ALL') {
    $sql .= " AND i.Status = '$Status'";
}

if ($paymentType !== 'ALL') {
    $sql .= " AND i.Payment_Type = '$paymentType'";
}

// Apply date range filter with converted DateFrom and DateTo
$sql .= " AND i.Invoice_Date BETWEEN '$dateFrom' AND '$dateTo'";

// Group the data by invoice to handle aggregation if needed
$sql .= " GROUP BY i.Invoice_Id ORDER BY i.Invoice_Date ASC";

// Execute the query
$result = $conn->query($sql);

// Error handling: Check if the query execution was successful
if (!$result) {
    // Log MySQL error
    echo "SQL Error: " . $conn->error;
    exit;
}

$productData = array();
$totalItemCount = 0;
$totalDiscountTotal = 0;
$totalProfitTotal = 0;
$totalGrandTotal = 0;
$totalDueTotal = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedItemCount = number_format($row["Item_Count"]);
        $formattedSubTotal = number_format($row["Sub_Total"], 2);
        $formattedDiscount = number_format($row["Discount_Total"], 2);
        $formattedProfit = number_format($row["Profit_Total"], 2);
        $formattedGrandTotal = number_format($row["Grand_Total"], 2);
        $formattedPaidAmount = number_format($row["Paid_Amount"], 2);
        $formattedBalanceTotal = number_format($row["Balance_Total"], 2);
        $formattedDueTotal = number_format($row["Due_Total"], 2);

        // Add individual product data to the productData array
        array_push($productData, array(
            "Invoice_Id" => $row["Invoice_Id"],
            "Customer_Name" => $row["Customer_Name"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"],
            "Sale_Type" => $row["Sale_Type"],
            "Item_Count" => $formattedItemCount,
            "Status" => $row["Status"],
            "Sub_Total" => $formattedSubTotal,
            "Discount_Total" => $formattedDiscount,
            "Profit_Total" => $formattedProfit,
            "Grand_Total" => $formattedGrandTotal,
            "Paid_Amount" => $formattedPaidAmount,
            "Balance_Total" => $formattedBalanceTotal,
            "Due_Total" => $formattedDueTotal,
            "Payment_Type" => $row["Payment_Type"],
            "Invoice_Date" => $row["Invoice_Date"]
        ));

        // Sum the totals
        $totalItemCount += $row["Total_Item_Count"];
        $totalDiscountTotal += $row["Total_Discount_Total"];
        $totalProfitTotal += $row["Total_Profit_Total"];
        $totalGrandTotal += $row["Total_Grand_Total"];
        $totalDueTotal += $row["Total_Due_Total"];
    }

    // Prepare the response structure with total values and product data
    $response = array(
        "success" => "true",
        "pageData" => array(
            "Total_Items" => number_format($totalItemCount),
            "Total_Discounts" => number_format($totalDiscountTotal, 2),
            "Total_Sales" => number_format($totalGrandTotal, 2),
            "Total_Outstandings" => number_format($totalDueTotal, 2),
            "Net_Profit" => number_format($totalProfitTotal, 2),
        ),
        "productData" => $productData
    );
} else {
    // If no data is found, return an error response
    $response = array(
        "success" => "false",
        "message" => "No data found for the selected filters"
    );
}

// Return the JSON response
echo json_encode($response);

mysqli_close($conn);
?>
