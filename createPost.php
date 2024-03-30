<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Checking for the submission of the form and error handeling likewise.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string(trim($_POST['title']));
    $content = $conn->real_escape_string(trim($_POST['content']));
    $topic = $conn->real_escape_string(trim($_POST['topic']));
    $userID = $_SESSION['user_id']; 

    if (isset ($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) 
    {
        $postImage = file_get_contents($_FILES['image']['tmp_name']);
    } 
    else 
    {
        $postImage = null; 
    }

    //Insert Statement
    $null = null;
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image, topic, userID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssbsi", $title, $content, $null, $topic, $userID);
    $stmt->send_long_data(2, $postImage);

    if ($stmt->execute()) 
    {
        echo "Post created successfully.";
        header("Location: mainpage.php");
    } 
    else 
    {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>