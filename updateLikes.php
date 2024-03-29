<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

// Database connection details
$servername = "localhost";
$username = "47130992"; // Your database username
$password = "freshstart360"; // Your database password
$dbname = "db_47130992"; // Your database name

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve post ID from the request
$postID = isset($_POST['postID']) ? intval($_POST['postID']) : 0;

// Update likes count
$stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE postID = ?");
$stmt->bind_param("i", $postID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update likes']);
}

// Close connections
$stmt->close();
$conn->close();
?>