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

date_default_timezone_set("Asia/Colombo");
$today = date('Y-m-d');

// Fetch the value from 'tbl_temp_quotation'
$query = "SELECT `Value` FROM `tbl_temp_quotation` ORDER BY `Id` DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $quotationNumber = 'QUTR' . str_pad($row['Value'], 5, '0', STR_PAD_LEFT);
} else {
    $quotationNumber = 'QUTR00001'; // Default value if query fails
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo ($companyName); ?> - Quotation</title>

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
    <link rel="stylesheet" href="assets/css/dark_mode_style.css">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        .tag-cloud {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 5px;
            background-color: #be3235;
            color: #ffff;
            margin-top: 8px;
            width: 100%;
        }

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

        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            /* Allows wrapping of items if there is not enough space */
            gap: 15px;
            /* Adjust spacing between radio buttons as needed */
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-right: 15px;
            /* Space between each radio button */
        }

        .form-check-input {
            margin-top: 0px;
            margin-right: 5px;
            /* Space between radio button and label */
        }

        .form-check-label {
            margin-bottom: 0;
            /* Remove default margin from label */
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
                        <div class="col">
                            <h3 class="page-title">Quotation</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Quotation</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Model Alerts -->
                <div class="modal fade" id="SaveSuccessModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-check-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#26af48;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Success</b></h3>
                                <p>Record Saved Successfully !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="SaveDuplicateModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Record Already Exist !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="SaveFailedModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Record Not Saved !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn2" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="FirstRowErrorModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>The First Row Cannot Remove !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="UnitPriceErrorModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Unit Price Cannot Be Less Than Cost !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="OverUnitPriceErrorModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Enter Price is Greater Than Selling Price !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ProductErrorModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Please Select a Product !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="UnitPriceEmptyModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Please Enter a Unit Price !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="QtyEmptyModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Please Enter a Qty !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ProductDuplicateModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Product is Already Added !</p>
                            </div>
                            <div class="modal-body">
                                <button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn1" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Model Alerts -->

                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid justify-content-center w-100">
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link" href="quotation.php">
                                <i class="fa fa-usd" aria-hidden="true"></i>
                                <span>Whole Sale</span>
                            </a>
                        </li>
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link active" href="quotation2.php">
                                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                <span>Retail Sale</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content profile-tab-cont">
                            <?php
                            require_once '../Controllers/select_controller.php';

                            $db_handle = new DBController();
                            $countryResult = $db_handle->runQuery("SELECT * FROM tbl_customers ORDER BY Customer_Id ASC");
                            $countryResult2 = $db_handle->runQuery("SELECT DISTINCT Product_Id FROM tbl_product_details WHERE Inventort_Updated = 'True' ORDER BY Product_Id ASC");
                            ?>

                            <!-- Whole Sales Tab -->
                            <div class="tab-pane fade show active" id="whole_sale">
                                <!-- Whole Sales Details -->
                                <form method="POST" action="../../API/Quotation/saveQuotation.php" id="quotationForm" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 text-left mt-4">
                                                            <h6 class="page-title">
                                                                <h6 class="text-xs font-weight-bold mb-1">
                                                                    <?php
                                                                    $Id = $fetch['Id'];
                                                                    $First_Name = $fetch['First_Name'];
                                                                    $Last_Name = $fetch['Last_Name'];
                                                                    $combinedQuotationNumber = $quotationNumber . $Id; // Combine Temp Quotation No + User Id
                                                                    ?>
                                                                    <input style="display:none;" type="text" name="Quotation_Id" class="form-control text-right" value="<?php echo $combinedQuotationNumber; ?>" readonly required>
                                                                    <label for="customerSelect" class="mb-0" style="white-space: nowrap;">Customer Name:<label class="text-danger">*</label>
                                                                        <select name="Customer_Id" id="customerSelect" class="form-control select2" required="">
                                                                            <option selected disabled value="">Select Customer</option>
                                                                            <?php
                                                                            if (!empty($countryResult)) {
                                                                                foreach ($countryResult as $key => $value) {
                                                                                    echo '<option value="' . $countryResult[$key]['Customer_Id'] . '">' . $countryResult[$key]['Customer_Name'] . '</option>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                </h6>
                                                                <small class="text-muted customer-outstandings" id="customerOutstandings" style="display: none;">Customer Outstandings (LKR): 0.00</small>
                                                            </h6>
                                                        </div>

                                                        <div class="col-md-6 text-right mt-4">
                                                            <h6 class="page-title">
                                                                <h6 class="text-xs font-weight-bold mb-1">
                                                                    <span>Date: <?php echo $today ?></span>
                                                                </h6>
                                                                <h6 class="text-xs font-weight-bold mb-1">
                                                                    <input style="width: auto; display:none;" type="text" name="User_Id" class="form-control text-right" value="<?php echo $Id; ?>" readonly required>
                                                                    <span>Quote By: <?php echo $First_Name . ' ' . $Last_Name; ?></span>
                                                                </h6>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label class="font-weight-bold">Product Code</label><label class="text-danger">*</label>
                                                            <select class="form-control select2 product-select" name="Product_Id" id="productSelect" required>
                                                                <option selected disabled>Select Product Code</option>
                                                                <?php
                                                                if (!empty($countryResult2)) {
                                                                    foreach ($countryResult2 as $key => $value) {
                                                                        echo '<option value="' . $countryResult2[$key]['Product_Id'] . '">' . $countryResult2[$key]['Product_Id'] . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input style="display:none;" type="number" name="Id" id="Id" class="form-control w-100" min="1" readonly required>
                                                            <label class="font-weight-bold">Product Name</label><label class="text-danger">*</label>
                                                            <input type="text" name="Product_Name" class="form-control product-name w-100" readonly required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="font-weight-bold">Cost Price</label><label class="text-danger">*</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">LKR:</span>
                                                                </div>
                                                                <input type="text" name="Landing_Cost" class="form-control text-right" readonly required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="font-weight-bold">Unit Price</label><label class="text-danger">*</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">LKR:</span>
                                                                </div>
                                                                <input type="text" name="Unit_Price" class="form-control unit-price-input text-right">
                                                            </div>
                                                            <input style="display:none;" type="text" name="Retail_Price" class="form-control text-right" readonly required>
                                                            <input style="display:none;" type="text" name="unit-discount-input" class="form-control text-right" readonly required>
                                                            <small class="text-muted discount-value">Unit Discount (LKR): 0.00</small>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="font-weight-bold">Qty</label><label class="text-danger">*</label>
                                                            <input type="number" name="Qty" class="form-control qty-input w-100" min="1">
                                                            <small class="text-muted remaining-qty">Available Qty: 0</small>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="font-weight-bold">Total Price</label><label class="text-danger">*</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">LKR:</span>
                                                                </div>
                                                                <input type="text" name="Total_Price" class="form-control text-right total-price" readonly required>
                                                            </div>
                                                            <input style="display:none;" type="text" name="Total_Cost" class="form-control text-right total-cost" readonly required>
                                                            <input style="display:none;" type="text" name="Total_Profit" class="form-control text-right total-profit" readonly required>
                                                            <input style="display:none;" type="text" name="total-discount-input" class="form-control text-right" readonly required>
                                                            <small class="text-muted total-discount-value">Total Discount (LKR): 0.00</small>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button style="margin-top:30px;" type="button" class="btn btn bg-success-light add-row mr-2 w-100">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12 text-left mt-4">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="productTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Product Code</th>
                                                                            <th>Product Name</th>
                                                                            <th class="text-right">Cost Price</th>
                                                                            <th class="text-right">Unit Price</th>
                                                                            <th class="text-center">Qty</th>
                                                                            <th class="text-right">Total Price</th>
                                                                            <th class="text-center">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="ng-star-inserted">
                                                                            <td colspan="7" class="text-center py-5 my-xl-3 text-muted"><strong>No Product Added</strong></td>
                                                                        </tr>
                                                                        <!-- Rows will be dynamically added here -->
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="margin-top:17px;" class="row">
                                                        <div class="col-md-4 text-left mt-4">
                                                            <h6 class="text-xs font-weight-bold mb-1">No. of Items: <span id="itemCount" class="font-weight-bold">0</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Item_Count" name="Item_Count" class="form-control text-right" value="0" readonly required>
                                                        </div>
                                                        <div class="col-md-4 text-left mt-4">
                                                            <h6 class="text-xs font-weight-bold mb-1">Sub Total (LKR): <span id="subtotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Sub_Total" name="Sub_Total" class="form-control text-right" readonly required>
                                                        </div>
                                                        <div class="col-md-4 text-right mt-4">
                                                            <h6 class="text-xs font-weight-bold mb-1">Discount (LKR): <span id="discountTotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Discount_Total" name="Discount_Total" class="form-control text-right" readonly required>
                                                        </div>
                                                    </div>

                                                    <div style="margin-top:17px;" class="row">
                                                        <div class="col-md-3 text-left mt-4">
                                                            <input style="width: auto; display:none;" type="text" id="ServiceChargeIsPercentage" name="Service_Charge_Type" class="form-control text-right" readonly required>
                                                            <h6 class="text-xs font-weight-bold mb-1">Service Charge <span id="serviceCharge" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Service_Charge" name="Service_Charge" class="form-control text-right" readonly required>
                                                        </div>
                                                        <div class="col-md-3 text-left mt-4">
                                                            <input style="width: auto; display:none;" type="text" id="TaxIsPercentage" name="Tax_Charge_Type" class="form-control text-right" readonly required>
                                                            <h6 class="text-xs font-weight-bold mb-1" id="taxChargeLabel">Tax <span id="taxCharge" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Tax_Charge" name="Tax_Charge" class="form-control text-right" readonly required>
                                                        </div>
                                                        <div class="col-md-3 text-right mt-4">
                                                            <input style="width: auto; display:none;" type="text" id="VatIsPercentage" name="Vat_Charge_Type" class="form-control text-right" readonly required>
                                                            <h6 class="text-xs font-weight-bold mb-1" id="vatChargeLabel">Vat <span id="vatCharge" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Vat_Charge" name="Vat_Charge" class="form-control text-right" readonly required>
                                                        </div>
                                                        <div class="col-md-3 text-right mt-4">
                                                            <input style="width: auto; display:none;" type="text" id="DeliveryIsPercentage" name="Delivery_Charge_Type" class="form-control text-right" readonly required>
                                                            <h6 class="text-xs font-weight-bold mb-1" id="deliveryChargeLabel">Delivery Charge <span id="deliveryCharge" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Delivery_Charge" name="Delivery_Charge" class="form-control text-right" readonly required>
                                                        </div>
                                                    </div>

                                                    <div style="margin-top:17px;" class="row">
                                                        <div class="col-md-12 text-right mt-4">
                                                            <h6 style="display:none;" class="text-xs font-weight-bold mb-1">Total Profit (LKR): <span id="profitTotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Profit_Total" name="Profit_Total" class="form-control text-right" readonly required>
                                                            <h6 class="text-xs font-weight-bold mb-1">GRAND TOTAL (LKR): <span id="grandTotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Grand_Total" name="Grand_Total" class="form-control text-right" readonly required>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <input style="display:none;" type="text" name="Sale_Type" class="form-control" required="" readonly="true" value="Whole Sale">
                                                                <textarea id="my-text" name="Description" class="form-control" rows="8" placeholder="Enter Description . . ."></textarea>
                                                                <p id="count-result">0/1000</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save & Submit button -->
                                    <div class="form-group text-right">
                                        <button type="submit" id="saveSubmitBtn" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Save & Print</button>
                                    </div>
                                </form>
                                <!-- /Whole Sales Details -->
                            </div>
                            <!-- /Whole Sales Tab -->
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

        <!-- Custom JS -->
        <script src="assets/js/script.js"></script>

        <!-- Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {

                var editingRow = null; // Track the row being edited

                // Initialize Select2
                $('.select2').select2();

                // Customer Select Change Event
                $('#customerSelect').on('change', function() {
                    var customerId = $(this).val();
                    if (customerId) {

                        // Show loader before AJAX call
                        $('#pageLoader').show();

                        $.ajax({
                            url: '../../API/Quotation/searchCustomerQt.php',
                            method: 'GET',
                            data: {
                                Customer_Id: customerId
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#customerOutstandings').text('Customer Outstandings (LKR): ' + response.TotalDue).show();
                            },
                            error: function() {
                                $('#customerOutstandings').text('Customer Outstandings (LKR): Error fetching data').show();
                            },
                            complete: function() {
                                $('#pageLoader').hide();
                            }
                        });
                    } else {
                        $('#customerOutstandings').hide();
                    }
                });

                // Product Select Change Event
                $('#productSelect').on('change', function() {
                    fetchProductData($(this));
                    $('input[name="Qty"]').val('');
                    $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                    $('input[name="total-discount-input"]').val('');
                    $('.total-price').val('').show(); // Show total price with commas
                    $('.total-cost').val('').hide(); // Show total cost with commas
                    $('.total-profit').val('').hide(); // Show total profit with commas
                });

                // Fetch Product Data Function
                function fetchProductData($select) {
                    var productId = $select.val();
                    if (!productId) {
                        $('.remaining-qty').show();
                        $('.discount-value').show();
                        $('.total-discount-value').show();
                        return;
                    }

                    // Show loader before AJAX call
                    $('#pageLoader').show();

                    $.ajax({
                        url: "../../API/Quotation/searchProductDataQt.php",
                        method: "GET",
                        data: {
                            Product_Id: productId
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data.length > 0) {
                                var product = data[0];
                                $('input[name="Id"]').val(product.Id);
                                $('input[name="Product_Name"]').val(product.Product_Name);
                                $('input[name="Landing_Cost"]').val(product.Landing_Cost);
                                $('input[name="Retail_Price"]').val(product.Retail_Price);
                                $('input[name="Unit_Price"]').val(product.Retail_Price);
                                $('.discount-value').text('Unit Discount (LKR): 0.00').show();
                                $('input[name="unit-discount-input"]').val('0.00');
                                $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                                $('input[name="total-discount-input"]').val('');
                                $('.remaining-qty').text(`Available Qty: ${product.SumQty.toLocaleString()}`).show();
                                $('input[name="Qty"]').attr('data-max', product.SumQty);
                            } else {
                                alert("No product data found.");
                            }
                        },
                        error: function(xhr, status, error) {
                            alert("Unauthorized access");
                        },
                        complete: function() {
                            $('#pageLoader').hide();
                        }
                    });
                }

                // Function to fetch and display system details
                function fetchSystemDetails() {

                    $('#pageLoader').show(); // Show loader before sending

                    $.ajax({
                        type: 'GET',
                        url: '../../API/Admin/getSystemConfiguration.php',
                        dataType: 'json',
                        success: function(response) {

                            //Service Charge
                            if (response.ServiceCharge_IsPercentage === "1") {

                                var serviceCharge = parseFloat(response.ServiceCharge); // Ensure serviceCharge is treated as a float
                                var serviceChargeIsPercentage = parseFloat(response.ServiceCharge_IsPercentage);

                                $('#ServiceChargeIsPercentage').val(serviceChargeIsPercentage);

                                // Set the formatted service charge text in the #serviceCharge element
                                $('#serviceCharge').text('(%): ' + serviceCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the service charge with two decimal places and comma separation
                                $('input[name="Service_Charge"]').val(serviceCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));

                            } else {

                                var serviceCharge = parseFloat(response.ServiceCharge); // Ensure serviceCharge is treated as a float
                                var serviceChargeIsPercentage = parseFloat(response.ServiceCharge_IsPercentage);

                                $('#ServiceChargeIsPercentage').val(serviceChargeIsPercentage);

                                // Set the formatted service charge text in the #serviceCharge element
                                $('#serviceCharge').text('(LKR): ' + serviceCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the service charge with two decimal places and comma separation
                                $('input[name="Service_Charge"]').val(serviceCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                            }

                            // Tax Charge
                            if (response.Tax_IsPercentage === "1") {

                                var taxCharge = parseFloat(response.Tax); // Ensure taxCharge is treated as a float
                                var taxChargeIsPercentage = parseFloat(response.Tax_IsPercentage);

                                $('#TaxIsPercentage').val(taxChargeIsPercentage);

                                // Set the formatted tax charge text in the #taxCharge element
                                $('#taxCharge').text('(%): ' + taxCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the tax charge with two decimal places and comma separation
                                $('input[name="Tax_Charge"]').val(taxCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));


                            } else {
                                var taxCharge = parseFloat(response.Tax); // Ensure taxCharge is treated as a float
                                var taxChargeIsPercentage = parseFloat(response.Tax_IsPercentage);

                                $('#TaxIsPercentage').val(taxChargeIsPercentage);

                                // Set the formatted service charge text in the #taxCharge element
                                $('#taxCharge').text('(LKR): ' + taxCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the tax charge with two decimal places and comma separation
                                $('input[name="Tax_Charge"]').val(taxCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                            }

                            // VAT Charge
                            if (response.Vat_IsPercentage === "1") {

                                var vatCharge = parseFloat(response.Vat); // Ensure vatCharge is treated as a float
                                var vatChargeIsPercentage = parseFloat(response.Vat_IsPercentage);

                                $('#VatIsPercentage').val(vatChargeIsPercentage);

                                // Set the formatted vat charge text in the #vatCharge element
                                $('#vatCharge').text('(%): ' + vatCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the vat charge with two decimal places and comma separation
                                $('input[name="Vat_Charge"]').val(vatCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));

                            } else {
                                var vatCharge = parseFloat(response.Vat); // Ensure vatCharge is treated as a float
                                var vatChargeIsPercentage = parseFloat(response.Vat_IsPercentage);

                                $('#VatIsPercentage').val(vatChargeIsPercentage);

                                // Set the formatted vat charge text in the #vatCharge element
                                $('#vatCharge').text('(LKR): ' + vatCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the vat charge with two decimal places and comma separation
                                $('input[name="Vat_Charge"]').val(vatCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                            }

                            // Delivery Charge
                            if (response.Delivery_IsPercentage === "1") {

                                var deliveryCharge = parseFloat(response.Delivery); // Ensure deliveryCharge is treated as a float
                                var deliveryChargeIsPercentage = parseFloat(response.Delivery_IsPercentage);

                                $('#DeliveryIsPercentage').val(deliveryChargeIsPercentage);

                                // Set the formatted delivery charge text in the #deliveryCharge element
                                $('#deliveryCharge').text('(%): ' + deliveryCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the delivery charge with two decimal places and comma separation
                                $('input[name="Delivery_Charge"]').val(deliveryCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));

                            } else {
                                var deliveryCharge = parseFloat(response.Delivery); // Ensure deliveryCharge is treated as a float
                                var deliveryChargeIsPercentage = parseFloat(response.Delivery_IsPercentage);

                                $('#DeliveryIsPercentage').val(deliveryChargeIsPercentage);

                                // Set the formatted delivery charge text in the #deliveryCharge element
                                $('#deliveryCharge').text('(LKR): ' + deliveryCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })).show();

                                // Format the delivery charge with two decimal places and comma separation
                                $('input[name="Delivery_Charge"]').val(deliveryCharge.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', status, error);
                        },
                        complete: function () {
                            $('#pageLoader').hide(); // Hide loader after response (success or error)
                        }
                    });
                }

                fetchSystemDetails();

                // Handle unit price input validation
                $(document).on('input', '.unit-price-input', function() {

                    var $input = $(this);
                    var value = $input.val().replace(/[^0-9.,]/g, ''); // Remove non-numeric characters except comma and dot
                    $input.val(value);

                    // // Check if a product is selected
                    if (!$('.product-name').val()) {
                        $input.val(''); // Clear invalid quantity input
                        return;
                    }

                    // Validate the entered quantity
                    if (isNaN(enteredValue) || enteredValue < 1) {
                        $input.val('');
                    }
                });

                // Handle Unit Price input validation with alert
                $(document).on('blur keydown', '.unit-price-input', function(e) {

                    // Only trigger on blur or enter key
                    if (e.type === 'keydown' && e.key !== 'Enter') {
                        return;
                    }

                    var maxQty = parseInt($('input[name="Qty"]').attr('data-max'));
                    var wholesalePrice = parseFloat($('input[name="Retail_Price"]').val().replace(/,/g, ''));
                    var landingCost = parseFloat($('input[name="Landing_Cost"]').val().replace(/,/g, ''));
                    var enteredValue = parseFloat($(this).val().replace(/,/g, ''));

                    $(this).val(enteredValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

                    var unitDiscount = wholesalePrice - enteredValue;

                    if (isNaN(enteredValue)) {
                        $(this).val('');
                    } else if (enteredValue < landingCost) {
                        $('#UnitPriceErrorModel').modal('show');
                        $(this).val(wholesalePrice.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('.discount-value').text('Unit Discount (LKR): 0.00').show();
                        $('input[name="unit-discount-input"]').val('0.00');
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.qty-input').val('').show();
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').hide(); // Show total cost with commas
                        $('.total-profit').val('').hide(); // Show total profit with commas
                    } else if (enteredValue > wholesalePrice) {
                        $('#OverUnitPriceErrorModel').modal('show');
                        $(this).val(wholesalePrice.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('.discount-value').text('Unit Discount (LKR): 0.00').show();
                        $('input[name="unit-discount-input"]').val('0.00');
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.qty-input').val('').show();
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').hide(); // Show total cost with commas
                        $('.total-profit').val('').hide(); // Show total profit with commas
                    } else {
                        $('.discount-value').text(`Unit Discount (LKR): ${(unitDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
                        $('input[name="unit-discount-input"]').val(unitDiscount.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.qty-input').val('').show();
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').hide(); // Show total cost with commas
                        $('.total-profit').val('').hide(); // Show total profit with commas
                    }

                });

                // Handle Qty input validation
                $(document).on('input', '.qty-input', function() {
                    var maxQty = parseInt($('input[name="Qty"]').attr('data-max'));
                    var enteredQty = parseInt($(this).val());

                    var cost = parseFloat($('input[name="Landing_Cost"]').val().replace(/,/g, ''));
                    var wholesalePrice = parseFloat($('input[name="Retail_Price"]').val().replace(/,/g, ''));
                    var unitPrice = parseFloat($('input[name="Unit_Price"]').val().replace(/,/g, ''));

                    var totalCost = cost * enteredQty;
                    var totalPrice = unitPrice * enteredQty;

                    var unitProfit = totalPrice - totalCost;
                    var totalDiscount = (wholesalePrice - unitPrice) * enteredQty;

                    // Check if a product is selected
                    if (!$('.product-name').val()) {
                        $(this).val(''); // Clear invalid quantity input
                        return;
                    }

                    if (!$('.unit-price-input').val()) {
                        $(this).val(''); // Clear invalid quantity input
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show();
                        return;
                    }

                    // Validate the entered quantity
                    if (isNaN(enteredQty) || enteredQty < 1) {
                        $(this).val('');
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').hide(); // Show total cost with commas
                        $('.total-profit').val('').hide(); // Show total profit with commas
                    }  
                    else {
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-discount-value').text(`Total Discount (LKR): ${(totalDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
                        $('input[name="total-discount-input"]').val(totalDiscount.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                        $('.total-price').val(`${(unitPrice * enteredQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show total price with commas
                        $('.total-cost').val(`${(cost * enteredQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).hide(); // Show total cost with commas
                        $('.total-profit').val(`${(unitProfit).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).hide(); // Show total profit with commas
                    }
                });

                $(".add-row").click(function() {

                    $("#productTable tbody tr").removeClass("table-danger"); // Remove highlight from other rows
                    var productId = $("#productSelect").val();
                    var Id = $("#Id").val();
                    var productName = $("input[name='Product_Name']").val();
                    var landingCost = $("input[name='Landing_Cost']").val();
                    var unitPrice = $("input[name='Unit_Price']").val();
                    var wholesalePrice = $("input[name='Retail_Price']").val();
                    var unitDiscount = $("input[name='unit-discount-input']").val();
                    var qty = $("input[name='Qty']").val();
                    var totalPrice = $("input[name='Total_Price']").val();
                    var totalCost = $("input[name='Total_Cost']").val();
                    var totalProfit = $("input[name='Total_Profit']").val();
                    var totalDiscount = $("input[name='total-discount-input']").val();

                    if (!productId || !productName) {
                        $('#ProductErrorModel').modal('show');
                        return;
                    }

                    if (!unitPrice) {
                        $('#UnitPriceEmptyModel').modal('show');
                        return;
                    }

                    if (!qty || qty <= 0) {
                        $('#QtyEmptyModel').modal('show');
                        return;
                    }

                    //Remove "No Product Added" message if present
                    if ($("#productTable tbody").find("td.text-muted").length > 0) {
                        $("#productTable tbody").empty(); // Clears the message
                    }

                    let existingRow = $("#productTable tbody tr").filter(function() {
                        return $(this).find("input[name='Product_Id[]']").val() === productId;
                    });

                    if (existingRow.length > 0) {
                        // If product already exists and is being edited, update the existing row
                        existingRow.find("input[name='Id[]']").val(Id);
                        existingRow.find("input[name='Product_Name[]']").val(productName);
                        existingRow.find("input[name='Landing_Cost[]']").val(landingCost);
                        existingRow.find("input[name='Unit_Price[]']").val(unitPrice);
                        existingRow.find("input[name='Retail_Price[]']").val(wholesalePrice);
                        existingRow.find("input[name='unit-discount-input[]']").val(unitDiscount);
                        existingRow.find("input[name='Qty[]']").val(qty);
                        existingRow.find("input[name='Total_Price[]']").val(totalPrice);
                        existingRow.find("input[name='Total_Cost[]']").val(totalCost);
                        existingRow.find("input[name='Total_Profit[]']").val(totalProfit);
                        existingRow.find("input[name='total-discount-input[]']").val(totalDiscount);

                        // Update the visible labels
                        existingRow.find("td:nth-child(2)").contents().last().replaceWith(` ${productName}`);
                        existingRow.find("td:nth-child(5)").contents().last().replaceWith(` ${qty}`);
                        existingRow.find("td:nth-child(6)").contents().last().replaceWith(` ${totalPrice}`);

                        updateTotals();
                        resetForm();
                        return;
                    }

                    // Append new row if product is not already in table
                    var newRow = `
                    <tr>
                        <td>
                            <input style="display:none;" type="text" name="Quotation_No[]" class="form-control" value="<?php echo $combinedQuotationNumber; ?>" readonly>
                            <input style="display:none;" type="text" name="Product_Id[]" class="form-control" value="${productId}" readonly>
                            ${productId}
                        </td>
                        <td>
                            <input style="display:none;" type="number" name="Id[]" class="form-control" min="1" value="${Id}" readonly>
                            <input style="display:none;" type="text" name="Product_Name[]" class="form-control" value="${productName}" readonly>
                            ${productName}
                        </td>
                        <td class="text-right">
                            <input style="display:none;" type="text" name="Landing_Cost[]" class="form-control" value="${landingCost}" readonly>
                            ${landingCost}
                        </td>
                        <td class="text-right">
                            <input style="display:none;" type="text" name="Unit_Price[]" class="form-control" value="${unitPrice}" readonly>
                            <input style="display:none;" type="text" name="Retail_Price[]" class="form-control" value="${wholesalePrice}" readonly>
                            <input style="display:none;" type="text" name="unit-discount-input[]" class="form-control" value="${unitDiscount}" readonly>
                            ${unitPrice}
                        </td>
                        <td class="text-center">
                            <input style="display:none;" type="number" name="Qty[]" class="form-control" value="${qty}" readonly>
                            ${qty}
                        </td>
                        <td class="text-right">
                            <input style="display:none;" type="text" name="Total_Price[]" class="form-control" value="${totalPrice}" readonly>
                            <input style="display:none;" type="text" name="Total_Cost[]" class="form-control" value="${totalCost}" readonly>
                            <input style="display:none;" type="text" name="Total_Profit[]" class="form-control" value="${totalProfit}" readonly>
                            <input style="display:none;" type="text" name="total-discount-input[]" class="form-control" value="${totalDiscount}" readonly>
                            ${totalPrice}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn bg-primary-light btn-sm edit-row"><i class="fe fe-pencil"></i></button>
                            <button type="button" class="btn btn bg-danger-light btn-sm remove-row"><i class="fe fe-trash"></i></button>
                        </td>
                    </tr>
                `;
                    $("#productTable tbody").append(newRow);
                    let rowCount = $("#productTable tbody tr").length;
                    updateRowCount(rowCount);
                    updateTotals();
                    resetForm();
                });

                // Edit Row
                $("#productTable").on("click", ".edit-row", function() {

                    var maxQty = parseInt($('input[name="Qty"]').attr('data-max'));

                    let row = $(this).closest("tr");

                    // Highlight the selected row
                    row.addClass("table-danger"); // Add highlight to the clicked row

                    // Fetch values from the row
                    let productId = row.find('input[name="Product_Id[]"]').val();
                    let Id = row.find('input[name="Id[]"]').val();
                    let productName = row.find('input[name="Product_Name[]"]').val();
                    let landingCost = row.find('input[name="Landing_Cost[]"]').val();
                    let unitPrice = row.find('input[name="Unit_Price[]"]').val();
                    let wholesalePrice = row.find('input[name="Retail_Price[]"]').val();
                    let unitDiscount = row.find('input[name="unit-discount-input[]"]').val();
                    let qty = row.find('input[name="Qty[]"]').val();
                    let remaingQty = maxQty - qty;
                    let totalPrice = row.find('input[name="Total_Price[]"]').val();
                    let totalCost = row.find('input[name="Total_Cost[]"]').val();
                    let totalProfit = row.find('input[name="Total_Profit[]"]').val();
                    let totalDiscount = row.find('input[name="total-discount-input[]"]').val();

                    // Populate values in the respective fields
                    $("#productSelect").val(productId).trigger("change.select2");
                    $("input[name='Id']").val(Id);
                    $("input[name='Product_Name']").val(productName);
                    $("input[name='Landing_Cost']").val(landingCost);
                    $("input[name='Unit_Price']").val(unitPrice);
                    $("input[name='Retail_Price']").val(wholesalePrice);
                    $("input[name='unit-discount-input']").val(unitDiscount);
                    $("input[name='Qty']").val(qty);
                    $('.remaining-qty').text(`Available Qty: ${remaingQty.toLocaleString()}`).show();
                    $("input[name='Total_Price']").val(totalPrice);
                    $("input[name='Total_Cost']").val(totalCost);
                    $("input[name='Total_Profit']").val(totalProfit);
                    $("input[name='total-discount-input']").val(totalDiscount);

                    // Store row reference to update it later
                    editingRow = row;
                });

                // Reset editingRow on form reset
                function resetForm() {
                    editingRow = null;
                }

                // Remove Row
                $("#productTable").on("click", ".remove-row", function() {
                    $(this).closest("tr").remove();

                    let rowCount = $("#productTable tbody tr").length;

                    if (rowCount === 0 || $("#productTable tbody tr:visible").length === 0) {
                        $("#productTable tbody").html('<tr><td colspan="7" class="text-center py-5 my-xl-3 text-muted"><strong>No Product Added</strong></td></tr>');
                        rowCount = 0; // Ensure Item Count is set to 0

                        // Reset totals when no products are left
                        resetTotals();
                    }

                    updateRowCount(rowCount);
                    updateTotals();
                });

                // Reset form fields
                function resetForm() {
                    $("#productSelect").val('Select Product Code').trigger('change'); // Reset Select2 dropdown
                    $("input[name='Id'], input[name='Product_Name'], input[name='Landing_Cost'], input[name='Unit_Price'], input[name='Retail_Price'], input[name='unit-discount-input'], input[name='Qty'], input[name='Total_Price']").val('');
                    $('.discount-value').text(`Unit Discount (LKR): 0.00`).show();
                    $('.remaining-qty').text(`Available Qty: 0`).show();
                }

                // Calculate and update row count
                function updateRowCount(count) {
                    $('#itemCount').text(count);
                    $('#Item_Count').val(count);
                }

                // Function to reset the totals to default values
                function resetTotals() {
                    $('#subtotal').text("0.00");
                    $('#Sub_Total').val('');
                    $('#discountTotal').text("0.00");
                    $('#Discount_Total').val('');
                    $('#profitTotal').text("0.00");
                    $('#Profit_Total').val('');
                    $('#grandTotal').text("0.00");
                    $('#Grand_Total').val('');
                }

                // Calculate and update totals
                function updateTotals() {
                    var subTotal = 0;
                    var discountTotal = 0;
                    var profitTotal = 0;
                    var grandTotal = 0;

                    $('#productTable tbody tr').each(function() {
                        var qty = parseInt($(this).find('input[name="Qty[]"]').val()) || 0;
                        var unitPrice = parseFloat($(this).find('input[name="Retail_Price[]"]').val().replace(/,/g, '')) || 0;
                        var totalPrice = parseFloat($(this).find('input[name="Total_Price[]"]').val().replace(/,/g, '')) || 0;
                        var totalProfit = parseFloat($(this).find('input[name="Total_Profit[]"]').val().replace(/,/g, '')) || 0;
                        var totalDiscountText = parseFloat($(this).find('input[name="total-discount-input[]"]').val().replace(/,/g, '')) || 0;

                        subTotal += unitPrice * qty;
                        discountTotal += totalDiscountText;
                        profitTotal += totalProfit;
                        grandTotal += totalPrice;

                    });

                    var serviceChargeIsPercentage = $('#ServiceChargeIsPercentage').val();
                    var taxIsPercentage = $('#TaxIsPercentage').val();
                    var vatIsPercentage = $('#VatIsPercentage').val();
                    var deliveryIsPercentage = $('#DeliveryIsPercentage').val();

                    // Get Additional Charges
                    var tempServiceCharge = parseFloat($('#Service_Charge').val().replace(/,/g, '')) || 0;
                    var tempTaxCharge = parseFloat($('#Tax_Charge').val().replace(/,/g, '')) || 0;
                    var tempVatCharge = parseFloat($('#Vat_Charge').val().replace(/,/g, '')) || 0;
                    var tempDeliveryCharge = parseFloat($('#Delivery_Charge').val().replace(/,/g, '')) || 0;

                    var serviceCharge = (serviceChargeIsPercentage === "1") ? (tempServiceCharge / 100) * grandTotal : tempServiceCharge;
                    var taxCharge = (taxIsPercentage === "1") ? (tempTaxCharge / 100) * grandTotal : tempTaxCharge;
                    var vatCharge = (vatIsPercentage === "1") ? (tempVatCharge / 100) * grandTotal : tempVatCharge;
                    var deliveryCharge = (deliveryIsPercentage === "1") ? (tempDeliveryCharge / 100) * grandTotal : tempDeliveryCharge;

                    // Add Additional Charges to Grand Total
                    grandTotal += serviceCharge + taxCharge + vatCharge + deliveryCharge;

                    $('#subtotal').text(subTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#Sub_Total').val(subTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#discountTotal').text(discountTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#Discount_Total').val(discountTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#profitTotal').text(profitTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#Profit_Total').val(profitTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#grandTotal').text(grandTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#Grand_Total').val(grandTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                }

                $(document).on('input', '.paid-amount-input', function() {
                    var $input = $(this);
                    var value = $input.val().replace(/[^0-9.,]/g, ''); // Remove all non-numeric characters except dot and comma
                    $input.val(value); // Set the cleaned value back to the input
                });

                // Function to show and hide alerts based on response
                function showSaveAlerts(response) {
                    if (response.success === 'true') {
                        // Show SaveSuccessModel only if success is true
                        $('#SaveSuccessModel').modal('show');
                    } else if (response.success === 'false' && response.error === 'duplicate') {
                        // Show SaveDuplicateModel only if success is false and error is duplicate
                        $('#SaveDuplicateModel').modal('show');
                    } else {
                        // Show SaveFailedModel for any other failure scenario
                        $('#SaveFailedModel').modal('show');
                    }
                }

                // Function to add a new invoice
                $('#quotationForm').submit(function(event) {

                    event.preventDefault();

                    $('#pageLoader').show(); // Show loader before sending

                    $.ajax({
                        type: 'POST',
                        url: '../../API/Quotation/saveQuotation.php',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.success === 'string') {
                                response = JSON.parse(response);
                            }

                            // Show the appropriate modal based on response
                            showSaveAlerts(response);

                            // Log the response for debugging
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', status, error);
                            $('#SaveFailedModel').modal('show');
                        },
                        complete: function () {
                            $('#pageLoader').hide(); // Hide loader after response (success or error)
                        }
                    });
                });

                // Handle the "Ok" button click in the SaveSuccessModel
                $('#OkBtn').on('click', function() {
                    // Refresh the page when "Ok" is clicked
                    var quotationNumber = encodeURIComponent('<?php echo $combinedQuotationNumber; ?>');
                    window.location.href = 'printQuotation.php?Quotation_No=' + quotationNumber;
                });

                // Character Count for Description Textarea
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