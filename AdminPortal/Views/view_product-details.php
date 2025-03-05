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
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 117") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_edit_details = true;
    }

	// Check if user has access to deleteDetails.php
    $has_access_to_delete_details = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 106") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_delete_details = true;
    }

	// Check if user has access to addInventory.php
    $has_access_to_add_inventories = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 131") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_add_inventories = true;
    }

	// Check if user has access to removeInventory.php
    $has_access_to_remove_inventories = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 114") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_remove_inventories = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Product Details</title>
		
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
						<div style="display:none" id="UpdateSuccessAlert" class="alert alert-info" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b> Success!</b> Data Updated Successfully</div>
						<div style="display:none" id="UpdateFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b> Failed!</b> Data Update Unsuccessfull</div>
					<!-- /Update-Alerts -->

					<!-- /Delete-Alerts -->
						<div style="display:none" id="DeleteSuccessAlert" class="alert alert-danger" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b> Success!</b> Data Deleted Successfully</div>
						<div style="display:none" id="DeleteFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b> Failed!</b> Data Delete Unsuccessfull</div>
					<!-- /Delete-Alerts -->

					<!-- /Inventory-Alerts -->
						<div style="display:none" id="InventorySuccessAlert" class="alert alert-warning" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b> Success!</b> Inventory Updated Successfully</div>
						<div style="display:none" id="InventoryFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b> Failed!</b> Inventory Update Unsuccessfull</div>
					<!-- /Inventory-Alerts -->
				
					<!-- Page Header -->
					<div class="page-header">
						 <?php
                    			$Id = $_REQUEST["Id"]; 
                    			$query1 = mysqli_query($conn, "SELECT 
								pd.Id, 
								pd.Product_Id,
								pd.Cost,
								pd.Landing_Cost,
								pd.Retail_Price,
								pd.Wholesale_Price,
								pd.Qty, 
								p.Product_Name,
								c.Category_Id, 
								c.Category_Name,
								s.Supplier_Id, 
								s.Supplier_Name
							FROM 
								tbl_product_details pd
							JOIN 
								tbl_product p ON pd.Product_Id = p.Product_Id
							JOIN 
								tbl_category c ON p.Category_Id = c.Category_Id
							JOIN 
								tbl_suppliers s ON pd.Supplier_Id = s.Supplier_Id
							WHERE 
								pd.Id = '$Id'") or die(mysqli_error());
                    			$fetch1 = mysqli_fetch_array($query1);
                    	?>

								<!-- Edit and Delete Buttons -->
    							<div class="row">
									<div class="col-md-12 text-left">
										<?php if ($has_access_to_edit_details): ?>
                                			<a href="#Update_Details" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php endif; ?>

										<?php if ($has_access_to_delete_details): ?>
                                			<a href="#Delete_Details" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
                            			<?php endif; ?>
										
										<?php if ($has_access_to_add_inventories): ?>
                                			<a id="AddInventoryButton" href="#Add_Inventory" data-toggle="modal" class="btn btn bg-success-light"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Active</a>
                            			<?php endif; ?>

                            			<?php if ($has_access_to_remove_inventories): ?>
                                			<a id="RemoveInventoryButton" href="#Remove_Inventory" data-toggle="modal" class="btn btn bg-warning-light"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Inactive</a>
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
                    									<a href="add_product-history.php" class="breadcrumb-item active">Product History</a>
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
            								<h5 class="text-xs font-weight-bold mb-1">Received Qty</h5>
											<p class="mx-auto" id="receivedQty"></p>
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

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Product Status</h5>
											<p class="mx-auto" id="inventory"></p>
        								</h5>
    								</div>
								</div>
					</div>
				</div>
			</div>

			<!-- Edit Details Modal-->
			<div class="modal fade" id="Update_Details" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit Product Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/updateDetails.php" id="updateDetailsForm" enctype="multipart/form-data">
								<div class="row form-row">

								<?php
                                    
									require_once '../Controllers/select_controller.php';

									$db_handle = new DBController();
									$countryResult = $db_handle->runQuery("SELECT * FROM tbl_suppliers ORDER BY Supplier_Id ASC");
								?>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Cost of Product</label><label class="text-danger">*</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input style="display:none;" type="text" name="Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Id']; ?>">
												<input type="number" name="Cost" class="form-control text-right currency-input" min="1" step="any" required="" value="<?php echo number_format((float)$fetch1['Cost'], 2, '.', ''); ?>">
											</div>
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Landing Cost</label><label class="text-danger">*</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input type="number" name="Landing_Cost" class="form-control text-right currency-input" min="1" step="any" required="" value="<?php echo number_format((float)$fetch1['Landing_Cost'], 2, '.', ''); ?>">
											</div>
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Retail Price</label><label class="text-danger">*</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input type="number" name="Retail_Price" class="form-control text-right currency-input" min="1" step="any" required="" value="<?php echo number_format((float)$fetch1['Retail_Price'], 2, '.', ''); ?>">
											</div>
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Wholesale Price</label><label class="text-danger">*</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input type="number" name="Wholesale_Price" class="form-control text-right currency-input" min="1" step="any" required="" value="<?php echo number_format((float)$fetch1['Wholesale_Price'], 2, '.', ''); ?>">
											</div>
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Received Qty</label><label class="text-danger">*</label>
											<input type="number" name="Qty" class="form-control qty-input" min="1" required="" value="<?php echo (int)$fetch1['Qty']; ?>">
										</div>
									</div>

									<div class="col-12 col-sm-6">
        								<div class="form-group">
            								<label>Supplier Name</label><label class="text-danger">*</label>
            									<select style="width:100%;" name="Supplier_Id" id="supplierSelect" class="form-control select2" required="">
                									<option disabled>Select Supplier</option>
                										<?php
                    										if (!empty($countryResult)) {
                        										foreach ($countryResult as $key => $value) {
                            										$selected = ($countryResult[$key]['Supplier_Id'] == $fetch1['Supplier_Id']) ? 'selected' : '';
                            										echo '<option value="' . $countryResult[$key]['Supplier_Id'] . '" ' . $selected . '>' . $countryResult[$key]['Supplier_Name'] . '</option>';
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
			<div class="modal fade" id="Delete_Details" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Delete Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-content p-2">
								<h4 class="modal-title">Delete <?php echo $fetch1['Product_Name']; ?> Details</h4>
								<p class="mb-4">Are you sure want to delete ?</p>

								<form method="POST" action="../../API/Admin/deleteDetails.php" id="deleteDetailsForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Id" value="<?php echo $fetch1['Id']; ?>">
									<button type="submit" name="delete" class="btn btn-primary btn-block">Delete </button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/Delete Modal -->

			<!-- Add Inventory Modal -->
			<div class="modal fade" id="Add_Inventory" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Add To Inventory</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-content p-2">
								<h4 class="modal-title">Confirmation !</h4>
								<p class="mb-4">Are you sure want to Update the Inventory ?</p>

								<form method="POST" action="../../API/Admin/addInventory.php" id="addInventoryForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Id" value="<?php echo $fetch1['Id']; ?>">
									<button type="submit" name="delete" class="btn btn-primary btn-block">Confirm </button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/Add Inventory Modal -->

			<!-- Remove Inventory Modal -->
			<div class="modal fade" id="Remove_Inventory" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Remove From Inventory</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-content p-2">
								<h4 class="modal-title">Confirmation !</h4>
								<p class="mb-4">Are you sure want to Update the Inventory ?</p>

								<form method="POST" action="../../API/Admin/removeInventory.php" id="removeInventoryForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Id" value="<?php echo $fetch1['Id']; ?>">
									<button type="submit" name="delete" class="btn btn-primary btn-block">Confirm </button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/Remove Inventory Modal -->

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

				$('#supplierSelect').select2();

				// Function to hide alerts
				function hideAlerts() {
					$('#UpdateSuccessAlert, #UpdateFailedAlert, #DeleteSuccessAlert, #DeleteFailedAlert').fadeOut(3000);
				}

				// Function to show and hide alerts based on response for updating
				function showUpdateAlerts(success, error) {
					if (success === 'true') {
						$('#UpdateSuccessAlert').fadeIn();
						hideAlerts();
					} else {
						$('#UpdateFailedAlert').fadeIn();
						hideAlerts();
					}
				}

				// Function to show and hide alerts based on response for deleting
				function showDeleteAlerts(success, error) {
					if (success === 'true') {
						$('#DeleteSuccessAlert').fadeIn();
						hideAlerts();
					} else {
						$('#DeleteFailedAlert').fadeIn();
						hideAlerts();
					}
				}

				// Function to show and hide alerts based on response for inventory updating
				function showInventoryAlerts(success, error) {
					if (success === 'true') {
						$('#InventorySuccessAlert').fadeIn();
						hideAlerts();
					} else {
						$('#InventoryFailedAlert').fadeIn();
						hideAlerts();
					}
				}

				// Function to fetch and display product data
				function fetchProductData(Id) {
					$.ajax({
						type: 'GET',
						url: '../../API/Admin/viewProductDetails.php',
						data: { Id: Id },
						success: function (response) {
							if (!response.productData) {
								console.error('Failed to fetch product data');
								return;
							}

							const productData = response.productData;
							const productQueued = response.productQueued;

							$('#productName').text(productData.Product_Name);
							$('#productId').text(productData.Product_Id);
							$('#productBrand').text(productData.Brand_Name);
							$('#productCategory').text(productData.Category_Name);
							$('#cost').text('LKR: '+productData.Cost);
							$('#landingCost').text('LKR: '+productData.Landing_Cost);
							$('#retailPrice').text('LKR: '+productData.Retail_Price);
							$('#wholesalePrice').text('LKR: '+productData.Wholesale_Price);
							$('#receivedQty').text(productData.Qty);
							$('#supplierName').text(productData.Supplier_Name);
							$('#receivedDate').text(productData.Received_Date);
							
							// Update inventory status with badge
							const inventoryStatus = productData.Inventort_Updated === 'True' 
								? '<span class="badge badge-info">Active</span>' 
								: '<span class="badge badge-danger">Inactive</span>';
							$('#inventory').html(inventoryStatus);

							// Show/hide buttons based on inventory status
							if (productData.Inventort_Updated === 'True') {
								$('#AddInventoryButton').hide();
								$('#RemoveInventoryButton').show();
							} else {
								$('#AddInventoryButton').show();
								$('#RemoveInventoryButton').hide();
							}

						},
						error: function (xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				}

				// Get the Id from the URL
				const urlParams = new URLSearchParams(window.location.search);
				const Id = urlParams.get('Id');
				// Fetch and display product data
				fetchProductData(Id);

				// Function to edit a details
				$('#updateDetailsForm').submit(function (event) {
					event.preventDefault();
					$.ajax({
						type: 'POST',
						url: '../../API/Admin/updateDetails.php',
						data: $(this).serialize(),
						success: function (response) {
							showUpdateAlerts(response.success, response.error);
							if (response.success === 'true') {
								$('#Update_Details').modal('hide');
								setTimeout(function () {
									window.location.href = 'add_product-history.php';
								}, 3000);
							}
						},
						error: function (xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				});

				// Function to delete a details
				$('#deleteDetailsForm').submit(function (event) {
					event.preventDefault();
					$.ajax({
						type: 'POST',
						url: '../../API/Admin/deleteDetails.php',
						data: $(this).serialize(),
						success: function (response) {
							showDeleteAlerts(response.success, response.error);
							if (response.success === 'true') {
								$('#Delete_Details').modal('hide');
								setTimeout(function () {
									window.location.href = 'add_product-history.php';
								}, 3000);
							}
						},
						error: function (xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				});

				// Function to add to inventory
				$('#addInventoryForm').submit(function (event) {
					event.preventDefault();
					$.ajax({
						type: 'POST',
						url: '../../API/Admin/addInventory.php',
						data: $(this).serialize(),
						success: function (response) {
							showInventoryAlerts(response.success, response.error);
							if (response.success === 'true') {
								$('#Add_Inventory').modal('hide');
								setTimeout(function () {
									window.location.href = 'add_product-history.php';
								}, 3000);
							}
						},
						error: function (xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				});

				// Function to remove from inventory
				$('#removeInventoryForm').submit(function (event) {
					event.preventDefault();
					$.ajax({
						type: 'POST',
						url: '../../API/Admin/removeInventory.php',
						data: $(this).serialize(),
						success: function (response) {
							showInventoryAlerts(response.success, response.error);
							if (response.success === 'true') {
								$('#Remove_Inventory').modal('hide');
								setTimeout(function () {
									window.location.href = 'add_product-history.php';
								}, 3000);
							}
						},
						error: function (xhr, status, error) {
							console.error('Error:', status, error);
						}
					});
				});

				// Handle Number input validation
				$('#updateDetailsForm').on('input', '.currency-input', function () {
					var enteredValue = parseFloat($(this).val());

					// Validate the entered quantity
					if (isNaN(enteredValue) || enteredValue < 0) {
						$(this).val('');
					}
				});

				// Handle Qty input validation
				$('#updateDetailsForm').on('input', '.qty-input', function () {
					var enteredValue = parseInt($(this).val());

					// Validate the entered quantity
					if (isNaN(enteredValue) || enteredValue < 1) {
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
			});
		</script>
		
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->
</html>