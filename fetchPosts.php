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

// Initialize an array to hold the fetched posts
$posts = [];

// First, fetch all posts
$postQuery = "SELECT * FROM posts ORDER BY created_at DESC";
$postResult = $conn->query($postQuery);

if ($postResult->num_rows > 0) {
    while ($postRow = $postResult->fetch_assoc()) {
        // Initialize comments array for each post
        $postRow['comments'] = [];
        $posts[$postRow['postID']] = $postRow;
    }
}

// Next, fetch all comments for these posts
$commentQuery = "SELECT * FROM comments ORDER BY created_at ASC";
$commentResult = $conn->query($commentQuery);

if ($commentResult->num_rows > 0) {
    while ($commentRow = $commentResult->fetch_assoc()) {
        // Check if this comment's postID exists in the $posts array
        if (array_key_exists($commentRow['postID'], $posts)) {
            // Append this comment to the post's comments array
            $posts[$commentRow['postID']]['comments'][] = $commentRow;
        }
    }
}

// Close the database connection
$conn->close();

// Set header to indicate the content type is JSON
header('Content-Type: application/json');

// Convert the $posts array into JSON and output it
echo json_encode(array_values($posts)); // Use array_values to re-index the array numerically
?>