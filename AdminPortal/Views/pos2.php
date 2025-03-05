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
        $invoiceNumber = 'INVR' . str_pad($row['Value'], 5, '0', STR_PAD_LEFT);
    } else {
        $invoiceNumber = 'INVR00001'; // Default value if query fails
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
<head>
        <meta charset="utf-8">
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
		.tag-cloud 
		{
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

					<!-- /Model Alerts -->
					<div class="modal fade" id="SaveSuccessModel" role="dialog">
						<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
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
						<!-- Modal content-->
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
						<!-- Modal content-->
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
						<!-- Modal content-->
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
						<!-- Modal content-->
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

					<div class="modal fade" id="QtyErrorModel" role="dialog">
						<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
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
					<!-- /Model Alerts -->

					<div class="profile-menu">
    					<ul class="nav nav-tabs nav-tabs-solid justify-content-center w-100">
        					<li class="nav-item flex-fill text-center">
            					<a class="nav-link" href="pos.php">
                					<i class="fa fa-usd" aria-hidden="true"></i>
                					<span>Whole Sale</span>
            					</a>
        					</li>
        					<li class="nav-item flex-fill text-center">
            					<a class="nav-link active" href="pos2.php">
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
										<!-- Form fields here -->

										<div class="row">
											<div class="col-lg-12">
												<div class="card">
													<div class="card-body">	
														<div class="row">
															<div class="col-md-6 text-left mt-4">
																<h6 class="page-title">
																	<h6 class="text-xs font-weight-bold mb-1">
																	<input style="width: auto; display:;" type="text" name="Invoice_Id" class="form-control text-right" value="<?php echo $invoiceNumber; ?>" readonly required>	
																	<span>Invoice No: <?php echo $invoiceNumber; ?></span>
																	</h6>
																	<h6 class="text-xs font-weight-bold mb-1">
																			<label for="customerSelect" class="mb-0" style="white-space: nowrap;">Customer Name:<label class="text-danger">*</label>
																			<select style="width: auto;" name="Customer_Id" id="customerSelect" class="form-control select2" required="">
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
																		<?php
																			$Id = $fetch['Id']; 
																			$First_Name = $fetch['First_Name'];
																			$Last_Name = $fetch['Last_Name']; 
																		?>
																		<input style="width: auto; display:;" type="text" name="User_Id" class="form-control text-right" value="<?php echo $Id; ?>" readonly required>
																		<span>Invoice By: <?php echo $First_Name . ' ' . $Last_Name; ?></span>
																	</h6>
																</h6>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-12 text-left mt-4">
																<div class="table-responsive">
																	<table class="table table-bordered" id="productTable">
																		<thead>
																			<tr>
																				<th>Product Id</th>
																				<th>Product Name</th>
																				<th>Cost</th>
																				<th>Unit Price</th>
																				<th>Qty</th>
																				<th>Total Price</th>
																				<th>Action</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td>
																					<input style="width: auto; display:;" type="text" name="Invoice_No[]" class="form-control" value="<?php echo $invoiceNumber; ?>" readonly required>
																					<select style="width: auto;" class="form-control select2 product-select" name="Product_Id[]" required>
																						<option selected disabled>Select Product Code</option>
																						<?php
																							if (!empty($countryResult2)) {
																								foreach ($countryResult2 as $key => $value) {
																									echo '<option value="' . $countryResult2[$key]['Product_Id'] . '">' . $countryResult2[$key]['Product_Id'] . '</option>';
																								}
																							}
																						?>
																					</select>
																				</td>
																				<td>
																					<input style="width: auto; display:;" type="number" name="Id[]" class="form-control" min="1" readonly required>
																					<input style="width: auto;" type="text" name="Product_Name[]" class="form-control" readonly required>
																				</td>
																				<td>
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">LKR:</span>
																							<input style="width: auto;" type="text" name="Landing_Cost[]" class="form-control text-right" readonly required>
																						</div>
																					</div>
																				</td>
																				<td>
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">LKR:</span>
																							<input style="width: auto;" type="text" name="Unit_Price[]" class="form-control unit-price-input text-right" required>
																						</div>
																						<input style="width: auto; display:;" type="text" name="Retail_Price" class="form-control text-right" readonly required>	
																					</div>
																					<small class="text-muted discount-value" style="display: ;">Unit Discount (LKR): 0.00</small>
																					<input style="width: auto; display:;" type="text" name="unit-discount-input[]" class="form-control text-right" readonly required>	
																				</td>
																				<td>
																					<input style="width: auto;" type="number" name="Qty[]" class="form-control qty-input" min="1" required>
																					<small class="text-muted remaining-qty" style="display: ;">Available Qty: </small>
																				</td>
																				<td>
																					<div class="input-group">
																						<div class="input-group-prepend">
																							<span class="input-group-text">LKR:</span>
																							<input style="width: auto;" type="text" name="Total_Price[]" class="form-control text-right total-price" readonly required>
																							<input style="width: auto; display:;" type="text" name="Total_Cost[]" class="form-control text-right total-cost" readonly required>
																							<input style="width: auto; display:;" type="text" name="Total_Profit[]" class="form-control text-right total-profit" readonly required>
																						</div>
																					</div>
																					<small class="text-muted total-discount-value" style="display: ;">Total Discount (LKR): 0.00</small>
																					<input style="width: auto; display:;" type="text" name="total-discount-input[]" class="form-control text-right" readonly required>	
																				</td>
																				<td>
																				<div class="d-flex align-items-center">
																						<button type="button" class="btn btn bg-success-light add-row mr-2">
																							<i class="fa fa-plus" aria-hidden="true"></i>
																						</button>
																						<button type="button" class="btn btn bg-danger-light remove-row">
																							<i class="fa fa-times" aria-hidden="true"></i>
																						</button>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
														<div style="margin-top:17px;" class="row">
															<div class="col-md-3 text-left mt-4">
																<h6 class="text-xs font-weight-bold mb-1">No. of Items: <span id="itemCount" class="font-weight-bold">1</span></h6>
																<input style="width: auto; display:;" type="text" id="Item_Count" name="Item_Count" class="form-control text-right" value="1" readonly required>
															</div>
															<div class="col-md-3 text-left mt-4">
																<h6 class="text-xs font-weight-bold mb-1">Sub Total (LKR): <span id="subtotal" class="font-weight-bold">0.00</span></h6>
																<input style="width: auto; display:;" type="text" id="Sub_Total" name="Sub_Total" class="form-control text-right" readonly required>
															</div>
															<div class="col-md-3 text-right mt-4">
																<h6 class="text-xs font-weight-bold mb-1">Discount (LKR): <span id="discountTotal" class="font-weight-bold">0.00</span></h6>
																<input style="width: auto; display:;" type="text" id="Discount_Total" name="Discount_Total" class="form-control text-right" readonly required>
															</div>
															<div class="col-md-3 text-right mt-4">
																<h6 style="display:;" class="text-xs font-weight-bold mb-1">Total Profit (LKR): <span id="profitTotal" class="font-weight-bold">0.00</span></h6>
																<input style="width: auto; display:;" type="text" id="Profit_Total" name="Profit_Total" class="form-control text-right" readonly required>
																<h6 class="text-xs font-weight-bold mb-1">GRAND TOTAL (LKR): <span id="grandTotal" class="font-weight-bold">0.00</span></h6>
																<input style="width: auto; display:;" type="text" id="Grand_Total" name="Grand_Total" class="form-control text-right" readonly required>
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
																		<input style="width: auto; display:;" type="text" id="Balance_Total" name="Balance_Total" class="form-control text-right" readonly required>
																	</div>
																</div>
															</div>

															<!-- Due Amount -->
															<div class="col-md-4 mt-4">
																<div class="form-group">
																	<h6 class="text-xs font-weight-bold mb-1">Due Amount (LKR): <span id="dueTotal" class="font-weight-bold">0.00</span></h6>
																	<div class="input-group">
																		<input style="width: auto; display:;" type="text" id="Due_Total" name="Due_Total" class="form-control text-right" readonly required>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-12 mt-4">
																<div class="form-group">
																	<input style="display:;" type="text" name="Sale_Type" class="form-control" required="" readonly="true" value="Retail Sale">
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

			$('#customerSelect').on('change', function () {
            var customerId = $(this).val();
            
            // Make sure customerId is not empty or invalid
				if (customerId) {
						$.ajax({
							url: '../../API/POS/searchCustomer.php',
							method: 'GET',
							data: { Customer_Id: customerId },
							dataType: 'json',
							success: function (response) {
								// Show and update the Customer Outstandings with the fetched TotalDue
								$('#customerOutstandings').text('Customer Outstandings (LKR): ' + response.TotalDue).show();
							},
							error: function () {
								$('#customerOutstandings').text('Customer Outstandings (LKR): Error fetching data').show();
							}
						});
						} else {
						// Hide the outstandings if no customer is selected
						$('#customerOutstandings').hide();
				}
        	});

			$('.select2').select2();

			$('#productTable').on('change', '.product-select', function () {
				fetchProductData($(this));
			});

			// Add row functionality
			$('#productTable').on('click', '.add-row', function () {
				var productOptions = `<?php
					if (!empty($countryResult2)) {
						foreach ($countryResult2 as $key => $value) {
							echo '<option value="' . $countryResult2[$key]['Product_Id'] . '">' . $countryResult2[$key]['Product_Id'] . '</option>';
						}
					}
				?>`;

				var newRow = `<tr>
					<td>
						<input style="width: auto; display:;" type="text" name="Invoice_No[]" class="form-control" value="<?php echo $invoiceNumber; ?>" readonly required>
						<select style="width: auto;" name="Product_Id[]" class="form-control select2 product-select" required="">
							<option selected disabled>Select Product Code</option>
							${productOptions}
						</select>
					</td>
					<td>
						<input style="width: auto; display:;" type="number" name="Id[]" class="form-control" min="1" readonly required>
						<input style="width: auto;" type="text" name="Product_Name[]" class="form-control" readonly required>
					</td>
					<td>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">LKR:</span>
								<input style="width: auto;" type="text" name="Landing_Cost[]" class="form-control text-right" readonly required>
							</div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">LKR:</span>
								<input style="width: auto;" type="text" name="Unit_Price[]" class="form-control unit-price-input text-right" required>
							</div>
							<input style="width: auto; display:;" type="name" name="Retail_Price" class="form-control text-right" readonly required>    
						</div>
						<small class="text-muted discount-value" style="display: none;">Unit Discount (LKR): 0.00</small>
						<input style="width: auto; display:;" type="text" name="unit-discount-input[]" class="form-control text-right" readonly required>
					</td>
					<td>
						<input style="width: auto;" type="number" name="Qty[]" class="form-control qty-input" min="1" required>
						<small class="text-muted remaining-qty" style="display: none;">Available Qty: </small>
					</td>
					<td>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">LKR:</span>
								<input style="width: auto;" type="text" name="Total_Price[]" class="form-control text-right total-price" readonly required>
								<input style="width: auto; display:;" type="text" name="Total_Cost[]" class="form-control text-right total-cost" readonly required>
								<input style="width: auto; display:;" type="text" name="Total_Profit[]" class="form-control text-right total-profit" readonly required>
							</div>
						</div>
						<small class="text-muted total-discount-value" style="display: none;">Total Discount (LKR): 0.00</small>
						<input style="width: auto; display:;" type="text" name="total-discount-input[]" class="form-control text-right" readonly required>
					</td>
					<td>
						<div class="d-flex align-items-center">
							<button type="button" class="btn btn bg-success-light add-row mr-2">
								<i class="fa fa-plus" aria-hidden="true"></i>
							</button>
							<button type="button" class="btn btn bg-danger-light remove-row">
								<i class="fa fa-times" aria-hidden="true"></i>
							</button>
						</div>
					</td>
				</tr>`;
				$('#productTable tbody').append(newRow);

				// Reinitialize Select2 for the new row's dropdown
				$('#productTable tbody tr:last-child select').select2();
				updateRowCount(); // Update row count when quantity changes
				updateTotals(); // Update row count when quantity changes
			});

			// Remove row functionality
			$('#productTable').on('click', '.remove-row', function () {
				if ($('#productTable tbody tr').length > 1) {
					$(this).closest('tr').remove();
				} else {
					$('#FirstRowErrorModel').modal('show');
					//alert('The First Row Cannot Remove.');
				}
				updateRowCount(); // Update row count when quantity changes
				updateTotals(); // Update row count when quantity changes
			});

			// Fetch product data
			function fetchProductData($select) {
				var $row = $select.closest('tr');
				var productId = $select.val();

				if (!productId) {
					$row.find('.remaining-qty').hide();
					$row.find('.discount-value').hide();
					$row.find('.total-discount-value').hide();
					return;
				}

				$.ajax({
					url: "../../API/POS/searchProductData.php",
					method: "GET",
					data: { Product_Id: productId },
					dataType: "json",
					success: function (data) {
						if (data.length > 0) {
							var product = data[0];  // Access the first object in the array
							$row.find('input[name="Id[]"]').val(product.Id);
							$row.find('input[name="Product_Name[]"]').val(product.Product_Name);
							$row.find('input[name="Landing_Cost[]"]').val(product.Landing_Cost);
							$row.find('input[name="Retail_Price"]').val(product.Retail_Price);
							$row.find('input[name="Unit_Price[]"]').val(product.Retail_Price);
							$row.find('.discount-value').text('Unit Discount (LKR): 0.00').show();  // Show unit discount label
							$row.find('input[name="unit-discount-input[]"]').val('0.00'); // Show unit discount in input
							$row.find('.total-discount-value').text('Total Discount (LKR): 0.00').show();  // Show total discount label
							$row.find('input[name="total-discount-input[]"]').val('0.00'); // Show total discount in input
							$row.find('.remaining-qty').text(`Available Qty: ${product.SumQty.toLocaleString()}`).show();
							$row.find('input[name="Qty[]"]').attr('data-max', product.SumQty);
						} else {
							alert("No product data found.");
						}
					},
					error: function (xhr, status, error) {
						alert("Unauthorized access");
					}
				});
			}

			// Ensure only numeric values are entered in Unit Price field
			$('#productTable').on('input', '.unit-price-input', function () {
        		var $input = $(this);
        		var value = $input.val().replace(/[^0-9.,]/g, ''); // Remove non-numeric characters except comma and dot
        		$input.val(value);
    		});

			// Handle Unit Price input validation with alert
			$('#productTable').on('blur keydown', '.unit-price-input', function (e) {
				// Only trigger on blur or enter key
				if (e.type === 'keydown' && e.key !== 'Enter') {
					return;
				}

				var $row = $(this).closest('tr');
				var maxQty = parseInt($row.find('input[name="Qty[]"]').attr('data-max'));
				var wholesalePrice = parseFloat($row.find('input[name="Retail_Price"]').val().replace(/,/g, ''));
				var landingCost = parseFloat($row.find('input[name="Landing_Cost[]"]').val().replace(/,/g, ''));
				var enteredValue = parseFloat($(this).val().replace(/,/g, ''));

				var unitDiscount = wholesalePrice - enteredValue;

				// Check if a product is selected
				if (!$row.find('.product-select').val()) {
					$row.find('.discount-value').hide();
					$row.find('.total-discount-value').hide();
					$(this).val(''); // Clear invalid unit price input
					return;
				}

				if (isNaN(enteredValue)) {
					$(this).val('');
				} else if (enteredValue < landingCost) {
					// alert(`Unit Price Cannot Be Less Than Cost`);
					$('#UnitPriceErrorModel').modal('show');
					$(this).val(wholesalePrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
					$row.find('.discount-value').text('Unit Discount (LKR): 0.00').show();
					$row.find('input[name="unit-discount-input[]"]').val('0.00');
					$row.find('.total-discount-value').text('Total Discount (LKR): 0.00').show();
					$row.find('input[name="total-discount-input[]"]').val('0.00');
					$row.find('.qty-input').val('').show();
					$row.find('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
					$row.find('.total-price').val('').show(); // Show total price with commas
					$row.find('.total-cost').val('').hide(); // Show total cost with commas
					$row.find('.total-profit').val('').hide(); // Show total profit with commas
				} else if (enteredValue > wholesalePrice) {
					alert(`Enter Price is Greater Than Selling Price`);
					$(this).val(wholesalePrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
					$row.find('.discount-value').text('Unit Discount (LKR): 0.00').show();
					$row.find('input[name="unit-discount-input[]"]').val('0.00');
					$row.find('.total-discount-value').text('Total Discount (LKR): 0.00').show();
					$row.find('input[name="total-discount-input[]"]').val('0.00');
					$row.find('.qty-input').val('').show();
					$row.find('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
					$row.find('.total-price').val('').show(); // Show total price with commas
					$row.find('.total-cost').val('').hide(); // Show total cost with commas
					$row.find('.total-profit').val('').hide(); // Show total profit with commas
				} else {
					$row.find('.discount-value').text(`Unit Discount (LKR): ${(unitDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
					$row.find('input[name="unit-discount-input[]"]').val(unitDiscount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
					$row.find('.total-discount-value').text('Total Discount (LKR): 0.00').show();
					$row.find('input[name="total-discount-input[]"]').val('0.00');
					$row.find('.qty-input').val('').show();
					$row.find('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
					$row.find('.total-price').val('').show(); // Show total price with commas
					$row.find('.total-cost').val('').hide(); // Show total cost with commas
					$row.find('.total-profit').val('').hide(); // Show total profit with commas
				}
				
			});

			// Handle Qty input validation
			$('#productTable').on('input', '.qty-input', function () {
				var $row = $(this).closest('tr');
				var maxQty = parseInt($row.find('input[name="Qty[]"]').attr('data-max'));
				var enteredQty = parseInt($(this).val());

				var cost = parseFloat($row.find('input[name="Landing_Cost[]"]').val().replace(/,/g, ''));
				var wholesalePrice = parseFloat($row.find('input[name="Retail_Price"]').val().replace(/,/g, ''));
				var unitPrice = parseFloat($row.find('input[name="Unit_Price[]"]').val().replace(/,/g, ''));

				var totalCost = cost * enteredQty;
				var totalPrice = unitPrice * enteredQty;

				var unitProfit = totalPrice - totalCost;
				var totalDiscount = (wholesalePrice - unitPrice) * enteredQty;

				// Check if a product is selected
				if (!$row.find('.product-select').val()) {
					$row.find('.remaining-qty').hide();
					$(this).val(''); // Clear invalid quantity input
					return;
				}

				// Validate the entered quantity
				if (isNaN(enteredQty) || enteredQty < 1) {
					$(this).val('');
					$row.find('.remaining-qty').text(`Available Qty: ${maxQty.toLocaleString()}`).show(); // Show available qty with commas
					$row.find('.total-discount-value').text('Total Discount (LKR): 0.00').show();
					$row.find('input[name="total-discount-input[]"]').val('0.00');
					$row.find('.total-price').val('').show(); // Show total price with commas
					$row.find('.total-cost').val('').hide(); // Show total cost with commas
					$row.find('.total-profit').val('').hide(); // Show total profit with commas
				} else if (enteredQty > maxQty) {
					$(this).val(maxQty);
					$row.find('.remaining-qty').text('Available Qty: 0').show();
					// alert("Entered Qty Exceeds Available Qty");
					$('#QtyErrorModel').modal('show');
					$row.find('.total-discount-value').text(`Total Discount (LKR): ${(totalDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
					$row.find('input[name="total-discount-input[]"]').val(totalDiscount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
					$row.find('.total-price').val(`${(unitPrice * maxQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show total price with commas
					$row.find('.total-cost').val(`${(cost * maxQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).hide(); // Show total cost with commas
					$row.find('.total-profit').val(`${(unitProfit).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).hide(); // Show total profit with commas
					updateTotals(); // Update row count when quantity changes
				} else {
					$row.find('.remaining-qty').text(`Available Qty: ${(maxQty - enteredQty).toLocaleString()}`).show(); // Show available qty with commas
					$row.find('.total-discount-value').text(`Total Discount (LKR): ${(totalDiscount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show discount with commas
					$row.find('input[name="total-discount-input[]"]').val(totalDiscount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
					$row.find('.total-price').val(`${(unitPrice * enteredQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).show(); // Show total price with commas
					$row.find('.total-cost').val(`${(cost * enteredQty).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).hide(); // Show total cost with commas
					$row.find('.total-profit').val(`${(unitProfit).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`).hide(); // Show total profit with commas
					updateTotals(); // Update row count when quantity changes
				}
			});

			// Handle unit price input validation
			$('#productTable').on('input', '.unit-price-input', function () {
				var $row = $(this).closest('tr');
				var enteredValue = parseInt($(this).val());

				// Check if a product is selected
				if (!$row.find('.product-select').val()) {
					$(this).val(''); // Clear invalid quantity input
					return;
				}

				// Validate the entered quantity
				if (isNaN(enteredValue) || enteredValue < 1) {
					$(this).val('');
				}
			});

			// Hide Available Qty if no product is selected
			$('#productTable').on('change', '.product-select', function () {
				var $row = $(this).closest('tr');
				if (!$(this).val()) {
					$row.find('.remaining-qty').hide();
					$row.find('input[name="Qty[]"]').val('');  // Clear Qty if no product selected
					$row.find('.discount-value').hide(); // Hide discount label if no product selected
				}
			});

			// Calculate and update row count
			function updateRowCount() {
				var itemCount = $('#productTable tbody tr').length;
				$('#itemCount').text(itemCount);
				$('#Item_Count').val(itemCount);
			}

			// Calculate and update totals
			function updateTotals() {
				var subTotal = 0;
				var discountTotal = 0;
				var profitTotal = 0;
				var grandTotal = 0;

				$('#productTable tbody tr').each(function () {
					var qty = parseInt($(this).find('input[name="Qty[]"]').val()) || 0;
					var unitPrice = parseFloat($(this).find('input[name="Retail_Price"]').val().replace(/,/g, '')) || 0;
					var totalPrice = parseFloat($(this).find('input[name="Total_Price[]"]').val().replace(/,/g, '')) || 0;
					var totalProfit = parseFloat($(this).find('input[name="Total_Profit[]"]').val().replace(/,/g, '')) || 0;

					var totalDiscountText = $(this).find('.total-discount-value').text();
					var cleanedText = totalDiscountText.replace(/[^0-9.,]/g, '');
					var totalDiscount = parseFloat(cleanedText.replace(/,/g, ''));
					
					subTotal += unitPrice * qty;
					discountTotal += totalDiscount;
					profitTotal += totalProfit;
					grandTotal += totalPrice;
					
				});

				$('#subtotal').text(subTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#Sub_Total').val(subTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#discountTotal').text(discountTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#Discount_Total').val(discountTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#profitTotal').text(profitTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#Profit_Total').val(profitTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#grandTotal').text(grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
				$('#Grand_Total').val(grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
			}

			$(document).ready(function() {
				// Event listener for keydown (Enter key) and blur (losing focus)
				$('input[name="Paid_Amount"]').on('keydown blur', function(event) {
					if (event.type === 'blur' || event.key === 'Enter') {
						if (event.key === 'Enter') {
							event.preventDefault(); // Prevent the default action, which may cause form submission
						}

						var TotalBill = parseFloat($('#Grand_Total').val().replace(/,/g, '')) || 0;
						var PaidAmount = parseFloat($(this).val()) || 0;

						if (TotalBill === 0) {
							$(this).val('');
						} else {
							var Temp = PaidAmount - TotalBill;

							if (Temp >= 0) {
								var balance = Temp;
								var due = 0;

								$('#balanceTotal').text(balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
								$('#Balance_Total').val(balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
								$('#dueTotal').text(due.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
								$('#Due_Total').val(due.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
							} else {
								var balance = 0;
								var due = Temp * -1;

								$('#balanceTotal').text(balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
								$('#Balance_Total').val(balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
								$('#dueTotal').text(due.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
								$('#Due_Total').val(due.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
							}
						}

						// Check if PaidAmount is 0 and select "Not Selected" payment option
						if (PaidAmount === 0) {
							$('#paymentNotSelected').prop('checked', true);

							$('#paymentCash').prop('disabled', true);
							$('#paymentCard').prop('disabled', true);
							$('#paymentCheque').prop('disabled', true);
							$('#paymentBankTransfer').prop('disabled', true);
							$('#paymentNotSelected').prop('disabled', false);
						}
						else
						{
							$('#paymentCash').prop('checked', true);

							$('#paymentCash').prop('disabled', false);
							$('#paymentCard').prop('disabled', false);
							$('#paymentCheque').prop('disabled', false);
							$('#paymentBankTransfer').prop('disabled', false);
							$('#paymentNotSelected').prop('disabled', true);
						}
					}
				});
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

			document.addEventListener('DOMContentLoaded', function() {
				document.getElementById('saveSubmitBtn').addEventListener('click', function(event) {
					event.preventDefault(); // Prevent the default form submission

					// Collect Invoice Data
					const invoiceData = {
						Invoice_No: document.querySelector('input[name="Invoice_No"]').value,
						Customer_Id: document.querySelector('select[name="Customer_Id"]').value,
						User_Id: document.querySelector('input[name="User_Id"]').value,
						Sale_Type: document.querySelector('input[name="Sale_Type"]').value,
						Sub_Total: document.querySelector('input[name="Sub_Total"]').value,
						Discount_Total: document.querySelector('input[name="Discount_Total"]').value,
						Profit_Total: document.querySelector('input[name="Profit_Total"]').value,
						Grand_Total: document.querySelector('input[name="Grand_Total"]').value,
						Paid_Amount: document.querySelector('input[name="Paid_Amount"]').value,
						Balance_Total: document.querySelector('input[name="Balance_Total"]').value,
						Due_Total: document.querySelector('input[name="Due_Total"]').value,
						Type: document.querySelector('input[name="Type"]:checked').value,
						Description: document.querySelector('textarea[name="Description"]').value,
					};

					// Collect Item Data
					const products = [];
					const rows = document.querySelectorAll('#productTable tbody tr');
					rows.forEach(row => {
						const product = {
							Invoice_No: row.querySelector('input[name="Invoice_No[]"]').value,
							Product_Id: row.querySelector('select[name="Product_Id[]"]').value,
							Product_Name: row.querySelector('input[name="Product_Name[]"]').value,
							Landing_Cost: row.querySelector('input[name="Landing_Cost[]"]').value,
							Unit_Price: row.querySelector('input[name="Unit_Price[]"]').value,
							Qty: row.querySelector('input[name="Qty[]"]').value,
							Total_Price: row.querySelector('input[name="Total_Price[]"]').value,
							Total_Cost: row.querySelector('input[name="Total_Cost[]"]').value,
							Total_Profit: row.querySelector('input[name="Total_Profit[]"]').value,
							unit_discount: row.querySelector('input[name="unit-discount-input[]"]').value,
							total_discount: row.querySelector('input[name="total-discount-input[]"]').value
						};
						products.push(product);
					});
				});
			});
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
		$('#invoiceForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/POS/saveInvoice.php',
                data: $(this).serialize(),
                success: function (response) {
					if (response.success === 'string')
					{
						response = JSON.parse(response);
					}

					// Show the appropriate modal based on response
					showSaveAlerts(response);

					// Log the response for debugging
					console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', status, error);
					$('#SaveFailedModel').modal('show');
                }
            });
        });

		// Handle the "Ok" button click in the SaveSuccessModel
		$('#OkBtn').on('click', function () {
			// Refresh the page when "Ok" is clicked
			var invoiceNumber = encodeURIComponent('<?php echo $invoiceNumber; ?>');
			window.location.href = 'printInvoice.php?Invoice_No=' + invoiceNumber;
		});
		</script>

    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->
</html>