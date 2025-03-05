<?php

require '../../API/Connection/BackEndPermission.php';

$Role_Id = $_REQUEST["Role_Id"];

// Fetch role data
$sqlRole = "SELECT * FROM tbl_roles WHERE `Role_Id` = '$Role_Id'";
$resultRole = $conn->query($sqlRole);

$roleData = array();
$Role_Name = "";

if ($resultRole->num_rows > 0) {
    $rowRole = $resultRole->fetch_assoc();

    // Construct Role data
    $roleData = array(
        'Role_Id' => $rowRole["Role_Id"],
        'Role_Name' => $rowRole["Role_Name"]
    );

    $Role_Name = $rowRole["Role_Name"];
}

// Fetch users with the given Role_Name
$sqlUsers = "SELECT u.Id, u.First_Name, u.Last_Name, u.Username, u.Status, u.Img
             FROM tbl_user u
             JOIN tbl_roles r ON u.Status = r.Role_Name
             WHERE r.Role_Id = '$Role_Id'";
$resultUsers = $conn->query($sqlUsers);

$users = array();

if ($resultUsers->num_rows > 0) {
    while ($rowUser = $resultUsers->fetch_assoc()) {
        // Construct user data
        $userData = array(
            'Id' => $rowUser['Id'],
            'Img' => $rowUser['Img'],
            'First_Name' => $rowUser['First_Name'],
            'Last_Name' => $rowUser['Last_Name'],
            'Username' => $rowUser['Username'],
            'Status' => $rowUser['Status']
        );
        array_push($users, $userData);
    }
}

// Fetch all backend screens
$sqlBackends = "SELECT b.Backend_Id AS Screen_Id, b.Backend_Name AS Screen_Name, b.Screen_Category, b.Screen_Sub_Category, 'Backend' AS Screen_Type, 'tbl_backend' AS Table_Name
                FROM tbl_backend b";
$resultBackends = $conn->query($sqlBackends);

// Fetch all frontend screens
$sqlFrontends = "SELECT s.Screen_Id, s.Screen_Name, s.Screen_Category, s.Screen_Sub_Category, 'Frontend' AS Screen_Type, 'tbl_screens' AS Table_Name
                FROM tbl_screens s";
$resultFrontends = $conn->query($sqlFrontends);

$screens = array();

// Combine backend and frontend screens
if ($resultBackends->num_rows > 0) {
    while ($rowBackend = $resultBackends->fetch_assoc()) {
        $screens[] = $rowBackend;
    }
}

if ($resultFrontends->num_rows > 0) {
    while ($rowFrontend = $resultFrontends->fetch_assoc()) {
        $screens[] = $rowFrontend;
    }
}

// Fetch assigned screens with Permission_Id for the role
$sqlAssignedScreens = "SELECT b.Backend_Id AS Screen_Id, 'Backend' AS Screen_Type, 'tbl_backend' AS Table_Name, bp.Permission_Id
                       FROM tbl_backend b
                       JOIN tbl_backend_permissions bp ON b.Backend_Id = bp.Backend_Id
                       WHERE bp.Role = '$Role_Name'
                       UNION
                       SELECT s.Screen_Id, 'Frontend' AS Screen_Type, 'tbl_screens' AS Table_Name, sp.Permission_Id
                       FROM tbl_screens s
                       JOIN tbl_screen_permissions sp ON s.Screen_Id = sp.Screen_Id
                       WHERE sp.Role = '$Role_Name'";
$resultAssignedScreens = $conn->query($sqlAssignedScreens);

$assignedScreens = array();
if ($resultAssignedScreens->num_rows > 0) {
    while ($rowAssigned = $resultAssignedScreens->fetch_assoc()) {
        $assignedScreens[] = array(
            'Screen_Id' => $rowAssigned['Screen_Id'],
            'Table_Name' => $rowAssigned['Table_Name'],
            'Permission_Id' => $rowAssigned['Permission_Id']
        );
    }
}

// Function to group screens by category and subcategory
function groupScreensByCategory($screens, $assignedScreens) {
    $groupedScreens = array();

    foreach ($screens as $screen) {
        $category = $screen['Screen_Category'];
        $subCategory = $screen['Screen_Sub_Category'];
        $screenId = $screen['Screen_Id'];
        $tableName = $screen['Table_Name'];
        $isAssigned = in_array($screenId, array_column($assignedScreens, 'Screen_Id'));

        if (!isset($groupedScreens[$category])) {
            $groupedScreens[$category] = array();
        }

        if (!isset($groupedScreens[$category][$subCategory])) {
            $groupedScreens[$category][$subCategory] = array();
        }

        $groupedScreens[$category][$subCategory][] = array(
            'Screen_Id' => $screenId,
            'Screen_Name' => $screen['Screen_Name'],
            'Assigned' => $isAssigned,
            'Table_Name' => $tableName
        );
    }

    return $groupedScreens;
}

// Group screens by category and subcategory
$groupedScreens = groupScreensByCategory($screens, $assignedScreens);

// Combine role data, users, and grouped screens
$response = array(
    'roleData' => $roleData,
    'users' => $users,
    'screens' => $groupedScreens,
    'assignedScreens' => $assignedScreens
);

// Return the combined data as JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
