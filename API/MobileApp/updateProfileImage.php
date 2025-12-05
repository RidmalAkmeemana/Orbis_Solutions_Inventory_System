<?php

require_once '../../API/Connection/config.php';
include '../../API/Connection/uploadurl.php';
header("Content-Type: application/json");

if (!isset($_REQUEST['username'])) {
    echo json_encode(["success" => false, "message" => "User login is required"]);
    exit;
};

// Retrieve data from REQUEST request
$username = $_REQUEST['username'];

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
                    SET `Img` = '$uploadedUrl' 
                    WHERE `tbl_user`.`Username` = '$username';";
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