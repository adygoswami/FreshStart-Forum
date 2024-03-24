<?php
/*need to add
$servername = "";
$username = "";
$password = "";
$dbname = "";

DO NOT USE. TESTING. use submit-post.php

*/

/*  connection to sql
$conn = new mysqli($servername, $username, $password, $dbname);
*/

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// for security
$community = mysqli_real_escape_string($conn, $_POST['community']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$text = mysqli_real_escape_string($conn, $_POST['text']);

// insert query execution
$sql = "INSERT INTO posts (community, title, text) VALUES ('$community', '$title', '$text')";
if(mysqli_query($conn, $sql)){
    echo "Post added successfully.";
    header("Location: main-page.html"); // redirect to main page after insert
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}


// close connection
mysqli_close($conn);
?>

