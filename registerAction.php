<?php
session_start();
///// SERVER STUFF /////
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // making a connection to the db
    $conn = new mysqli($servername, $username, $password, $dbname);

    // form data
    // with filter_var, I can sanitize the data and ensure that there is no harmful user input data
    $firstName = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];


    // check if the username already exists
    $userStmt = $conn->prepare('SELECT * FROM user_details WHERE username = ?');
    $userStmt->bind_param('s', $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    if ($userResult->num_rows > 0) {
        $_SESSION['error'] = 'Username already exists';

        header('Location: register.php');
        exit();
    }

    // to include a number inthe password || (/d) is an expression that checks for a digit
    if (!preg_match('/\d/', $password)) {
        die ('Password must contain at least one number');
    }

    // check length of the password
    if (strlen($password) < 8) {
        die ('Password must be at least 8 characters long');
    }

    // check if the password matches the confpassword
    if ($password !== $confirmPassword) {
        die ('Passwords do not match.');
    }

    // hashing the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // this is the default hashing algorithm

    // SQL statements to insert the data
    $stmt = $conn->prepare('INSERT INTO user_details(first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $firstName, $lastName, $email, $username, $hashedPassword);
    $stmt->execute();

    //redirect the user to the login page
    header('Location: login.php');
    exit();
}

?>