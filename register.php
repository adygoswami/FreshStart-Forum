<?php

$host = 'localhost';
$dbname = 'db_47130992';
$dbuser = '47130992';
$dbpass = '47130992';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // making a connection to the db
    $con = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);

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
    $stmt = $con->prepare('INSERT INTO user_details(first_name, last_name, email, username, password) VALUES (:firstname, :lastname, :email, :username, :password)');

    $stmt->execute([
        ':firstname' => $firstName,
        ':lastname' => $lastName,
        ':email' => $email,
        ':username' => $username,
        ':password' => $hashedPassword
    ]);

    //redirect the user to the login page
    header('Location: login.html');
    exit();
}

?>