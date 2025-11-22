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

// Check if user has access to deleteQuotation.php
$has_access_to_delete_details = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 185") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_delete_details = true;
}

// Check if user has access to viewQuotationDataCopy.php
$has_access_to_print_quotation = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 184") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_print_quotation = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title><?php echo ($companyName); ?> - Sales Quotation</title>

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


	<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->


	<style>
		.background-container {
			background-size: cover;
			background-position: center;
			height: 250px;
			/* Adjust the height as needed */
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
		}

		.tag-cloud {
			display: inline-block;
			padding: 8px 20px;
			border-radius: 5px;
			background-color: #be3235;
			color: #ffff;
			margin-top: 8px;
			width: 100%;
		}

		/* Flexbox container to align radio buttons in a line */
		.payment-methods {
			display: flex;
			flex-wrap: wrap;
			/* Allows wrapping if there's not enough space */
			gap: 15px;
			/* Space between radio buttons, adjust as needed */
		}

		.form-check {
			margin-bottom: 0;
			/* Remove default margin */
		}

		/* Ensure proper alignment and spacing for labels and inputs */
		.form-check-input {
			margin-right: 5px;
			/* Space between radio button and label */
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


				<!-- /Model Alerts -->
				<div class="modal fade" id="UpdateSuccessModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-check-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#26af48;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Success</b></h3>
								<p>Record Updated Successfully !</p>
							</div>
							<div class="modal-body">
								<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
							</div>
						</div>

					</div>
				</div>

				<div class="modal fade" id="UpdateDuplicateModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>Record Already Exist !</p>
							</div>
							<div class="modal-body">
								<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
							</div>
						</div>

					</div>
				</div>

				<div class="modal fade" id="UpdateFailedModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>Record Not Updated !</p>
							</div>
							<div class="modal-body">
								<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
							</div>
						</div>

					</div>
				</div>

				<div class="modal fade" id="DeleteSuccessModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-check-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#26af48;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Success</b></h3>
								<p>Record Deleted Successfully !</p>
							</div>
							<div class="modal-body">
								<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
							</div>
						</div>

					</div>
				</div>

				<div class="modal fade" id="DeleteFailedModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>Record Not Deleted !</p>
							</div>
							<div class="modal-body">
								<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
							</div>
						</div>

					</div>
				</div>
				<!-- /Model Alerts -->

				<!-- Page Header -->
				<div class="page-header">
					<?php
					$Quotation_Id = $_REQUEST["Quotation_No"];
					$query1 = mysqli_query($conn, "SELECT * FROM tbl_quotation WHERE Quotation_Id = '$Quotation_Id'") or die(mysqli_error());
					$fetch1 = mysqli_fetch_array($query1);
					?>
					<!-- Edit and Delete Buttons -->
					<div class="row">
						<div class="col-md-12 text-left">
							<?php if ($has_access_to_delete_details): ?>
								<a href="#Delete_Details" data-toggle="modal" class="btn btn bg-danger-light"><i class="fa fa-retweet"></i> Delete</a>
							<?php else: ?>
								<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fa fa-retweet"></i> Delete</a>
							<?php endif; ?>
							<?php if ($has_access_to_print_quotation): ?>
								<a href="printQuotationCopy.php?Quotation_No=<?php echo $Quotation_Id ?>" class="btn btn bg-warning-light"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</a>
							<?php else: ?>
								<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-warning-light"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6 text-left mt-4">
												<h6 class="page-title">
													<h6 class="text-xs mb-1">
														<span><strong>Quotation No:</strong> <span id="quotationNo"></span></span>
													</h6>
													<h6 class="text-xs mb-1">
														<label class="mb-0" style="white-space: nowrap;"><strong>Customer Name:</strong> <span id="customerName"></span>
													</h6>
												</h6>
											</div>
											<div class="col-md-6 text-right mt-4">
												<h6 class="page-title">
													<h6 class="text-xs mb-1">
														<span><strong>Quotation Date:</strong> <span id="quotationDate"></span></span>
													</h6>
													<h6 class="text-xs mb-1">
														<span><strong>Quotation By:</strong> <span id="quotationBy"></span></span>
													</h6>
													<h6 class="text-xs mb-1">
														<span><strong>Sale Type:</strong> <span id="saleType"></span></span>
													</h6>
												</h6>
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-md-12 text-left mt-4">
												<div class="table-responsive">
													<table id="productListTable" class="datatable table table-hover table-center mb-0">
														<thead>
															<tr>
																<th>Product Id</th>
																<th>Product Name</th>
																<th>Cost</th>
																<th>Unit Price</th>
																<th>Qty</th>
																<th>Total Price</th>
															</tr>
														</thead>

														<tbody id="productList">
															<!-- Data will be populated here -->
														</tbody>

													</table>
												</div>
											</div>
										</div>
										<div style="margin-top:17px;" class="row">
											<div class="col-md-4 text-left mt-4">
												<h6 class="text-xs font-weight-bold mb-1">No. of Items: <span id="itemCount" class="font-weight-bold"></span></h6>
											</div>
											<div class="col-md-4 text-left mt-4">
												<h6 class="text-xs font-weight-bold mb-1">Sub Total (LKR): <span id="subTotal" class="font-weight-bold"></span></h6>
											</div>
											<div class="col-md-4 text-right mt-4">
												<h6 class="text-xs font-weight-bold mb-1">Discount (LKR): <span id="discountTotal" class="font-weight-bold"></span></h6>
											</div>
										</div>

										<div style="margin-top:17px;" class="row">
											<div class="col-md-3 text-left mt-4">
												<h6 class="text-xs font-weight-bold mb-1">Service Charge <span id="serviceCharge" class="font-weight-bold">0.00</span></h6>
											</div>
											<div class="col-md-3 text-left mt-4">
												<h6 class="text-xs font-weight-bold mb-1" id="taxChargeLabel">Tax <span id="taxCharge" class="font-weight-bold">0.00</span></h6>
											</div>
											<div class="col-md-3 text-right mt-4">
												<h6 class="text-xs font-weight-bold mb-1" id="vatChargeLabel">Vat <span id="vatCharge" class="font-weight-bold">0.00</span></h6>
											</div>
											<div class="col-md-3 text-right mt-4">
												<h6 class="text-xs font-weight-bold mb-1" id="deliveryChargeLabel">Delivery Charge <span id="deliveryCharge" class="font-weight-bold">0.00</span></h6>
											</div>
										</div>

										<div style="margin-top:17px;" class="row">
											<div class="col-md-12 text-right mt-4">
												<h6 style="display:none;" class="text-xs font-weight-bold mb-1">Total Profit (LKR): <span id="profitTotal" class="font-weight-bold"></span></h6>
												<h6 class="text-xs font-weight-bold mb-1">GRAND TOTAL (LKR): <span id="grandTotal" class="font-weight-bold"></span></h6>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12 text-left mt-4">
												<!-- Back Button -->
												<div class="form-group text-right mt-5">
													<button onclick="window.history.back();" class="btn btn-back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to List</button>
												</div>
												<!-- Back Button -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Edit Payment Modal-->
		<div class="modal fade" id="Update_Details" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Update Payment Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" action="../../API/Admin/updateInvoice.php" id="updatePaymentForm" enctype="multipart/form-data">
							<div class="row form-row">
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Grand Total</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">LKR:</span>
											</div>
											<input style="display:none;" type="text" name="Updated_By" class="form-control" required="" readonly="true" value="<?php echo $fetch['Id']; ?>">
											<input style="display:none;" type="text" name="Invoice_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Invoice_Id']; ?>">
											<input type="number" id="Grand_Total" name="Grand_Total" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Grand_Total'], 2, '.', ''); ?>">
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Paid Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">LKR:</span>
											</div>
											<input type="number" id="Last_Paid_Amount" name="Last_Paid_Amount" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Paid_Amount'], 2, '.', ''); ?>">
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Balance Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">LKR:</span>
											</div>
											<input style="display:none;" type="number" id="Temp_Balance_Total" name="Temp_Balance_Total" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Balance_Total'], 2, '.', ''); ?>">
											<input type="number" id="Balance_Total" name="Balance_Total" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Balance_Total'], 2, '.', ''); ?>">
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Due Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">LKR:</span>
											</div>
											<input style="display:none;" type="number" id="Temp_Due_Total" name="Temp_Due_Total" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Due_Total'], 2, '.', ''); ?>">
											<input type="number" id="Due_Total" name="Due_Total" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Due_Total'], 2, '.', ''); ?>">
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<label>Amount to Be Paid</label><label class="text-danger">*</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">LKR:</span>
											</div>
											<input type="number" id="Paid_Amount" name="Paid_Amount" class="form-control text-right currency-input" required min="1" step="any">
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<input style="display:none;" type="text" name="Sale_Type" class="form-control" required="" readonly="true" value="Whole Sale">
										<label>Payment Method</label><label class="text-danger">*</label>
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
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<label>Description</label>
										<textarea id="my-text" name="Description" class="form-control" rows="8"><?php echo $fetch1['Description']; ?></textarea>
										<p id="count-result">0/1000</p>
									</div>
								</div>
							</div>
							<button type="submit" name="edit" class="btn btn-primary btn-block">Pay Now</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--/Edit Payment Modal -->

		<!-- Delete Modal -->
		<div class="modal fade" id="Delete_Details" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete Quotation</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-content p-2">
							<h4 class="modal-title">Delete <?php echo $fetch1['Quotation_Id']; ?></h4>
							<p class="mb-4">Are you sure want to Delete Quotation ?</p>
							<form method="POST" action="../../API/Admin/deleteQuotation.php" id="deleteDetailsForm" enctype="multipart/form-data">
								<input style="display: none;" type="text" name="Quotation_Id" value="<?php echo $fetch1['Quotation_Id']; ?>">
								<button type="submit" name="delete" class="btn btn-primary btn-block">Delete Quotation</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/Delete Modal -->

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

				// Function to show and hide alerts based on response
				function showUpdateAlerts(response) {
					// Hide the Update Brand modal before showing any alert modals
					$('#Update_Details').modal('hide');

					if (response.success === 'true') {
						// Show UpdateSuccessModel only if success is true
						$('#UpdateSuccessModel').modal('show');
					} else if (response.success === 'false' && response.error === 'duplicate') {
						// Show UpdateDuplicateModel only if success is false and error is duplicate
						$('#UpdateDuplicateModel').modal('show');
					} else {
						// Show UpdateFailedModel for any other failure scenario
						$('#UpdateFailedModel').modal('show');
					}
				}

				function showDeleteAlerts(response) {
					// Hide the Delete Brand modal before showing any alert modals
					$('#Delete_Details').modal('hide');

					if (response.success === 'true') {
						// Show DeleteSuccessModel only if success is true
						$('#DeleteSuccessModel').modal('show');
					} else {
						// Show DeleteFailedModel for any other failure scenario
						$('#DeleteFailedModel').modal('show');
					}
				}

				function formatCharge(selector, isPercentage, value) {
					// Remove commas before parsing
					var cleanedValue = value.toString().replace(/,/g, '');
					var formattedValue = parseFloat(cleanedValue).toLocaleString(undefined, {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					});

					if (isPercentage === "1") {
						$(selector).text('(%):' + formattedValue).show();
					} else {
						$(selector).text('(LKR):' + formattedValue).show();
					}
				}

				// Function to fetch and display quotation data
				function fetchQuotationData(Quotation_Id) {
					$.ajax({
						type: 'GET',
						url: '../../API/Quotation/viewQuotationData.php',
						data: {
							Quotation_Id: Quotation_Id
						},
						dataType: 'json',
						success: function(response) {
							if (!response.QuotationData) {
								console.error('Failed to fetch quotation data');
								return;
							}

							const quotationBy = response.QuotationData.First_Name + ' ' + response.QuotationData.Last_Name;
							const invoiceDecription = response.QuotationData.Description ? response.QuotationData.Description : 'N/A';

							// Populate invoice details
							$('#quotationNo').text(response.QuotationData.Quotation_No);
							$('#customerName').text(response.QuotationData.Customer_Name);
							$('#quotationDate').text(response.QuotationData.Quotation_Date);
							$('#quotationBy').text(quotationBy);
							$('#saleType').text(response.QuotationData.Sale_Type);
							$('#itemCount').text(response.QuotationData.Item_Count);
							$('#subTotal').text(response.QuotationData.Sub_Total);
							$('#discountTotal').text(response.QuotationData.Discount_Total);

							//Charges
							formatCharge('#serviceCharge', response.QuotationData.ServiceCharge_IsPercentage, response.QuotationData.ServiceCharge);
							formatCharge('#taxCharge', response.QuotationData.Tax_IsPercentage, response.QuotationData.Tax);
							formatCharge('#vatCharge', response.QuotationData.Vat_IsPercentage, response.QuotationData.Vat);
							formatCharge('#deliveryCharge', response.QuotationData.Delivery_IsPercentage, response.QuotationData.Delivery);


							$('#grandTotal').text(response.QuotationData.Grand_Total);

							// Destroy existing DataTable, if any
							$('#productListTable').DataTable().destroy();

							// Initialize DataTable
							var Itemtable = $('#productListTable').DataTable({
								searching: true, // Enable search
								columnDefs: [{
										targets: [2, 3, 5], // Index of the Grand Total column
										className: 'text-right'
									},
									{
										targets: 4,
										className: 'text-center'
									}
								]
							});

							// Clear existing rows
							Itemtable.clear();

							// Populate product list
							if (response.products.length > 0) {
								$.each(response.products, function(index, product) {
									const formattedLandingCost = 'LKR: ' + product.Landing_Cost;
									const formattedUnitPrice = 'LKR: ' + product.Unit_Price;
									const formattedTotalPrice = 'LKR: ' + product.Product_Total_Price;

									Itemtable.row.add([
										product.Product_Id,
										product.Product_Name,
										formattedLandingCost,
										formattedUnitPrice,
										product.Qty,
										formattedTotalPrice,
										product.Product_Total_Profit
									]);
								});
							} else {
								console.log('No products received.');
							}

							// Draw the table
							Itemtable.draw();
						},
						error: function(xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				}

				// Get the Quotation_No from the URL
				const urlParams = new URLSearchParams(window.location.search);
				const Quotation_Id = urlParams.get('Quotation_No');

				// Fetch and display invoice data
				fetchQuotationData(Quotation_Id);

				// Function to delete details
				$('#deleteDetailsForm').submit(function(event) {

					event.preventDefault();

					$('#pageLoader').show(); // Show loader before sending

					$.ajax({
						type: 'POST',
						url: '../../API/Admin/deleteQuotation.php',
						data: $(this).serialize(),
						success: function(response) {
							// Parse the response as a JSON object (if not already parsed)
							if (typeof response === 'string') {
								response = JSON.parse(response);
							}

							// Show the appropriate modal based on response
							showDeleteAlerts(response);

							// Log the response for debugging
							console.log(response);
						},
						error: function(xhr, status, error) {
							console.error('Error:', status, error);
							$('#Delete_Details').modal('hide');
							$('#DeleteFailedModel').modal('show');
						},
						complete: function() {
							$('#pageLoader').hide(); // Hide loader after response (success or error)
						}
					});
				});

				// Handle the "Ok" button click in the DeleteSuccessModel
				$('#DeleteSuccessModel #OkBtn').on('click', function() {
					// Refresh the page when "Ok" is clicked
					window.location.href = 'sales_quotation.php';
				});

				// Helper function to format numbers as strings
				function number_format(number, decimals) {
					return parseFloat(number).toFixed(decimals);
				}

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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->

</html>