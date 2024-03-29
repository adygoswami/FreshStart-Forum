<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the query to fetch posts
$query = "SELECT * FROM posts ORDER BY created_at DESC";

// Execute the query
$result = $conn->query($query);

// Initialize an array to hold the fetched posts
$posts = [];

// Iterate over the result set and add each post to the $posts array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Close the database connection
$conn->close();

// Set header to indicate the content type is JSON
header('Content-Type: application/json');

// Convert the $posts array into JSON and output it
echo json_encode($posts);