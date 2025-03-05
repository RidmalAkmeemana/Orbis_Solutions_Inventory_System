<?php

require '../../API/Connection/BackEndPermission.php';

// SQL query to get the Role details and the sum of user count related to Status
$sql = "SELECT tbl_roles.Role_Id, 
           tbl_roles.Role_Name, 
           COUNT(tbl_user.Id) AS user_count
    FROM tbl_roles
    LEFT JOIN tbl_user ON tbl_roles.Role_Name = tbl_user.Status
    GROUP BY tbl_roles.Role_Id, tbl_roles.Role_Name
    ORDER BY tbl_roles.Role_Id ASC";

$result = $conn->query($sql);

$dataset = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($dataset, array(
            "Role_Id" => $row["Role_Id"],
            "Role_Name" => $row["Role_Name"],
            "user_count" => number_format($row["user_count"])
        ));
    }
}

echo json_encode($dataset); // No JSON_NUMERIC_CHECK to avoid conversion of numeric strings
mysqli_close($conn);

?>
