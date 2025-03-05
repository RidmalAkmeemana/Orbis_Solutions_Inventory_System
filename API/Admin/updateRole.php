<?php

require '../../API/Connection/BackEndPermission.php';

$Role_Id = $_POST['Role_Id'];
$Role_Name = $_POST['Role_Name'];

if (empty($Role_Id) || empty($Role_Name)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} 
else {
    
    // Check if Role_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_roles` WHERE `tbl_roles`.`Role_Id` = '$Role_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Role_Id exists, perform the update
        $updateQuery = "UPDATE `tbl_roles` SET `Role_Name` = '$Role_Name' WHERE `tbl_roles`.`Role_Id` = '$Role_Id';";
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
        // If Role_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_role_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>