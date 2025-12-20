<?php
require_once '../../API/Connection/config.php';
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_REQUEST['username'])) {
    echo json_encode(["success" => false, "message" => "User login is required"]);
    exit;
};

// Retrieve data from POST request
$Id = $_POST['Id'];
$First_Name = $_POST['First_Name'];
$Last_Name = $_POST['Last_Name'];
$Username = $_POST['Username'];

// Update user data without image
$sql = "UPDATE `tbl_user` SET `First_Name` = '$First_Name', `Last_Name` = '$Last_Name', `Username` = '$Username' WHERE `tbl_user`.`Id` = '$Id';";

// Execute the update query
if (mysqli_query($conn, $sql)) {
    $myObj = new \stdClass();
    $myObj->success = 'true';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myJSON = json_encode($myObj);
    echo $myJSON;
}

?>