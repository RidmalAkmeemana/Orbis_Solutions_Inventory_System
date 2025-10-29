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
$counts['Count_Customers'] = getCount($conn, "tbl_customers");
$counts['Count_Users'] = getCount($conn, "tbl_user");
$counts['Count_Roles'] = getCount($conn, "tbl_roles");
$counts['Count_Invoices'] = getCount($conn, "tbl_invoice");
$counts['Count_Quotations'] = getCount($conn, "tbl_quotation");
$counts['Count_Brands'] = getCount($conn, "tbl_brand");
$counts['Count_Categories'] = getCount($conn, "tbl_category");
$counts['Count_Products'] = getCount($conn, "tbl_product");
$counts['Count_Expenses_Types'] = getCount($conn, "tbl_expenses_types");
$counts['Count_Expenses_Categories'] = getCount($conn, "tbl_expenses_categories");
$counts['Count_Expenses'] = getCount($conn, "tbl_expenses");
$counts['Count_Suppliers'] = getCount($conn, "tbl_suppliers");

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


// FINAL RESPONSE
echo json_encode([
    'success' => 'true',
    'pageData' => $counts,
    'fastProducts' => $fastProducts
]);

$conn->close();
