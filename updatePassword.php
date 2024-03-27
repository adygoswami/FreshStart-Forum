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

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'User not authenticated']);
    exit();
}

$userId = $_SESSION['user_id'];
$newPassword = $_POST['newPassword'] ?? '';

// Validate the new password (e.g., length, complexity) according to your project's requirements
if (strlen($newPassword) < 8) {
    echo json_encode(['message' => 'Password must be at least 8 characters long']);
    exit();
}

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the user's password
$stmt = $conn->prepare("UPDATE user_details SET password = ? WHERE user_id = ?");
$stmt->bind_param("si", $hashedPassword, $userId);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Password updated successfully']);
} else {
    echo json_encode(['message' => 'Failed to update password']);
}