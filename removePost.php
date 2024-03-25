<?php
session_start();

//DB Connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);

//Checking Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Securely checking if the user is an admin or not.
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo "You do not have permission to perform this action.";
    exit;
}

// Check if postId is set and not empty jsut to be sure while deleting a post.
if (isset($_POST['postId']) && !empty($_POST['postId'])) {
    $postId = $_POST['postId'];

    // Several steps to check for the deletion of correct post
    if (filter_var($postId, FILTER_VALIDATE_INT) === false) 
    {
        echo "Invalid Post ID.";
        exit;
    }

    // DELETE statement
    $stmt = $conn->prepare("DELETE FROM posts WHERE postID = ?");
    $stmt->bind_param("i", $postId);

    // Checking for success!
    if ($stmt->execute()) 
    {
        echo "Post removed successfully.";
    } else {
        echo "An error occurred. Please try again.";
    }
} else {
    echo "No Post ID provided.";
}

$conn->close();
?>