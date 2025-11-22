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

    <!-- Daily Sales From Last Month -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Daily Sales (Last 30 Days)</h5>
                    <canvas id="dailySalesChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Daily Sales From Last Month -->

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
        <!-- Pie Chart -->

        <!-- Top Users Bubble Chart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Top 10 Users by Billing</h5>
                    <canvas id="topUsersBubble" height="300"></canvas>
                </div>
            </div>
        </div>
        <!-- Top Users Bubble Chart -->

        <!-- Most Used Payment Methods -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Most Used Payment Methods</h5>
                    <canvas id="paymentMethodBar" height="300"></canvas>
                </div>
            </div>
        </div>
        <!-- Most Used Payment Methods -->
    </div>

    <div class="row mb-4">
        <!-- Fast Moving Products -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Top 10 Moving Product Details</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-center" id="fastProductTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
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
        <!-- Fast Moving Products -->

        <!-- Top 10 Customers Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Top 10 Customers by Billing</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-center" id="topCustomersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be injected -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top 10 Customers Table -->
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
    return `<div class="col-sm-6 col-md-2 mb-3">
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
            <td>${p.product_id}</td>
            <td>${p.product_name}</td>
            <td>${p.qty_sold}</td>
            <td>${p.available_qty}</td>
        `;
        tbody.appendChild(tr);
    });
}

// --- Render Daily Sales Bar Chart ---
function renderDailySalesChart(data) {
    if (!data.dailySales || data.dailySales.length === 0) return;

    const ctx = document.getElementById("dailySalesChart");

    const labels = data.dailySales.map(s => s.date);
    const sales = data.dailySales.map(s => s.total_sales);

    // Two color alternating pattern
    const colors = sales.map((_, i) => i % 2 === 0 ? "#be3235" : "#000000");

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Daily Sales (LKR)",
                data: sales,
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { ticks: { font: { size: 11 } } },
                y: {
                    ticks: { font: { size: 11 } },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return "LKR " + Number(context.raw).toLocaleString(undefined, { minimumFractionDigits: 2 });
                        }
                    }
                }
            }
        }
    });
}

// --- Render Top Users Bubble Chart ---
function renderTopUsersBubble(data){
    if (!data.topUsers || data.topUsers.length === 0) return;

    const ctx = document.getElementById("topUsersBubble");

    // Color palette for users
    const colors = ['#be3235','#000000','#26af48','#009efb','#f39c12','#8207DB','#53EAFD','#FFA2A2','#162456','#31C950'];

    // Assign color per user (stable mapping)
    const userColorMap = {};
    let colorIndex = 0;

    const chartData = data.topUsers.map(u => {
        if(!userColorMap[u.user_id]){
            userColorMap[u.user_id] = colors[colorIndex % colors.length];
            colorIndex++;
        }

        const baseColor = userColorMap[u.user_id];

        return {
            x: u.invoice_count,
            y: u.total_sales,
            r: Math.max(8, Math.min(u.total_sales / 5000, 30)),
            label: u.user_name,
            color: baseColor
        };
    });

    new Chart(ctx, {
        type: 'bubble',
        data: {
            datasets: [{
                label: "Users",
                data: chartData,
                backgroundColor: chartData.map(p => hexToRgba(p.color, 0.65)),
                borderColor: chartData.map(p => hexToRgba(p.color, 1)),
                borderWidth: 1.5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const d = context.raw;
                            return [
                                `User: ${d.label}`,
                                `Invoices: ${d.x}`,
                                `Sales: LKR ${Number(d.y).toLocaleString(undefined, {minimumFractionDigits:2})}`
                            ];
                        }
                    }
                },
                legend: { display: false }
            },
            scales: {
                x: { title: { display: true, text: 'Invoice Count', font: { size: 14 } } },
                y: { title: { display: true, text: 'Total Sales (LKR)', font: { size: 14 } } }
            }
        }
    });
}

// Convert HEX to RGBA for smooth opacity
function hexToRgba(hex, opacity){
    const r = parseInt(hex.slice(1,3), 16);
    const g = parseInt(hex.slice(3,5), 16);
    const b = parseInt(hex.slice(5,7), 16);
    return `rgba(${r},${g},${b},${opacity})`;
}

// --- Render Most Used Payment Methods Bar Graph ---
function renderPaymentMethodBar(data){
    if(!data.paymentMethods || data.paymentMethods.length === 0) return;

    const ctx = document.getElementById("paymentMethodBar");

    const labels = data.paymentMethods.map(p => p.method);
    const counts = data.paymentMethods.map(p => p.usage_count);

    const barColors = ['#be3235','#000000','#26af48','#009efb','#f39c12'];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Usage Count",
                data: counts,
                backgroundColor: labels.map((_, i) => barColors[i % barColors.length]),
                borderColor: labels.map((_, i) => barColors[i % barColors.length]),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `Used: ${context.raw} times`
                    }
                }
            },
            scales: {
                x: { title: { display: true, text: 'Payment Method' } },
                y: { title: { display: true, text: 'Usage Count' }, beginAtZero: true }
            }
        }
    });
}

// --- Render Top Customers Table ---
function renderTopCustomers(data){
    if(!data.topCustomers || data.topCustomers.length === 0) return;

    const tbody = document.querySelector("#topCustomersTable tbody");
    tbody.innerHTML = "";

    data.topCustomers.forEach((c, index) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${c.customer_name}</td>
            <td>${c.address}</td>
            <td>${c.contact_no}</td>
            <td>${c.email}</td>
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
            renderDailySalesChart(res);
            renderTopUsersBubble(res);
            renderPaymentMethodBar(res);
            renderTopCustomers(res);
        }
    });
}

$(document).ready(fetchDashboard);
</script>
</body>
</html>
