<?php

session_start();

// Check if the user is logged in and has a valid token
if (!isset($_SESSION['user']) || !isset($_SESSION['token'])) {
    http_response_code(401); // Unauthorized status code
    exit(json_encode(["error" => "Unauthorized access"]));
}

include '../Connection/config.php';
header("Content-Type: application/json; charset=UTF-8");

// Retrieve data from POST request
$Id = $_POST['Id'];
$First_Name = $_POST['First_Name'];
$Last_Name = $_POST['Last_Name'];
$Username = $_POST['Username'];

// Check if an image is uploaded
if (isset($_FILES['Img']) && $_FILES['Img']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['Img']['tmp_name'];
    $fileName = $_FILES['Img']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    if (in_array($fileExtension, $allowedExtensions)) {
        // Define image file path and URL
        $imageLocation = "../../Images/Admins/$Username.$fileExtension";
        $imagePath = "Images/Admins/$Username.$fileExtension";
        $uploadedUrl = $imagePath;

        // Move uploaded file to the target directory
        if (move_uploaded_file($fileTmpPath, $imageLocation)) {
            // Update user data including image
            $sql = "UPDATE `tbl_user` 
                    SET `First_Name` = '$First_Name', `Last_Name` = '$Last_Name', `Username` = '$Username', `Img` = '$uploadedUrl' 
                    WHERE `tbl_user`.`Id` = '$Id';";
        } else {
            $myObj = new \stdClass();
            $myObj->success = 'false';
            $myJSON = json_encode($myObj);
            echo $myJSON;
            exit();
        }
    } else {
        $myObj = new \stdClass();
        $myObj->success = 'false';
        $myObj->error = 'invalid_file_type';
        $myJSON = json_encode($myObj);
        echo $myJSON;
        exit();
    }
} else {
    // Update user data without image
    $sql = "UPDATE `tbl_user` 
            SET `First_Name` = '$First_Name', `Last_Name` = '$Last_Name', `Username` = '$Username' 
            WHERE `tbl_user`.`Id` = '$Id';";
}

// Execute the update query
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

?>