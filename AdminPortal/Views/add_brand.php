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

    // Check if user has access to addNewBrand.php
    $has_access_to_add_brand = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 148") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_add_brand = true;
    }
?>


<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/specialities.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:49 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Brands</title>
		
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
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-7 col-auto">
								<h3 class="page-title">Brands</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Brands</li>
								</ul>
							</div>
							<div class="col-sm-5 col">
								<?php if ($has_access_to_add_brand): ?>
                                	<a href="#Add_Brand" data-toggle="modal" class="btn btn-primary float-right mt-2"> <i class="fa fa-plus-square" aria-hidden="true"></i> Add New Brand</a>
                            	<?php else: ?>
                                	<a style="display:none;" href="#" data-toggle="modal" class="btn btn-primary float-right mt-2"> <i class="fa fa-plus-square" aria-hidden="true"></i> Add New Brand</a>
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

					<div class="modal fade" id="SaveDuplicateModel" role="dialog">
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
													<th>Brand ID</th>
													<th>Brand Name</th>
                                                    <th>Product Count</th>
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
			<div class="modal fade" id="Add_Brand" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Add Brand</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form method="POST" action="../../API/Admin/addNewBrand.php" id="addBrandForm" enctype="multipart/form-data">
								<div class="row form-row">

									<div class="col-12">
										<div class="form-group">
											<label>Brand ID</label><label class="text-danger">*</label>
											<input type="text" name="Brand_Id" class="form-control" required="">
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Brand Name</label><label class="text-danger">*</label>
											<input type="text" name="Brand_Name" class="form-control" required="">
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

		<script>
    	$(document).ready(function () {
        // Make an AJAX request to getAllBrandData.php
        $.ajax({
            type: 'POST',
            url: '../../API/Admin/getAllBrandData.php',
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
                            row.Brand_Id,
                            row.Brand_Name,
                            row.product_count,
                            '<div class="actions"><a class="btn btn-sm bg-success-light" href="view_brand.php?Brand_Id=' + row.Brand_Id + '"><i class="fe fe-eye"></i> View </a></div>'
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
			// Hide the Add Supplier modal before showing any alert modals
			$('#Add_Brand').modal('hide');
			
			if (response.success === 'true') {
				// Show SaveSuccessModel only if success is true
				$('#SaveSuccessModel').modal('show');
			} else if (response.success === 'false' && response.error === 'duplicate') {
				// Show SaveDuplicateModel only if success is false and error is duplicate
				$('#SaveDuplicateModel').modal('show');
			} else {
				// Show SaveFailedModel for any other failure scenario
				$('#SaveFailedModel').modal('show');
			}
		}

        // Function to add a new supplier
        $('#addBrandForm').submit(function (event) {

            event.preventDefault();

			$('#pageLoader').show(); // Show loader before sending

            $.ajax({
                type: 'POST',
                url: '../../API/Admin/addNewBrand.php',
                data: $(this).serialize(),
                success: function (response) {
					// Parse the response as a JSON object (if not already parsed)
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
					// Hide the Add Supplier modal in case of any AJAX errors and show failure modal
					$('#Add_Brand').modal('hide');
					$('#SaveFailedModel').modal('show');
				},
				complete: function () {
                    $('#pageLoader').hide(); // Hide loader after response (success or error)
                }
            });
        });

		// Handle the "Ok" button click in the SaveSuccessModel
		$('#SaveSuccessModel #OkBtn').on('click', function () {
			// Refresh the page when "Ok" is clicked
			window.location.href = 'add_brand.php';
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