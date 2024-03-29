<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
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

// Check if the user is an admin
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Retrieve the post ID from the request
$postID = isset($_POST['postID']) ? $_POST['postID'] : 0;

// Protect against SQL injection
$postID = $conn->real_escape_string($postID);

// Initialize a variable to indicate if the deletion is authorized
$authorizedToDelete = false;

if ($isAdmin) {
    // Admins are authorized to delete any post
    $authorizedToDelete = true;
} else {
    // Regular users can only delete their own posts
    // Retrieve the userID of the post
    $query = "SELECT userID FROM posts WHERE postID = '$postID'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['userID'] == $_SESSION['user_id']) {
            // The user is the author of the post
            $authorizedToDelete = true;
        }
    }
}

if ($authorizedToDelete) {
    // Delete the post
    $deleteQuery = "DELETE FROM posts WHERE postID = '$postID'";
    if ($conn->query($deleteQuery)) {
        echo "Post deleted successfully.";
    } else {
        echo "Error deleting post: " . $conn->error;
    }
} else {
    // The user is not authorized to delete the post
    echo "You are not authorized to delete this post.";
}

// Close the connection
$conn->close();
?>