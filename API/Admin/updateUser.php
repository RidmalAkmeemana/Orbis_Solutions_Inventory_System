<?php

require '../../API/Connection/BackEndPermission.php';

$Id = $_POST['Id'];
$First_Name = $_POST['First_Name'];
$Last_Name = $_POST['Last_Name'];
$Status = $_POST['Status'];
$Username = $_POST['Username'];
$Password = $_POST['Password']; // Leave this unprocessed for now

if (empty($Id) || empty($First_Name) || empty($Last_Name) || empty($Status) || empty($Username)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
    exit(); // Stop further execution
}

// Check if user_id exists in the database
$checkQuery = "SELECT * FROM `tbl_user` WHERE `tbl_user`.`Id` = '$Id';";
$result = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    // If password is empty, update without changing the password
    if (empty($Password)) {
        $updateQuery = "UPDATE `tbl_user` SET `First_Name` = '$First_Name', `Last_Name` = '$Last_Name', `Status` = '$Status', `Username` = '$Username' WHERE `tbl_user`.`Id` = '$Id';";
    } else {
        // Hash the password and update including the password
        $hashedPassword = md5($Password);
        $updateQuery = "UPDATE `tbl_user` SET `First_Name` = '$First_Name', `Last_Name` = '$Last_Name', `Status` = '$Status', `Username` = '$Username', `Password` = '$hashedPassword' WHERE `tbl_user`.`Id` = '$Id';";
    }

    // Execute the update query
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
    // If user_id doesn't exist, send appropriate response
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'no_user_data';
    $myJSON = json_encode($myObj);
    echo $myJSON;
}

mysqli_close($conn);
?>
