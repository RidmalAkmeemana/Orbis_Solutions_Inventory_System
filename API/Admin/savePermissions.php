<?php
// Database connection
require_once '../../API/Connection/BackEndPermission.php';

// Function to check if a record exists in a table
function recordExists($conn, $tableName, $roleName, $screenId, $idColumn) {
    $sql = "SELECT COUNT(*) AS count FROM $tableName WHERE Role = ? AND $idColumn = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $roleName, $screenId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }
    return false;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the data sent via POST request
    $formData = $_POST;

    // Initialize arrays for separating data
    $backendData = array();
    $screensData = array();
    $resultData = array();
    $success = true;

    // Check if Role_Id is set
    if (isset($formData['Role_Id'])) {
        $Role_Id = $formData['Role_Id'];

        // Prepare the SQL query to fetch Role_Name
        $sql = "SELECT Role_Name FROM tbl_roles WHERE Role_Id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind the Role_Id parameter
            $stmt->bind_param("s", $Role_Id); // Use "s" for string type

            // Execute the statement
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Check if a row is returned
            if ($result->num_rows > 0) {
                // Fetch the result
                $row = $result->fetch_assoc();
                $Role_Name = $row['Role_Name'];

                // Separate the form data into two arrays based on Table_Name
                foreach ($formData as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (isset($item['Table_Name']) && isset($item['ScreenId']) && isset($item['Checked'])) {
                                // Convert Checked to a boolean value
                                $checked = filter_var($item['Checked'], FILTER_VALIDATE_BOOLEAN);
                                
                                // Create a new array with the required details
                                $newItem = array(
                                    'Table_Name' => $item['Table_Name'],
                                    'Role_Name' => $Role_Name,
                                    'ScreenId' => $item['ScreenId'],
                                    'Checked' => $checked
                                );

                                // Process backend data
                                if ($item['Table_Name'] === 'tbl_backend') {
                                    if ($checked) {
                                        // Insert if not exists
                                        if (!recordExists($conn, 'tbl_backend_permissions', $Role_Name, $item['ScreenId'], 'Backend_Id')) {
                                            $sqlInsert = "INSERT INTO tbl_backend_permissions (Role, Backend_Id) VALUES (?, ?)";
                                            if ($stmtInsert = $conn->prepare($sqlInsert)) {
                                                $stmtInsert->bind_param("ss", $Role_Name, $item['ScreenId']);
                                                if (!$stmtInsert->execute()) {
                                                    $success = false;
                                                }
                                                $stmtInsert->close();
                                            } else {
                                                $success = false;
                                            }
                                        }
                                    } else {
                                        // Delete if exists
                                        if (recordExists($conn, 'tbl_backend_permissions', $Role_Name, $item['ScreenId'], 'Backend_Id')) {
                                            $sqlDelete = "DELETE FROM tbl_backend_permissions WHERE Role = ? AND Backend_Id = ?";
                                            if ($stmtDelete = $conn->prepare($sqlDelete)) {
                                                $stmtDelete->bind_param("ss", $Role_Name, $item['ScreenId']);
                                                if (!$stmtDelete->execute()) {
                                                    $success = false;
                                                }
                                                $stmtDelete->close();
                                            } else {
                                                $success = false;
                                            }
                                        }
                                    }
                                    $backendData[] = $newItem;
                                } elseif ($item['Table_Name'] === 'tbl_screens') {
                                    if ($checked) {
                                        // Insert if not exists
                                        if (!recordExists($conn, 'tbl_screen_permissions', $Role_Name, $item['ScreenId'], 'Screen_Id')) {
                                            $sqlInsert = "INSERT INTO tbl_screen_permissions (Role, Screen_Id) VALUES (?, ?)";
                                            if ($stmtInsert = $conn->prepare($sqlInsert)) {
                                                $stmtInsert->bind_param("ss", $Role_Name, $item['ScreenId']);
                                                if (!$stmtInsert->execute()) {
                                                    $success = false;
                                                }
                                                $stmtInsert->close();
                                            } else {
                                                $success = false;
                                            }
                                        }
                                    } else {
                                        // Delete if exists
                                        if (recordExists($conn, 'tbl_screen_permissions', $Role_Name, $item['ScreenId'], 'Screen_Id')) {
                                            $sqlDelete = "DELETE FROM tbl_screen_permissions WHERE Role = ? AND Screen_Id = ?";
                                            if ($stmtDelete = $conn->prepare($sqlDelete)) {
                                                $stmtDelete->bind_param("ss", $Role_Name, $item['ScreenId']);
                                                if (!$stmtDelete->execute()) {
                                                    $success = false;
                                                }
                                                $stmtDelete->close();
                                            } else {
                                                $success = false;
                                            }
                                        }
                                    }
                                    $screensData[] = $newItem;
                                }

                                // Store in resultData array
                                $resultData[] = $newItem;
                            }
                        }
                    }
                }

            } else {
                // Role_Id not found
                echo json_encode(array('success' => false, 'message' => 'Role not found'));
                exit;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Error preparing the SQL statement
            echo json_encode(array('success' => false, 'message' => 'Database query error'));
            exit;
        }
    } else {
        // Role_Id not provided
        echo json_encode(array('success' => false, 'message' => 'Role_Id not provided'));
        exit;
    }

    // Return the success status
    echo json_encode(array('success' => $success));
}
?>
