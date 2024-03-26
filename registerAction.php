<?php
session_start();
///// XAMPP ADMIN /////
/*
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_47130992";
//*/


///// SERVER /////
///* 
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";
//*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // making a connection to the db
    $conn = new mysqli($servername, $username, $password, $dbname);

    // in case to head back to the previous page (from lab 9) 
    if (isset ($_SERVER['HTTP_REFERER'])) {
        $prev_page = $_SERVER['HTTP_REFERER'];
    }

    // form data
    // with filter_var, I can sanitize the data and ensure that there is no harmful user input data
    $firstName = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];
    // USER PROFILE PICTURE
    $default_pfp_path = 'img/default_pfp.jpg';
    if (isset ($_FILES['profilePicture']) && is_uploaded_file($_FILES['profilePicture']['tmp_name'])) {
        $profile_picture = file_get_contents($_FILES['profilePicture']['tmp_name']);
    } elseif (file_exists($default_pfp_path)) {
        $profile_picture = file_get_contents($default_pfp_path);
    } elseif (!file_exists($default_pfp_path)) {
        echo "The default image does not exist";
        echo '<br><a href=' . $prev_page . '>Return to the Registration page</a>';
        header('Location: register.php');
    }


    // check if the username already exists
    $userStmt = $conn->prepare('SELECT * FROM user_details WHERE username = ?');
    $userStmt->bind_param('s', $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    if ($userResult->num_rows > 0) {
        // i am doing this because this cannot be checked in the form validation i have using js
        // there is a div in the registration form that will display this error message
        $_SESSION['error'] = 'Username already exists';
        header('Location: register.php');
        exit();
    }

    if (!preg_match('/\d/', $password)) {
        // i am doing this because this is already checked in the form validation using js, but just in case
        echo "Password must contain at least one number";
        echo '<br><a href=' . $prev_page . '>Return to the Registration page</a>';
    }

    // check length of the password
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long";
        echo '<br><a href=' . $prev_page . '>Return to the Registration page</a>';
    }

    // check if the password matches the confpassword
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        echo '<br><a href=' . $prev_page . '>Return to the Registration page</a>';
    }

    // hashing the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // this is the default hashing algorithm

    // // INSERT WITHOUT THE PROFILE PICTURE:
    // $stmt = $conn->prepare('INSERT INTO user_details(first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)');
    // $stmt->bind_param('sssss', $firstName, $lastName, $email, $username, $hashedPassword);
    // $stmt->execute();

    // INSERT WITH THE PROFILE PICTURE:
    $null = null; // Define null for the blob placeholder
    $stmt = $conn->prepare('INSERT INTO user_details (first_name, last_name, email, username, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssb', $firstName, $lastName, $email, $username, $hashedPassword, $null);
    $stmt->send_long_data(5, $profile_picture);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Registration successful';
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['error'] = 'Registration failed';
        header('Location: register.php');
        exit();
    }
}
//john1234
//John1234$

?>