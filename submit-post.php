<?php
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

// sql connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO posts (community, title, text) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $community, $title, $text);

// Set parameters from the form and execute
$stmt = $conn->prepare("INSERT INTO posts (community, title, text) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $community, $title, $text);
$community = $_POST['community'];
$title = $_POST['title'];
$text = $_POST['text'];
$stmt->execute();


echo "New post created successfully";

$stmt->close();
$conn->close();

// redirect to the main page
//header("Location: mainpage.php"); // path to the main page
header("mainpage.php"); // path to the main page
exit();
?>
