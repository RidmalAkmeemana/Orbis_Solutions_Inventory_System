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

    // Check if user has access to updateRole.php
    $has_access_to_edit_role = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 127") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_edit_role = true;
    }

	// Check if user has access to deleteRole.php
    $has_access_to_delete_role = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 128") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_delete_role = true;
    }

    // Check if user has access to savePermissions.php
    $has_access_to_save_permission = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 130") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_save_permission = true;
    }

    // Check if user has access to viewUserData.php
    $has_access_to_view_user_data = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 136") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_view_user_data = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - User Roles</title>
		
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
				
					<!-- Page Header -->
					<div class="page-header">
						 <?php
                    			$Role_Id = $_REQUEST["Role_Id"]; 
                    			$query1 = mysqli_query($conn, "SELECT * FROM tbl_roles WHERE `Role_Id` = '$Role_Id'") or die(mysqli_error());
                    			$fetch1 = mysqli_fetch_array($query1);
                    	?>

								<!-- Edit and Delete Buttons -->
    							<div class="row">
        							<div class="col-md-12 text-left">
										<?php if ($has_access_to_edit_role): ?>
                                			<a href="#Update_Role" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php endif; ?>

										<?php if ($has_access_to_delete_role): ?>
                                			<a href="#Delete_Role" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
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
                    								<h1 class="text-xs font-weight-bold text-uppercase mb-1" id="roleName"></h1>
                                                    <h5 class="text-xs font-weight-bold text-uppercase mb-1" id="roleId"></h5>
                    									<a href="home.php" class="breadcrumb-item" style="color: black;"><i class="fa fa-home"></i> Home</a>
                    									<a href="add_roles.php" class="breadcrumb-item active">User Roles</a>
                								</h5>
            								</div>
        								</div>
    								</div>

									<div class="col-md-12 text-left mt-4">
        								<h3 class="page-title">
            								<h3 class="text-xs font-weight-bold mb-1">Screen Permission</h3>
        								</h3>
    								</div>

									<div class="col-md-12 text-left mt-4">
										<div id="screensList" class="row"></div>
									</div>

                                    <div class="col-md-12 text-left mt-4">
										<!-- /Permission-Alerts -->
						                    <div style="display:none" id="PermissionSuccessAlert" class="alert alert-warning" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b> Success!</b> User Permission Updated Successfully</div>
						                    <div style="display:none" id="PermissionFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b> Failed!</b> User Permission Update Unsuccessfull</div>
					                    <!-- /Permission-Alerts -->
									</div>

    								<div class="col-md-12 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="tag-cloud text-xs font-weight-bold mb-1">User List</h5>
        								</h5>
                                        <br><br>
                                        <div class="table-responsive">
										    <table class="datatable table table-hover table-center mb-0">
											    <thead>
												    <tr>
													    <th>User ID</th>
													    <th>Full Name</th>
                                                        <th>Username</th>
                                                        <th>User Role</th>

													    <?php if ($has_access_to_view_user_data): ?>
                                							<th>Action</th>
                            							<?php else: ?>
                                							<th style="display:none;">Action</th>
                            							<?php endif; ?>
												    </tr>
											    </thead>
											
											    <tbody id="usersList">
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
			<div class="modal fade" id="Update_Role" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit User Role Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/updateRole.php" id="updateRoleForm" enctype="multipart/form-data">
								<div class="row form-row">

									<div class="col-12">
										<div class="form-group">
											<label>User Role Name</label><label class="text-danger">*</label>
											<input style="display:none;" type="text" name="Role_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Role_Id']; ?>">
											<input type="text" name="Role_Name" class="form-control" required="" value="<?php echo $fetch1['Role_Name']; ?>">
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
			<div class="modal fade" id="Delete_Role" aria-hidden="true" role="dialog">
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
								<h4 class="modal-title">Delete <?php echo $fetch1['Role_Name']; ?></h4>
								<p class="mb-4">Are you sure want to delete ?</p>

								<form method="POST" action="../../API/Admin/deleteRole.php" id="deleteRoleForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Role_Id" value="<?php echo $fetch1['Role_Id']; ?>">
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

		<script>
    var hasAccessToSavePermissions = <?php echo $has_access_to_save_permission ? 'true' : 'false'; ?>;

    $(document).ready(function () {
        // Function to hide all alerts
        function hideAlerts() {
            $('#UpdateSuccessAlert, #UpdateFailedAlert, #DeleteSuccessAlert, #DeleteFailedAlert, #InventorySuccessAlert, #PermissionSuccessAlert, #PermissionFailedAlert').fadeOut(3000);
        }

        // Function to show and hide alerts based on response for updating
        function showUpdateAlerts(success) {
            if (success) {
                $('#UpdateSuccessAlert').fadeIn();
            } else {
                $('#UpdateFailedAlert').fadeIn();
            }
            hideAlerts();
        }

        // Function to show and hide alerts based on response for deleting
        function showDeleteAlerts(success) {
            if (success) {
                $('#DeleteSuccessAlert').fadeIn();
            } else {
                $('#DeleteFailedAlert').fadeIn();
            }
            hideAlerts();
        }

        // Function to show and hide alerts based on response for permission updating
        function showPermissionAlerts(success) {
            if (success) {
                $('#PermissionSuccessAlert').fadeIn();
            } else {
                $('#PermissionFailedAlert').fadeIn();
            }
            hideAlerts();
        }

        // Function to fetch and display role data
        function fetchRoleData(Role_Id) {
            $.ajax({
                type: 'GET',
                url: '../../API/Admin/viewRoleData.php',
                data: { Role_Id: Role_Id },
                dataType: 'json',
                success: function (response) {
                    if (response.success === "false") {
                        console.error('Failed to fetch role data');
                        return;
                    }

                    $('#roleName').text(response.roleData.Role_Name);
                    $('#roleId').text(response.roleData.Role_Id);

                    // Destroy existing DataTable, if any
                    $('.datatable').DataTable().destroy();

                    // Initialize DataTable
                    var table = $('.datatable').DataTable({
                        searching: true, // Enable search
                    });

                    // Clear existing rows
                    table.clear();

                    // Populate user list
                    if (response.users.length > 0) {
                        $.each(response.users, function (index, user) {

                            var hasAccessToViewUserData = <?php echo json_encode($has_access_to_view_user_data); ?>;

                            let actions = '';
							if (hasAccessToViewUserData) {
								actions = '<a href="view_user.php?Id=' + user.Id + '" class="btn btn-sm bg-success-light add-to-inventory"><i class="fe fe-eye"></i> View </a>';
							} else {
								actions = '<a style="display:none;" href="#"><i class="fe fe-eye"></i> View </a>';
							}

                            table.row.add([
                                user.Id,
                                `${user.First_Name} ${user.Last_Name}`,
                                user.Username,
                                user.Status,
                                actions
                            ]);
                        });
                    } else {
                        console.log('No data received.');
                    }

                    // Draw the table
                    table.draw();

                    // Construct screen list
                    let screenList = '';
                    if (Object.keys(response.screens).length > 0) {
                        $.each(response.screens, function (category, subCategories) {
                            let categoryId = category.replace(/ /g, '');
                            screenList += `
                                <div class="col-md-12 text-left mt-4 category" data-category="${categoryId}">
                                    <h4 class="page-title">
                                        <h4 class="text-xs font-weight-bold mb-1">${category}</h4>
                                    </h4>
                                    <div class="row">
                            `;
                            $.each(subCategories, function (subCategory, screens) {
                                let allAssigned = screens.every(screen => screen.Assigned);
                                let subCategoryId = subCategory.replace(/ /g, '');
                                screenList += `
                                    <div class="col-md-3 text-left mt-4 subCategory" data-category="${categoryId}" data-subcategory="${subCategoryId}">
                                        <p class="text-xs font-weight-bold mb-1">
                                            <input type="checkbox" id="${subCategoryId}" class="subCategoryCheckbox" ${allAssigned ? 'checked' : ''}>
                                            <label for="${subCategoryId}" class="mx-auto">${subCategory}</label>
                                        </p>
                                        <div class="pages">
                                `;
                                screens.forEach(function (screen) {
                                    let screenId = screen.Screen_Id;
                                    let screenName = screen.Screen_Name.replace(/ /g, '');
                                    screenList += `
                                            <div style="display:none;" class="col-md-12 text-left mt-2">
                                                <p class="text-xs font-weight-bold mb-1">
                                                    <input type="checkbox" id="${screenName}" class="pageCheckbox ${categoryId}-${subCategoryId}-pageCheckbox" data-screen-id="${screenId}" data-table-name="${screen.Table_Name}" ${screen.Assigned ? 'checked' : ''} disabled>
                                                    <label for="${screenName}" class="mx-auto">${screen.Table_Name}-${screen.Screen_Id}</label>
                                                </p>
                                            </div>
                                        `;
                                });
                                screenList += `</div></div>`;
                            });
                            screenList += `</div></div>`;
                        });
                    } else {
                        screenList = `
                            <div class="col-md-12 text-left mt-5">
                                <p class="text-xs font-weight-bold mb-1">No screens available for this role</p>
                            </div>
                        `;
                    }

                    if (hasAccessToSavePermissions) {
                        screenList += `
                            <div class="col-md-12 text-right mt-4">
                                <button style="width:10%;" type="button" id="savePermissionsBtn" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save Permission</button>
                            </div>
                        `;
                    }

                    $('#screensList').html(screenList);

                    // Add event listener to subcategory checkboxes
                    $('.subCategoryCheckbox').change(function () {
                        let subCategoryId = $(this).attr('id');
                        let categoryId = $(this).closest('.category').data('category');
                        let isChecked = $(this).is(':checked');
                        $(`.${categoryId}-${subCategoryId}-pageCheckbox`).prop('checked', isChecked);
                    });

                    // Add event listener to save button
                    if (hasAccessToSavePermissions) {
                        $('#savePermissionsBtn').click(function () {
                            let screenData = [];
                            $('.pageCheckbox').each(function () {
                                screenData.push({
                                    Table_Name: $(this).data('table-name'),
                                    ScreenId: $(this).data('screen-id'),
                                    Checked: $(this).is(':checked')
                                });
                            });

                            // Send the screen data to savePermissions.php
                            $.ajax({
                                type: 'POST',
                                url: '../../API/Admin/savePermissions.php',
                                data: { screenData: screenData, Role_Id: Role_Id },
                                success: function (response) {
                                    showPermissionAlerts(response.success);
                                    if (response.success) {
                                        setTimeout(function () {
                                            window.location.href = 'add_roles.php';
                                        }, 3000);
                                    } else {
                                        alert('Failed to save permissions');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error:', status, error);
                                }
                            });
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        }

        // Get the Role_Id from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const Role_Id = urlParams.get('Role_Id');
        // Fetch and display role data
        fetchRoleData(Role_Id);

        // Function to edit a role
        $('#updateRoleForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/Admin/updateRole.php',
                data: $(this).serialize(),
                success: function (response) {
                    showUpdateAlerts(response.success);
                    if (response.success) {
                        $('#Update_Role').modal('hide');
                        setTimeout(function () {
                            window.location.href = 'add_roles.php';
                        }, 3000);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        });

        // Function to delete a role
        $('#deleteRoleForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/Admin/deleteRole.php',
                data: $(this).serialize(),
                success: function (response) {
                    showDeleteAlerts(response.success);
                    if (response.success) {
                        $('#Delete_Role').modal('hide');
                        setTimeout(function () {
                            window.location.href = 'add_roles.php';
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


    </body>

<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:51 GMT -->
</html>