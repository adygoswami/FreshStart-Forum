<?php
// Connection to the database
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "test"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get posts from the database
$sql = "SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts Page</title>
</head>
<body>
    <!--Page Title-->

    <h1>Posts</h1>
    
    <!--Posts-->

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="post">
                <h2><?= htmlspecialchars($row["title"]); ?></h2>
                <p><?= htmlspecialchars($row["content"]); ?></p>
                <small>Posted on: <?= $row["created_at"]; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <?php $conn->close(); // Close the database connection ?>

</body>
</html>
