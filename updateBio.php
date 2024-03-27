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
$bio = $_POST['bio'] ?? '';

// Update the user's bio
$stmt = $conn->prepare("UPDATE user_details SET bio = ? WHERE user_id = ?");
$stmt->bind_param("si", $bio, $userId);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Bio updated successfully']);
} else {
    echo json_encode(['message' => 'Failed to update bio']);
}