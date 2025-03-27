<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to fetch company details
$sql = "SELECT * FROM tbl_system_configuration LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Create an associative array for response
    $response = array(
        "Id" => $row["Id"],
        "ServiceCharge_IsPercentage" => $row["ServiceCharge_IsPercentage"],
        "ServiceCharge" => $row["ServiceCharge"],
        "Tax_IsPercentage" => $row["Tax_IsPercentage"],
        "Tax" => $row["Tax"],
        "Vat_IsPercentage" => $row["Vat_IsPercentage"],
        "Vat" => $row["Vat"],
        "Delivery_IsPercentage" => $row["Delivery_IsPercentage"],
        "Delivery" => $row["Delivery"]
    );

    echo json_encode($response); // Return JSON without array
} else {
    echo json_encode(["error" => "No company data found"]);
}

mysqli_close($conn);

?>
