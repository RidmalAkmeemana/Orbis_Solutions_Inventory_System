<?php
require '../../API/Connection/config.php'; // Ensure correct path to DB connection file

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
    <title><?php echo($companyName); ?> - Login</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="assets/img/logo-white.png" alt="Logo">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Login</h1>
                            <p class="account-subtitle">Access to Administrator dashboard</p>
                            <!-- Form -->
                            <form method="POST" class="needs-validation" novalidate="">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Username" name="username">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="Password" name="password">
                                </div>
                                <div id="error-message" class="text-danger"></div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit" name="login">Login</button>
                                    <br>
                                    <p>Username: system_admin</p>
                                    <p>Password: 123456</p>
                                </div>
                            </form>
                            <!-- /Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>

    <script>
        $(document).ready(function () {
    $('form').submit(function (event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: '../../API/Admin/getAdminLogin.php',
            data: $('form').serialize(),
            success: function (response) {
                if (response.success === 'true') {
                    // Store the token in localStorage for future requests
                    localStorage.setItem('token', response.token);
                    window.location.href = 'home.php';
                } else {
                    if (response.error === 'empty') {
                        $('#error-message').html("<center><label class='text-danger'>Username & Password is Required</label></center>");
                    } else {
                        $('#error-message').html("<center><label class='text-danger'>Invalid Username or Password</label></center>");
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', status, error);
            }
        });
    });
});
    </script>

</body>

</html>