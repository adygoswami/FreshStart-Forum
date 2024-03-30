<?php
session_start();

// Checking if the user is logged in or not to determine the functionality that is just for the logged in user!

if (!isset($_SESSION['user_id']))
{
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}


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
$postID = isset($_POST['postID']) ? intval($_POST['postID']) : 0;
$commentText = isset($_POST['commentText']) ? trim($_POST['commentText']) : '';
$commentText = $conn->real_escape_string($commentText);

//Using session to get the user id!
$userID = $_SESSION['user_id'];

//Insert Statement to send data to the Database
$stmt = $conn->prepare("INSERT INTO comments (postID, userID, commentText, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $postID, $userID, $commentText);

if ($stmt->execute()) 
{
    echo json_encode(['success' => true, 'message' => 'Comment added successfully']);
} 
else 
{
    echo json_encode(['error' => 'Failed to add comment']);
}

$stmt->close();
$conn->close();
?>