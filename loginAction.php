<?php
session_start();
///// SERVER STUFF /////
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create a new DB connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a select statement
    $stmt = $conn->prepare("SELECT * FROM user_details WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // if the user exists
    if ($result->num_rows > 0) {
        // fetching the user data
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // start a session unique to the user and store their username

            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['user_id'];

            header('Location: mainpage.php');
            exit();

        } else {
            // storing an error message in the session and this can be displayed in the login HTML file

            $_SESSION['error'] = 'Incorrect password';

            header('Location: login.php');
            exit();
        }
    } else {

        $_SESSION['error'] = 'User does not exist';
        header('Location: login.php');
        exit();
    }
}
?>