<?php
// Start the session
session_start();

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

// Retrieve the search query from the URL parameters
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Protect against SQL injection
$searchTerm = str_replace(['%', '_'], ['\%', '\_'], $conn->real_escape_string($searchQuery));

// Define the SQL query to search posts by title or content
$query = "SELECT * FROM posts WHERE title LIKE '%$searchTerm%' OR content LIKE '%$searchTerm%' ORDER BY created_at DESC";

// Execute the query
$result = $conn->query($query);

// Initialize an array to hold the matching posts
$posts = [];

// Fetch the results and add them to the $posts array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Close the database connection
$conn->close();

// Set the content type of the response to application/json
header('Content-Type: application/json');

// Encode the $posts array to JSON and output it
echo json_encode($posts);