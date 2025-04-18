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

    // Check if user has access to addNewUser.php
    $has_access_to_add_user = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 125") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_add_user = true;
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
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-7 col-auto">
								<h3 class="page-title">Users</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Users</li>
								</ul>
							</div>
							<div class="col-sm-5 col">
								<?php if ($has_access_to_add_user): ?>
                                	<a href="#Add_User" data-toggle="modal" class="btn btn-primary float-right mt-2"> <i class="fa fa-plus-square" aria-hidden="true"></i> Add New User</a>
                            	<?php else: ?>
                                	<a style="display:none;" href="#" data-toggle="modal" class="btn btn-primary float-right mt-2"> <i class="fa fa-plus-square" aria-hidden="true"></i> Add New User</a>
                            	<?php endif; ?>
							</div>
						</div>
					</div>

					<!-- /Alerts -->
					<div style="display:none" id="SaveSuccessAlert" class="alert alert-success" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b> Success!</b> Data Saved Successfully</div>
					<div style="display:none" id="SaveFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b> Failed!</b> Data Saved Unsuccessfull</div>
					<!-- /Alerts -->

					<!-- /Page Header -->
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="datatable table table-hover table-center mb-0">
											<thead>
												<tr>
													<th>ID</th>
													<th>Name of Users</th>
													<th>Role</th>
													<th>Username</th>
													<th>Action</th>
												</tr>
											</thead>
											
											<tbody>
												<!-- Data will be populated here -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>			
					</div>
				</div>			
			</div>
			<!-- /Page Wrapper -->
			
			
			<!-- Add Modal -->
			<div class="modal fade" id="Add_User" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Add User</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/addNewUser.php" id="addUserForm" enctype="multipart/form-data">
								<div class="row form-row">

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>First Name</label><label class="text-danger">*</label>
											<input type="text" name="First_Name" class="form-control" required="">
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Last Name</label><label class="text-danger">*</label>
											<input type="text" name="Last_Name" class="form-control" required="">
										</div>
									</div>

									<?php
									
									require_once '../Controllers/select_controller.php';
									
									$db_handle = new DBController();
									$countryResult = $db_handle->runQuery("SELECT * FROM tbl_roles ORDER BY Role_Id ASC");
								?>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Username</label><label class="text-danger">*</label>
											<input type="text" name="Username" class="form-control" required="">
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Password</label><label class="text-danger">*</label>
											<input type="password" name="Password" class="form-control" required="">
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Role Name</label><label class="text-danger">*</label>
											<select style="width:100%;" name="Status" id="roleSelect" class="form-control" required="">
												<option selected disabled>Select Role Name</option>
													<?php
                        								if (! empty($countryResult)) 
														{
                            								foreach ($countryResult as $key => $value) 
															{
                                								echo '<option value="' . $countryResult[$key]['Role_Name'] . '">' . $countryResult[$key]['Role_Name'] . '</option>';
                            								}
                        								}
                        							?>
											</select>
										</div>
									</div>

								</div>
								<button type="submit" name="save" class="btn btn-primary btn-block">Save Changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Add Modal -->
			
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
        // Make an AJAX request to getAllUserData.php
        $.ajax({
            type: 'POST',
            url: '../../API/Admin/getAllUserData.php',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    // Destroy existing DataTable, if any
                    $('.datatable').DataTable().destroy();

                    var table = $('.datatable').DataTable({
                        searching: true, // Enable search
                    });

                    // Clear existing rows
                    table.clear();

                    $.each(data, function (index, row) {
                        table.row.add([
							row.id,
                            '<a href="' + row.img + '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' + row.img + '"></a>' + row.firstname + ' ' + row.lastname,
                            row.status,
                            row.username,
                            '<div class="actions"><a class="btn btn-sm bg-success-light" href="view_user.php?Id=' + row.id + '"><i class="fe fe-eye"></i> View </a></div>'
                        ]);
                    });

                    // Draw the table
                    table.draw();

                } else {
                    console.log('No data received.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', status, error);
            }
        });

		function hideAlerts() {
            $('#SaveSuccessAlert, #SaveFailedAlert').fadeOut(3000);
        }

        // Function to show and hide alerts based on response
        function showHideAlerts(success, error) {
            if (success === 'true') {
                $('#SaveSuccessAlert').fadeIn();
                hideAlerts();
            } else if (success === 'false' && error === 'duplicate') {
                $('#SaveDuplicateAlert').fadeIn();
                hideAlerts();
            } else {
                $('#SaveFailedAlert').fadeIn();
                hideAlerts();
            }
        }

        // Function to add a new user
        $('#addUserForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/Admin/addNewUser.php',
                data: $(this).serialize(),
                success: function (response) {
					showHideAlerts(response.success, response.error);
                    if (response.success === 'true') {
						
                        $('#SaveSuccessAlert').fadeIn();
                        // Hide modal after 3 seconds
                        $('#Add_User').modal('hide');
                        setTimeout(function () {
                            window.location.href = 'add_users.php';
                        }, 3000);
						console.log(response.success);

                    } 
					else
					{
						// Handle other responses if needed
						$('#SaveFailedAlert').fadeIn();
                        // Hide modal after 3 seconds
                        $('#Add_User').modal('hide');
                        setTimeout(function () {
                            window.location.href = 'add_users.php';
                        }, 3000);
                        console.log(response.error);
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

		function hideAlerts() {
            $('#SaveSuccessAlert, #SaveFailedAlert').fadeOut(3000);
        }

    });
	</script>
		
    </body>
</html>