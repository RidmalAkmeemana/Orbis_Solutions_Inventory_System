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

	// Check if user has access to updateUser.php
    $has_access_to_edit_user = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 134") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_edit_user = true;
    }

	// Check if user has access to deleteUser.php
    $has_access_to_delete_user = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 135") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_delete_user = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Users</title>
		
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
                    			$Id = $_REQUEST["Id"]; 
                    			$query1 = mysqli_query($conn, "SELECT * FROM tbl_user WHERE `Id` = '$Id'") or die(mysqli_error());
                    			$fetch1 = mysqli_fetch_array($query1);
                    	?>

								<!-- Edit and Delete Buttons -->
    							<div class="row">
									<div class="col-md-12 text-left">
										<?php if ($has_access_to_edit_user): ?>
                                			<a href="#Update_User" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php endif; ?>

										<?php if ($has_access_to_delete_user): ?>
                                			<a href="#Delete_User" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
                            			<?php endif; ?>
        							</div>
    							</div>

								<div class="row">
									<div class="col-md-12 text-center mt-4 position-relative">
        								<div class="background-container" style="background-image: url('assets/img/cover.png');">
            								<div class="col-md-12 text-center mt-4">
											<a id="userImageLink" href="" class="avatar avatar-sm mr-2"><img id="userImage" style="height:45px; width:45px;" class="avatar-img rounded-circle" src=""></a>
                								<h5 class="page-title">
                    								<h1 class="text-xs font-weight-bold text-uppercase mb-1" id="userID"></h1>
                    									<a href="home.php" class="breadcrumb-item" style="color: black;"><i class="fa fa-home"></i> Home</a>
                    									<a href="add_users.php" class="breadcrumb-item active">Users</a>
                								</h5>
            								</div>
        								</div>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h3 class="text-xs font-weight-bold mb-1">First Name</h3>
											<p class="mx-auto" id="firstName"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h3 class="text-xs font-weight-bold mb-1">Last Name</h3>
											<p class="mx-auto" id="lastName"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h3 class="text-xs font-weight-bold mb-1">Username</h3>
											<p class="mx-auto" id="userName"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h3 class="text-xs font-weight-bold mb-1">Role Name</h3>
											<p class="mx-auto" id="userRole"></p>
        								</h5>
    								</div>
								</div>
					</div>
				</div>
			</div>

			<!-- Edit Details Modal-->
			<div class="modal fade" id="Update_User" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit User Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/updateUser.php" id="updateUserForm" enctype="multipart/form-data">
								<div class="row form-row">

								<?php
                                    
									require_once '../Controllers/select_controller.php';

									$db_handle = new DBController();
									$countryResult = $db_handle->runQuery("SELECT * FROM tbl_roles ORDER BY Role_Id ASC");
								?>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>First Name</label><label class="text-danger">*</label>
											<input style="display:none;" type="text" name="Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Id']; ?>">
											<input type="text" name="First_Name" class="form-control" required="" value="<?php echo $fetch1['First_Name']; ?>">
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Last Name</label><label class="text-danger">*</label>
											<input type="text" name="Last_Name" class="form-control" required="" value="<?php echo $fetch1['Last_Name']; ?>">
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Username</label><label class="text-danger">*</label>
											<input type="text" name="Username" class="form-control" required="" value="<?php echo $fetch1['Username']; ?>">
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Password</label><label class="text-danger"></label>
											<input type="password" name="Password" class="form-control">
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Role Name</label><label class="text-danger">*</label>
											<select style="width:100%;" name="Status" id="roleSelect" class="form-control select2" required="">
                									<option disabled>Select Role Name</option>
                										<?php
                    										if (!empty($countryResult)) {
                        										foreach ($countryResult as $key => $value) {
                            										$selected = ($countryResult[$key]['Role_Name'] == $fetch1['Status']) ? 'selected' : '';
                            										echo '<option value="' . $countryResult[$key]['Role_Name'] . '" ' . $selected . '>' . $countryResult[$key]['Role_Name'] . '</option>';
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
			<div class="modal fade" id="Delete_User" aria-hidden="true" role="dialog">
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
								<h4 class="modal-title">Delete <?php echo $fetch1['First_Name']; ?> <?php echo $fetch1['Last_Name']; ?></h4>
								<p class="mb-4">Are you sure want to delete ?</p>

								<form method="POST" action="../../API/Admin/deleteUser.php" id="deleteUserForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Id" value="<?php echo $fetch1['Id']; ?>">
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
		<script  src="assets/js/script.js"></script>

		<!-- Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

		<script>
    $(document).ready(function () {

		$('#roleSelect').select2();

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

        // Fetch and display user data
function fetchUserData(Id) {
    $.ajax({
        type: 'GET',
        url: '../../API/Admin/viewUserData.php',
        data: { Id: Id },
        success: function (response) {
            if (response.success === "false") {
                console.error('Failed to fetch user data');
                return;
            }

            $('#userID').text(response.userData.First_Name + ' ' + response.userData.Last_Name);
			$('#firstName').text(response.userData.First_Name);
			$('#lastName').text(response.userData.Last_Name);
			$('#userName').text(response.userData.Username);
            $('#userRole').text(response.userData.Status );
            $('#userImage').attr('src', response.userData.img_url);
            $('#userImageLink').attr('href', response.userData.img_url);
        },
        error: function (xhr, status, error) {
            console.error('Error:', status, error);
        }
    });
}

        // Get the Id from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const Id = urlParams.get('Id');

        // Fetch and display user data
        fetchUserData(Id);

        // Function to edit a user
        $('#updateUserForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/Admin/updateUser.php',
                data: $(this).serialize(),
                success: function (response) {
                    showUpdateAlerts(response.success, response.error);
                    if (response.success === 'true') {
                        // Hide modal after 3 seconds
                        $('#Update_User').modal('hide');
                        setTimeout(function () {
                            window.location.href = 'add_users.php';
                        }, 3000);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        });

        // Function to delete a user
        $('#deleteUserForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/Admin/deleteUser.php',
                data: $(this).serialize(),
                success: function (response) {
                    showDeleteAlerts(response.success, response.error);
                    if (response.success === 'true') {
                        // Hide modal after 3 seconds
                        $('#Delete_User').modal('hide');
                        setTimeout(function () {
                            window.location.href = 'add_users.php';
                        }, 3000);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', status, error);
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
    });
</script>
		
    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->
</html>