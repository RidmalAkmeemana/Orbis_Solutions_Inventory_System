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
    
<!-- Mirrored from dreamguys.co.in/demo/doccure/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 30 Nov 2019 04:12:20 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title><?php echo($companyName); ?> - Dashboard</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Feathericon CSS -->
        <link rel="stylesheet" href="assets/css/feathericon.min.css">
		
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
		
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
							<div class="col-sm-12">
								<h3 id="adminName" class="page-title">Welcome <?php echo $fetch['First_Name']; ?> <?php echo $fetch['Last_Name']; ?></h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					<!-- Page Wrapper -->
					<div class="row">
						<?php

 						$userRole =	$fetch['Status'];
						// If Super Admin → show Super Admin dashboard
						if ($userRole == "Super Admin") {
							include '../../Dashboard/dashboard_super_admin.php';

						} else { 
							// Normal users → standard dashboard
							include '../../Dashboard/dashboard_user.php';
						}
						?>
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
		<script  src="assets/js/script.js"></script>

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

		// document.addEventListener("DOMContentLoaded", function () {
		// 	const toggleButton = document.getElementById("darkModeToggle");
		// 	const icon = document.getElementById("darkModeIcon");

		// 	// Load state
		// 	if (localStorage.getItem("darkMode") === "enabled") {
		// 		document.body.classList.add("dark-mode");
		// 		icon.classList.remove("fa-moon-o");
		// 		icon.classList.add("fa-sun-o");
		// 	}

		// 	toggleButton.addEventListener("click", function () {
		// 		document.body.classList.toggle("dark-mode");

		// 		if (document.body.classList.contains("dark-mode")) {
		// 			localStorage.setItem("darkMode", "enabled");
		// 			icon.classList.remove("fa-moon-o");
		// 			icon.classList.add("fa-sun-o");
		// 		} else {
		// 			localStorage.setItem("darkMode", "disabled");
		// 			icon.classList.remove("fa-sun-o");
		// 			icon.classList.add("fa-moon-o");
		// 		}
		// 	});
		// });

    </script>
    <!-- /Loader Script -->
		
    </body>
</html>