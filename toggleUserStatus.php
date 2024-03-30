<?php
session_start();

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Looking for user access again here!
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "Unauthorized access.";
    exit;
}

// Toggle UserStatus I have given the admin user I created in the Database the ability to have a list of teh users on the Website and just Ban/Unban them!:)
if (isset($_POST['userId'], $_POST['isEnabled'])) 
{
    $userId = $_POST['userId'];
    $isEnabled = $_POST['isEnabled'];
    $userId = filter_var($userId, FILTER_VALIDATE_INT);
    if ($userId === false) 
    {
        echo "Invalid User ID.";
        exit;
    }
    $isEnabled = $isEnabled === '1' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE user_details SET is_enabled = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $isEnabled, $userId);
    if ($stmt->execute()) 
    {
        echo "Update Successful";
    } 
    else 
    {
        echo "Update did not go through";
    }
} 
else 
{
    echo "Required data not provided.";
}

$conn->close();
?>