<?php
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

// SQL connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form data is set
if(isset($_POST['community'], $_POST['title'], $_POST['text'])) {
    $community = $_POST['community'];
    $title = $_POST['title'];
    $text = $_POST['text'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO posts (community, title, text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $community, $title, $text);

    // Execute the statement
    if($stmt->execute()) {
        echo "New post created successfully";
        $stmt->close();
        // Redirect to the main page
        header("Location: mainpage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Form data is missing";
}

$conn->close();
?>
