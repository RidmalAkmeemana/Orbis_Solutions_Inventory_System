<?php

require '../../API/Connection/BackEndPermission.php';

$Expense_Type_Id = $_POST['Expense_Type_Id'];
$Expense_Type = $_POST['Expense_Type'];
$Expense_Category_Id = $_POST['Expense_Category_Id'];

if (empty($Expense_Type_Id) || empty($Expense_Type) || empty($Expense_Category_Id)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    $myJSON = json_encode($myObj);
    echo $myJSON;
} 
else {
    
    // Check if Expense_Type_Id exists in the database
    $checkQuery = "SELECT * FROM `tbl_expenses_types` WHERE `tbl_expenses_types`.`Expense_Type_Id` = '$Expense_Type_Id';";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // If Expense_Type_Id exists, perform the update
        $updateQuery = "UPDATE `tbl_expenses_types` SET `Expense_Type` = '$Expense_Type', `Expense_Category_Id` = '$Expense_Category_Id' WHERE `tbl_expenses_types`.`Expense_Type_Id` = '$Expense_Type_Id';";
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
        // If Expense_Type_Id doesn't exist, send appropriate response
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'no_expense_type_data';
        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
}
?>