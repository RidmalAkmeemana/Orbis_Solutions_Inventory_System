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

// Check if user has access to updateDetails.php
$has_access_to_edit_details = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 143") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_edit_details = true;
}

// Check if user has access to deleteDetails.php
$has_access_to_delete_details = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 144") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_delete_details = true;
}

// Check if user has access to viewInvoiceDataCopy.php
$has_access_to_print_invoice = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 141") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_print_invoice = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title><?php echo ($companyName); ?> - Sales Invoice</title>

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

				<div class="modal fade" id="AlreadyPaidModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>This Invoice has already been Paid !</p>
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
					$Invoice_Id = $_REQUEST["Invoice_No"];
					$query1 = mysqli_query($conn, "SELECT * FROM tbl_invoice WHERE Invoice_Id = '$Invoice_Id'") or die(mysqli_error());
					$fetch1 = mysqli_fetch_array($query1);
					?>
					<!-- Edit and Delete Buttons -->
					<div class="row">
						<div class="col-md-12 text-left">
							<?php if ($has_access_to_edit_details): ?>
								<a href="#Update_Details" data-toggle="modal" class="btn btn bg-primary-light"><i class="fa fa-usd" aria-hidden="true"></i> Payment</a>
							<?php else: ?>
								<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fa fa-usd" aria-hidden="true"></i> Payment</a>
							<?php endif; ?>
							<?php if ($has_access_to_delete_details): ?>
								<a href="#Delete_Details" data-toggle="modal" class="btn btn bg-danger-light"><i class="fa fa-retweet"></i> Delete</a>
							<?php else: ?>
								<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fa fa-retweet"></i> Delete</a>
							<?php endif; ?>
							<?php if ($has_access_to_print_invoice): ?>
								<a href="printInvoiceCopy.php?Invoice_No=<?php echo $Invoice_Id ?>" class="btn btn bg-warning-light"><i class="fa fa-print" aria-hidden="true"></i> Re-Print</a>
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
														<span><strong>Invoice No:</strong> <span id="invoiceNo"></span></span>
													</h6>
													<h6 class="text-xs mb-1">
														<label class="mb-0" style="white-space: nowrap;"><strong>Customer Name:</strong> <span id="customerName"></span>
													</h6>
													<h6 class="text-xs mb-1">
														<label class="mb-0" style="white-space: nowrap;"><strong>Last Payment Date:</strong> <span id="paymentDate"></span>
													</h6>
													<h6 class="text-xs mb-1">
														<label class="mb-0" style="white-space: nowrap;"><strong>Status:</strong> <span id="status"></span>
													</h6>
												</h6>
											</div>
											<div class="col-md-6 text-right mt-4">
												<h6 class="page-title">
													<h6 class="text-xs mb-1">
														<span><strong>Invoiced Date:</strong> <span id="invoiceDate"></span></span>
													</h6>
													<h6 class="text-xs mb-1">
														<span><strong>Invoice By:</strong> <span id="invoiceBy"></span></span>
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
												<h5 class="page-title">
													<h5 class="tag-cloud text-xs font-weight-bold mb-1">Payments Summery</h5>
												</h5>
											</div>
										</div>
										<div class="row">
											<!-- Paid Amount -->
											<div class="col-md-4 mt-4">
												<div class="form-group">
													<label class="mb-0" style="white-space: nowrap;"><strong>Paid Amount (LKR): <span id="paidAmount"></span></strong>
												</div>
											</div>
											<!-- Balance Amount -->
											<div class="col-md-4 mt-4">
												<div class="form-group">
													<label class="mb-0" style="white-space: nowrap;"><strong>Balance Amount (LKR): <span id="balanceAmount"></span></strong>
												</div>
											</div>
											<!-- Due Amount -->
											<div class="col-md-4 mt-4">
												<div class="form-group">
													<label class="mb-0" style="white-space: nowrap;"><strong>Due Amount (LKR): <span id="dueAmount"></span></strong>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-12">
												<div class="form-group">
													<span><strong>Payment Method:</strong> <span id="paymentMethod"></span></span>
												</div>
											</div>
											<div class="col-12">
												<div class="form-group">
													<label><strong>Description</strong></label>
													<textarea id="description" name="Description" class="form-control" rows="8" readonly></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-left">
												<h5 class="page-title">
													<h5 class="tag-cloud text-xs font-weight-bold mb-1">Payments History</h5>
												</h5>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-left mt-4">
												<div class="table-responsive">
													<table id="paymentListTable" class="datatable table table-hover table-center mb-0">
														<thead>
															<tr>
																<th>Reciept No</th>
																<th>Payment Date</th>
																<th>Description</th>
																<th>Paid Amount</th>
																<th>Balance Total</th>
																<th>Payment Type</th>
																<th>Due Total</th>
																<th>Updated By</th>
															</tr>
														</thead>

														<tbody id="paymentList">
															<!-- Data will be populated here -->
														</tbody>

													</table>
												</div>
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
						<h5 class="modal-title">Delete Invoice</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-content p-2">
							<h4 class="modal-title">Delete <?php echo $fetch1['Invoice_Id']; ?></h4>
							<p class="mb-4">Are you sure want to Delete Invoice ?</p>
							<form method="POST" action="../../API/Admin/deleteInvoice.php" id="deleteDetailsForm" enctype="multipart/form-data">
								<input style="display: none;" type="text" name="Invoice_Id" value="<?php echo $fetch1['Invoice_Id']; ?>">
								<button type="submit" name="delete" class="btn btn-primary btn-block">Delete Invoice</button>
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

				// Function to fetch and display invoice data
				function fetchInvoiceData(Invoice_Id) {
					$.ajax({
						type: 'GET',
						url: '../../API/POS/viewInvoiceData.php',
						data: {
							Invoice_Id: Invoice_Id
						},
						dataType: 'json',
						success: function(response) {
							if (!response.InvoiceData) {
								console.error('Failed to fetch invoice data');
								return;
							}

							const paymentDate = response.InvoiceData.Payment_Date ? response.InvoiceData.Payment_Date : 'N/A';
							const invoiceBy = response.InvoiceData.First_Name + ' ' + response.InvoiceData.Last_Name;
							const invoiceDecription = response.InvoiceData.Description ? response.InvoiceData.Description : 'N/A';

							let statusBadge = '';

							if (response.InvoiceData.Status === 'Fully Paid') {
								statusBadge = '<span class="badge badge-info">Fully Paid</span>';
							} else if (response.InvoiceData.Status === 'Partially Paid') {
								statusBadge = '<span class="badge badge-warning">Partially Paid</span>';
							} else if (response.InvoiceData.Status === 'Unpaid') {
								statusBadge = '<span class="badge badge-danger">Unpaid</span>';
							}

							// Populate invoice details
							$('#invoiceNo').text(response.InvoiceData.Invoice_No);
							$('#customerName').text(response.InvoiceData.Customer_Name);
							$('#status').html(statusBadge);
							$('#paymentDate').text(paymentDate);
							$('#invoiceDate').text(response.InvoiceData.Invoice_Date);
							$('#invoiceBy').text(invoiceBy);
							$('#saleType').text(response.InvoiceData.Sale_Type);
							$('#itemCount').text(response.InvoiceData.Item_Count);
							$('#subTotal').text(response.InvoiceData.Sub_Total);
							$('#discountTotal').text(response.InvoiceData.Discount_Total);

							//Charges
							formatCharge('#serviceCharge', response.InvoiceData.ServiceCharge_IsPercentage, response.InvoiceData.ServiceCharge);
							formatCharge('#taxCharge', response.InvoiceData.Tax_IsPercentage, response.InvoiceData.Tax);
							formatCharge('#vatCharge', response.InvoiceData.Vat_IsPercentage, response.InvoiceData.Vat);
							formatCharge('#deliveryCharge', response.InvoiceData.Delivery_IsPercentage, response.InvoiceData.Delivery);


							$('#grandTotal').text(response.InvoiceData.Grand_Total);
							$('#paidAmount').text(response.InvoiceData.Paid_Amount);
							$('#balanceAmount').text(response.InvoiceData.Balance_Total);
							$('#dueAmount').text(response.InvoiceData.Due_Total);
							$('#paymentMethod').text(response.InvoiceData.Payment_Type);
							$('#description').text(invoiceDecription);

							// Destroy existing DataTable, if any
							$('#productListTable').DataTable().destroy();
							$('#paymentListTable').DataTable().destroy();

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

							var paymentTable = $('#paymentListTable').DataTable({
								searching: true,
								columnDefs: [{
										targets: [3, 4, 6, 7],
										className: 'text-right'
									},
									{
										targets: 5,
										className: 'text-center'
									}
								]
							});

							// Clear existing rows
							Itemtable.clear();
							paymentTable.clear();

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

							// Populate payment details
							if (response.payments && response.payments.length > 0) {
								$.each(response.payments, function(index, payments) {
									const formattedPaidAmount = 'LKR: ' + payments.Paid_Amount;
									const formattedBalanceTotal = 'LKR: ' + payments.Balance_Total;
									const formattedDueTotal = 'LKR: ' + payments.Due_Total;

									paymentTable.row.add([
										`${payments.Invoice_Id}/${payments.Payment_Id}`,
										payments.Payment_Date || 'N/A',
										payments.Description || 'N/A',
										formattedPaidAmount,
										formattedBalanceTotal,
										payments.Payment_Type || 'N/A',
										formattedDueTotal,
										`${payments.First_Name} ${payments.Last_Name}`
									]);
								});
							} else {
								paymentTable.row.add(['', '', '', 'No payment details available', '', '', '', '']).draw();
							}


							paymentTable.draw();
						},
						error: function(xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				}

				// Get the Invoice_No from the URL
				const urlParams = new URLSearchParams(window.location.search);
				const Invoice_Id = urlParams.get('Invoice_No');

				// Fetch and display invoice data
				fetchInvoiceData(Invoice_Id);

				// Function to edit details
				$('#updatePaymentForm').submit(function(event) {

					event.preventDefault();

					$('#pageLoader').show(); // Show loader before sending

					$.ajax({
						type: 'POST',
						url: '../../API/Admin/updateInvoice.php',
						data: $(this).serialize(),
						success: function(response) {
							// Parse the response as a JSON object (if not already parsed)
							if (typeof response === 'string') {
								response = JSON.parse(response);
							}

							// Show the appropriate modal based on response
							showUpdateAlerts(response);

							// Log the response for debugging
							console.log(response);
						},
						error: function(xhr, status, error) {
							console.error('Error:', status, error);
							$('#Update_Details').modal('hide');
							$('#UpdateFailedModel').modal('show');
						},
						complete: function() {
							$('#pageLoader').hide(); // Hide loader after response (success or error)
						}
					});
				});

				// Handle the "Ok" button click in the UpdateSuccessModel
				$('#UpdateSuccessModel #OkBtn').on('click', function() {
					// Refresh the page when "Ok" is clicked
					window.location.href = 'sales_invoice.php';
				});

				// Function to delete details
				$('#deleteDetailsForm').submit(function(event) {

					event.preventDefault();

					$('#pageLoader').show(); // Show loader before sending

					$.ajax({
						type: 'POST',
						url: '../../API/Admin/deleteInvoice.php',
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
					window.location.href = 'sales_invoice.php';
				});

				// Handle Number input validation
				$('#updatePaymentForm').on('input', '.currency-input', function() {
					var enteredValue = parseFloat($(this).val());

					// Validate the entered quantity
					if (isNaN(enteredValue) || enteredValue < 0) {
						$(this).val('');
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

				// Calculate amounts on input or keydown in Paid_Amount field
				$('#Paid_Amount').on('blur keydown', function(event) {

					if (event.type === 'keydown' && event.key !== 'Enter') {
						return;
					}

					let paidAmount = parseFloat($(this).val()) || 0;
					let dueTotal = parseFloat($('#Temp_Due_Total').val()) || 0;
					let balanceTotal = parseFloat($('#Temp_Balance_Total').val()) || 0;

					if (dueTotal === 0) {
						$('#Update_Details').modal('hide');
						$('#AlreadyPaidModel').modal('show');
						$(this).val('');
					}

					if (paidAmount === 0) {
						$('#Due_Total').val(number_format(dueTotal, 2));
						$('#Balance_Total').val(number_format(balanceTotal, 2));
					}

					if (paidAmount <= dueTotal) {
						// If Paid_Amount <= Due_Total
						$('#Due_Total').val(number_format(dueTotal - paidAmount, 2));
					} else {
						// If Paid_Amount > Due_Total
						$('#Balance_Total').val(number_format(balanceTotal + (paidAmount - dueTotal), 2));
						$('#Due_Total').val('0.00'); // Due_Total becomes 0
					}
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