<?php

session_start();

// Connect to the database
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // Retrieve form data
    $community = $_POST['community'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userId = $_SESSION['user_id']; 
   
    if (isset ($_FILES['image-upload']) && is_uploaded_file($_FILES['image-upload']['tmp_name'])) {
        $post_img = file_get_contents($_FILES['image-upload']['tmp_name']);
    }

    // SQL to insert post
    $null = null;
    $stmt = $conn->prepare("INSERT INTO posts (userID, community, title, content, post_img) VALUES (?, ?, ?, ? , ?)");
    $stmt->bind_param("isssb", $userId, $community, $title, $content, $null);
    $stmt->send_long_data(4,$post_img);

    // Execute and check if the query was successful
    if($stmt->execute()) {
        // Redirect to the main page
        header('Location: mainpage.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Not a POST request or user not logged in
    echo "No post data received or user not logged in";
}

?>
