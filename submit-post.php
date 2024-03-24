<?php
$servername = "localhost"; // need change
$username = "root"; // change 'root' if need
$password = ""; // Default password is empty but need change if diff
$dbname = "freshstart_forum"; //  database name

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
