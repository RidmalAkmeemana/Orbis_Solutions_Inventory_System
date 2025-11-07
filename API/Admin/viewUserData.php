<?php

require '../../API/Connection/BackEndPermission.php';
include '../Connection/uploadurl.php';

$Id = $_REQUEST["Id"];

// Fetch role data
$sqlRole = "SELECT * FROM tbl_user WHERE `Id` = '$Id'";
$resultRole = $conn->query($sqlRole);

$userData = array();

if ($resultRole->num_rows > 0) {
    $rowUser = $resultRole->fetch_assoc();

    // Check if the imgPath is 'C:' and set default image path accordingly
    $imgPath = $rowUser["Img"];
    if ($imgPath === 'C:') {
        $imgPath = 'Images/Admins/admin.jpg'; // Set default image path
        $img_url = $base_url . $imgPath;
    } else {
        $img_url = $base_url . $imgPath;
    }

    // Construct user data
    $userData = array(
        'Id' => $rowUser["Id"],
        'First_Name' => $rowUser["First_Name"],
        'Last_Name' => $rowUser["Last_Name"],
        'Username' => $rowUser["Username"],
        'Status' => $rowUser["Status"],
        'img_url' => $img_url
    );
}

// Combine user data into a single response object
$response = array(
    'userData' => $userData
);

// Encode response object as JSON and output it
echo json_encode($response);

?>
