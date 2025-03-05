<?php

require '../../API/Connection/BackEndPermission.php';
include '../Connection/uploadurl.php';

// SQL query to fetch supplier details
$sql = "SELECT * FROM tbl_user ORDER BY tbl_user.Id ASC";

$result = $conn->query($sql);

$dataset = array();

$dataset = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imgPath = $row["Img"];
        // Check if the imgPath is 'C:' and set default image path accordingly
        if ($imgPath === 'C:') {
            $imgPath = 'Images/Admins/admin.jpg'; // Set default image path
            $img_url = $base_url . $imgPath;
        } else {
            $img_url = $imgPath;
        }

        array_push($dataset, array(
            "id" => $row["Id"],
            "img" => $img_url,
            "firstname" => $row["First_Name"],
            "lastname" => $row["Last_Name"],
            "status" => $row["Status"],
            "username" => $row["Username"]
        ));
    }
}

echo json_encode($dataset);
mysqli_close($conn);
?>
