<?php
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 

// Establish a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data has been sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO posts (community, title, text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $community, $title, $text);

    // Set parameters and execute
    $community = htmlspecialchars($_POST['community']);
    $title = htmlspecialchars($_POST['title']);
    $text = htmlspecialchars($_POST['text']);
    
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        echo "New post created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No post data to process.";
}

// Redirect back to the main page
header("Location: mainpage.php");
exit();
?>
