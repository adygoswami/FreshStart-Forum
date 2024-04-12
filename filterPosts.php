<?php
session_start();

$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Determine whether to filter by topic or fetch popular posts
$topic = isset($_GET['topic']) ? $_GET['topic'] : '';
$popular = isset($_GET['popular']) && $_GET['popular'] == 'true';

$posts = [];
if ($popular) 
{
    $stmt = $conn->prepare("SELECT postID, title, content, image, likes, dislikes, created_at FROM posts WHERE likes > 3 ORDER BY likes DESC, created_at DESC");
} 
else if ($topic != '') 
{
    $stmt = $conn->prepare("SELECT postID, title, content, image, likes, dislikes, created_at FROM posts WHERE topic = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $topic);
}

if ($stmt) 
{
    $stmt->execute();
    $result = $stmt->get_result();

    while ($post = $result->fetch_assoc()) 
    {
        if (!is_null($post['image'])) 
        {
            $post['image'] = base64_encode($post['image']);
        }
        $post['comments'] = [];
        $posts[$post['postID']] = $post;
    }

    $commentResult = $conn->query("SELECT commentID, postID, userID, commentText, created_at FROM comments ORDER BY created_at ASC");
    while ($comment = $commentResult->fetch_assoc()) 
    {
        if (isset($posts[$comment['postID']])) 
        {
            $posts[$comment['postID']]['comments'][] = $comment;
        }
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode(array_values($posts));
?>