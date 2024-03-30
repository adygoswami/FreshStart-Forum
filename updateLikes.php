<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

//DB Connect
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
$stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE postID = ?");
$stmt->bind_param("i", $postID);

if ($stmt->execute()) 
{
    echo json_encode(['success' => true]);
} 
else 
{
    echo json_encode(['error' => 'Failed to update likes']);
}


$stmt->close();
$conn->close();
?>