<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch company details
$sql = "SELECT * FROM tbl_company_info LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Create an associative array for response
    $response = array(
        "Company_Id" => $row["Id"],
        "Company_Name" => $row["Company_Name"],
        "Company_Address" => $row["Company_Address"],
        "Company_Email" => $row["Company_Email"],
        "Company_Tel1" => $row["Company_Tel1"],
        "Company_Tel2" => $row["Company_Tel2"],
        "Company_Tel3" => $row["Company_Tel3"]
    );

    echo json_encode($response); // Return JSON without array
} else {
    echo json_encode(["error" => "No company data found"]);
}

mysqli_close($conn);

?>
