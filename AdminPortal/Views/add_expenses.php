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

    // Check if user has access to addNewExpense.php
    $has_access_to_add_expense = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 160") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_add_expense = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Expenses</title>
		
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

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

		<!-- Select2 CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

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
		
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->

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
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-7 col-auto">
								<h3 class="page-title">Expenses</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Expenses</li>
								</ul>
							</div>
							<div class="col-sm-5 col">
								<?php if ($has_access_to_add_expense): ?>
                                	<a href="#Add_Expense" data-toggle="modal" class="btn btn-primary float-right mt-2"> <i class="fa fa-plus-square" aria-hidden="true"></i> Add New Expense</a>
                            	<?php else: ?>
                                	<a style="display:none;" href="#" data-toggle="modal" class="btn btn-primary float-right mt-2"> <i class="fa fa-plus-square" aria-hidden="true"></i> Add New Expense</a>
                            	<?php endif; ?>
							</div>
						</div>
					</div>

					<!-- /Model Alerts -->
					<div class="modal fade" id="SaveSuccessModel" role="dialog">
						<div class="modal-dialog modal-dialog-centered">
						<!-- Modal content-->
						<div class="modal-content text-center">
							<div class="modal-body mt-4">
								<i class="fa fa-check-circle animate__animated animate__tada animate__infinite" style="font-size: 100px; margin-top:20px; color:#26af48;" aria-hidden="true"></i>
								<h3 class="modal-title"><b>Success</b></h3>
								<p>Record Saved Successfully !</p>
							</div>
								<div class="modal-body">
									<button style="width:20%;" type="button" class="btn btn-primary" id="OkBtn" data-dismiss="modal">OK</button>
								</div>
							</div>
						
						</div>
					</div>

					<div class="modal fade" id="SaveInvalidModel" role="dialog">
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

					<div class="modal fade" id="SaveFailedModel" role="dialog">
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

					<!-- /Page Header -->
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="datatable table table-hover table-center mb-0">
											<thead>
												<tr>
													<th>Expense ID</th>
													<th>Title</th>
													<th>Expense Category</th>
													<th>Expense Type</th>
													<th>Status</th>
													<th>Total Amount</th>
													<th>Payment Type</th>
													<th>Added On</th>
													<th>Added By</th>
													<th>Last Payment Date</th>
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
			<div class="modal fade" id="Add_Expense" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Add Expense</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/addNewExpense.php" id="addExpenseForm" enctype="multipart/form-data">
								<div class="row form-row">

								<?php
									
									require_once '../Controllers/select_controller.php';
									
									$db_handle = new DBController();
									$countryResult = $db_handle->runQuery("SELECT * FROM tbl_expenses_types ORDER BY Expense_Type_Id ASC");
								?>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Expense Type</label><label class="text-danger">*</label>
											<select style="width:100%;" name="Expense_Type_Id" id="expenseTypeSelect" class="form-control" required="">
												<option selected disabled value="">Select Expenses Type</option>
													<?php
                        								if (! empty($countryResult)) 
														{
                            								foreach ($countryResult as $key => $value) 
															{
                                								echo '<option value="' . $countryResult[$key]['Expense_Type_Id'] . '">' . $countryResult[$key]['Expense_Type'] . '</option>';
                            								}
                        								}
                        							?>
											</select>
										</div>
									</div>

									<div class="col-12 col-sm-6">
										<div class="form-group">
											<label>Title</label><label class="text-danger">*</label>
											<input style="display:none;" type="text" name="User_Id" class="form-control" required="" readonly="true" value="<?php echo $fetch['Id']; ?>">
											<input type="text" name="Expense_Title" class="form-control" required="">
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Total Amount</label><label class="text-danger">*</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">LKR:</span>
												</div>
												<input type="number" id="Expense_Amount" name="Expense_Amount" class="form-control text-right currency-input" required min="1" step="any">
											</div>
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Support Document</label><label class="text-danger">*</label>
											<input type="file" name="Doc" class="form-control" required="">
											<label class="text-muted" style="font-size: 0.85rem;">File must be <b>JPG, JPEG, PNG, PDF, DOCX, XLSX</b></label>
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Description</label>
											<textarea id="my-text" name="Expense_Description" class="form-control" rows="4" placeholder="Enter Description . . ."></textarea>
											<p id="count-result">0/150</p>
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

		<?php 
    		require 'footer.php';
		?>
		
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
		
		$('#expenseTypeSelect').select2();
        // Make an AJAX request to getAllExpenseTypeData.php
		
        $.ajax({
            type: 'POST',
            url: '../../API/Admin/getAllExpenseData.php',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    // Destroy existing DataTable, if any
                    $('.datatable').DataTable().destroy();

                    var table = $('.datatable').DataTable({
                        searching: true, // Enable search
						columnDefs: [
							{
								targets: 5, // Index of the Grand Total column
								className: 'text-right'
							}
						]
                    });

                    // Clear existing rows
                    table.clear();

                    $.each(data, function (index, row) {

						const formattedExpenseAmount = 'LKR: ' + row.Expense_Amount;
						const updateBy = row.First_Name + ' ' + row.Last_Name;
						const paymentDate = row.Payment_Date ? row.Payment_Date : 'N/A';

						let statusBadge = '';
						if (row.Status === 'Fully Paid') {
							statusBadge = '<span class="badge badge-info">Fully Paid</span>';
						} else if (row.Status === 'Partially Paid') {
							statusBadge = '<span class="badge badge-warning">Partially Paid</span>';
						} else if (row.Status === 'Unpaid') {
							statusBadge = '<span class="badge badge-danger">Unpaid</span>';
						}

                        table.row.add([
                            row.Expense_Id,
							row.Expense_Title,
                            row.Expense_Category,
							row.Expense_Type,
							statusBadge,
							formattedExpenseAmount,
							row.Payment_Type,
							row.Expence_Date,
							updateBy,
							paymentDate,
                            '<div class="actions"><a class="btn btn-sm bg-success-light" href="view_expenses.php?Expense_Id=' + row.Expense_Id + '"><i class="fe fe-eye"></i> View </a></div>'
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

        // Function to show and hide alerts based on response
		function showSaveAlerts(response) {
			// Hide the Add ExpenseType modal before showing any alert modals
			$('#Add_Expense').modal('hide');
			
			if (response.success === 'true') {
				// Show SaveSuccessModel only if success is true
				$('#SaveSuccessModel').modal('show');
			} else if (response.success === 'false' && response.error === 'invalid file type') {
				// Show SaveInvalidModel only if success is false and error is invalid file type
				$('#SaveInvalidModel').modal('show');
			} else {
				// Show SaveFailedModel for any other failure scenario
				$('#SaveFailedModel').modal('show');
			}
		}

		// Function to add a new expense
		$('#addExpenseForm').submit(function (event) {

			event.preventDefault();

			$('#pageLoader').show(); // Show loader before sending

			$.ajax({
				type: 'POST',
				url: '../../API/Admin/addNewExpense.php',
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function (response) {

					if (typeof response === 'string') {
						response = JSON.parse(response);
					}

					// Show the appropriate modal based on response
					showSaveAlerts(response);

					// Log the response for debugging
					console.log(response);
				},
				error: function (xhr, status, error) {
					console.error('Error:', status, error);
					// Hide the Add ExpenseType modal in case of any AJAX errors and show failure modal
					$('#Add_Expense').modal('hide');
					$('#SaveFailedModel').modal('show');
				},
				complete: function () {
                    $('#pageLoader').hide(); // Hide loader after response (success or error)
                }
			});
		});


		// Handle the "Ok" button click in the SaveSuccessModel
		$('#OkBtn').on('click', function () {
			// Refresh the page when "Ok" is clicked
			window.location.href = 'add_expenses.php';
		});

        // Count characters in textarea
        let myText = document.getElementById("my-text");
        let result = document.getElementById("count-result");
        myText.addEventListener("input", () => {
            let limit = 150;
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

	<!-- Loader Script -->
    <script>
        let startTime = performance.now(); // Capture the start time when the page starts loading

        window.addEventListener("load", function () {
            let endTime = performance.now(); // Capture the end time when the page is fully loaded
            let loadTime = endTime - startTime; // Calculate the total loading time

            // Ensure the loader stays for at least 500ms but disappears dynamically based on actual load time
            let delay = Math.max(loadTime); 

            setTimeout(function () {
                document.getElementById("pageLoader").style.display = "none";
            }, delay);
        });
    </script>
    <!-- /Loader Script -->
		
    </body>
</html>