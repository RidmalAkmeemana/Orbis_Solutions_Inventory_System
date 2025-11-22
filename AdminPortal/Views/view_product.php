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

// Check if user has access to updateProduct.php
$has_access_to_edit_product = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 118") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_edit_product = true;
}

// Check if user has access to deleteProduct.php
$has_access_to_delete_product = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 107") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_delete_product = true;
}

// Check if user has access to viewCustomerData.php
$has_access_to_view_customer_data = false;
$permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 121") or die(mysqli_error());
if (mysqli_num_rows($permission_query) > 0) {
	$has_access_to_view_customer_data = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title><?php echo ($companyName); ?> - Products</title>

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
					$Product_Id = $_REQUEST["Product_Id"];
					$query1 = mysqli_query($conn, "SELECT 
								pd.Id, 
								pd.Product_Id, 
								p.Product_Name,
								b.Brand_Id,
								c.Category_Id, 
								c.Category_Name,
								s.Supplier_Id, 
								s.Supplier_Name
							FROM 
								tbl_product_details pd
							JOIN 
								tbl_product p ON pd.Product_Id = p.Product_Id
							JOIN 
								tbl_brand b ON p.Brand_Id = b.Brand_Id
							JOIN 
								tbl_category c ON p.Category_Id = c.Category_Id
							JOIN 
								tbl_suppliers s ON pd.Supplier_Id = s.Supplier_Id
							WHERE 
								pd.Product_Id = '$Product_Id'") or die(mysqli_error());
					$fetch1 = mysqli_fetch_array($query1);
					?>

					<!-- Edit and Delete Buttons -->
					<div class="row">
						<div class="col-md-12 text-left">


						</div>
					</div>

					<div class="row">
						<div class="col-md-12 text-left">
							<?php if ($has_access_to_edit_product): ?>
								<a href="#Update_Product" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
							<?php else: ?>
								<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
							<?php endif; ?>

							<?php if ($has_access_to_delete_product): ?>
								<a href="#Delete_Product" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
							<?php else: ?>
								<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
							<?php endif; ?>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 text-center mt-4 position-relative">
							<div class="background-container" style="background-image: url('assets/img/cover.png');">
								<div class="col-md-12 text-center mt-4">
									<h5 class="page-title">
										<h1 class="text-xs font-weight-bold text-uppercase mb-1" id="productName"></h1>
										<h5 class="text-xs font-weight-bold text-uppercase mb-1" id="productId"></h5>
										<a href="home.php" class="breadcrumb-item" style="color: black;"><i class="fa fa-home"></i> Home</a>
										<a href="add_products.php" class="breadcrumb-item active">Products</a>
									</h5>
								</div>
							</div>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Product Brand</h5>
								<p class="mx-auto" id="productBrand"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Product Category</h5>
								<p class="mx-auto" id="productCategory"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Cost of Product</h5>
								<p class="mx-auto" id="cost"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Landing Cost of Product</h5>
								<p class="mx-auto" id="landingCost"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Retail Price</h5>
								<p class="mx-auto" id="retailPrice"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Wholesale Price</h5>
								<p class="mx-auto" id="wholesalePrice"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Available Qty</h5>
								<p class="mx-auto" id="availableQty"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Supplier Name</h5>
								<p class="mx-auto" id="supplierName"></p>
							</h5>
						</div>

						<div class="col-md-4 text-left mt-4">
							<h5 class="page-title">
								<h5 class="text-xs font-weight-bold mb-1">Received Date</h5>
								<p class="mx-auto" id="receivedDate"></p>
							</h5>
						</div>

						<div class="col-md-12 text-left mt-4">
							<h5 class="page-title">
								<h5 class="tag-cloud text-xs font-weight-bold mb-1">List of Customers</h5>
							</h5>
							<br><br>
							<div class="table-responsive">
								<table class="datatable table table-hover table-center mb-0">
									<thead id="customerList">
										<tr>
											<th>Customer ID</th>
											<th>Customer Name</th>
											<th>Qty Purchased</th>

											<?php if ($has_access_to_view_customer_data): ?>
												<th>Action</th>
											<?php else: ?>
												<th style="display:none;">Action</th>
											<?php endif; ?>
										</tr>
									</thead>

									<tbody>
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

		<!-- Edit Details Modal-->
		<div class="modal fade" id="Update_Product" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Edit Product Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" action="../../API/Admin/updateProduct.php" id="updateProductForm" enctype="multipart/form-data">
							<div class="row form-row">

								<?php

								require_once '../Controllers/select_controller.php';

								$db_handle = new DBController();
								$countryResult = $db_handle->runQuery("SELECT * FROM tbl_brand ORDER BY Brand_Id ASC");
								$countryResult1 = $db_handle->runQuery("SELECT * FROM tbl_category ORDER BY Category_Id ASC");
								?>

								<div class="col-12">
									<div class="form-group">
										<label>Product Name</label><label class="text-danger">*</label>
										<input style="display:none;" type="text" name="Product_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Product_Id']; ?>">
										<input type="text" name="Product_Name" class="form-control" required="" value="<?php echo $fetch1['Product_Name']; ?>">
									</div>
								</div>

								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Brand Name</label><label class="text-danger">*</label>
										<select style="width:100%;" name="Brand_Id" id="brandSelect" class="form-control select2" required="">
											<option disabled>Select Brand</option>
											<?php
											if (!empty($countryResult)) {
												foreach ($countryResult as $key => $value) {
													$selected = ($countryResult[$key]['Brand_Id'] == $fetch1['Brand_Id']) ? 'selected' : '';
													echo '<option value="' . $countryResult[$key]['Brand_Id'] . '" ' . $selected . '>' . $countryResult[$key]['Brand_Name'] . '</option>';
												}
											}
											?>
										</select>
									</div>
								</div>

								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Category Name</label><label class="text-danger">*</label>
										<select style="width:100%;" name="Category_Id" id="categorySelect" class="form-control select2" required="">
											<option disabled>Select Category</option>
											<?php
											if (!empty($countryResult1)) {
												foreach ($countryResult1 as $key => $value) {
													$selected = ($countryResult1[$key]['Category_Id'] == $fetch1['Category_Id']) ? 'selected' : '';
													echo '<option value="' . $countryResult1[$key]['Category_Id'] . '" ' . $selected . '>' . $countryResult1[$key]['Category_Name'] . '</option>';
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
		<div class="modal fade" id="Delete_Product" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-content p-2">
							<h4 class="modal-title">Delete <?php echo $fetch1['Product_Name']; ?></h4>
							<p class="mb-4">Are you sure want to delete ?</p>

							<form method="POST" action="../../API/Admin/deleteProduct.php" id="deleteProductForm" enctype="multipart/form-data">
								<input style="display: none;" type="text" name="Product_Id" value="<?php echo $fetch1['Product_Id']; ?>">
								<button type="submit" name="delete" class="btn btn-primary btn-block">Delete </button>
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
				
				$('#brandSelect, #categorySelect').select2();

				// Function to show and hide alerts based on response
				function showUpdateAlerts(response) {
					// Hide the Update Brand modal before showing any alert modals
					$('#Update_Product').modal('hide');

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
					$('#Delete_Product').modal('hide');

					if (response.success === 'true') {
						// Show DeleteSuccessModel only if success is true
						$('#DeleteSuccessModel').modal('show');
					} else {
						// Show DeleteFailedModel for any other failure scenario
						$('#DeleteFailedModel').modal('show');
					}
				}

				// Function to fetch and display product data
				function fetchProductData(Product_Id) {
					$.ajax({
						type: 'GET',
						url: '../../API/Admin/viewProductData.php',
						data: {
							Product_Id: Product_Id
						},
						dataType: 'json',
						success: function(response) {
							if (!response.productData) {
								console.error('Failed to fetch category data');
								return;
							}

							$('#productName').text(response.productData.Product_Name);
							$('#productId').text(response.productData.Product_Id);
							$('#productBrand').text(response.productData.Brand_Name);
							$('#productCategory').text(response.productData.Category_Name);
							$('#cost').text('LKR: ' + response.productData.Cost);
							$('#landingCost').text('LKR: ' + response.productData.Landing_Cost);
							$('#retailPrice').text('LKR: ' + response.productData.Retail_Price);
							$('#wholesalePrice').text('LKR: ' + response.productData.Wholesale_Price);
							$('#availableQty').text(response.productData.SumQty);
							$('#supplierName').text(response.productData.Supplier_Name);
							$('#receivedDate').text(response.productData.Received_Date);

							// Destroy existing DataTable, if any
							$('.datatable').DataTable().destroy();

							// Initialize DataTable
							var table = $('.datatable').DataTable({
								searching: true, // Enable search
							});

							// Clear existing rows
							table.clear();

							// Populate customers list
							if (response.customers.length > 0) {
								$.each(response.customers, function(index, customers) {

									var hasAccessToViewcustomerData = <?php echo json_encode($has_access_to_view_customer_data); ?>;

									let actions = '';
									if (hasAccessToViewcustomerData) {
										actions = '<a href="view_customer.php?Customer_Id=' + customers.Customer_Id + '" class="btn btn-sm bg-success-light add-to-inventory"><i class="fe fe-eye"></i> View </a>';
									} else {
										actions = '<a style="display:none;" href="#" class="btn btn-sm bg-success-light add-to-inventory"><i class="fe fe-eye"></i> View </a>';
									}

									table.row.add([
										customers.Customer_Id,
										customers.Customer_Name,
										customers.Qty_Purchased,
										actions
									]);
								});
							} else {
								console.log('No data received.');
							}

							// Draw the table
							table.draw();
						},
						error: function(xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				}

				// Get the Product_Id from the URL
				const urlParams = new URLSearchParams(window.location.search);
				const Product_Id = urlParams.get('Product_Id');
				// Fetch and display category data
				fetchProductData(Product_Id);

				// Update product
				$('#updateProductForm').on('submit', function(event) {

					event.preventDefault();

					$('#pageLoader').show(); // Show loader before sending

					$.ajax({
						type: 'POST',
						url: '../../API/Admin/updateProduct.php',
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
							$('#Update_Product').modal('hide');
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
					window.location.href = 'add_products.php';
				});

				// Delete product
				$('#deleteProductForm').on('submit', function(event) {

					event.preventDefault();

					$('#pageLoader').show(); // Show loader before sending

					$.ajax({
						type: 'POST',
						url: '../../API/Admin/deleteProduct.php',
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
							$('#Delete_Product').modal('hide');
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
					window.location.href = 'add_products.php';
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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->

</html>