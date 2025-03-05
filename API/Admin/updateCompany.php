<?php

require '../../API/Connection/BackEndPermission.php';

$Company_Id = $_POST['Company_Id'];
$Company_Name = $_POST['Company_Name'];
$Company_Address = $_POST['Company_Address'];
$Company_Email = $_POST['Company_Email'];
$Company_Tel1 = $_POST['Company_Tel1'];
$Company_Tel2 = !empty($_POST['Company_Tel2']) ? "'" . mysqli_real_escape_string($conn, $_POST['Company_Tel2']) . "'" : 'NULL';
$Company_Tel3 = !empty($_POST['Company_Tel3']) ? "'" . mysqli_real_escape_string($conn, $_POST['Company_Tel3']) . "'" : 'NULL';

// Validate required fields
if (empty($Company_Id) || empty($Company_Name) || empty($Company_Address) || empty($Company_Email) || empty($Company_Tel1)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
    exit;
}

// Check if Company_Id exists to perform the update
$updateCheckQuery = "SELECT * FROM `tbl_company_info` WHERE `Id` = '$Company_Id';";
$updateResult = mysqli_query($conn, $updateCheckQuery);

if (mysqli_num_rows($updateResult) > 0) {
    // Company_Id exists, perform the update
    $updateQuery = "UPDATE `tbl_company_info` SET 
                    `Company_Name` = '$Company_Name', 
                    `Company_Address` = '$Company_Address', 
                    `Company_Email` = '$Company_Email', 
                    `Company_Tel1` = '$Company_Tel1',
                    `Company_Tel2` = $Company_Tel2,
                    `Company_Tel3` = $Company_Tel3 
                    WHERE `Id` = '$Company_Id';";

    if (mysqli_query($conn, $updateQuery)) {
        $myObj = new \stdClass();
        $myObj->success = 'true';
        echo json_encode($myObj);
    } else {
        // Debugging error message
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'update_failed';
        $myObj->sql_error = mysqli_error($conn); // Shows actual SQL error
        echo json_encode($myObj);
    }
} else {
    // If Company_Id doesn't exist, send appropriate response
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'no_company_data';
    echo json_encode($myObj);
}

?>
