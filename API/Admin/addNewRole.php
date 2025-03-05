<?php

require '../../API/Connection/BackEndPermission.php';

$Role_Name = $_POST['Role_Name'];

if (empty($Role_Name)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Role_Name already exists in the database
    $checkQuery = "SELECT * FROM `tbl_roles` WHERE `Role_Name`='$Role_Name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Role_Name already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {
        // Get the maximum Role_Id in the database
        $maxRolIDQuery = "SELECT MAX(Role_Id) AS max_role_id FROM tbl_roles";
        $maxRolIDResult = mysqli_query($conn, $maxRolIDQuery);
        $maxRolIDRow = mysqli_fetch_assoc($maxRolIDResult);
        $maxRolID = $maxRolIDRow['max_role_id'];

        // If there are no existing roles, start with ROL0001
        if (!$maxRolID) {
            $newRoleId = 'ROL0001';
        } else {
            // Extract the numeric part of the Role_Id and increment it
            $maxRolNum = intval(substr($maxRolID, 3));
            $newRolNum = str_pad($maxRolNum + 1, 4, '0', STR_PAD_LEFT);
            $newRoleId = 'ROL' . $newRolNum;
        }

        // Perform the insertion
        $sql = "INSERT INTO `tbl_roles` (`Role_Id`, `Role_Name`)
                VALUES ('$newRoleId', '$Role_Name');";

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