<?php

require '../../API/Connection/BackEndPermission.php';

$Customer_Name = $_POST['Customer_Name'];
$Customer_Contact = $_POST['Customer_Contact'];
$Customer_Email = isset($_POST['Customer_Email']) && !empty($_POST['Customer_Email']) ? mysqli_real_escape_string($conn, $_POST['Customer_Email']) : 'NULL';
$Customer_Address = isset($_POST['Customer_Address']) && !empty($_POST['Customer_Address']) ? mysqli_real_escape_string($conn, $_POST['Customer_Address']) : 'NULL';

if (empty($Customer_Name) || empty($Customer_Contact)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if the Customer_Name already exists in the database
    $checkQuery = "SELECT * FROM `tbl_customers` WHERE `Customer_Name`='$Customer_Name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If Customer_Name already exists, return 'false'
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'duplicate';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    } else {
        // Get the maximum Customer_Id in the database
        $maxCusIDQuery = "SELECT MAX(Customer_Id) AS max_customer_id FROM tbl_customers";
        $maxCusIDResult = mysqli_query($conn, $maxCusIDQuery);
        $maxCusIDRow = mysqli_fetch_assoc($maxCusIDResult);
        $maxCusID = $maxCusIDRow['max_customer_id'];

        // If there are no existing customers, start with CUS0001
        if (!$maxCusID) {
            $newCustomerId = 'CUS0001';
        } else {
            // Extract the numeric part of the Customer_Id and increment it
            $maxCusNum = intval(substr($maxCusID, 3));
            $newCusNum = str_pad($maxCusNum + 1, 4, '0', STR_PAD_LEFT);
            $newCustomerId = 'CUS' . $newCusNum;
        }

        // Prepare NULL values correctly for SQL
        $Customer_Address = $Customer_Address === 'NULL' ? 'NULL' : "'$Customer_Address'";
        $Customer_Email = $Customer_Email === 'NULL' ? 'NULL' : "'$Customer_Email'";

        // Perform the insertion
        $sql = "INSERT INTO `tbl_customers` (`Customer_Id`, `Customer_Name`, `Customer_Address`, `Customer_Contact`, `Customer_Email`)
                VALUES ('$newCustomerId', '$Customer_Name', $Customer_Address, '$Customer_Contact', $Customer_Email);";

        if (mysqli_query($conn, $sql)) {
            $myObj = new \stdClass();
            $myObj->success = 'true';
            $myJSON = json_encode($myObj);
            echo $myJSON;
        } else {
            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myObj->error = mysqli_error($conn);
            $myJSON = json_encode($myObj);
            echo $myJSON;
        }
    }
}
?>
