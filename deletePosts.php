<?php
session_start();
if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");
    exit;
}

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

$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

$postID = isset($_POST['postID']) ? $_POST['postID'] : 0;

$postID = $conn->real_escape_string($postID);

$authorizedToDelete = false;
if ($isAdmin) 
{

    $authorizedToDelete = true;
} 
else 
{
    $query = "SELECT userID FROM posts WHERE postID = '$postID'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        if ($row['userID'] == $_SESSION['user_id'])
        {
            $authorizedToDelete = true;
        }
    }
}

if ($authorizedToDelete) 
{
    $deleteQuery = "DELETE FROM posts WHERE postID = '$postID'";
    if ($conn->query($deleteQuery)) {
        echo "Deletion Successful";
    } else {
        echo "Error deleting post: " . $conn->error;
    }
} 
else 
{
    echo "This fuction is out of your authority";
}

$conn->close();
?>