<?php
session_start();

//DB Connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Checking user status since task is user authority specific as mentioned in the MCF rubrics!!:)
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) 
{
    echo "You lack the authority to perform this action";
    exit;
}

if (isset($_POST['postId']) && !empty($_POST['postId'])) 
{
    $postId = $_POST['postId'];

    if (filter_var($postId, FILTER_VALIDATE_INT) === false) 
    {
        echo "Invalid Post ID.";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE postID = ?");
    $stmt->bind_param("i", $postId);

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