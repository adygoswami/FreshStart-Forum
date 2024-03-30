<?php
session_start();

//DB connect
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$posts = [];
//FetchingPosts
$postQuery = "SELECT postID, title, content, image, likes, dislikes, created_at FROM posts ORDER BY created_at DESC";
$postResult = $conn->query($postQuery);

if ($postResult->num_rows > 0) 
{
    while ($postRow = $postResult->fetch_assoc()) 
    {
        if (!is_null($postRow['image'])) 
        {
            $postRow['image'] = base64_encode($postRow['image']);
        }
        $postRow['comments'] = [];
        $posts[$postRow['postID']] = $postRow;
    }
}

//Fetching Comments
$commentQuery = "SELECT commentID, postID, userID, commentText, created_at FROM comments ORDER BY created_at ASC";
$commentResult = $conn->query($commentQuery);

if ($commentResult->num_rows > 0) 
{
    while ($commentRow = $commentResult->fetch_assoc()) 
    {
        if (array_key_exists($commentRow['postID'], $posts)) 
        {
            $posts[$commentRow['postID']]['comments'][] = [
                'commentID' => $commentRow['commentID'],
                'userID' => $commentRow['userID'],
                'commentText' => $commentRow['commentText'],
                'created_at' => $commentRow['created_at']
            ];
        }
    }
}
$conn->close();
header('Content-Type: application/json');
echo json_encode(array_values($posts));
?>