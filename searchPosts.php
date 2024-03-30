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

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$searchTerm = str_replace(['%', '_'], ['\%', '\_'], $conn->real_escape_string($searchQuery));
$query = "SELECT * FROM posts WHERE title LIKE '%$searchTerm%' OR content LIKE '%$searchTerm%' ORDER BY created_at DESC";
$result = $conn->query($query);


$posts = [];
if ($result->num_rows > 0) 
{
    while ($row = $result->fetch_assoc()) 
    {
        $posts[] = $row;
    }
}


$conn->close();
header('Content-Type: application/json');
echo json_encode($posts);