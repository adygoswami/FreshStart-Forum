<?php


// JSON payload from the JavaScript Fetch API
$data = json_decode(file_get_contents('php://input'), true);

// Get the post ID and vote direction from the JSON payload
$postId = $data['postId'];
$voteDirection = $data['vote']; // 'up' or 'down'

// Connect to database
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for a database connection error
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}



// Depending on the voting direction, update the database
if ($voteDirection === 'up') {
    $sql = "UPDATE posts SET votes = votes + 1 WHERE id = ?";
} elseif ($voteDirection === 'down') {
    $sql = "UPDATE posts SET votes = votes - 1 WHERE id = ?";
} else {
    // Handle error - incorrect vote direction
    $conn->close();
    echo json_encode(['error' => 'Invalid vote direction']);
    exit();
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);

if ($stmt->execute()) {
    // After updating the votes, fetch the new vote count to return it to the client
    $sql = "SELECT votes FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Return the new vote count as JSON
    echo json_encode(['newVoteCount' => $row['votes']]);
} else {
    // Handle error - perhaps the update failed
    echo json_encode(['error' => 'Failed to update votes']);
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
