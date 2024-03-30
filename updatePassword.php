<?php
session_start();
//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['user_id'])) 
{
    echo json_encode(['message' => 'User not authenticated']);
    exit();
}

$userId = $_SESSION['user_id'];
$newPassword = $_POST['newPassword'] ?? '';

if (strlen($newPassword) < 8)
{
    echo json_encode(['message' => 'Password need to be 8 Characters long!']);
    exit();
}

//Security function needed for the MCF!
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE user_details SET password = ? WHERE user_id = ?");
$stmt->bind_param("si", $hashedPassword, $userId);

if ($stmt->execute()) 
{
    echo json_encode(['message' => 'Password updated successfully']);
} 
else 
{
    echo json_encode(['message' => 'Failed to update password']);
}