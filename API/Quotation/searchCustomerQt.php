<?php

require '../../API/Connection/BackEndPermission.php';

$Customer_Id = $_REQUEST['Customer_Id'];  // Customer_Id parameter

// Calculate the SUM of Due_Total for the given Customer_Id
$sql_due_total = "
    SELECT SUM(Due_Total) AS TotalDue
    FROM tbl_invoice
    WHERE Customer_Id = ?
";

$stmt_due_total = $conn->prepare($sql_due_total);
$stmt_due_total->bind_param("s", $Customer_Id);
$stmt_due_total->execute();
$result_due_total = $stmt_due_total->get_result();
$due_total_row = $result_due_total->fetch_assoc();
$total_due = $due_total_row['TotalDue'];

// Return the TotalDue as JSON
echo json_encode(array("TotalDue" => number_format($total_due, 2)));

mysqli_close($conn);

?>
