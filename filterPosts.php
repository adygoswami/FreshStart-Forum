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

// Retrieve the topic from the URL parameters
$topic = isset($_GET['topic']) ? $_GET['topic'] : '';

// Protect against SQL injection
$filteredTopic = $conn->real_escape_string($topic);

// Define the SQL query to fetch posts by topic
$query = "SELECT * FROM posts WHERE topic = '$filteredTopic' ORDER BY created_at DESC";

// Prepare the SQL statement
$stmt = $conn->prepare($query);

// Execute the prepared statement
$stmt->execute();

// Bind the result to variables
$stmt->bind_result($postID, $title, $content, $userID, $created_at, $topic);

// Initialize an array to hold the fetched posts
$posts = [];

// Fetch the results and add them to the $posts array
while ($stmt->fetch()) {
    $posts[] = [
        'postID' => $postID,
        'title' => $title,
        'content' => $content,
        'userID' => $userID,
        'created_at' => $created_at,
        'topic' => $topic
    ];
}

// Close the statement and the database connection
$stmt->close();
$conn->close();

// Set the content type of the response to application/json
header('Content-Type: application/json');

// Encode the $posts array to JSON and output it
echo json_encode($posts);