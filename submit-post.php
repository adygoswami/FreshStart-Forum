<?php
session_start();
// Connect to the database
$servername = "localhost";
$username = "47130992"; // Replace with your username
$password = "freshstart360"; // Replace with your password
$dbname = "db_47130992"; // Replace with your dbname

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $community = $_POST['community'];
    $title = $_POST['title'];
    $text = $_POST['text'];
    // $image = $_FILES['image-upload']; // If you're handling file uploads

    // SQL to insert post
    $stmt = $conn->prepare("INSERT INTO posts (community, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $community, $title, $text);

    // Execute and check if the query was successful
    if($stmt->execute()) {
        // Output the new post's HTML
        echo "<div class='post'>";
        echo "<h3>" . htmlspecialchars($title) . "</h3>";
        echo "<p>" . htmlspecialchars($text) . "</p>";
        // Include image or video if uploaded
        echo "</div>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Not a POST request
    echo "No post data received";
}
?>
