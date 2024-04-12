<?php
session_start();
if (!isset($_SESSION['user_id'])) 
{
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'];

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$newUsername = isset($_POST['username']) ? $_POST['username'] : '';

$stmt = $conn->prepare("SELECT user_id FROM user_details WHERE username = ? AND user_id != ?");
$stmt->bind_param("si", $newUsername, $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) 
{
    echo json_encode(['success' => false, 'message' => 'Username already taken']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt = $conn->prepare("UPDATE user_details SET username = ? WHERE user_id = ?");
$stmt->bind_param("si", $newUsername, $userId);

if ($stmt->execute()) 
{
    echo json_encode(['success' => true, 'message' => 'Username was updated successfully']);
} 
else 
{
    echo json_encode(['success' => false, 'message' => 'Failed to update the username']);
}

$stmt->close();
$conn->close();
?>