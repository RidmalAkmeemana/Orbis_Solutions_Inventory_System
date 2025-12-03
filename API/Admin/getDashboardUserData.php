<?php
// getDashboardSuperAdminData.php
require '../../API/Connection/config.php';
header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => 'false', 'message' => 'unauthorized']);
    exit;
}

// 1) Counts (Safe Queries)
function getCount($conn, $table) {
    $q = $conn->query("SELECT COUNT(*) AS c FROM $table");
    if ($q && $row = $q->fetch_assoc()) return (int)$row['c'];
    return 0;
}

$counts = [];

// 2) Monetary Totals (Safe)
$row = $conn->query("SELECT IFNULL(SUM(Grand_Total),0) AS s, IFNULL(SUM(Paid_Amount),0) AS p, IFNULL(SUM(Due_Total),0) AS d FROM tbl_invoice");
$invoiceTotals = $row ? $row->fetch_assoc() : ['s'=>0,'p'=>0,'d'=>0];

$counts['Total_Sales'] = (float)$invoiceTotals['s'];
$counts['Total_Paid'] = (float)$invoiceTotals['p'];
$counts['Total_Outstanding'] = (float)$invoiceTotals['d'];

$row2 = $conn->query("SELECT IFNULL(SUM(Expense_Amount),0) AS expenses FROM tbl_expenses");
$expTotals = $row2 ? $row2->fetch_assoc()['expenses'] : 0;
$counts['Total_Expenses'] = (float)$expTotals;

// 3) Fast Moving Products (Top 15 by Qty Sold with Available Qty - Corrected)
$fastProducts = [];
$q = $conn->query("
    SELECT 
        p.Product_Id,
        p.Product_Name,
        b.Brand_Name,
        c.Category_Name,
        it.Product_Detail_Id,
        IFNULL(SUM(it.Qty),0) AS qty_sold,
        (
            SELECT IFNULL(SUM(pd2.Qty),0)
            FROM tbl_product_details pd2
            WHERE pd2.Product_Id = p.Product_Id
        ) AS available_qty
    FROM tbl_item it
    INNER JOIN tbl_product_details pd ON pd.Id = it.Product_Detail_Id
    INNER JOIN tbl_product p ON p.Product_Id = pd.Product_Id
    LEFT JOIN tbl_brand b ON b.Brand_Id = p.Brand_Id
    LEFT JOIN tbl_category c ON c.Category_Id = p.Category_Id
    GROUP BY it.Product_Detail_Id
    HAVING qty_sold > 0
    ORDER BY qty_sold DESC
    LIMIT 10
");

if($q){
    while($r = $q->fetch_assoc()){
        $fastProducts[] = [
            'product_detail_id' => $r['Product_Detail_Id'],
            'product_id' => $r['Product_Id'],
            'product_name' => $r['Product_Name'],
            'brand' => $r['Brand_Name'],
            'category' => $r['Category_Name'],
            'qty_sold' => (int)$r['qty_sold'],
            'available_qty' => (int)$r['available_qty']
        ];
    }
}

// 4) Daily Sales of Last Month (Last 30 Days)
$dailySales = [];

$q = $conn->query("
    SELECT 
        DATE(Invoice_Date) AS sale_date,
        SUM(Grand_Total) AS total_sales
    FROM tbl_invoice
    WHERE Invoice_Date >= CURDATE() - INTERVAL 30 DAY
    GROUP BY DATE(Invoice_Date)
    ORDER BY sale_date ASC
");

if($q){
    while($r = $q->fetch_assoc()){
        $dailySales[] = [
            'date' => $r['sale_date'],
            'total_sales' => (float)$r['total_sales']
        ];
    }
}

// 5) Top 10 Users with highest billing
$topUsers = [];
$q = $conn->query("
    SELECT 
        u.Id,
        CONCAT(u.First_Name, ' ', u.Last_Name) AS user_name,
        COUNT(i.Invoice_Id) AS invoice_count,
        IFNULL(SUM(i.Grand_Total),0) AS total_sales
    FROM tbl_user u
    LEFT JOIN tbl_invoice i ON i.User_Id = u.Id
    GROUP BY u.Id
    HAVING total_sales > 0
    ORDER BY total_sales DESC
    LIMIT 10
");

if($q){
    while($r = $q->fetch_assoc()){
        $topUsers[] = [
            'user_id' => $r['Id'],
            'user_name' => $r['user_name'],
            'invoice_count' => (int)$r['invoice_count'],
            'total_sales' => (float)$r['total_sales']
        ];
    }
}

// 6) Most Used Payment Methods
$paymentMethods = [];
$q = $conn->query("
    SELECT 
        Payment_Type,
        COUNT(*) AS usage_count
    FROM tbl_invoice
    GROUP BY Payment_Type
    ORDER BY usage_count DESC
    LIMIT 5
");

if($q){
    while($r = $q->fetch_assoc()){
        $paymentMethods[] = [
            'method' => $r['Payment_Type'],
            'usage_count' => (int)$r['usage_count']
        ];
    }
}

// 7) Top 10 Customers (Highest Billing)
$topCustomers = [];
$q = $conn->query("
    SELECT 
        c.Customer_Name,
        COALESCE(c.Customer_Address, 'N/A') AS Customer_Address,
        COALESCE(c.Customer_Contact, 'N/A') AS Customer_Contact,
        COALESCE(c.Customer_Email, 'N/A') AS Customer_Email,
        SUM(i.Grand_Total) AS total_spent
    FROM tbl_customers c 
    INNER JOIN tbl_invoice i ON i.Customer_Id = c.Customer_Id
    GROUP BY c.Customer_Id
    ORDER BY total_spent DESC
    LIMIT 10
");

if($q){
    while($r = $q->fetch_assoc()){
        $topCustomers[] = [
            'customer_name' => $r['Customer_Name'],
            'address' => $r['Customer_Address'],
            'contact_no' => $r['Customer_Contact'],
            'email' => $r['Customer_Email']
        ];
    }
}

// FINAL RESPONSE
echo json_encode([
    'success' => 'true',
    'pageData' => $counts,
    'fastProducts' => $fastProducts,
    'dailySales' => $dailySales,
    'topUsers' => $topUsers,
    'paymentMethods' => $paymentMethods,
    'topCustomers' => $topCustomers
]);

$conn->close();
