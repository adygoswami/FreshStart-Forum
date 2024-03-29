<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $title = $conn->real_escape_string(trim($_POST['title']));
    $content = $conn->real_escape_string(trim($_POST['content']));
    $topic = $conn->real_escape_string(trim($_POST['topic']));
    $userID = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Handle the image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        // Attempt to move the uploaded file to your target directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            echo "Failed to upload image.";
            exit;
        }
    } else {
        $imagePath = null; // No image provided or an error occurred
    }

    // Prepare an INSERT statement
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image, topic, userID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $content, $imagePath, $topic, $userID);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Post created successfully.";
        // Redirect to the main page or wherever you'd like the user to go next
        header("Location: mainpage.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>