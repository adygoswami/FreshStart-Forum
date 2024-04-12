<?php
session_start();

if (!isset($_SESSION['user_id'])) 
{
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$postID = isset($_POST['postID']) ? intval($_POST['postID']) : 0;
$userID = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT interaction_id, interaction_type FROM post_interactions WHERE post_id = ? AND user_id = ?");
$stmt->bind_param("ii", $postID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) 
{
    $interaction = $result->fetch_assoc();
    if ($interaction['interaction_type'] == 'dislike') 
    {
        $deleteStmt = $conn->prepare("DELETE FROM post_interactions WHERE interaction_id = ?");
        $deleteStmt->bind_param("i", $interaction['interaction_id']);
        $deleteStmt->execute();
        $conn->query("UPDATE posts SET dislikes = dislikes - 1 WHERE postID = $postID");
    } 
    else 
    {
        $updateStmt = $conn->prepare("UPDATE post_interactions SET interaction_type = 'dislike' WHERE interaction_id = ?");
        $updateStmt->bind_param("i", $interaction['interaction_id']);
        $updateStmt->execute();
        $conn->query("UPDATE posts SET dislikes = dislikes + 1, likes = likes - 1 WHERE postID = $postID");
    }
} 
else 
{
    $insertStmt = $conn->prepare("INSERT INTO post_interactions (user_id, post_id, interaction_type) VALUES (?, ?, 'dislike')");
    $insertStmt->bind_param("ii", $userID, $postID);
    $insertStmt->execute();
    $conn->query("UPDATE posts SET dislikes = dislikes + 1 WHERE postID = $postID");
}

echo json_encode(['success' => true]);

$conn->close();

?>