<?php

include '../Connection/config.php';

header("Content-Type: application/json; charset=UTF-8");

$username = $_POST['username'];
$password = mysqli_real_escape_string($conn, $_POST["password"]); 
$encryptedPassword = md5($password);

$sql = "SELECT * FROM `tbl_user` WHERE `username` ='$username' AND `password` = '$encryptedPassword'";
$result = $conn->query($sql);

$myObj = new \stdClass();

if (empty($username) || empty($password)) {
    $myObj->success = "false";
    $myObj->error = "empty";
    $myJSON = json_encode($myObj);
    echo $myJSON;
    exit(); // Terminate script execution
} elseif ($result->num_rows > 0) {
    // Username and password are correct
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['user'] = $username; // You may need to store other relevant data in the session

    // Generate a token
    $token = bin2hex(random_bytes(32));

    // Store the token in the session
    $_SESSION['token'] = $token;

    // Return the token and status in the response
    $myObj->success = "true";
    $myObj->token = $token;
    $myObj->status = $row['Status'];
    $myJSON = json_encode($myObj);
    echo $myJSON;
    exit(); // Terminate script execution
} else {
    $myObj->success = "false";
    $myObj->error = "invalid";
    $myJSON = json_encode($myObj);
    echo $myJSON;
    exit(); // Terminate script execution
}

mysqli_close($conn);
?>
