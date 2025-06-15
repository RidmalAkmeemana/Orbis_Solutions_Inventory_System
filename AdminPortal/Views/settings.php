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
?>


<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title><?php echo ($companyName); ?> - System Configuration</title>

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

	<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

	<style>
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

				<div class="modal fade" id="UpdateFailedModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>Record Not Saved !</p>
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
					<div class="row">
						<div class="col">
							<h3 class="page-title">System Information</h3>
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
								<li class="breadcrumb-item active">System Information</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /Page Header -->

				<div class="row">
					<div class="col-md-12">
						<div class="profile-header">
							<div class="row align-items-center">
								<div class="col-auto profile-image">
									<a href="#">
										<img src="assets/img/logo-small.png" alt="Logo" width="30" height="30">
									</a>
								</div>
								<div class="col ml-md-n2 profile-user-info">
									<h4 class="user-name mb-0" id="CompanyName">Company Name</h4>
								</div>
							</div>
						</div>

						<div class="profile-menu">
							<ul class="nav nav-tabs nav-tabs-solid justify-content-center w-100">
								<li class="nav-item flex-fill text-center">
									<a class="nav-link active" data-toggle="tab" href="#per_details_tab">
										<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
										<span>Company Info</span>
									</a>
								</li>
								<li class="nav-item flex-fill text-center">
									<a class="nav-link" data-toggle="tab" href="#system_info_tab">
										<i class="fa fa-cogs" aria-hidden="true"></i>
										<span>Other Configuration</span>
									</a>
								</li>
							</ul>
						</div>

						<div class="tab-content profile-tab-cont">

							<!-- Company Info Tab -->
							<div class="tab-pane fade show active" id="per_details_tab">

								<!-- Company Info -->
								<div class="row">
									<div class="col-lg-12">
										<div class="card">
											<div class="card-body">
												<h5 class="card-title d-flex justify-content-between">
													<span>Company Info</span>
													<a class="edit-link" data-toggle="modal" href="#Update_Company" style="color:#66676b;"><i class="fa fa-edit mr-1"></i>Edit</a>
												</h5>
												<div class="row">
													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Company Name</h6>
															<p class="mx-auto" id="CompanyName1"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Company Address</h6>
															<p class="mx-auto" id="CompanyAddress"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Company Email</h6>
															<p class="mx-auto" id="CompanyEmail"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Company Tel.1</h6>
															<p class="mx-auto" id="CompanyTel1"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Company Tel.2</h6>
															<p class="mx-auto" id="CompanyTel2"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Company Tel.3</h6>
															<p class="mx-auto" id="CompanyTel3"></p>
														</h5>
													</div>
												</div>
											</div>
										</div>

										<!-- Edit Details Modal -->
										<div class="modal fade" id="Update_Company" aria-hidden="true" role="dialog">
											<div class="modal-dialog modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Company Details</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<form method="POST" action="../../API/Admin/updateCompany.php" id="updateCompanyForm" enctype="multipart/form-data">
															<div class="row form-row">
																<div class="col-12">
																	<div class="form-group">
																		<label>Company Name</label><label class="text-danger">*</label>
																		<input style="display:none;" type="text" name="Company_Id" class="form-control" id="FormCompanyId" readonly="true" required="">
																		<input type="text" name="Company_Name" class="form-control" id="FormCompanyName" required="">
																	</div>
																</div>
																<div class="col-12">
																	<div class="form-group">
																		<label>Company Address</label><label class="text-danger">*</label>
																		<textarea id="my-text" name="Company_Address" class="form-control" rows="5" placeholder="Enter Company Address . . ." required=""></textarea>
																		<p id="count-result">0/1000</p>
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Company Email</label><label class="text-danger">*</label>
																		<input type="text" name="Company_Email" class="form-control" id="FormCompanyEmail" required="">
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Company Tel.1</label><label class="text-danger">*</label>
																		<input type="text" name="Company_Tel1" class="form-control" id="FormCompanyTel1" required="">
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Company Tel.2</label>
																		<input type="text" name="Company_Tel2" class="form-control" id="FormCompanyTel2">
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Company Tel.3</label>
																		<input type="text" name="Company_Tel3" class="form-control" id="FormCompanyTel3">
																	</div>
																</div>
															</div>
															<button type="submit" name="save" class="btn btn-primary btn-block">Save Changes</button>
														</form>
													</div>
												</div>
											</div>
										</div>
										<!-- /Edit Details Modal -->

									</div>


								</div>
								<!-- /Company Info -->

							</div>
							<!-- /Company Info Tab -->

							<!-- Change Password Tab -->
							<div id="system_info_tab" class="tab-pane fade">

								<div class="card">
									<div class="card-body">
										<h5 class="card-title">System Configuration</h5>
										<div class="row">
											<div class="col-md-10 col-lg-12">
												<form method="POST" action="../../API/Admin/updateConfiguration.php" id="updateConfigurationForm" enctype="multipart/form-data">
													<div class="form-group row">
														<div class="col-md-3">
															<label class="font-weight-bold">Service Charge</label><label class="text-danger">*</label>
															<input style="display:none;" type="text" name="Configuration_Id" class="form-control" id="FormConfigurationId" readonly="true" required="">
															<div class="service-charge">
																<div class="form-check form-check-inline">
																	<input type="radio" id="percentage" name="Service_Charge_Type" class="form-check-input service-charge" value="1" checked>
																	<label class="form-check-label" for="percentage">Percentage (%)</label>
																</div>
																<div class="form-check form-check-inline">
																	<input type="radio" id="flat" name="Service_Charge_Type" class="form-check-input service-charge" value="0">
																	<label class="form-check-label" for="flat">Flat (LKR)</label>
																</div>
																<input type="number" class="form-control mt-2" min="0" step="any" required name="serviceCharge" id="serviceCharge">
															</div>
														</div>

														<div class="col-md-3">
															<label class="font-weight-bold">Delivery Charge</label><label class="text-danger">*</label>
															<div class="delivery-charge">
																<div class="form-check form-check-inline">
																	<input type="radio" id="percentage" name="Delivery_Charge_Type" class="form-check-input delivery-charge" value="1" checked>
																	<label class="form-check-label" for="percentage">Percentage (%)</label>
																</div>
																<div class="form-check form-check-inline">
																	<input type="radio" id="flat" name="Delivery_Charge_Type" class="form-check-input delivery-charge" value="0">
																	<label class="form-check-label" for="flat">Flat (LKR)</label>
																</div>
																<input type="number" class="form-control mt-2" min="0" step="any" required name="deliveryCharge" id="deliveryCharge">
															</div>
														</div>

														<div class="col-md-3">
															<label class="font-weight-bold">Tax Charge</label><label class="text-danger">*</label>
															<div class="tax-charge">
																<div class="form-check form-check-inline">
																	<input type="radio" id="percentage" name="Tax_Charge_Type" class="form-check-input tax-charge" value="1" checked>
																	<label class="form-check-label" for="percentage">Percentage (%)</label>
																</div>
																<div class="form-check form-check-inline">
																	<input type="radio" id="flat" name="Tax_Charge_Type" class="form-check-input tax-charge" value="0">
																	<label class="form-check-label" for="flat">Flat (LKR)</label>
																</div>
																<input type="number" class="form-control mt-2" min="0" step="any" required name="taxCharge" id="taxCharge">
															</div>
														</div>

														<div class="col-md-3">
															<label class="font-weight-bold">VAT Charge</label><label class="text-danger">*</label>
															<div class="vat-charge">
																<div class="form-check form-check-inline">
																	<input type="radio" id="percentage" name="Vat_Charge_Type" class="form-check-input vat-charge" value="1" checked>
																	<label class="form-check-label" for="percentage">Percentage (%)</label>
																</div>
																<div class="form-check form-check-inline">
																	<input type="radio" id="flat" name="Vat_Charge_Type" class="form-check-input vat-charge" value="0">
																	<label class="form-check-label" for="flat">Flat (LKR)</label>
																</div>
																<input type="number" class="form-control mt-2" min="0" step="any" required name="vatCharge" id="vatCharge">
															</div>
														</div>
													</div>
													<button class="btn btn-primary" type="submit" name="change">Save Changes</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--/Change Password Tab -->

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

	<!-- Custom JS -->
	<script src="assets/js/script.js"></script>

	<script>
		$(document).ready(function() {

			function setChargeAttributes(inputName, inputId) {
				if ($('input[name="' + inputName + '"]:checked').val() === "1") {
					// Percentage selected
					$('#' + inputId).attr({
						'min': 0.00,
						'max': 99.99,
						'step': 'any'
					});
				} else {
					// Flat selected
					$('#' + inputId).removeAttr('max');
					$('#' + inputId).attr({
						'min': 0.00,
						'step': 'any'
					});
				}
			}

			function setupChargeHandler(inputName, inputId) {
				setChargeAttributes(inputName, inputId);

				$('input[name="' + inputName + '"]').change(function() {
					$('#' + inputId).val('');
					setChargeAttributes(inputName, inputId);
				});

				$('#' + inputId).on('input', function() {
					let type = $('input[name="' + inputName + '"]:checked').val();
					let value = parseFloat($(this).val());

					if (type === "1" && value > 99.99) {
						$(this).val('99.99');
					}
				});
			}

			// Setup all charges
			setupChargeHandler('Service_Charge_Type', 'serviceCharge');
			setupChargeHandler('Delivery_Charge_Type', 'deliveryCharge');
			setupChargeHandler('Tax_Charge_Type', 'taxCharge');
			setupChargeHandler('Vat_Charge_Type', 'vatCharge');

			// Existing your code below here unchanged
			// -----------------------------------------

			function showUpdateAlerts(response) {
				$('#Update_Company').modal('hide');
				if (response.success === 'true') {
					$('#UpdateSuccessModel').modal('show');
				} else {
					$('#UpdateFailedModel').modal('show');
				}
			}

			$('#editProfileBtn').click(function() {
				$('#Update_Company').modal('show');
			});

			function fetchCompanyDetails() {
				$.ajax({
					type: 'GET',
					url: '../../API/Admin/getCompanyDetails.php',
					dataType: 'json',
					success: function(response) {
						const CompanyTel2 = response.Company_Tel2 && response.Company_Tel2.trim() !== '' ? response.Company_Tel2 : 'N/A';
						const CompanyTel3 = response.Company_Tel3 && response.Company_Tel3.trim() !== '' ? response.Company_Tel3 : 'N/A';

						$('#CompanyName').text(response.Company_Name);
						$('#CompanyName1').text(response.Company_Name);
						$('#CompanyAddress').text(response.Company_Address);
						$('#CompanyEmail').text(response.Company_Email);
						$('#CompanyTel1').text(response.Company_Tel1);
						$('#CompanyTel2').text(CompanyTel2);
						$('#CompanyTel3').text(CompanyTel3);

						$('#FormCompanyId').val(response.Company_Id);
						$('#FormCompanyName').val(response.Company_Name);
						$('#my-text').val(response.Company_Address);
						$('#FormCompanyEmail').val(response.Company_Email);
						$('#FormCompanyTel1').val(response.Company_Tel1);
						$('#FormCompanyTel2').val(response.Company_Tel2);
						$('#FormCompanyTel3').val(response.Company_Tel3);
					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
					}
				});
			}

			fetchCompanyDetails();

			$('#updateCompanyForm').submit(function(event) {

				event.preventDefault();

				$('#pageLoader').show(); // Show loader before sending

				$.ajax({
					type: 'POST',
					url: '../../API/Admin/updateCompany.php',
					data: new FormData(this),
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						if (typeof response === 'string') {
							response = JSON.parse(response);
						}

						showUpdateAlerts(response);
						console.log(response);
						fetchCompanyDetails();
					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
						$('#Update_Company').modal('hide');
						$('#UpdateFailedModel').modal('show');
					},
					complete: function() {
						$('#pageLoader').hide(); // Hide loader after response (success or error)
					}
				});
			});

			function fetchSystemDetails() {
				$.ajax({
					type: 'GET',
					url: '../../API/Admin/getSystemConfiguration.php',
					dataType: 'json',
					success: function(response) {
						$('#FormConfigurationId').val(response.Id);

						if (response.ServiceCharge_IsPercentage === "1") {
							$('input[name="Service_Charge_Type"][value="1"]').prop('checked', true);
						} else {
							$('input[name="Service_Charge_Type"][value="0"]').prop('checked', true);
						}

						if (response.Delivery_IsPercentage === "1") {
							$('input[name="Delivery_Charge_Type"][value="1"]').prop('checked', true);
						} else {
							$('input[name="Delivery_Charge_Type"][value="0"]').prop('checked', true);
						}

						if (response.Tax_IsPercentage === "1") {
							$('input[name="Tax_Charge_Type"][value="1"]').prop('checked', true);
						} else {
							$('input[name="Tax_Charge_Type"][value="0"]').prop('checked', true);
						}

						if (response.Vat_IsPercentage === "1") {
							$('input[name="Vat_Charge_Type"][value="1"]').prop('checked', true);
						} else {
							$('input[name="Vat_Charge_Type"][value="0"]').prop('checked', true);
						}

						$('#serviceCharge').val(response.ServiceCharge);
						$('#deliveryCharge').val(response.Delivery);
						$('#taxCharge').val(response.Tax);
						$('#vatCharge').val(response.Vat);

						// Set attributes correctly based on fetched values
						setupChargeHandler('Service_Charge_Type', 'serviceCharge');
						setupChargeHandler('Delivery_Charge_Type', 'deliveryCharge');
						setupChargeHandler('Tax_Charge_Type', 'taxCharge');
						setupChargeHandler('Vat_Charge_Type', 'vatCharge');
					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
					}
				});
			}

			fetchSystemDetails();

			$('#updateConfigurationForm').submit(function(event) {

				event.preventDefault();

				$('#pageLoader').show(); // Show loader before sending
				
				$.ajax({
					type: 'POST',
					url: '../../API/Admin/updateConfiguration.php',
					data: new FormData(this),
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						if (typeof response === 'string') {
							response = JSON.parse(response);
						}

						showUpdateAlerts(response);
						console.log(response);
						fetchCompanyDetails();
					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
						$('#Update_Company').modal('hide');
						$('#UpdateFailedModel').modal('show');
					},
					complete: function() {
						$('#pageLoader').hide(); // Hide loader after response (success or error)
					}
				});
			});

			let myText = document.getElementById("my-text");
			let result = document.getElementById("count-result");
			myText.addEventListener("input", () => {
				let limit = 1000;
				let count = myText.value.length;
				result.textContent = `${count} / ${limit}`;

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

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:46 GMT -->

</html>