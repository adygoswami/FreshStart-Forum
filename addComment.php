<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

// Database connection details
$servername = "localhost";
$username = "47130992";  // Your database username
$password = "freshstart360";  // Your database password
$dbname = "db_47130992";  // Your database name

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve post ID and comment text from the request
$postID = isset($_POST['postID']) ? intval($_POST['postID']) : 0;
$commentText = isset($_POST['commentText']) ? trim($_POST['commentText']) : '';

// Sanitize input
$commentText = $conn->real_escape_string($commentText);

// Retrieve the user ID from the session
$userID = $_SESSION['user_id'];

// Prepare an INSERT statement to add the new comment
$stmt = $conn->prepare("INSERT INTO comments (postID, userID, commentText, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $postID, $userID, $commentText);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comment added successfully']);
} else {
    echo json_encode(['error' => 'Failed to add comment']);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>