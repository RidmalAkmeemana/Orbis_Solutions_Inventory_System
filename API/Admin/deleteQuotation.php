<?php

require '../../API/Connection/BackEndPermission.php';

$Quotation_Id = $_POST['Quotation_Id'];

if (empty($Quotation_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} else {
    // Check if Quotation_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_quotation` WHERE `tbl_quotation`.`Quotation_Id` = '$Quotation_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Begin transaction to ensure both deletions happen atomically
        mysqli_begin_transaction($conn);

        try {
            // Delete from tbl_quote_item first where Quotation_Id matches
            $deleteItemsQuery = "DELETE FROM tbl_quote_item WHERE Quotation_Id = '$Quotation_Id';";
            $deleteItemsResult = mysqli_query($conn, $deleteItemsQuery);

            // Delete from tbl_quotation where Quotation_Id matches
            $deleteQuotationQuery = "DELETE FROM tbl_quotation WHERE Quotation_Id = '$Quotation_Id';";
            $deleteQuotationResult = mysqli_query($conn, $deleteQuotationQuery);

            // Check if both deletions were successful
            if ($deleteItemsResult && $deleteQuotationResult) {
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
        // If Quotation_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_quotation_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}

mysqli_close($conn);
?>
