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
$bio = $_POST['bio'] ?? '';

$stmt = $conn->prepare("UPDATE user_details SET bio = ? WHERE user_id = ?");
$stmt->bind_param("si", $bio, $userId);

if ($stmt->execute()) 
{
    echo json_encode(['message' => 'Bio updation was successful']);
} 
else 
{
    echo json_encode(['message' => 'Bio update did not go through']);
}