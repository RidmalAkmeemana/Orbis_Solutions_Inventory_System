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

    // Check if user has access to addNewProduct.php
    $has_access_to_add_product = false;
    $permission_query = mysqli_query($conn, "SELECT * FROM `tbl_backend_permissions` WHERE `Role` = '$user_status' AND `Backend_Id` = 102") or die(mysqli_error());
    if (mysqli_num_rows($permission_query) > 0) {
        $has_access_to_add_product = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Inventory Report</title>
		
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
								<h3 class="page-title">Inventory Report</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
									<li class="breadcrumb-item active">Inventory Report</li>
								</ul>
							</div>
						</div>
					</div>

					<!-- /Page Header -->
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">

                                    <?php
										
										require_once '../Controllers/select_controller.php';

										$db_handle = new DBController();
										$countryResult = $db_handle->runQuery("SELECT DISTINCT Product_Id FROM tbl_product_details WHERE Inventort_Updated = 'True' ORDER BY Product_Id ASC");
                                        $countryResult1 = $db_handle->runQuery("SELECT * FROM tbl_category ORDER BY Category_Id ASC");
                                        $countryResult2 = $db_handle->runQuery("SELECT * FROM tbl_suppliers ORDER BY Supplier_Id ASC");
                                        $countryResult3 = $db_handle->runQuery("SELECT * FROM tbl_brand ORDER BY Brand_Id ASC");

									?>

                                    <!-- Add Filters Section Above the Table -->
                                    <form method="POST" action="printInventoryReport.php" id="filterForm" enctype="multipart/form-data">
                                        <div class="row mb-4">
                                            <div class="col-md-4">
                                                <label><strong>Product Id</strong></label><label class="text-danger">*</label>
                                                <select style="width: 100%;" class="form-control select2 product-select" name="Product_Id" required>
                                                    <option selected>ALL</option>
                                                        <?php
                                                            if (!empty($countryResult)) 
                                                            {
                                                                foreach ($countryResult as $key => $value) 
                                                                {
                                                                    echo '<option value="' . $countryResult[$key]['Product_Id'] . '">' . $countryResult[$key]['Product_Id'] . '</option>';
                                                                }
                                                            }
                                                        ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label><strong>Brand Name</strong></label><label class="text-danger">*</label>
                                                <select style="width: 100%;" class="form-control select2 product-brand" name="Brand_Id" required>
                                                    <option selected>ALL</option>
                                                        <?php
                                                            if (!empty($countryResult3)) 
                                                            {
                                                                foreach ($countryResult3 as $key => $value) 
                                                                {
                                                                    echo '<option value="' . $countryResult3[$key]['Brand_Id'] . '">' . $countryResult3[$key]['Brand_Name'] . '</option>';
                                                                }
                                                            }
                                                        ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label><strong>Product Category</strong></label><label class="text-danger">*</label>
                                                <select style="width: 100%;" class="form-control select2 product-category" name="Category_Id" required>
                                                    <option selected>ALL</option>
                                                        <?php
                                                            if (!empty($countryResult1)) 
                                                            {
                                                                foreach ($countryResult1 as $key => $value) 
                                                                {
                                                                    echo '<option value="' . $countryResult1[$key]['Category_Id'] . '">' . $countryResult1[$key]['Category_Name'] . '</option>';
                                                                }
                                                            }
                                                        ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-2">
                                                <label><strong>Supplier Name</strong></label><label class="text-danger">*</label>
                                                <select style="width: 100%;" class="form-control select2 product-supplier" name="Supplier_Id" required>
                                                    <option selected>ALL</option>
                                                        <?php
                                                            if (!empty($countryResult2)) 
                                                            {
                                                                foreach ($countryResult2 as $key => $value) 
                                                                {
                                                                    echo '<option value="' . $countryResult2[$key]['Supplier_Id'] . '">' . $countryResult2[$key]['Supplier_Name'] . '</option>';
                                                                }
                                                            }
                                                        ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mt-2">
                                                <label><strong>Qty From</strong></label><label class="text-danger">*</label>
                                                <input type="number" name="QtyFrom" class="form-control" required="">
                                            </div>

                                            <div class="col-md-3 mt-2">
                                                <label><strong>Qty To</strong></label><label class="text-danger">*</label>
                                                <input type="number" name="QtyTo" class="form-control" required="">
                                            </div>
                                            
                                            <!-- Adjusted column size to align the button inline -->
                                            <div class="col-md-3" style="margin-top:40px;">
                                                <button type="submit" class="btn btn-primary w-100" id="filterBtn">Generate</button>
                                            </div>
                                        </div>
                                    </form>
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

        $('.select2').select2();

        $('input[name="QtyTo"]').on('keydown blur', function(event) {
		    if (event.type === 'blur' || event.key === 'Enter') {
				if (event.key === 'Enter') {
					event.preventDefault(); // Prevent the default action, which may cause form submission
				}

				const qtyFrom = parseInt(document.querySelector('input[name="QtyFrom"]').value);
                const qtyTo = parseInt(document.querySelector('input[name="QtyTo"]').value);

                if(qtyFrom > qtyTo)
                {
                    alert('Quantity From Cannot Greater Than Quantity To'); // Show alert
                    $(this).val(qtyFrom);
                }
                if(qtyTo < 0)
                {
                    $(this).val(0);
                }
		    }
		});

        $('input[name="QtyFrom"]').on('keydown blur', function(event) {
		    if (event.type === 'blur' || event.key === 'Enter') {
				if (event.key === 'Enter') {
					event.preventDefault(); // Prevent the default action, which may cause form submission
				}

				const qtyFrom = parseInt(document.querySelector('input[name="QtyFrom"]').value);
                const qtyTo = parseInt(document.querySelector('input[name="QtyTo"]').value);

                if(qtyFrom > qtyTo)
                {
                    alert('Quantity From Cannot Greater Than Quantity To'); // Show alert
                    $('input[name="QtyTo"]').val(qtyFrom);
                }
                if(qtyTo < 0)
                {
                    $('input[name="QtyTo"]').val(0);
                }
		    }
		});

        $('input[name="QtyFrom"]').on('keydown blur', function(event) {
		    if (event.type === 'blur' || event.key === 'Enter') {
				if (event.key === 'Enter') {
					event.preventDefault(); // Prevent the default action, which may cause form submission
				}

				const qtyFrom = parseInt(document.querySelector('input[name="QtyFrom"]').value);
                
                if(qtyFrom < 0)
                {
                    $(this).val(0);
                }
		    }
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
</html>