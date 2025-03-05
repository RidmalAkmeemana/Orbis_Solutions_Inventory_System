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

    // Check if user has access to updateExpensesTypes.php
    $has_access_to_edit_expenses_types = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 157") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_edit_expenses_types = true;
    }

	// Check if user has access to deleteExpensesTypes.php
    $has_access_to_delete_expenses_type = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 158") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_delete_expenses_type = true;
    }

	// Check if user has access to viewExpenseData.php
    $has_access_to_view_expenses_data = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 162") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_view_expenses_data = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Expenses Types</title>
		
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
                    			$Expense_Type_Id = $_REQUEST["Expense_Type_Id"]; 
                    			$query1 = mysqli_query($conn, "SELECT * FROM tbl_expenses_types WHERE `Expense_Type_Id` = '$Expense_Type_Id'") or die(mysqli_error());
                    			$fetch1 = mysqli_fetch_array($query1);
                    	?>

								<!-- Edit and Delete Buttons -->
    							<div class="row">
									<div class="col-md-12 text-left">
										<?php if ($has_access_to_edit_expenses_types): ?>
                                			<a href="#Update_Expense_Type" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php else: ?>
                                			<a style="display:none;" href="#" data-toggle="modal" class="btn btn bg-primary-light"><i class="fe fe-pencil"></i> Edit</a>
                            			<?php endif; ?>

										<?php if ($has_access_to_delete_expenses_type): ?>
                                			<a href="#Delete_Expense_Type" data-toggle="modal" class="btn btn bg-danger-light"><i class="fe fe-trash"></i> Delete</a>
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
                    								<h1 class="text-xs font-weight-bold text-uppercase mb-1" id="expenseType"></h1>
                                                    <h5 class="text-xs font-weight-bold text-uppercase mb-1" id="expenseTypeId"></h5>
                    									<a href="home.php" class="breadcrumb-item" style="color: black;"><i class="fa fa-home"></i> Home</a>
                    									<a href="add_expenses_types.php" class="breadcrumb-item active">Expenses Types</a>
                								</h5>
            								</div>
        								</div>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Expense Category</h5>
											<p class="mx-auto" id="ExpenseCategory"></p>
        								</h5>
    								</div>

									<div class="col-md-4 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="text-xs font-weight-bold mb-1">Expense Type</h5>
											<p class="mx-auto" id="ExpenseType"></p>
        								</h5>
    								</div>

    								<div class="col-md-12 text-left mt-4">
        								<h5 class="page-title">
            								<h5 class="tag-cloud text-xs font-weight-bold mb-1">List of Expenses</h5>
        								</h5>
                                        <br><br>
                                        <div class="table-responsive">
										    <table class="datatable table table-hover table-center mb-0">
											    <thead id="expensesList">
												    <tr>
													    <th>Expense ID</th>
                                                        <th>Expense Title</th>
														<th>Expense Amount</th>
														<th>Expense Date</th>

														<?php if ($has_access_to_view_expenses_data): ?>
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
    								</div>
								</div>
					</div>
				</div>
			</div>

			<!-- Edit Details Modal-->
			<div class="modal fade" id="Update_Expense_Type" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Edit Expense Type Details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/updateExpensesTypes.php" id="updateExpenseTypeForm" enctype="multipart/form-data">
								<div class="row form-row">

								<?php
                                    
									require_once '../Controllers/select_controller.php';

									$db_handle = new DBController();
									$countryResult = $db_handle->runQuery("SELECT * FROM tbl_expenses_categories ORDER BY Expense_Category_Id ASC");
								?>

									<div class="col-12">
										<div class="form-group">
											<label>Expense Type</label><label class="text-danger">*</label>
											<input style="display:none;" type="text" name="Expense_Type_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch1['Expense_Type_Id']; ?>">
											<input type="text" name="Expense_Type" class="form-control" required="" value="<?php echo $fetch1['Expense_Type']; ?>">
										</div>
									</div>

									<div class="col-12">
        								<div class="form-group">
            								<label>Expense Category</label><label class="text-danger">*</label>
            									<select style="width:100%;" name="Expense_Category_Id" id="expenseCategorySelect" class="form-control select2" required="">
                									<option disabled>Select Category</option>
                										<?php
                    										if (!empty($countryResult)) {
                        										foreach ($countryResult as $key => $value) {
                            										$selected = ($countryResult[$key]['Expense_Category_Id'] == $fetch1['Expense_Category_Id']) ? 'selected' : '';
                            										echo '<option value="' . $countryResult[$key]['Expense_Category_Id'] . '" ' . $selected . '>' . $countryResult[$key]['Expense_Category_Name'] . '</option>';
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
			<div class="modal fade" id="Delete_Expense_Type" aria-hidden="true" role="dialog">
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
								<h4 class="modal-title">Delete <?php echo $fetch1['Expense_Type']; ?></h4>
								<p class="mb-4">Are you sure want to delete ?</p>

								<form method="POST" action="../../API/Admin/deleteExpensesTypes.php" id="deleteExpenseTypeForm" enctype="multipart/form-data">
									<input style="display: none;" type="text" name="Expense_Type_Id" value="<?php echo $fetch1['Expense_Type_Id']; ?>">
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

		$('#expenseCategorySelect').select2();
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

    // Function to fetch and display expense type data
function fetchExpenseTypeData(Expense_Type_Id) {
    $.ajax({
        type: 'GET',
        url: '../../API/Admin/viewExpenseTypeData.php',
        data: { Expense_Type_Id: Expense_Type_Id },
        dataType: 'json',
        success: function (response) {
            // Check if expenseTypeData exists
            if (!response.expenseTypeData || Object.keys(response.expenseTypeData).length === 0) {
                console.error('Failed to fetch expense type data');
                return;
            }

            // Update expense type details
            $('#expenseType').text(response.expenseTypeData.Expense_Type);
            $('#expenseTypeId').text(response.expenseTypeData.Expense_Type_Id);
            $('#ExpenseCategory').text(response.expenseTypeData.Expense_Category_Name);
			$('#ExpenseType').text(response.expenseTypeData.Expense_Type);

            // Destroy existing DataTable, if any
            if ($.fn.DataTable.isDataTable('.datatable')) {
                $('.datatable').DataTable().destroy();
            }

            // Initialize DataTable
            var table = $('.datatable').DataTable({
                searching: true, // Enable search
					columnDefs: [
							{
								targets: [2,3], // Index of the Expense Amount column
								className: 'text-right'
							},
							{
								targets: 4,
								className: 'text-center'
							}
						]
            });

            // Clear existing rows
            table.clear();

            // Populate expenses list
            if (response.expenses && response.expenses.length > 0) {
                $.each(response.expenses, function (index, expense) {

					var hasAccessToViewExpensesData = <?php echo json_encode($has_access_to_view_expenses_data); ?>;

					let actions = '';
							if (hasAccessToViewExpensesData) {
								actions = '<div class="actions"><a class="btn btn-sm bg-success-light" href="view_expenses.php?Invoice_No=' + expense.Expense_Id + '"><i class="fe fe-eye"></i> View </a></div>';
							} else {
								actions = '<div class="actions" style="display:none;"><a class="btn btn-sm bg-success-light" href="#"><i class="fe fe-eye"></i> View </a></div>';
							}
                    
                    table.row.add([
                        expense.Expense_Id,
                        expense.Expense_Title,
                        `LKR: ${expense.Expense_Amount}`,
                        expense.Expence_Date,
                        actions
                    ]);
                });
            } else {
                console.log('No expenses data received.');
            }

            // Draw the table
            table.draw();
        },
        error: function (xhr, status, error) {
            console.error('Error:', status, error);
        }
    });
}


    // Get the Expense_Type_Id from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const Expense_Type_Id = urlParams.get('Expense_Type_Id');
    // Fetch and display expense type data
    fetchExpenseTypeData(Expense_Type_Id);

    // Function to edit a expense type
    $('#updateExpenseTypeForm').submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../../API/Admin/updateExpensesTypes.php',
            data: $(this).serialize(),
            success: function (response) {
                showUpdateAlerts(response.success, response.error);
                if (response.success === 'true') {
                    $('#Update_Expense_Type').modal('hide');
                    setTimeout(function () {
                        window.location.href = 'add_expenses_types.php';
                    }, 3000);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', status, error);
            }
        });
    });

    // Function to delete a expense type
    $('#deleteExpenseTypeForm').submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../../API/Admin/deleteExpensesTypes.php',
            data: $(this).serialize(),
            success: function (response) {
                showDeleteAlerts(response.success, response.error);
                if (response.success === 'true') {
                    $('#Delete_Expense_Type').modal('hide');
                    setTimeout(function () {
                        window.location.href = 'add_expenses_types.php';
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