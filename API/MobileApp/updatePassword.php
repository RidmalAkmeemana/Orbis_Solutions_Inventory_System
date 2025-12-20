<?php
require_once '../../API/Connection/config.php';
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_REQUEST['username'])) {
    echo json_encode(["success" => false, "message" => "User login is required"]);
    exit;
};

    $Id = $_POST['Id'];
    $newpassword = md5($_POST['newpassword']);
    $conpassword = md5($_POST['conpassword']);

if (empty($newpassword) || empty($conpassword)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
}
if ($newpassword != $conpassword)
{
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'password_mismatch';
    $myJSON = json_encode($myObj);
    echo $myJSON;
}
else
{
    // Check if Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_user` WHERE `tbl_user`.`Id` = '$Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Id exists, perform the update
        $updateQuery = "UPDATE `tbl_user` SET `password` = '$newpassword' WHERE `tbl_user`.`Id` = '$Id';";
        if (mysqli_query($conn, $updateQuery)) {
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
    } else {
        // If Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_user_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>