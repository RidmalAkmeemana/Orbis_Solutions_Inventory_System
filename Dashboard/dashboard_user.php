<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?php echo htmlspecialchars($companyName ?: 'Dashboard'); ?> - Super Admin Dashboard</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark_mode_style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .tile-number {
            font-size: 22px;
            font-weight: 700;
        }

        .tile-label {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="content container-fluid">

        <!-- Monetary Totals -->
        <div class="row g-3 mb-3" id="tiles-money"></div>

        <!-- Daily Sales From Last Month -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Daily Sales (Last 30 Days)</h5>
                        <canvas id="dailySalesChart" height="120"></canvas>
                        <div id="dailySalesNoData" class="text-center py-5 my-xl-3 text-muted" style="display:none;"><strong>No Results</strong></div>
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
                        <div id="fastProductNoData" class="text-center py-5 my-xl-3 text-muted" style="display:none;"><strong>No Results</strong></div>
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
                        <div id="topUsersNoData" class="text-center py-5 my-xl-3 text-muted" style="display:none;"><strong>No Results</strong></div>
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
                        <div id="paymentMethodNoData" class="text-center py-5 my-xl-3 text-muted" style="display:none;"><strong>No Results</strong></div>
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
                                    <tr id="fastProductTableNoData" style="display:none;">
                                        <td colspan="5" class="text-center py-5 my-xl-3 text-muted">
                                            <strong>No Results</strong>
                                        </td>
                                    </tr>
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
                                <tr id="topCustomersNoData" style="display:none;">
                                    <td colspan="5" class="text-center py-5 my-xl-3 text-muted">
                                        <strong>No Results</strong>
                                    </td>
                                </tr>
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
            return Number(v).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
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
                        tile.innerText = isCurrency ?
                            Number(target).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) :
                            Math.round(target).toLocaleString();
                        clearInterval(interval);
                    } else {
                        tile.innerText = isCurrency ?
                            Number(count).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) :
                            Math.round(count).toLocaleString();
                    }
                }, stepTime);
            });
        }

        // --- Render Tiles ---
        function renderTiles(pageData) {
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
        function renderFastProductsPie(data) {

            const canvas = document.getElementById("fastProductPie");
            const noDataText = document.getElementById("fastProductNoData");

            const tableBody = document.querySelector("#fastProductTable tbody");
            const noDataRow = document.getElementById("fastProductTableNoData");

            // ----------------------------
            // CASE 1: NO DATA
            // ----------------------------
            if (!data.fastProducts || data.fastProducts.length === 0) {

                // Hide chart, show "No Results"
                canvas.style.display = "none";
                noDataText.style.display = "block";

                // Clear table rows and show No Results row
                tableBody.querySelectorAll("tr:not(#fastProductTableNoData)").forEach(tr => tr.remove());
                noDataRow.style.display = "table-row";

                return;
            }

            // ----------------------------
            // CASE 2: DATA EXISTS
            // ----------------------------

            // Show chart, hide "No Results"
            canvas.style.display = "block";
            noDataText.style.display = "none";

            // Hide table No Results row
            noDataRow.style.display = "none";

            // Clear existing table rows
            tableBody.querySelectorAll("tr:not(#fastProductTableNoData)").forEach(tr => tr.remove());

            // Render Pie Chart
            const labels = data.fastProducts.map(p => p.product_name);
            const qtySold = data.fastProducts.map(p => p.qty_sold);

            const colors = ['#be3235', '#000000', '#26af48', '#009efb', '#f39c12',
                '#8207DB', '#53EAFD', '#FFA2A2', '#162456', '#31C950'
            ];

            new Chart(canvas, {
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
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });

            // Fill Table Rows
            data.fastProducts.forEach((p, index) => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${p.product_id}</td>
                    <td>${p.product_name}</td>
                    <td>${p.qty_sold}</td>
                    <td>${p.available_qty}</td>
                `;
                tableBody.appendChild(tr);
            });
        }


        // --- Render Daily Sales Bar Chart ---
        function renderDailySalesChart(data) {

            const chartCanvas = document.getElementById("dailySalesChart");
            const noDataText = document.getElementById("dailySalesNoData");

            // If no data → show "No Results", hide canvas, stop rendering
            if (!data.dailySales || data.dailySales.length === 0) {
                chartCanvas.style.display = "none";
                noDataText.style.display = "block";
                return;
            }

            // If data exists → show chart, hide "No Results"
            chartCanvas.style.display = "block";
            noDataText.style.display = "none";

            const ctx = chartCanvas;

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
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return "LKR " + Number(context.raw).toLocaleString(undefined, {
                                        minimumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    }
                }
            });
        }


        // --- Render Top Users Bubble Chart ---
        function renderTopUsersBubble(data) {
            const canvas = document.getElementById("topUsersBubble");
            const noData = document.getElementById("topUsersNoData");

            if (!data.topUsers || data.topUsers.length === 0) {
                // Hide chart
                canvas.style.display = "none";
                // Show no results message
                noData.style.display = "block";
                return;
            }

            // Show chart
            canvas.style.display = "block";
            // Hide no results message
            noData.style.display = "none";

            const ctx = canvas;

            const colors = ['#be3235', '#000000', '#26af48', '#009efb', '#f39c12', '#8207DB', '#53EAFD', '#FFA2A2', '#162456', '#31C950'];

            const userColorMap = {};
            let colorIndex = 0;

            const chartData = data.topUsers.map(u => {
                if (!userColorMap[u.user_id]) {
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
                                        `Sales: LKR ${Number(d.y).toLocaleString(undefined, { minimumFractionDigits: 2 })}`
                                    ];
                                }
                            }
                        },
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Invoice Count',
                                font: { size: 14 }
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Sales (LKR)',
                                font: { size: 14 }
                            }
                        }
                    }
                }
            });
        }


        // Convert HEX to RGBA for smooth opacity
        function hexToRgba(hex, opacity) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `rgba(${r},${g},${b},${opacity})`;
        }

        // --- Render Most Used Payment Methods Bar Graph ---
        function renderPaymentMethodBar(data) {
            const canvas = document.getElementById("paymentMethodBar");
            const noData = document.getElementById("paymentMethodNoData");

            // No results case
            if (!data.paymentMethods || data.paymentMethods.length === 0) {
                canvas.style.display = "none";
                noData.style.display = "block";
                return;
            }

            // Show normal chart
            canvas.style.display = "block";
            noData.style.display = "none";

            const ctx = canvas;

            const labels = data.paymentMethods.map(p => p.method);
            const counts = data.paymentMethods.map(p => p.usage_count);

            const barColors = ['#be3235', '#000000', '#26af48', '#009efb', '#f39c12'];

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
                        x: {
                            title: {
                                display: true,
                                text: 'Payment Method'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Usage Count'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        }


        // --- Render Top Customers Table ---
        function renderTopCustomers(data) {
            const tbody = document.querySelector("#topCustomersTable tbody");
            const noDataRow = document.getElementById("topCustomersNoData");

            // No results case
            if (!data.topCustomers || data.topCustomers.length === 0) {
                tbody.innerHTML = ""; 
                tbody.appendChild(noDataRow);
                noDataRow.style.display = "table-row";
                return;
            }

            // Results available → hide no-data row
            noDataRow.style.display = "none";

            // Clear existing rows
            tbody.innerHTML = "";

            // Append dynamic rows
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
                url: '../../API/Admin/getDashboardUserData.php',
                type: 'POST',
                dataType: 'json',
                success: function(res) {
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