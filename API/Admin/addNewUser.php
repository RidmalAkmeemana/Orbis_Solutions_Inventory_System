<?php

require '../../API/Connection/BackEndPermission.php';
include '../Connection/uploadurl.php';

$First_Name = $_POST['First_Name'];
$Last_Name = $_POST['Last_Name'];
$Username = $_POST['Username'];
$Password = md5($_POST['Password']);
$Status = $_POST['Status'];

$imagpath = "Images/Admins/default_profile.png";
$uploade_url = $base_url . $imagpath;
$img = $uploade_url;

if (empty($First_Name) || empty($Last_Name) || empty($Username) || empty($Password) || empty($Status)) {
    $myObj = new \stdClass(); 
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Username already exists in the database
    $checkQuery = "SELECT * FROM `tbl_user` WHERE `Username`='$Username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Username already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {
        // If Username doesn't exist, perform the insertion
        $sql = "INSERT INTO `tbl_user` (`First_Name`, `Last_Name`, `Username`, `Password`, `Status`, `img`)
        VALUES ('$First_Name', '$Last_Name', '$Username', '$Password', '$Status', '$img');";

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
    }
}
?>