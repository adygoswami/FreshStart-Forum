<?php
session_start();

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);

//Checking Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Controlling Access
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Unauthorized access.";
    exit;
}

// Toggle UserStatus
if (isset($_POST['userId'], $_POST['isEnabled'])) {
    $userId = $_POST['userId'];
    $isEnabled = $_POST['isEnabled'];

    //Several steps to check for the deletion of correct post, ensuring id is an integer.
    $userId = filter_var($userId, FILTER_VALIDATE_INT);
    if ($userId === false) {
        echo "Invalid User ID.";
        exit;
    }

    // Ensuring function isEnabled is either 1 or 0
    $isEnabled = $isEnabled === '1' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE user_details SET is_enabled = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $isEnabled, $userId);
    
    if ($stmt->execute()) 
    {
        echo "User status updated successfully.";
    } else {
        echo "Error updating user status.";
    }
} else {
    echo "Required data not provided.";
}

$conn->close();
?>