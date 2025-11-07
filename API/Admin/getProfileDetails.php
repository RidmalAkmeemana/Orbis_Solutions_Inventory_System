<?php
// Include necessary files
require_once '../../API/Connection/validator.php';
require_once '../../API/Connection/config.php';
include '../../API/Connection/uploadurl.php';

// Initialize response array
$response = array();

// Check if user is logged in
if(isset($_SESSION['user'])) {
    // Retrieve user details from the database
    $username = $_SESSION['user'];
    $query = "SELECT * FROM `tbl_user` WHERE `Username` = '$username'";
    $result = mysqli_query($conn, $query);

    if($result) {
        // Fetch user details
        $user = mysqli_fetch_assoc($result);

        // Set success flag
        $response['success'] = true;

        // Store user details in response array
        $response['First_Name'] = $user['First_Name'];
        $response['Last_Name'] = $user['Last_Name'];
        $response['Username'] = $user['Username'];
        $response['Status'] = $user['Status'];
        $response['Img'] = $base_url.$user['Img'];
        
    } else {
        // Set error flag
        $response['success'] = false;
        $response['message'] = 'Failed to fetch user details';
    }
} else {
    // Set error flag if user is not logged in
    $response['success'] = false;
    $response['message'] = 'User is not logged in';
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>