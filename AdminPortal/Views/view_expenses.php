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

    // Check if user has access to updateExpenses.php
    $has_access_to_edit_expenses = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 163") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_edit_expenses = true;
    }

	// Check if user has access to deleteExpenses.php
    $has_access_to_delete_expenses = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 164") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_delete_expenses = true;
    }

	// Check if user has access to expensePayment.php
    $has_access_to_payment_expenses = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 165") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_payment_expenses = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>O<?php echo($companyName); ?> - Expenses</title>
		
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
			.background-container 
			{
    			background-size: cover;
    			background-position: center;
    			height: 250px; /* Adjust the height as needed */
    			display: flex;
    			align-items: center;
    			justify-content: center;
    			text-align: center;
			}

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
			/* Flexbox container to align radio buttons in a line */
			.payment-methods {
				display: flex;
				flex-wrap: wrap; /* Allows wrapping if there's not enough space */
				gap: 15px; /* Space between radio buttons, adjust as needed */
			}

			.form-check {
				margin-bottom: 0; /* Remove default margin */
			}

			/* Ensure proper alignment and spacing for labels and inputs */
			.form-check-input {
				margin-right: 5px; /* Space between radio button and label */
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

					<!-- /Update-Alerts -->
						<div style="display:none" id="UpdateSuccessAlert" class="alert alert-info" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b>Success!</b> Data Updated Successfully</div>
						<div style="display:none" id="UpdateFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b>Failed!</b> Data Update Unsuccessfull</div>
					<!-- /Update-Alerts -->

					<!-- /Delete-Alerts -->
						<div style="display:none" id="DeleteSuccessAlert" class="alert alert-danger" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b>Success!</b> Data Deleted Successfully</div>
						<div style="display:none" id="DeleteFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b>Failed!</b> Data Delete Unsuccessfull</div>
					<!-- /Delete-Alerts -->
				
					<!-- Page Header -->
					<div class="page-header">
						 <?php
                    			$Expense_Id = $_REQUEST["Expense_Id"]; 
                    			$query1 = mysqli_query($conn, "SELECT e.*, et.Expense_Type FROM tbl_expenses AS e JOIN tbl_expenses_types AS et ON e.Expense_Type_Id = et.Expense_Type_Id WHERE `Expense_Id` = '$Expense_Id'") or die(mysqli_error());
                    			$fetch1 = mysqli_fetch_array($query1);
                    	?>

								<!-- Edit and Delete Buttons -->
    							<div class="row">
									<div class="col-md-12 text-left">
										<?php if ($has_access_to_edit_expenses): ?>
                                			<a href="#Update_Expense" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php endif; ?>

										<?php if ($has_access_to_delete_expenses): ?>
                                			<a href="#Delete_Expense" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
                            			<?php endif; ?>

										<?php if ($has_access_to_payment_expenses): ?>
                                			<a href="#Payment_Expense" data-toggle="modal" class="btn btn bg-warning-light"><i class="fa fa-usd"></i> Payment</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fa fa-usd"></i> Payment</a>
                            			<?php endif; ?>
        							</div>
    							</div>

								<div class="row">
    								<div class="col-md-12 text-center mt-4 position-relative">
        								<div class="background-container" style="background-image: url('assets/img/cover.png');">
            								<div class="col-md-12 text-center mt-4">
                								<h5 class="page-title">
                    								<h1 class="text-xs font-weight-bold text-uppercase mb-1" id="expenseTitle"></h1>
                                                    <h5 class="text-xs font-weight-bold text-uppercase mb-1" id="expenseId"></h5>
                    									<a href="home.php" class="breadcrumb-item" style="color: black;"><i class="fa fa-home"></i> Home</a>
                    									<a href="add_expenses.php" class="breadcrumb-item active">Expenses</a>
                								</h5>
            								</div>
        								</div>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Expense Type</h5>
											<p class="mx-auto" id="ExpenseType"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Status</h5>
											<p class="mx-auto" id="Status"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Added On</h5>
											<p class="mx-auto" id="AddedOn"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Added By</h5>
											<p class="mx-auto" id="AddedBy"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Total Amount</h5>
											<p class="mx-auto" id="TotalAmount"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Support Document</h5>
											<p class="mx-auto" id="SupportDocument"></p>
        								</h5>
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
											<label class="mb-0" style="white-space: nowrap;"><strong>Paid Amount (LKR): <span id="PaidAmount"></span></strong>
										</div>
									</div>
									<!-- Balance Amount -->
									<div class="col-md-4 mt-4">
										<div class="form-group">
											<label class="mb-0" style="white-space: nowrap;"><strong>Payment Method: </strong><span id="PaymentMethod"></span>
										</div>
									</div>
									<!-- Due Amount -->
									<div class="col-md-4 mt-4">
										<div class="form-group">
											<label class="mb-0" style="white-space: nowrap;"><strong>Due Amount (LKR): <span id="DueAmount"></span></strong>
										</div>
									</div>
								</div>		

								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label><strong>Description</strong></label>
												<textarea id="description" name="Expense_Description" class="form-control" rows="8" readonly></textarea>
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
														<th>Voucher No</th>
														<th>Payment Date</th>
														<th>Description</th>
														<th>Paid Amount</th>
														<th>Payment Type</th>
														<th>Due Amount</th>
														<th>Updated By</th>
													</tr>
												</thead>

												<tbody id="paymentList">
													<!-- Data will be populated here -->
												</tbody>

											</table>
										</div>
									</div>
								</div>
					</div>
				</div>
			</div>

			<!-- Edit Details Modal-->
			<div class="modal fade" id="Update_Expense" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit Expense Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/updateExpenses.php" id="updateExpenseForm" enctype="multipart/form-data">
								<div class="row form-row">

								<?php
                                    
									require_once '../Controllers/select_controller.php';

									$db_handle = new DBController();
									$countryResult = $db_handle->runQuery("SELECT * FROM tbl_expenses_types ORDER BY Expense_Type_Id ASC");
								?>

									<div class="col-12">
										<div class="form-group">
											<label>Expense Type</label><label class="text-danger">*</label>
											<input style="display:none;" type="text" name="Expense_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Expense_Id']; ?>">
											<input type="text" name="Expense_Title" class="form-control" required="" value="<?php echo $fetch1['Expense_Title']; ?>">
										</div>
									</div>

									<div class="col-12">
        								<div class="form-group">
            								<label>Expense Category</label><label class="text-danger">*</label>
            									<select style="width:100%;" name="Expense_Type_Id" id="expenseCategorySelect" class="form-control select2" required="">
                									<option disabled>Select Category</option>
                										<?php
                    										if (!empty($countryResult)) {
                        										foreach ($countryResult as $key => $value) {
                            										$selected = ($countryResult[$key]['Expense_Type_Id'] == $fetch1['Expense_Type_Id']) ? 'selected' : '';
                            										echo '<option value="' . $countryResult[$key]['Expense_Type_Id'] . '" ' . $selected . '>' . $countryResult[$key]['Expense_Type'] . '</option>';
                        										}
                    										}
                										?>
            									</select>
        								</div>
    								</div>
									
								</div>
								<button type="submit" name="edit" class="btn btn-primary btn-block">Update Changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--/Edit Details Modal -->

			<!-- Delete Modal -->
			<div class="modal fade" id="Delete_Expense" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Delete</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-content p-2">
								<h4 class="modal-title">Delete <?php echo $fetch1['Expense_Id']; ?></h4>
								<p class="mb-4">Are you sure want to delete ?</p>

								<form method="POST" action="../../API/Admin/deleteExpenses.php" id="deleteExpenseForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Expense_Id" value="<?php echo $fetch1['Expense_Id']; ?>">
									<button type="submit" name="delete" class="btn btn-primary btn-block">Delete </button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/Delete Modal -->

			<!-- Edit Payment Modal-->
			<div class="modal fade" id="Payment_Expense" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Update Payment Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/expensePayment.php" id="expensePaymentForm" enctype="multipart/form-data">
								<div class="row form-row">
									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Total Amount</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input style="display:none;" type="text" name="Updated_By" class="form-control" required="" readonly="true" value="<?php echo $fetch['Id']; ?>">
												<input style="display:none;" type="text" name="Expense_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Expense_Id']; ?>">
												<input style="display:none;" type="text" name="Expense_Type" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Expense_Type']; ?>">
												<input type="number" id="Expense_Amount" name="Expense_Amount" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Expense_Amount'], 2, '.', ''); ?>">
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
											<label>Due Amount</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input style="display:none;" type="number" id="Temp_Due_Amount" name="Temp_Due_Amount" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Due_Amount'], 2, '.', ''); ?>">
												<input type="number" id="Due_Amount" name="Due_Amount" class="form-control text-right currency-input" min="1" step="any" readonly value="<?php echo number_format((float)$fetch1['Due_Amount'], 2, '.', ''); ?>">
											</div>
										</div>
									</div>

									<div class="col-12 col-sm-6">
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
											<textarea id="my-text" name="Expense_Description" class="form-control" rows="8"><?php echo $fetch1['Expense_Description']; ?></textarea>
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
		<script  src="assets/js/script.js"></script>

		<!-- Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

		<script>
    
			$(document).ready(function () {
			// Initialize Select2 for expense category dropdown
			$('#expenseCategorySelect').select2();

			// Function to hide alerts after a delay
			function hideAlerts() {
				$('#UpdateSuccessAlert, #UpdateFailedAlert, #DeleteSuccessAlert, #DeleteFailedAlert').fadeOut(3000);
			}

			// Function to show and hide alerts for update responses
			function showUpdateAlerts(success) {
				if (success === 'true') {
					$('#UpdateSuccessAlert').fadeIn();
				} else {
					$('#UpdateFailedAlert').fadeIn();
				}
				hideAlerts();
			}

			// Function to show and hide alerts for delete responses
			function showDeleteAlerts(success) {
				if (success === 'true') {
					$('#DeleteSuccessAlert').fadeIn();
				} else {
					$('#DeleteFailedAlert').fadeIn();
				}
				hideAlerts();
			}

			// Function to fetch and display expense data
			function fetchExpenseData(Expense_Id) {
				$.ajax({
					type: 'GET',
					url: '../../API/Admin/viewExpenseData.php',
					data: { Expense_Id: Expense_Id },
					dataType: 'json',
					success: function (response) {
						if (!response.success) {
							console.error('Failed to fetch expense data');
							return;
						}

						const data = response.ExpenseData;
						
						// Populate expense details
						$('#expenseTitle').text(data.Expense_Title);
						$('#expenseId').text(data.Expense_Id);
						$('#ExpenseType').text(data.Expense_Type);
						$('#Status').html(getStatusBadge(data.Status));
						$('#AddedOn').text(data.Expence_Date || 'N/A');
						$('#AddedBy').text(`${data.First_Name} ${data.Last_Name}` || 'N/A');
						$('#TotalAmount').text(`LKR: ${data.Expense_Amount}` || 'N/A');
						$('#PaidAmount').text(`LKR: ${data.Paid_Amount}` || 'N/A');
						$('#DueAmount').text(`LKR: ${data.Due_Amount}` || 'N/A');
						$('#PaymentMethod').text(data.Payment_Type || 'N/A');
						$('#description').val(data.Expense_Description || 'N/A');
						$('#SupportDocument').html(data.Doc ? `<span class="badge badge-light">${data.Doc.split('/').pop()}</span> <a style="margin-left: 10px;" href="${data.Doc}" target="_blank"><span class="badge badge-success"><i class="fe fe-eye"></i></span></a> <a href="${data.Doc}" target="_blank" download><span class="badge badge-dark"><i class="fa fa-download"></i></span></a>` : 'N/A');

						// Populate payments table
						populatePaymentsTable(response.payments);
					},
					error: function (xhr, status, error) {
						console.error('Error fetching expense data:', error);
					}
				});
			}

			// Function to return a status badge based on the status
			function getStatusBadge(status) {
				const statusMap = {
					'Fully Paid': '<span class="badge badge-info">Fully Paid</span>',
					'Partially Paid': '<span class="badge badge-warning">Partially Paid</span>',
					'Unpaid': '<span class="badge badge-danger">Unpaid</span>',
				};
				return statusMap[status] || '<span class="badge badge-secondary">Unknown</span>';
			}

			// Function to populate payments table
			function populatePaymentsTable(payments) {
				const table = $('#paymentListTable').DataTable({
					destroy: true,
					searching: true,
					columns: [
						{ title: 'Voucher No' },
						{ title: 'Payment Date' },
						{ title: 'Description' },
						{ title: 'Paid Amount', className: 'text-right' },
						{ title: 'Payment Type', className: 'text-center' },
						{ title: 'Due Amount', className: 'text-right' },
						{ title: 'Updated By' }
					]
				});

				table.clear();

				if (payments && payments.length > 0) {
					payments.forEach(payment => {
						const { Expense_Id, Payment_Id, Payment_Date, Expense_Description, Paid_Amount, Payment_Type, Due_Amount, First_Name, Last_Name } = payment;
						table.row.add([
							`${Expense_Id}/${Payment_Id}`,
							Payment_Date || 'N/A',
							Expense_Description || 'N/A',
							`LKR: ${Paid_Amount}`,
							Payment_Type || 'N/A',
							`LKR: ${Due_Amount}`,
							`${First_Name} ${Last_Name}`
						]);
					});
				} else {
					console.log('No payment data available.');
				}

				table.draw();
			}

			// Get Expense_Id from the URL and fetch data
			const urlParams = new URLSearchParams(window.location.search);
			const Expense_Id = urlParams.get('Expense_Id');
			if (Expense_Id) {
				fetchExpenseData(Expense_Id);
			} else {
				console.error('Expense_Id not found in URL');
			}

			// Handle update form submission
			$('#updateExpenseForm').submit(function (event) {
				event.preventDefault();
				$.ajax({
					type: 'POST',
					url: '../../API/Admin/updateExpenses.php',
					data: $(this).serialize(),
					success: function (response) {
						showUpdateAlerts(response.success);
						if (response.success === 'true') {
							$('#Update_Expense').modal('hide');
							setTimeout(() => {
								window.location.href = 'add_expenses.php';
							}, 3000);
						}
					},
					error: function (xhr, status, error) {
						console.error('Error updating expense type:', error);
					}
				});
			});

			// Handle delete form submission
			$('#deleteExpenseForm').submit(function (event) {
				event.preventDefault();
				$.ajax({
					type: 'POST',
					url: '../../API/Admin/deleteExpenses.php',
					data: $(this).serialize(),
					success: function (response) {
						showDeleteAlerts(response.success);
						if (response.success === 'true') {
							$('#Delete_Expense').modal('hide');
							setTimeout(() => {
								window.location.href = 'add_expenses.php';
							}, 3000);
						}
					},
					error: function (xhr, status, error) {
						console.error('Error deleting expense type:', error);
					}
				});
			});

				// Function to edit details
				$('#expensePaymentForm').submit(function (event) {
				event.preventDefault();
				$.ajax({
					type: 'POST',
					url: '../../API/Admin/expensePayment.php',
					data: $(this).serialize(),
					success: function (response) {
						showUpdateAlerts(response.success, response.error);
						if (response.success === 'true') {
							$('#Payment_Expense').modal('hide');
							setTimeout(function () {
								window.location.href = 'add_expenses.php';
							}, 3000);
						}
					},
					error: function (xhr, status, error) {
						console.error('Error:', status, error);
					}
				});
			});

				// Handle Number input validation
				$('#expensePaymentForm').on('input', '.currency-input', function () {
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
			$('#Paid_Amount').on('blur keydown', function (event) {

			if (event.type === 'keydown' && event.key !== 'Enter') {
				return;
			}

			let paidAmount = parseFloat($(this).val()) || 0;
			let dueAmount = parseFloat($('#Temp_Due_Amount').val()) || 0;

			if (dueAmount === 0) {
				alert("This Expense has already been Paid");
				$(this).val('');
			} else if (paidAmount === 0) {
				$('#Due_Total').val(number_format(dueAmount, 2));
			} else if (paidAmount <= dueAmount) {
				// If Paid_Amount <= Due_Total
				$('#Due_Amount').val(number_format(dueAmount - paidAmount, 2));
			} else {
				// If Paid_Amount > Due_Total and dueAmount is not 0
				$('#Due_Amount').val('0.00'); // Due_Total becomes 0
				alert("Max Amount payable: " + number_format(dueAmount, 2));
				$('#Paid_Amount').val(number_format(dueAmount, 2)); // Paid_Amount becomes Due_Amount
			}
			});

			// Helper function to format numbers as strings
			function number_format(number, decimals) {
			return parseFloat(number).toFixed(decimals);
			}
		});
		</script>
		
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->
</html>