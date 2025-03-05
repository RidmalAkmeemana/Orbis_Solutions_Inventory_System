<?php
require_once 'validator.php';
require_once 'config.php';

$query = mysqli_query($conn, "SELECT * FROM `tbl_user` WHERE `username` = '$_SESSION[user]'") or die(mysqli_error());
$fetch = mysqli_fetch_array($query);

$screen_name = basename($_SERVER['PHP_SELF']);

$permission_query = mysqli_query($conn, "
    SELECT sp.*
    FROM `tbl_screen_permissions` sp
    JOIN `tbl_screens` s ON sp.Screen_Id = s.Screen_Id
    WHERE sp.Role = '$fetch[Status]' AND s.Screen_Name = '$screen_name'
") or die(mysqli_error());

if (mysqli_num_rows($permission_query) == 0) {
    // Redirect to an error page or display an error message
    header('Location: unauthorized_access.php');
    exit();
}
?>