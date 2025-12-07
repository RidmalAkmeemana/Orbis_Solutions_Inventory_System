<?php
require_once '../../API/Connection/validator.php';
require_once '../../API/Connection/config.php';

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
	<title><?php echo ($companyName); ?> - Profile</title>

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

				<div class="modal fade" id="UpdateInvalidModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>Invalid File Format !</p>
							</div>
							<div class="modal-body">
								<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="PasswordInvalidModel" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-exclamation-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#e63c3c;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Error</b></h3>
								<p>Password Missmatch !</p>
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
							<h3 class="page-title">Profile</h3>
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
								<li class="breadcrumb-item active">Profile</li>
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
										<img class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" alt="User Image" id="ProfileImage">
									</a>
								</div>
								<div class="col ml-md-n2 profile-user-info">
									<h4 class="user-name mb-0" id="FullName"></h4>
									<h6 class="text-muted" id="Status"></h6>
								</div>
							</div>
						</div>

						<div class="profile-menu">
							<ul class="nav nav-tabs nav-tabs-solid justify-content-center w-100">
								<li class="nav-item flex-fill text-center">
									<a class="nav-link active" data-toggle="tab" href="#per_details_tab">
										<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
										<span>About</span>
									</a>
								</li>
								<li class="nav-item flex-fill text-center">
									<a class="nav-link" data-toggle="tab" href="#password_tab">
										<i class="fa fa-hashtag" aria-hidden="true"></i>
										<span>Change Password</span>
									</a>
								</li>
							</ul>
						</div>

						<div class="tab-content profile-tab-cont">

							<!-- Personal Details Tab -->
							<div class="tab-pane fade show active" id="per_details_tab">

								<!-- Personal Details -->
								<div class="row">
									<div class="col-lg-12">
										<div class="card">
											<div class="card-body">
												<h5 class="card-title d-flex justify-content-between">
													<span>Personal Details</span>
													<a class="edit-link" data-toggle="modal" href="#Update_Profile" style="color:#66676b;"><i class="fa fa-edit mr-1"></i>Edit</a>
												</h5>
												<div class="row">
													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">First Name</h6>
															<p class="mx-auto" id="FirstName"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Last Name</h6>
															<p class="mx-auto" id="LastName"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">User Role</h6>
															<p class="mx-auto" id="userStatus"></p>
														</h5>
													</div>

													<div class="col-md-4 text-left mt-4">
														<h5 class="page-title">
															<h6 class="text-xs font-weight-bold mb-1">Username</h6>
															<p class="mx-auto" id="profileUsername"></p>
														</h5>
													</div>
												</div>
											</div>
										</div>

										<!-- Edit Details Modal -->
										<div class="modal fade" id="Update_Profile" aria-hidden="true" role="dialog">
											<div class="modal-dialog modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Personal Details</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<form method="POST" action="../../API/Admin/updateProfile.php" id="updateProfileForm" enctype="multipart/form-data">
															<div class="row form-row">
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>First Name</label><label class="text-danger">*</label>
																		<input style="display: none;" type="text" name="Id" class="form-control" value="<?php echo $fetch['Id']; ?>">
																		<input type="text" name="First_Name" class="form-control" id="FormFirstName" required="">
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Last Name</label><label class="text-danger">*</label>
																		<input type="text" name="Last_Name" class="form-control" id="FormLastName" required="">
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Username</label><label class="text-danger">*</label>
																		<input type="text" name="Username" class="form-control" id="FormUserName" required="">
																	</div>
																</div>
																<div class="col-12 col-sm-6">
																	<div class="form-group">
																		<label>Profile Image</label>
																		<input type="file" name="Img" class="form-control">
																		<label class="text-muted" style="font-size: 0.85rem;">Image must be <b>JPG, JPEG, PNG</b></label>
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
								<!-- /Personal Details -->

							</div>
							<!-- /Personal Details Tab -->

							<!-- Change Password Tab -->
							<div id="password_tab" class="tab-pane fade">

								<div class="card">
									<div class="card-body">
										<h5 class="card-title">Change Password</h5>
										<div class="row">
											<div class="col-md-10 col-lg-12">
												<form method="POST" action="../../API/Admin/updateAdminPassword.php" id="updatePasswordForm" enctype="multipart/form-data">
													<div class="form-group row">
														<div class="col-md-6">
															<label for="newpassword">New Password</label><label class="text-danger">*</label>
															<input type="text" name="Id" style="display: none;" value="<?php echo $fetch['Id']; ?>">
															<input type="password" class="form-control" required name="newpassword" id="newpassword">
														</div>
														<div class="col-md-6">
															<label for="conpassword">Confirm Password</label><label class="text-danger">*</label>
															<input type="password" class="form-control" required name="conpassword" id="conpassword">
														</div>
													</div>
													<button class="btn btn-primary" type="submit" name="change">Save Changes</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /Change Password Tab -->

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

			// Function to show and hide alerts based on response for updating
			function showUpdateAlerts(response) {
				// Hide the Add Update_Profile modal before showing any alert modals
				$('#Update_Profile').modal('hide');

				if (response.success === 'true') {
					// Show UpdateSuccessModel only if success is true
					$('#UpdateSuccessModel').modal('show');
				} else if (response.success === 'false' && response.error === 'invalid_file_type') {
					// Show UpdateInvalidModel only if success is false and error is invalid_file_type
					$('#UpdateInvalidModel').modal('show');
				} else if (response.success === 'false' && response.error === 'password_mismatch') {
					// Show UpdateInvalidModel only if success is false and error is password_mismatch
					$('#PasswordInvalidModel').modal('show');
				} else {
					// Show UpdateFailedModel for any other failure scenario
					$('#UpdateFailedModel').modal('show');
				}
			}

			// Function to show the modal when the "Edit Profile" button is clicked
			$('#editProfileBtn').click(function() {
				$('#Update_Profile').modal('show');
			});

			// Function to fetch and display profile details
			function fetchProfileDetails() {
				$.ajax({
					type: 'GET',
					url: '../../API/Admin/getProfileDetails.php',
					dataType: 'json',
					success: function(response) {
						$('#FullName').text(response.First_Name + ' ' + response.Last_Name);
						$('#Status').text(response.Status);
						$('#FirstName').text(response.First_Name);
						$('#LastName').text(response.Last_Name);
						$('#profileUsername').text(response.Username);
						$('#userStatus').text(response.Status);
						$('#ProfileImage').attr('src', response.Img);
						$('#FormFirstName').val(response.First_Name);
						$('#FormLastName').val(response.Last_Name);
						$('#FormUserName').val(response.Username);
					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
					}
				});
			}

			// Call fetchProfileDetails function to load profile details when the page is ready
			fetchProfileDetails();

			// Function to update profile
			$('#updateProfileForm').submit(function(event) {

				event.preventDefault();

				$('#pageLoader').show(); // Show loader before sending

				$.ajax({
					type: 'POST',
					url: '../../API/Admin/updateProfile.php',
					data: new FormData(this),
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						// Parse the response as a JSON object (if not already parsed)
						if (typeof response === 'string') {
							response = JSON.parse(response);
						}

						showUpdateAlerts(response);

						console.log(response);

						fetchProfileDetails();

					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
						$('#Update_Profile').modal('hide');
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
				window.location.href = 'logout.php';
			});

			// Function to update profile password
			$('#updatePasswordForm').submit(function(event) {

				event.preventDefault();

				$('#pageLoader').show(); // Show loader before sending

				$.ajax({
					type: 'POST',
					url: '../../API/Admin/updateAdminPassword.php',
					data: new FormData(this),
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(response) {
						// Parse the response as a JSON object (if not already parsed)
						if (typeof response === 'string') {
							response = JSON.parse(response);
						}

						showUpdateAlerts(response);

						console.log(response);

						fetchProfileDetails();
					},
					error: function(xhr, status, error) {
						console.error('Error:', status, error);
						$('#Update_Profile').modal('hide');
						$('#UpdateFailedModel').modal('show');
					},
					complete: function() {
						$('#pageLoader').hide(); // Hide loader after response (success or error)
					}
				});
			});

			// Count characters in textarea (assuming you have a textarea with ID 'my-text')
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