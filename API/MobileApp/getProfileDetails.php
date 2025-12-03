<?php
require_once '../../API/Connection/config.php';
include '../../API/Connection/uploadurl.php';
header("Content-Type: application/json");

if (!isset($_REQUEST['username'])) {
    echo json_encode(["success" => false, "message" => "User login is required"]);
    exit;
}

$username = $_REQUEST['username'];

$query = "SELECT * FROM tbl_user WHERE Username = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    echo json_encode([
        "success" => true,
        "Id" => $user['Id'],
        "First_Name" => $user['First_Name'],
        "Last_Name" => $user['Last_Name'],
        "Username" => $user['Username'],
        "Status" => $user['Status'],
        "Img" => $base_url.$user['Img']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
}
