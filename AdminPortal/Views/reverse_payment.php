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
        <title><?php echo($companyName); ?> - Reverse Payments</title>
		
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
								<h3 class="page-title">Reverse Payment</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Reverse Payment</li>
								</ul>
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
                                                    <th>Invoice No</th>
                                                    <th>Reciept No</th>
													<th>Payment Date</th>
													<th>Description</th>
													<th>Paid Amount</th>
													<th>Payment Type</th>
													<th>Due Total</th>
													<th>Updated By</th>
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

			<!-- Reverse Payment Modal -->
			<div class="modal fade" id="Reverse_Payment" aria-hidden="true" role="dialog">
				<div class="modal-dialog modal-dialog-centered" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Reverse The Last Payment</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-content p-2">
								<h4 class="modal-title">Confirmation !</h4>
								<p class="mb-4">Are you sure want to Reverse The Last Payment ?</p>

								<form method="POST" action="../../API/Admin/reversePayment.php" id="reversePaymentForm" enctype="multipart/form-data">
									<input style="display:none;" type="text" name="Invoice_Id" value="<?php echo $fetch1['Invoice_Id']; ?>">
									<button type="submit" name="reverse" class="btn btn-primary btn-block">Confirm </button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/Remove Inventory Modal -->

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
        // Make an AJAX request to getAllInvoicePaymentData.php
        $.ajax({
            type: 'POST',
            url: '../../API/Admin/getAllInvoicePaymentData.php',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    // Destroy existing DataTable, if any
                    $('.datatable').DataTable().destroy();

                    // Clear table body
                    $('.datatable tbody').empty();

                    // Group rows by Invoice_Id
                    const groupedData = {};
                    $.each(data, function (index, row) {
                        if (!groupedData[row.Invoice_Id]) {
                            groupedData[row.Invoice_Id] = [];
                        }
                        groupedData[row.Invoice_Id].push(row);
                    });

                    // Initialize DataTable
                    var table = $('.datatable').DataTable({
                        searching: true, // Enable search
                        paging: true, // Enable pagination
                        info: true, // Show table info
                        order: [] // Disable initial sorting
                    });

                    // Add rows to the DataTable
                    $.each(groupedData, function (invoiceId, rows) {
                        let PaymentIds = '';
                        let PaymentDates = '';
                        let Descriptions = '';
                        let PaidAmounts = '';
                        let PaidTypes = '';
                        let DueTotals = '';
                        let UpdateUsers = '';

                        rows.forEach(function (row) {
                            const formattedPaymentId = row.Invoice_Id + '/' + row.Payment_Id;
                            const formattedPaymentDate = row.Payment_Date ? row.Payment_Date : 'N/A';
							const formattedDescription = row.Description ? row.Description : 'N/A';
                            const formattedPaidAmount = 'LKR: ' + row.Paid_Amount;
                            const formattedDueTotal = 'LKR: ' + row.Due_Total;
                            const formattedUser = row.First_Name + ' ' + row.Last_Name;

                            PaymentIds += `${formattedPaymentId}<br>`;
                            PaymentDates += `${formattedPaymentDate}<br>`;
                            Descriptions += `${formattedDescription}<br>`;
                            PaidAmounts += `${formattedPaidAmount}<br>`;
                            PaidTypes += `${row.Payment_Type}<br>`;
                            DueTotals += `${formattedDueTotal}<br>`;
                            UpdateUsers += `${formattedUser}<br>`;
                        });

                        const actionButton = `
                            <div class="actions">
                                <a id="ReversePaymentButton" href="#Reverse_Payment" data-toggle="modal" 
                                data-invoice-id="${invoiceId}" class="btn btn bg-warning-light">
                                    <i class="fa fa-undo"></i> Reverse Payment
                                </a>
                            </div>
                        `;

                        table.row.add([
                            invoiceId,
                            PaymentIds.trim(),
                            PaymentDates.trim(),
                            Descriptions.trim(),
                            PaidAmounts.trim(),
                            PaidTypes.trim(),
                            DueTotals.trim(),
                            UpdateUsers.trim(),
                            actionButton
                        ]);
                    });

                    // Redraw the DataTable
                    table.draw();

                    // Attach event handler to dynamically set Invoice_Id in the modal
                    $('.datatable tbody').on('click', '#ReversePaymentButton', function () {
                        const invoiceId = $(this).data('invoice-id');
                        $('#Reverse_Payment input[name="Invoice_Id"]').val(invoiceId);
                    });
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
            // Hide the Reverse_Payment modal before showing any alert modals
            $('#Reverse_Payment').modal('hide');

            if (response.success === true) {
                // Show SaveSuccessModel only if success is true
                $('#SaveSuccessModel').modal('show');
            } else {
                // Show SaveFailedModel for any other failure scenario
                $('#SaveFailedModel').modal('show');
            }
        }

        // Function to add a new expense
        $('#reversePaymentForm').submit(function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../../API/Admin/reversePayment.php',
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
                    // Hide the Reverse_Payment modal in case of any AJAX errors and show failure modal
                    $('#Reverse_Payment').modal('hide');
                    $('#SaveFailedModel').modal('show');
                }
            });
        });

        // Handle the "Ok" button click in the SaveSuccessModel or SaveFailedModel
        $('#SaveSuccessModel, #SaveFailedModel').on('hidden.bs.modal', function () {
            // Refresh the page when either modal is closed
            window.location.href = 'reverse_payment.php';
        });
    });
</script>


		
    </body>
</html>