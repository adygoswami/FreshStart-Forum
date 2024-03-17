<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_47130992";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // making a connection to the db
    $conn = new mysqli($servername, $username, $password, $dbname);

    // form data
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // check if the password matches the confpassword
    if ($password !== $confirmPassword) {
        die ('Passwords do not match.');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // this is the default hashing algorithm

    // SQL statements to insert the data
    $stmt = $conn->prepare('INSERT INTO user_details(first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)');

    $stmt->bind_param('sssss', $firstName, $lastName, $email, $username, $hashedPassword);

    $stmt->execute();

    //redirect the user to the login page
    header('Location: main_page.html');
    exit();
}

?>