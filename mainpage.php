<?php
session_start();

// Check if the user is logged in and if they are an admin
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
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
        <input type="text" id="searchQuery" placeholder="Search posts...">
        <button onclick="searchPosts()">Search</button>

        <!-- User Profile or Login Link -->
        <?php if ($isLoggedIn): ?>
            <a href="userSettings.php">Profile</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </header>

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

    <!-- Topics List -->
    <aside id="topics-list">
        <button onclick="filterByTopic('Job Search')">Job Search</button>
        <button onclick="filterByTopic('Lab Switches')">Lab Switches</button>
        <button onclick="filterByTopic('UBCO Activities')">UBCO Activities</button>
        <button onclick="filterByTopic('HELP')">HELP</button>
        <button onclick="filterByTopic('Marketplace')">Marketplace</button>
        <button onclick="filterByTopic('Campus Resources')">Campus Resources</button>
    </aside>

    <!-- Main Content for Posts -->
    <main id="post-container">
        <!-- Posts will be dynamically loaded here by mainpage.js -->
    </main>
</body>
</html>