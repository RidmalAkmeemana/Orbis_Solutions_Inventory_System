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
    
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Sales Invoice</title>
		
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
								<h3 class="page-title">Sales Invoice</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Sales Invoice</li>
								</ul>
							</div>
						</div>
					</div>

					<!-- /Alerts -->
					<div style="display:none" id="SaveSuccessAlert" class="alert alert-success" role="alert"><i class="fa fa-check-circle" aria-hidden="true"></i> <b>Success!</b> Data Saved Successfully</div>
					<div style="display:none" id="SaveFailedAlert" class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i><b>Failed!</b> Data Saved Unsuccessfull</div>
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
                                                    <th>Invoice No</th>
                                                    <th>Caustomer Name</th>
													<th>Sale Type</th>
													<th>Item Count</th>
													<th>Status</th>
													<th>Grand Total</th>
													<th>Payment Type</th>
													<th>Invoice Date</th>
                                                    <th>Invoice By</th>
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
		<script src="assets/js/script.js"></script>

		<!-- Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

		<script>
    $(document).ready(function () {
        // Make an AJAX request to getAllInvoiceData.php
		$.ajax({
			type: 'POST',
			url: '../../API/Admin/getAllInvoiceData.php',
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					// Destroy existing DataTable, if any
					$('.datatable').DataTable().destroy();

					// Initialize DataTable with columnDefs for text-right class on the Grand Total column
					var table = $('.datatable').DataTable({
						searching: true, // Enable search
						columnDefs: [
							{
								targets: 5, // Index of the Grand Total column
								className: 'text-right'
							},
							{
								targets: 3,
								className: 'text-center'
							}
						]
					});

					// Clear existing rows
					table.clear();

					$.each(data, function (index, row) {
						const formattedGrandTotal = 'LKR: ' + row.Grand_Total;
						const invoiceBy = row.First_Name + ' ' + row.Last_Name;
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
							row.Invoice_Id,
							row.Customer_Name,
							row.Sale_Type,
							row.Item_Count,
							statusBadge,
							formattedGrandTotal, // No need to wrap it, columnDefs will handle the alignment
							row.Payment_Type,
							row.Invoice_Date,
							invoiceBy,
							paymentDate,
							'<div class="actions"><a class="btn btn-sm bg-success-light" href="view_invoice.php?Invoice_No=' + row.Invoice_Id + '"><i class="fe fe-eye"></i> View </a></div>'
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
    });
    </script>
		
    </body>
</html>