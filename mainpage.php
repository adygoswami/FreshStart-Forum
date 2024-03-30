<?php
session_start();

// Check if the user is logged in and if they are an admin
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Database connection details
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" href="css/mainpage.css">
    <script src="mainpage.js" defer></script>
</head>

<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>" data-is-admin="<?= $isAdmin ? 'true' : 'false' ?>">
    <header>
        <!-- Search Bar -->
        <a href="mainpage.php" id="fsheader">
            <h1>FreshStart</h1>
        </a>
        <input type="text" id="searchQuery" placeholder="Search posts..." class="searchQuery">
        <button onclick="searchPosts()">Search</button>

        <!-- User Profile or Login Link -->
        <?php if ($isLoggedIn): ?>
            <!-- <a href="userSettings.php">Profile</a> -->
            <a href="userSettings.php"><button class="searchQuery">Profile</button></a>
        <?php else: ?>
            <!-- <a href="login.php">Login</a> -->
            <a href="login.php"><button class="searchQuery">Login</button></a>
        <?php endif; ?>
    </header>
    <aside id="topics-list">
        <h1 id="disc-header">Discussion Topics</h1>
        <button onclick="filterByTopic('Job Search')">Job Search</button>
        <button onclick="filterByTopic('Lab Switches')">Lab Switches</button>
        <button onclick="filterByTopic('UBCO Activities')">UBCO Activities</button>
        <button onclick="filterByTopic('HELP')">HELP</button>
        <button onclick="filterByTopic('Marketplace')">Marketplace</button>
        <button onclick="filterByTopic('Campus Resources')">Campus Resources</button>
    </aside>
    <?php if ($isLoggedIn): ?>
        <!-- Form for Creating Posts (visible only to logged-in users) -->
        <div id="postCreationForm">
            <form id="createPostForm" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="content" placeholder="Content" required></textarea>
                <input type="file" name="image" accept="image/*">
                <select name="topic">
                    <option value="Job Search">Job Search</option>
                    <option value="Lab Switches">Lab Switches</option>
                    <option value="UBCO Activities">UBCO Activities</option>
                    <option value="HELP">HELP</option>
                    <option value="Marketplace">Marketplace</option>
                    <option value="Campus Resources">Campus Resources</option>
                </select>
                <button type="submit">Create Post</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Main Content for Posts -->
    <main id="post-container">
        <!-- Posts will be dynamically loaded here by mainpage.js -->
    </main>
</body>

</html>