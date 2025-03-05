<?php

require '../../API/Connection/BackEndPermission.php';

$Category_Id = $_POST['Category_Id'];
$Category_Name = $_POST['Category_Name'];

if (empty($Category_Id) || empty($Category_Name)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Category_Id already exists in the database
    $checkQuery = "SELECT * FROM `tbl_category` WHERE `Category_Id`='$Category_Id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Category_Id already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {

        // Perform the insertion
        $sql = "INSERT INTO `tbl_category` (`Category_Id`, `Category_Name`)
                VALUES ('$Category_Id', '$Category_Name');";

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