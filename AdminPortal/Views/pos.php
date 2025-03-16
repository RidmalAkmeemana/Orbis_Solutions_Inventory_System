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

    // Fetch the value from 'tbl_temp_invoice'
    $query = "SELECT `Value` FROM `tbl_temp_invoice` ORDER BY `Id` DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $invoiceNumber = 'INVW' . str_pad($row['Value'], 5, '0', STR_PAD_LEFT);
    } else {
        $invoiceNumber = 'INVW00001'; // Default value if query fails
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo($companyName); ?> - POS</title>
    
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

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        .tag-cloud {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 5px;
            background-color: #be3235;
            color:#ffff;
            margin-top: 8px;
            width: 100%;
        }
        .select2-container--default .select2-selection--single {
            height: 38px; /* Adjust this value as needed */
            padding: 6px;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px; /* Adjust to align text vertically */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px; /* Adjust this value to match the height */
        }

        .select2-dropdown {
            max-height: 300px; /* Adjust the dropdown height */
            overflow-y: auto;
        }

        .payment-methods {
            display: flex;
            flex-wrap: wrap; /* Allows wrapping of items if there is not enough space */
            gap: 15px; /* Adjust spacing between radio buttons as needed */
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-right: 15px; /* Space between each radio button */
        }

        .form-check-input {
            margin-top:0px;
            margin-right: 5px; /* Space between radio button and label */
        }

        .form-check-label {
            margin-bottom: 0; /* Remove default margin from label */
        }
    </style>
</head>
<body>
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
                            <h3 class="page-title">POS System</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">POS System</li>
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

                <div class="modal fade" id="QtyErrorModel" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center">
                            <div class="modal-body mt-4">
                                <i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
                                <h3 class="modal-title"><b>Error</b></h3>
                                <p>Entered Qty Exceeds Available Qty !</p>
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
                            <a class="nav-link active" href="pos.php">
                                <i class="fa fa-usd" aria-hidden="true"></i>
                                <span>Whole Sale</span>
                            </a>
                        </li>
                        <li class="nav-item flex-fill text-center">
                            <a class="nav-link" href="pos2.php">
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
                                <form method="POST" action="../../API/POS/saveInvoice.php" id="invoiceForm" enctype="multipart/form-data">
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
                                                                        $combinedInvoiceNumber = $invoiceNumber . $Id; // Combine Temp Invoice No + User Id
                                                                    ?>
                                                                    <label for="customerSelect" class="mb-0" style="white-space: nowrap;">Invoice No:<label class="text-danger">*</label>
                                                                    <input style="display:;" type="text" name="Invoice_Id" class="form-control text-right" value="<?php echo $combinedInvoiceNumber; ?>" readonly required>    
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
                                                                    <span>Invoice By: <?php echo $First_Name . ' ' . $Last_Name; ?></span>
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
                                                            <label class="font-weight-bold">Item Id</label><label class="text-danger">*</label>
                                                            <input style="display:;" type="number" name="Id" id="Id" class="form-control w-100" min="1" readonly required>
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
                                                                <input type="text" name="Unit_Price" class="form-control unit-price-input text-right" required>
                                                            </div>
                                                            <label class="font-weight-bold">Wholesale Price</label><label class="text-danger">*</label>
                                                            <input style="display:;" type="text" name="Wholesale_Price" class="form-control text-right" readonly required>
                                                            <label class="font-weight-bold">Unit Discount</label><label class="text-danger">*</label>
                                                            <input style="display:;" type="text" name="unit-discount-input" class="form-control text-right" readonly required>
                                                            <small class="text-muted discount-value">Unit Discount (LKR): 0.00</small>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="font-weight-bold">Qty</label><label class="text-danger">*</label>
                                                            <input type="number" name="Qty" class="form-control qty-input w-100" min="1" required>
                                                            <small class="text-muted remaining-qty">Available Qty: 0</small>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="font-weight-bold">Total Price</label><label class="text-danger">*</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">LKR:</span>
                                                                    <input type="text" name="Total_Price" class="form-control text-right total-price" readonly required>
                                                                </div>
                                                            </div>
                                                            <label class="font-weight-bold">Total Cost</label><label class="text-danger">*</label>
                                                            <input style="display:;" type="text" name="Total_Cost" class="form-control text-right total-cost" readonly required>
                                                            <label class="font-weight-bold">Total Profit</label><label class="text-danger">*</label>
                                                            <input style="display:;" type="text" name="Total_Profit" class="form-control text-right total-profit" readonly required>
                                                            <label class="font-weight-bold">Total Discount</label><label class="text-danger">*</label>
                                                            <input style="display:;" type="text" name="total-discount-input" class="form-control text-right" readonly required>
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
                                                        <div class="col-md-3 text-left mt-4">
                                                            <h6 class="text-xs font-weight-bold mb-1">No. of Items: <span id="itemCount" class="font-weight-bold">0</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Item_Count" name="Item_Count" class="form-control text-right" value="1" readonly required>
                                                        </div>
                                                        <div class="col-md-3 text-left mt-4">
                                                            <h6 class="text-xs font-weight-bold mb-1">Sub Total (LKR): <span id="subtotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Sub_Total" name="Sub_Total" class="form-control text-right" readonly required>
                                                        </div>
                                                        <div class="col-md-3 text-right mt-4">
                                                            <h6 class="text-xs font-weight-bold mb-1">Discount (LKR): <span id="discountTotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Discount_Total" name="Discount_Total" class="form-control text-right" readonly required>
                                                        </div>
                                                        <div class="col-md-3 text-right mt-4">
                                                            <h6 style="display:none;" class="text-xs font-weight-bold mb-1">Total Profit (LKR): <span id="profitTotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Profit_Total" name="Profit_Total" class="form-control text-right" readonly required>
                                                            <h6 class="text-xs font-weight-bold mb-1">GRAND TOTAL (LKR): <span id="grandTotal" class="font-weight-bold">0.00</span></h6>
                                                            <input style="width: auto; display:none;" type="text" id="Grand_Total" name="Grand_Total" class="form-control text-right" readonly required>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 text-left mt-4">
                                                            <h5 class="page-title">
                                                                <h5 class="tag-cloud text-xs font-weight-bold mb-1">Payments Section</h5>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <!-- Paid Amount -->
                                                        <div class="col-md-4 mt-4">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Paid Amount (LKR:)</label><label class="text-danger">*</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">LKR:</span>
                                                                        <input style="width: auto;" type="number" name="Paid_Amount" class="form-control paid-amount-input text-right" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Balance Amount -->
                                                        <div class="col-md-4 mt-4">
                                                            <div class="form-group">
                                                                <h6 class="text-xs font-weight-bold mb-1">Balance Amount (LKR): <span id="balanceTotal" class="font-weight-bold">0.00</span></h6>
                                                                <div class="input-group">
                                                                    <input style="width: auto; display:none;" type="text" id="Balance_Total" name="Balance_Total" class="form-control text-right" readonly required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Due Amount -->
                                                        <div class="col-md-4 mt-4">
                                                            <div class="form-group">
                                                                <h6 class="text-xs font-weight-bold mb-1">Due Amount (LKR): <span id="dueTotal" class="font-weight-bold">0.00</span></h6>
                                                                <div class="input-group">
                                                                    <input style="width: auto; display:none;" type="text" id="Due_Total" name="Due_Total" class="form-control text-right" readonly required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 mt-4">
                                                            <div class="form-group">
                                                                <input style="display:none;" type="text" name="Sale_Type" class="form-control" required="" readonly="true" value="Retail Sale">
                                                                <label class="font-weight-bold">Payment Method</label><label class="text-danger">*</label>
                                                                <div class="payment-methods">
                                                                    <div class="form-check">
                                                                        <input type="radio" id="paymentCash" name="Payment_Type" class="form-check-input payment-method" value="Cash" checked>
                                                                        <label class="form-check-label" for="paymentCash">Cash</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" id="paymentCard" name="Payment_Type" class="form-check-input payment-method" value="Card">
                                                                        <label class="form-check-label" for="paymentCard">Card</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" id="paymentCheque" name="Payment_Type" class="form-check-input payment-method" value="Cheque">
                                                                        <label class="form-check-label" for="paymentCheque">Cheque</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" id="paymentBankTransfer" name="Payment_Type" class="form-check-input payment-method" value="Bank Transfer">
                                                                        <label class="form-check-label" for="paymentBankTransfer">Bank Transfer</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input type="radio" id="paymentNotSelected" name="Payment_Type" class="form-check-input payment-method" value="N/A" disabled>
                                                                        <label class="form-check-label" for="paymentNotSelected">Not Selected</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Description</label>
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
        <script  src="assets/js/script.js"></script>

        <!-- Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <script>
            $(document).ready(function () {
                // Initialize Select2
                $('.select2').select2();

                // Customer Select Change Event
                $('#customerSelect').on('change', function () {
                    var customerId = $(this).val();
                    if (customerId) {
                        $.ajax({
                            url: '../../API/POS/searchCustomer.php',
                            method: 'GET',
                            data: { Customer_Id: customerId },
                            dataType: 'json',
                            success: function (response) {
                                $('#customerOutstandings').text('Customer Outstandings (LKR): ' + response.TotalDue).show();
                            },
                            error: function () {
                                $('#customerOutstandings').text('Customer Outstandings (LKR): Error fetching data').show();
                            }
                        });
                    } else {
                        $('#customerOutstandings').hide();
                    }
                });

                // Product Select Change Event
                $('#productSelect').on('change', function () {
                    fetchProductData($(this));
                    $('input[name="Qty"]').val('');
                    $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                    $('input[name="total-discount-input"]').val('');
                    $('.total-price').val('').show(); // Show total price with commas
                    $('.total-cost').val('').show(); // Show total cost with commas
                    $('.total-profit').val('').show(); // Show total profit with commas
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

                    $.ajax({
                        url: "../../API/POS/searchProductData.php",
                        method: "GET",
                        data: { Product_Id: productId },
                        dataType: "json",
                        success: function (data) {
                            if (data.length > 0) {
                                var product = data[0];
								$('input[name="Id"]').val(product.Id);
                                $('input[name="Product_Name"]').val(product.Product_Name);
                                $('input[name="Landing_Cost"]').val(product.Landing_Cost);
                                $('input[name="Wholesale_Price"]').val(product.Wholesale_Price);
                                $('input[name="Unit_Price"]').val(product.Wholesale_Price);
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
                        error: function (xhr, status, error) {
                            alert("Unauthorized access");
                        }
                    });
                }

                // Handle unit price input validation
				$(document).on('input', '.unit-price-input', function () {

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
                $(document).on('blur keydown', '.unit-price-input', function (e) {
                    
					// Only trigger on blur or enter key
                    if (e.type === 'keydown' && e.key !== 'Enter') {
                        return;
                    }

                    var maxQty = parseInt($('input[name="Qty"]').attr('data-max'));
                    var wholesalePrice = parseFloat($('input[name="Wholesale_Price"]').val().replace(/,/g, ''));
                    var landingCost = parseFloat($('input[name="Landing_Cost"]').val().replace(/,/g, ''));
                    var enteredValue = parseFloat($(this).val().replace(/,/g, ''));

                    var unitDiscount = wholesalePrice - enteredValue;

                    if (isNaN(enteredValue)) {
                        $(this).val('');
                    } else if (enteredValue < landingCost) {
                        // alert(`Unit Price Cannot Be Less Than Cost`);
                        $('#UnitPriceErrorModel').modal('show');
                        $(this).val(wholesalePrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('.discount-value').text('Unit Discount (LKR): 0.00').show();
                        $('input[name="unit-discount-input"]').val('0.00');
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.qty-input').val('').show();
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').show(); // Show total cost with commas
                        $('.total-profit').val('').show(); // Show total profit with commas
                    } else if (enteredValue > wholesalePrice) {
                        $('#OverUnitPriceErrorModel').modal('show');
                        $(this).val(wholesalePrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('.discount-value').text('Unit Discount (LKR): 0.00').show();
                        $('input[name="unit-discount-input"]').val('0.00');
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.qty-input').val('').show();
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').show(); // Show total cost with commas
                        $('.total-profit').val('').show(); // Show total profit with commas
                    } else {
                        $('.discount-value').text(`Unit Discount (LKR): ${(unitDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
                        $('input[name="unit-discount-input"]').val(unitDiscount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.qty-input').val('').show();
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').show(); // Show total cost with commas
                        $('.total-profit').val('').show(); // Show total profit with commas
                    }
                    
                });

                // Handle Qty input validation
                $(document).on('input', '.qty-input', function () {
                    var maxQty = parseInt($('input[name="Qty"]').attr('data-max'));
                    var enteredQty = parseInt($(this).val());

                    var cost = parseFloat($('input[name="Landing_Cost"]').val().replace(/,/g, ''));
                    var wholesalePrice = parseFloat($('input[name="Wholesale_Price"]').val().replace(/,/g, ''));
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
                        $('.total-cost').val('').show(); // Show total cost with commas
                        $('.total-profit').val('').show(); // Show total profit with commas
                    } else if (enteredQty > maxQty) {
                        $(this).val('');
                        $('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show();
                        $('#QtyErrorModel').modal('show');
                        $('.total-discount-value').text('Total Discount (LKR): 0.00').show();
                        $('input[name="total-discount-input"]').val('');
                        $('.total-price').val('').show(); // Show total price with commas
                        $('.total-cost').val('').show(); // Show total cost with commas
                        $('.total-profit').val('').show(); // Show total profit with commas
                        //updateTotals(); // Update row count when quantity changes
                    } else {
                        $('.remaining-qty').text(`Available Qty: ${(maxQty - enteredQty).toLocaleString()}`).show(); // Show available qty with commas
                        $('.total-discount-value').text(`Total Discount (LKR): ${(totalDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
                        $('input[name="total-discount-input"]').val(totalDiscount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('.total-price').val(`${(unitPrice * enteredQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show total price with commas
                        $('.total-cost').val(`${(cost * enteredQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show total cost with commas
                        $('.total-profit').val(`${(unitProfit).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show total profit with commas
                        //updateTotals(); // Update row count when quantity changes
                    }
                });

                $(".add-row").click(function () {
                    var productId = $("#productSelect").val();
                    var productName = $(".product-name").val();
                    var landingCost = $("input[name='Landing_Cost']").val();
                    var unitPrice = $("input[name='Unit_Price']").val();
                    var qty = $("input[name='Qty']").val();
                    var totalPrice = $("input[name='Total_Price']").val();

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

                    // Check if the product already exists in the table
                    var exists = false;
                    $("#productTable tbody tr").each(function () {
                        var rowProductId = $(this).find("td:first").text();
                        if (rowProductId === productId) {
                            exists = true;
                            $('#ProductDuplicateModel').modal('show');
                            return false;
                        }
                    });

                    if (exists) return;

                    // Remove "No Product Added" row if exists
                    $("#productTable tbody tr:first-child:contains('No Product Added')").remove();

                    // Append row to table
                    var newRow = `
                        <tr>
                            <td>${productId}</td>
                            <td>${productName}</td>
                            <td class="text-right">${landingCost}</td>
                            <td class="text-right">${unitPrice}<input style="display:none;" class="form-control text-right unit-price-edit" value="${unitPrice}" min="0"></td>
                            <td class="text-center">${qty}<input style="display:none;" type="number" class="form-control text-right qty-edit" value="${qty}" min="1"></td>
                            <td class="text-right">${totalPrice}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn bg-primary-light btn-sm edit-row"><i class="fe fe-pencil"></i></button>
                                <button type="button" class="btn btn bg-danger-light btn-sm remove-row"><i class="fe fe-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    $("#productTable tbody").append(newRow);

                    // Reset form fields
                    $("#productSelect").val('Select Product Code').trigger('change'); // Reset Select2 dropdown
                    $("input[name='Id'], input[name='Product_Name'], input[name='Landing_Cost'], input[name='Unit_Price'], input[name='Wholesale_Price'], input[name='unit-discount-input'], input[name='Qty'], input[name='Total_Price']").val('');
                    $('.remaining-qty').text(`Available Qty: 0`).show();

                });

                // Remove Row
                $("#productTable").on("click", ".remove-row", function () {
                    $(this).closest("tr").remove();
                    if ($("#productTable tbody tr").length === 0) {
                        $("#productTable tbody").append('<tr><td colspan="7" class="text-center py-5 my-xl-3 text-muted"><strong>No Product Added</strong></td></tr>');
                    }
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
    </body>
</html>