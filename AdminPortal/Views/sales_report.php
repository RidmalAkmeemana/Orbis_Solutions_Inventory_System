<?php
require_once '../../API/Connection/validator.php';
require_once '../../API/Connection/config.php';
require_once '../../API/Connection/ScreenPermission.php';

// Fetch Company Name from the database
$companyName = ""; // Default name if query fails

$query = "SELECT Company_Name FROM tbl_company_info LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $companyName = $row['Company_Name'];
}

// Fetch user permissions (assuming you have a function for this)
$username = $_SESSION['user'];
$query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `username` = '$username'") or die(mysqli_error());
$fetch = mysqli_fetch_array($query);
$user_status = $fetch['Status'];

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

    <!-- Datatables CSS -->
    <link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark_mode_style.css">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            /* Adjust this value as needed */
            padding: 6px;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            /* Adjust to align text vertically */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            /* Adjust this value to match the height */
        }

        .select2-dropdown {
            max-height: 300px;
            /* Adjust the dropdown height */
            overflow-y: auto;
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

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <div class="header">

            <!-- Logo -->
            <div class="header-left">
                <a href="home.php" class="logo">
                    <img src="assets/img/logo.png" alt="Logo">
                </a>
                <a href="home.php" class="logo logo-small">
                    <img src="assets/img/logo-small.png" alt="Logo" width="30" height="30">
                </a>
            </div>
            <!-- /Logo -->

            <a href="javascript:void(0);" id="toggle_btn">
                <i class="fe fe-text-align-left"></i>
            </a>

            <!-- Mobile Menu Toggle -->
            <a class="mobile_btn" id="mobile_btn">
                <i class="fa fa-bars"></i>
            </a>
            <!-- /Mobile Menu Toggle -->

            <!-- Header Right Menu -->
            <ul class="nav user-menu">

                <!-- User Menu -->
                <?php
                require 'usermenu.php';
                ?>
                <!-- /User Menu -->

            </ul>
            <!-- /Header Right Menu -->

        </div>
        <!-- /Header -->

        <!-- Sidebar -->
        <?php
        require 'sidebar.php';
        ?>
        <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-7 col-auto">
                            <h3 class="page-title">Sales Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Sales Report</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Model Alerts -->

                <div class="modal fade" id="ErrorModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>From Date cannot be greater than To Date !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /Model Alerts -->

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">

                                <?php

                                require_once '../Controllers/select_controller.php';

                                $db_handle = new DBController();
                                $countryResult = $db_handle->runQuery("SELECT * FROM tbl_invoice ORDER BY Invoice_Id ASC");
                                $countryResult1 = $db_handle->runQuery("SELECT * FROM tbl_customers ORDER BY Customer_Id ASC");
                                $countryResult2 = $db_handle->runQuery("SELECT * FROM tbl_user ORDER BY Id ASC");

                                ?>

                                <!-- Add Filters Section Above the Table -->
                                <form method="POST" action="printSalesReport.php" id="filterForm" enctype="multipart/form-data">
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label><strong>Invoice No</strong></label><label class="text-danger">*</label>
                                            <select style="width: 100%;" class="form-control select2 product-select" name="Invoice_Id" required>
                                                <option selected>ALL</option>
                                                <?php
                                                if (!empty($countryResult)) {
                                                    foreach ($countryResult as $key => $value) {
                                                        echo '<option value="' . $countryResult[$key]['Invoice_Id'] . '">' . $countryResult[$key]['Invoice_Id'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label><strong>Customer Name</strong></label><label class="text-danger">*</label>
                                            <select style="width: 100%;" class="form-control select2 product-category" name="Customer_Id" required>
                                                <option selected>ALL</option>
                                                <?php
                                                if (!empty($countryResult1)) {
                                                    foreach ($countryResult1 as $key => $value) {
                                                        echo '<option value="' . $countryResult1[$key]['Customer_Id'] . '">' . $countryResult1[$key]['Customer_Name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label><strong>Invoiced By</strong></label><label class="text-danger">*</label>
                                            <select style="width: 100%;" class="form-control select2 product-supplier" name="Id" required>
                                                <option selected>ALL</option>
                                                <?php
                                                if (!empty($countryResult2)) {
                                                    foreach ($countryResult2 as $key => $value) {
                                                        echo '<option value="' . $countryResult2[$key]['Id'] . '">' . $countryResult2[$key]['First_Name'] . ' ' . $countryResult2[$key]['Last_Name'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label><strong>Sale Type</strong></label><label class="text-danger">*</label>
                                            <select style="width: 100%;" class="form-control select2 product-supplier" name="Sale_Type" required>
                                                <option selected>ALL</option>
                                                <option value="Retail Sale">Retail Sale</option>
                                                <option value="Whole Sale">Whole Sale</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <label><strong>Status</strong></label><label class="text-danger">*</label>
                                            <select style="width: 100%;" class="form-control select2 product-supplier" name="Status" required>
                                                <option selected>ALL</option>
                                                <option value="Fully Paid">Fully Paid</option>
                                                <option value="Partially Paid">Partially Paid</option>
                                                <option value="Unpaid">Unpaid</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <label><strong>Payment Type</strong></label><label class="text-danger">*</label>
                                            <select style="width: 100%;" class="form-control select2 product-supplier" name="Payment_Type" required>
                                                <option selected>ALL</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Card">Card</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 mt-2">
                                            <label><strong>Invoice Date From</strong></label><label class="text-danger">*</label>
                                            <input type="date" name="DateFrom" class="form-control" required="">
                                        </div>

                                        <div class="col-md-2 mt-2">
                                            <label><strong>Invoice Date To</strong></label><label class="text-danger">*</label>
                                            <input type="date" name="DateTo" class="form-control" required="">
                                        </div>

                                        <!-- Adjusted column size to align the button inline -->
                                        <div class="col-md-2" style="margin-top:40px;">
                                            <button type="submit" class="btn btn-primary w-100" id="filterBtn">Generate</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Wrapper -->
    </div>
    <!-- /Main Wrapper -->

    <!-- Footer -->
    <?php
    require 'footer.php';
    ?>
    <!-- /Footer -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Datatables JS -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.select2').select2();

            $('input[name="DateTo"]').on('keydown blur', function(event) {
                if (event.type === 'blur' || event.key === 'Enter') {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Prevent the default action, which may cause form submission
                    }

                    const dateFrom = new Date(document.querySelector('input[name="DateFrom"]').value);
                    const dateTo = new Date(document.querySelector('input[name="DateTo"]').value);

                    if (dateFrom > dateTo) {
                        $('#ErrorModel').modal('show');
                        const formattedDateFrom = dateFrom.toISOString().split('T')[0];
                        $('input[name="DateTo"]').val(formattedDateFrom);
                    }
                }
            });

            $('input[name="DateFrom"]').on('keydown blur', function(event) {
                if (event.type === 'blur' || event.key === 'Enter') {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Prevent the default action, which may cause form submission
                    }

                    const dateFrom = new Date(document.querySelector('input[name="DateFrom"]').value);
                    const dateTo = new Date(document.querySelector('input[name="DateTo"]').value);

                    if (dateFrom > dateTo) {
                        $('#ErrorModel').modal('show');
                        const formattedDateFrom = dateFrom.toISOString().split('T')[0];
                        $('input[name="DateTo"]').val(formattedDateFrom);
                    }
                }
            });

            // Count characters in textarea
            let myText = document.getElementById("my-text");
            let result = document.getElementById("count-result");
            myText.addEventListener("input", () => {
                let limit = 1000;
                let count = (myText.value).length;
                document.getElementById("count-result").textContent = `${count} / ${limit}`;
                if (count > limit) {
                    myText.style.borderColor = "#F08080";
                    result.style.color = "#F08080";
                } else {
                    myText.style.borderColor = "#1ABC9C";
                    result.style.color = "#333";
                }
            });
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