<?php

require '../../API/Connection/BackEndPermission.php';

$Brand_Id = $_POST['Brand_Id'];
$Brand_Name = $_POST['Brand_Name'];

if (empty($Brand_Id) || empty($Brand_Name)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Brand_Id already exists in the database
    $checkQuery = "SELECT * FROM `tbl_brand` WHERE `Brand_Id`='$Brand_Id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Brand_Id already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {

        // Perform the insertion
        $sql = "INSERT INTO `tbl_brand` (`Brand_Id`, `Brand_Name`)
                VALUES ('$Brand_Id', '$Brand_Name');";

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