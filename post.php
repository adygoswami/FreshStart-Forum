<?php
/*need to add
$servername = "";
$username = "";
$password = "";
$dbname = "";
*/

/*  connection to sql
$conn = new mysqli($servername, $username, $password, $dbname);
*/

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Escape user inputs for security
$community = mysqli_real_escape_string($conn, $_POST['community']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$text = mysqli_real_escape_string($conn, $_POST['text']);

// Attempt insert query execution
$sql = "INSERT INTO posts (community, title, text) VALUES ('$community', '$title', '$text')";
if(mysqli_query($conn, $sql)){
    echo "Post added successfully.";
    header("Location: main-page.html"); // Redirect to main page after insert
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}


// Close connection
mysqli_close($conn);
?>
