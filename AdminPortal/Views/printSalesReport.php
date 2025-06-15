<?php

require_once '../../API/Connection/validator.php';
require_once '../../API/Connection/config.php';
require_once '../../API/Connection/ScreenPermission.php';

// Fetch Company Name from the database
$companyName = ""; // Default name if query fails

$query = "SELECT * FROM tbl_company_info LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $companyName = $row['Company_Name'];
    $companyAddress = $row['Company_Address'];
    $companyEmail = $row['Company_Email'];
    $companyTel1 = $row['Company_Tel1'];
    $companyTel2 = $row['Company_Tel2'];
    $companyTel3 = $row['Company_Tel3'];
}

$Invoice_Id = $_REQUEST["Invoice_Id"];
$Customer_Id = $_REQUEST["Customer_Id"];
$Id = $_REQUEST["Id"];
$Sale_Type = $_REQUEST["Sale_Type"];
$Status = $_REQUEST["Status"];
$Payment_Type = $_REQUEST["Payment_Type"];
$DateFrom = $_REQUEST["DateFrom"];
$DateTo = $_REQUEST["DateTo"];

if ($Customer_Id != 'ALL') {
    $query = mysqli_query($conn, "SELECT * FROM `tbl_customers` WHERE `Customer_Id` = '$Customer_Id'") or die(mysqli_error());
    $fetch = mysqli_fetch_array($query);
    $Customer_Name = $fetch['Customer_Name'];
} else {
    $Customer_Name = 'ALL';
}

if ($Id != 'ALL') {
    $query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `Id` = '$Id'") or die(mysqli_error());
    $fetch = mysqli_fetch_array($query);

    $First_Name = $fetch['First_Name'];
    $Last_Name = $fetch['Last_Name'];

    $Invoiced_By = $First_Name . ' ' . $Last_Name;
} else {
    $Invoiced_By = 'ALL';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo ($companyName); ?> - Sales Report</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="assets/css/feathericon.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Styling for the signature lines */
        .signature-line {
            border: none;
            border-top: 1px solid #e8e8e8;
            width: 150px;
            margin-top: 20px;
        }

        /* Styling for the signature labels */
        .signature-label {
            text-align: center;
            margin-top: 5px;
        }

        /* Ensure proper alignment of elements */
        .mt-4 {
            margin-top: 1.5rem;
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none;
            }
        }

        /* Black Back Button */
        .btn-back {
            background-color: black;
            color: white;
            border: none;
        }

        .btn-back:hover {
            background-color: #333;
            color: white;
        }

         /* Full-Screen Loader */
         #pageLoader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Spinner Animation */
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #be3235;
            border-top: 5px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Full-Screen Loader */
    </style>

    <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- Full-Screen Loader -->
    <div id="pageLoader">
        <div class="loader-content" style="display: flex; flex-direction: column; align-items: center;">
            <div class="spinner"></div>
            <div style="margin-top: 10px; font-size: 16px;">Loading . . .</div>
        </div>
    </div>
    <!-- /Full-Screen Loader -->

    <!-- Invoice Container -->
    <div class="invoice-container">
        <div class="row">
            <div class="col-6 m-b-20">
                <img alt="Logo" class="inv-logo img-fluid" src="assets/img/logo.png">
            </div>
            <div class="col-6 mt-4 m-b-20">
                <div class="invoice-details">
                    <h3 style="font-family: Arial;" class="text-uppercase font-weight-bold mb-1">
                        <span>Sales Report</span>
                    </h3>
                    <h6 style="font-family: Arial;" class="font-weight-bold mb-1">
                        <span>Date: </span>
                        <span id="Date"><?php echo date('Y-m-d H:i:s'); ?></span>
                    </h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div style="font-family: Arial; font-size: 17px;" class="col-sm-12 m-b-20">
                <ul class="list-unstyled mb-0">
                    <li class="font-weight-bold mb-1"><?php echo ($companyName); ?>,</li>
                    <li>Email: <?php echo ($companyEmail); ?></li>
                    <?php $companyTels = array_filter([$companyTel1, $companyTel2, $companyTel3]); ?>
                    <li>Tel: <?php echo implode(", ", $companyTels); ?></li>
                    <?php
                    $companyAddress = $companyAddress; // Assume this is fetched from DB
                    // Split address into multiple lines based on commas
                    $addressParts = array_map('trim', explode(',', $companyAddress));

                    // Format the address dynamically
                    $addressLine1 = isset($addressParts[0]) ? "No: " . $addressParts[0] . "," : "";
                    $addressLine2 = isset($addressParts[1]) ? $addressParts[1] . "," : "";
                    $addressLine3 = isset($addressParts[2]) ? $addressParts[2] . "," : "";
                    $addressLine4 = isset($addressParts[3]) ? $addressParts[3] . "," : "";
                    $addressLine5 = isset($addressParts[4]) ? $addressParts[4] . "." : "";
                    ?>
                    <?php if (!empty($addressLine1) || !empty($addressLine2)) echo "<li>$addressLine1 $addressLine2</li>"; ?>
                    <?php if (!empty($addressLine3) || !empty($addressLine4)) echo "<li>$addressLine3 $addressLine4</li>"; ?>
                    <?php if (!empty($addressLine5)) echo "<li>$addressLine5</li>"; ?>
                </ul>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-6 col-lg-7 col-xl-8 m-b-20 text-center">
                <h5 style="font-family: Arial;" class="font-weight-bold mb-1">Filter By</h5>
                <ul style="font-family: Arial; font-size: 17px;" class="list-unstyled mb-0">
                    <li><strong>Invoice No: </strong><span><?php echo htmlspecialchars($Invoice_Id); ?></span></li>
                    <li><strong>Customer Name: </strong><span><?php echo $Customer_Name; ?></span></li>
                    <li><strong>Invoiced By: </strong><span><?php echo $Invoiced_By; ?></span></li>
                    <li><strong>Sale Type: </strong><span><?php echo htmlspecialchars($Sale_Type); ?></span></li>
                    <li><strong>Status: </strong><span><?php echo htmlspecialchars($Status); ?></span></li>
                    <li><strong>Payment Type: </strong><span><?php echo htmlspecialchars($Payment_Type); ?></span></li>
                    <li><strong>Invoice Date From: </strong><span><?php echo htmlspecialchars($DateFrom); ?></span></li>
                    <li><strong>Invoice Date To: </strong><span><?php echo htmlspecialchars($DateTo); ?></span></li>
                </ul>
            </div>
        </div>

        <div class="table-responsive">
            <table style="font-family: Arial; font-size: 17px;" class="table">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Customer Name</th>
                        <th>Sale Type</th>
                        <th>Status</th>
                        <th class="text-nowrap text-right">Grand Total</th>
                        <th class="text-nowrap text-right">Due Total</th>
                    </tr>
                </thead>
                <tbody id="product_list">
                    <tr></tr>
                </tbody>
            </table>
        </div>

        <div class="row invoice-payment">
            <div class="col-sm-7"></div>
            <div class="col-sm-5">
                <div class="m-b-20">
                    <div class="table-responsive no-border">
                        <table style="font-family: Arial; font-size: 17px;" class="table">
                            <tbody>
                                <tr>
                                    <th>Total Sales (LKR): <span style="float: right;" id="total_Sales"></span></th>
                                </tr>
                                <tr>
                                    <th>Total Discounts (LKR): <span style="float: right;" id="total_Discounts"></span></th>
                                </tr>
                                <tr>
                                    <th>Total Outstandings (LKR): <span style="float: right;" id="total_Outstandings"></span></th>
                                </tr>
                                <tr>
                                    <th>Net Profit (LKR): <span style="float: right;" id="net_Profit"></span></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-lg-6 m-b-20 ml-auto">
                <div class="mt-4">
                    <div class="d-flex justify-content-end">
                        <div class="text-right">
                            <hr class="signature-line">
                            <div style="font-family: Arial;" class="signature-label">Authorized By</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12 m-b-20 d-flex justify-content-center"> <!-- Added d-flex and justify-content-center -->
                <div class="invoice-details">
                    <p class="text-muted mb-0 text-center ">
                        <span style="font-family: Arial;">System Design By Orbis Solutions</span>
                    </p>
                    <p class="text-muted mb-0 text-center">
                        <span style="font-family: Arial;">Hotline: 077 369 7070, 071 209 8989, 076 857 6851</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="no-print" style="text-align: center; margin-top:50px;">
            <button onclick="window.location.href = 'sales_report.php';" class="btn btn-back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
            <button onclick="window.print();" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>
    </div>
    <!-- /Invoice Container -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>

    <script>
        // Fetch sales data with filter parameters
        function fetchSalesData() {
            const filterParams = {

                Invoice_Id: "<?php echo addslashes($Invoice_Id); ?>",
                Customer_Id: "<?php echo addslashes($Customer_Id); ?>",
                Id: "<?php echo addslashes($Id); ?>",
                Sale_Type: "<?php echo addslashes($Sale_Type); ?>",
                Status: "<?php echo addslashes($Status); ?>",
                Payment_Type: "<?php echo addslashes($Payment_Type); ?>",
                DateFrom: "<?php echo addslashes($DateFrom); ?>",
                DateTo: "<?php echo addslashes($DateTo); ?>"

            };

            $.ajax({
                url: '../../API/Admin/getSalesReportData.php', // Update with actual path
                type: 'POST', // Use POST to match the server-side method
                data: filterParams,
                dataType: 'json',
                success: function(response) {
                    if (response.success === 'false') {
                        showNoDataFound();
                    } else {
                        populateSalesData(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ', status, error);
                }
            });
        }

        // Function to populate page data
        function populateSalesData(data) {
            const page = data.pageData;

            document.getElementById('total_Sales').innerText = page.Total_Sales ? Number(page.Total_Sales.replace(/,/g, '')).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '0.00';
            document.getElementById('total_Discounts').innerText = page.Total_Discounts ? Number(page.Total_Discounts.replace(/,/g, '')).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '0.00';
            document.getElementById('total_Outstandings').innerText = page.Total_Outstandings ? Number(page.Total_Outstandings.replace(/,/g, '')).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '0.00';
            document.getElementById('net_Profit').innerText = page.Net_Profit ? Number(page.Net_Profit.replace(/,/g, '')).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '0.00';

            // Populate products
            const products = data.productData;
            const productTable = document.getElementById('product_list');
            productTable.innerHTML = ''; // Clear previous rows

            products.forEach((product) => {
                const row = `
                <tr>
                    <td>${product.Invoice_Id}</td>
                    <td>${product.Customer_Name}</td>
                    <td>${product.Sale_Type}</td>
                    <td>${product.Status}</td>
                    <td class="text-right">LKR: ${product.Grand_Total ? Number(product.Grand_Total.replace(/,/g, '')).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '0.00'}</td>
                    <td class="text-right">LKR: ${product.Due_Total ? Number(product.Due_Total.replace(/,/g, '')).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') : '0.00'}</td>
                </tr>
                <tr></tr>
            `;
                productTable.insertAdjacentHTML('beforeend', row);
            });
        }

        // Function to show "No Data Found" in the table
        function showNoDataFound() {

            document.getElementById('total_Sales').innerText = '0.00';
            document.getElementById('total_Discounts').innerText = '0.00';
            document.getElementById('total_Outstandings').innerText = '0.00';
            document.getElementById('net_Profit').innerText = '0.00';

            // Clear the product table and display "No Data Found"
            const productTable = document.getElementById('product_list');
            productTable.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">No Data Found for the Selected Filters</td>
            </tr>
            <tr></tr>
        `;
        }

        // Call fetch function on page load
        $(document).ready(function() {
            fetchSalesData();
        });
    </script>

    <!-- Loader Script -->
    <script>
        let startTime = performance.now(); // Capture the start time when the page starts loading

        window.addEventListener("load", function() {
            let endTime = performance.now(); // Capture the end time when the page is fully loaded
            let loadTime = endTime - startTime; // Calculate the total loading time

            // Ensure the loader stays for at least 500ms but disappears dynamically based on actual load time
            let delay = Math.max(loadTime);

            setTimeout(function() {
                document.getElementById("pageLoader").style.display = "none";
            }, delay);
        });
    </script>
    <!-- /Loader Script -->
</body>

</html>