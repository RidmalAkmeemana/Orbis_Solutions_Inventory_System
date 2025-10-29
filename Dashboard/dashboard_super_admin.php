<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?php echo htmlspecialchars($companyName ?: 'Dashboard'); ?> - Super Admin Dashboard</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    .tile-number { font-size: 22px; font-weight:700; }
    .tile-label { font-size: 13px; color:#666; }
    </style>
</head>
<body>
<div class="content container-fluid">

    <!-- Monetary Totals -->
    <div class="row g-3 mb-3" id="tiles-money"></div>

    <!-- Counts -->
    <div class="row g-3 mb-3" id="tiles-counts"></div>

    <!-- Fast Moving Products -->
    <div class="row mb-4">
        <!-- Pie Chart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Top 10 Moving Products</h5>
                    <canvas id="fastProductPie"></canvas>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Top 10 Moving Product Details</h5>
                    <div class="table-responsive">
                        <table class="datatable table table-hover table-center mb-0" id="fastProductTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Sold Qty</th>
                                    <th>Available Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script>
function formatMoney(v) {
    return Number(v).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
}

// --- Make Tile HTML with card ---
function makeTileHtml(label, value) {
    return `<div class="col-sm-6 col-md-3 mb-3">
        <div class="card">
            <div class="card-body text-center p-3">
                <div class="tile-number" data-target="${value}">0</div>
                <div class="tile-label">${label}</div>
            </div>
        </div>
    </div>`;
}

// --- Animate numbers ---
function animateNumbers(containerSelector, duration = 800, isCurrency = false) {
    const tiles = document.querySelectorAll(containerSelector + ' .tile-number');
    tiles.forEach(tile => {
        const target = parseFloat(tile.getAttribute('data-target')) || 0;
        let count = 0;
        const steps = 100;
        const increment = target / steps;
        const stepTime = duration / steps;

        const interval = setInterval(() => {
            count += increment;
            if (count >= target) {
                tile.innerText = isCurrency
                    ? Number(target).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    : Math.round(target).toLocaleString();
                clearInterval(interval);
            } else {
                tile.innerText = isCurrency
                    ? Number(count).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    : Math.round(count).toLocaleString();
            }
        }, stepTime);
    });
}

// --- Render Tiles ---
function renderTiles(pageData) {
    // Counts Tiles
    let countsHtml = '';
    const countFields = [
        ['Users', pageData.Count_Users || 0],
        ['User Roles', pageData.Count_Roles || 0],
        ['Suppliers', pageData.Count_Suppliers || 0],
        ['Registered Customers', pageData.Count_Customers || 0],
        ['Brands', pageData.Count_Brands || 0],
        ['Categories', pageData.Count_Categories || 0],
        ['Products', pageData.Count_Products || 0],
        ['Expenses Types', pageData.Count_Expenses_Types || 0],
        ['Expenses Categories', pageData.Count_Expenses_Categories || 0],
        ['Count of Expenses', pageData.Count_Expenses || 0],
        ['Invoices', pageData.Count_Invoices || 0],
        ['Quotations', pageData.Count_Quotations || 0]
    ];
    countFields.forEach(([label, val]) => countsHtml += makeTileHtml(label, val));
    document.getElementById('tiles-counts').innerHTML = countsHtml;
    animateNumbers('#tiles-counts', 800, false);

    // Monetary Tiles
    const moneyHtml = `
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="tile-number" data-target="${pageData.Total_Sales || 0}">0</div>
                <div class="tile-label">Total Sales</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="tile-number" data-target="${pageData.Total_Expenses || 0}">0</div>
                <div class="tile-label">Total Expenses</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="tile-number" data-target="${pageData.Total_Outstanding || 0}">0</div>
                <div class="tile-label">Total Outstanding</div>
            </div>
        </div>
    </div>
    `;
    document.getElementById('tiles-money').innerHTML = moneyHtml;
    animateNumbers('#tiles-money', 800, true);
}

// --- Render Fast Moving Products Pie & Table ---
function renderFastProductsPie(data){
    if (!data.fastProducts || data.fastProducts.length === 0) return;

    const ctx = document.getElementById("fastProductPie");
    const labels = data.fastProducts.map(p => p.product_name);
    const qtySold = data.fastProducts.map(p => p.qty_sold);

    const colors = ['#be3235','#000000','#26af48','#009efb','#f39c12','#8207DB','#53EAFD','#FFA2A2','#162456','#31C950'];

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: "Qty Sold",
                data: qtySold,
                backgroundColor: labels.map((_, i) => colors[i % colors.length])
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            const p = data.fastProducts[index];
                            return [
                                `Code: ${p.product_id}`,
                                `Brand: ${p.brand}`,
                                `Category: ${p.category}`,
                                `Qty Sold: ${p.qty_sold}`,
                                `Available Qty: ${p.available_qty}`
                            ];
                        }
                    }
                },
                legend: {
                    position: 'right',
                    labels: { font: { size: 12 } }
                }
            }
        }
    });

    // Inject table rows with Rank
    const tbody = document.querySelector("#fastProductTable tbody");
    tbody.innerHTML = "";
    data.fastProducts.forEach((p, index) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${p.product_name}</td>
            <td>${p.brand}</td>
            <td>${p.category}</td>
            <td>${p.qty_sold}</td>
            <td>${p.available_qty}</td>
        `;
        tbody.appendChild(tr);
    });
}

// --- Fetch Dashboard Data ---
function fetchDashboard() {
    $.ajax({
        url: '../../API/Admin/getDashboardSuperAdminData.php',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (!res || res.success !== 'true') {
                alert('Failed to fetch dashboard data');
                return;
            }
            renderTiles(res.pageData || {});
            renderFastProductsPie(res);
        }
    });
}

$(document).ready(fetchDashboard);
</script>
</body>
</html>
