<?php
session_start();

// Check if the user is logged in and has a valid token
if (!isset($_SESSION['user']) || !isset($_SESSION['token'])) {
    // User is not logged in or doesn't have a valid token
    http_response_code(401); // Unauthorized status code
    exit(json_encode(["error" => "Unauthorized access"]));
}

include '../Connection/config.php';
header("Content-Type: application/json; charset=UTF-8");

$username = $_SESSION['user'];

// Fetch user role
$query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `username` = '$username'") or die(mysqli_error());
$fetch = mysqli_fetch_array($query);

$backend_name = basename($_SERVER['PHP_SELF']);

$permission_query = mysqli_query($conn, "
    SELECT bp.*
    FROM `tbl_backend_permissions` bp
    JOIN `tbl_backend` b ON bp.Backend_Id = b.Backend_Id
    WHERE bp.Role = '$fetch[Status]' AND b.Backend_Name = '$backend_name'
") or die(mysqli_error());

if (mysqli_num_rows($permission_query) == 0) {
    http_response_code(401); // Unauthorized status code
    exit(json_encode(["error" => "Unauthorized access"]));
}
?>