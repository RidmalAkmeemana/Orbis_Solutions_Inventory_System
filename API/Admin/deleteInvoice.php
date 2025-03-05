<?php

require '../../API/Connection/BackEndPermission.php';

$Invoice_Id = $_POST['Invoice_Id'];

if (empty($Invoice_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Invoice_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_invoice` WHERE `tbl_invoice`.`Invoice_Id` = '$Invoice_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Begin transaction to ensure both deletions happen atomically
        mysqli_begin_transaction($conn);

        try {
            // Delete from tbl_item first where Invoice_Id matches
            $deleteItemsQuery = "DELETE FROM tbl_item WHERE Invoice_Id = '$Invoice_Id';";
            $deleteItemsResult = mysqli_query($conn, $deleteItemsQuery);

            // Delete from tbl_invoice where Invoice_Id matches
            $deleteInvoiceQuery = "DELETE FROM tbl_invoice WHERE Invoice_Id = '$Invoice_Id';";
            $deleteInvoiceResult = mysqli_query($conn, $deleteInvoiceQuery);

            // Check if both deletions were successful
            if ($deleteItemsResult && $deleteInvoiceResult) {
                // Commit transaction if both deletions are successful
                mysqli_commit($conn);

                $myObj = new \stdClass();
                $myObj->success = 'true';
                $myJSON = json_encode($myObj);
                echo $myJSON;
            } else {
                // Rollback transaction if there is any failure
                mysqli_rollback($conn);

                $myObj = new \stdClass();
                $myObj->success = 'false';
                $myObj->error = 'deletion_failed';
                $myJSON = json_encode($myObj);
                echo $myJSON;
            }
        } catch (Exception $e) {
            // In case of exception, rollback the transaction
            mysqli_rollback($conn);

            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myObj->error = 'exception';
            $myObj->message = $e->getMessage();
            $myJSON = json_encode($myObj);
            echo $myJSON;
        }
    } else {
        // If Invoice_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_invoice_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}

mysqli_close($conn);
?>
