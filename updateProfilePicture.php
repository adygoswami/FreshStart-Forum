<?php
session_start();

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Checking for user Authentication, since the functionality is user specific!
if (!isset($_SESSION['user_id'])) 
{
    echo json_encode(['success' => false]);
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_FILES['profilePicture'])) 
{
    $profilePicture = file_get_contents($_FILES['profilePicture']['tmp_name']);
    $stmt = $conn->prepare("UPDATE user_details SET profile_picture = ? WHERE user_id = ?");
    $null = null;
    $stmt->bind_param("bi", $null, $userId);
    $stmt->send_long_data(0, $profilePicture);

    if ($stmt->execute()) 
    {
        echo json_encode(['success' => true, 'profilePicture' => 'data:image/jpeg;base64,' . base64_encode($profilePicture)]);
    } 
    else 
    {
        echo json_encode(['success' => false]);
    }
} 
else 
{
    echo json_encode(['success' => false]);
}