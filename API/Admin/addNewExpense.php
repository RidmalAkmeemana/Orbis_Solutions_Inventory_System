<?php

include '../Connection/uploadurl.php';
require '../../API/Connection/BackEndPermission.php';

// Get the current date and time
date_default_timezone_set("Asia/Colombo");
$currentDateTime = date('Y-m-d H:i:s');

// Sanitize and escape POST data
$Expense_Type_Id = mysqli_real_escape_string($conn, $_POST['Expense_Type_Id']);
$User_Id = mysqli_real_escape_string($conn, $_POST['User_Id']);
$Expense_Title = mysqli_real_escape_string($conn, $_POST['Expense_Title']);
$Expense_Amount = $_POST['Expense_Amount'];
$Expense_Description = isset($_POST['Expense_Description']) ? mysqli_real_escape_string($conn, $_POST['Expense_Description']) : 'NULL';
$Status = "Unpaid";
$Paid_Amount = 0.00;
$Due_Amount = $_POST['Expense_Amount'];
$Payment_Type = "N/A";
$Expense_Date = $currentDateTime;
$Payment_Date = 'NULL';

// If Expense_Description is empty, set it to NULL without quotes
if (empty($Expense_Description)) {
    $Expense_Description = 'NULL';
} else {
    $Expense_Description = "'$Expense_Description'";
}

// Handle file upload
if (isset($_FILES['Doc']) && $_FILES['Doc']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['Doc']['tmp_name'];
    $fileName = $_FILES['Doc']['name'];
    $fileSize = $_FILES['Doc']['size'];
    $fileType = $_FILES['Doc']['type'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'xlsx'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'invalid file type';
        echo json_encode($myObj);
        exit;
    }
} else {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'File upload error';
    echo json_encode($myObj);
    exit;
}

if (empty($Expense_Type_Id) || empty($Expense_Title) || empty($Expense_Amount)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'empty';
    echo json_encode($myObj);
    exit;
}

// Get the maximum Expense_Id in the database
$maxExpIDQuery = "SELECT MAX(Expense_Id) AS max_expense_id FROM tbl_expenses";
$maxExpIDResult = mysqli_query($conn, $maxExpIDQuery);
$maxExpIDRow = mysqli_fetch_assoc($maxExpIDResult);
$maxExpID = $maxExpIDRow['max_expense_id'];

// If there are no existing expense types, start with EXP0001
if (!$maxExpID) {
    $newExpenseId = 'EXP0001';
} else {
    // Extract the numeric part of the Expense_Id and increment it
    $maxExpNum = intval(substr($maxExpID, 3));
    $newExpNum = str_pad($maxExpNum + 1, 4, '0', STR_PAD_LEFT);
    $newExpenseId = 'EXP' . $newExpNum;
}

// File destination
$fileLocation = "../../Files/$newExpenseId.$fileExtension";
$filePath = "Files/$newExpenseId.$fileExtension";
$uploadedUrl = $filePath;

// Move the file to the destination
if (!move_uploaded_file($fileTmpPath, $fileLocation)) {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->error = 'file save error';
    echo json_encode($myObj);
    exit;
}

// Perform the insertion
$sql = "INSERT INTO `tbl_expenses` (Expense_Id, Expense_Type_Id, User_Id, Expense_Title, Expense_Amount, Doc, Expense_Description, Status, Paid_Amount, Due_Amount, Payment_Type, Expence_Date, Payment_Date)
        VALUES ('$newExpenseId', '$Expense_Type_Id', '$User_Id', '$Expense_Title', '$Expense_Amount', '$uploadedUrl', $Expense_Description, '$Status', '$Paid_Amount', '$Due_Amount', '$Payment_Type', '$Expense_Date', $Payment_Date)";

if (mysqli_query($conn, $sql)) {
    $myObj = new \stdClass();
    $myObj->success = 'true';
    echo json_encode($myObj);
} else {
    $myObj = new \stdClass();
    $myObj->success = 'false';
    $myObj->message = mysqli_error($conn);
    echo json_encode($myObj);
}
?>
