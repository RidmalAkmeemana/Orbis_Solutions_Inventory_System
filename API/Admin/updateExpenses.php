<?php

require '../../API/Connection/BackEndPermission.php';

$Expense_Id = $_POST['Expense_Id'];
$Expense_Title = $_POST['Expense_Title'];
$Expense_Type_Id = $_POST['Expense_Type_Id'];

if (empty($Expense_Id) || empty($Expense_Title) || empty($Expense_Type_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} 
else {
    
    // Check if Expense_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_expenses` WHERE `tbl_expenses`.`Expense_Id` = '$Expense_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Expense_Id exists, perform the update
        $updateQuery = "UPDATE `tbl_expenses` SET `Expense_Title` = '$Expense_Title', `Expense_Type_Id` = '$Expense_Type_Id' WHERE `tbl_expenses`.`Expense_Id` = '$Expense_Id';";
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
        // If Expense_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_expense_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>