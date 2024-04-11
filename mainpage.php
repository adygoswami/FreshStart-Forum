<!-- WE had to create the Mainpage and the functionality connected to it in one and a half day after our team member couldn't upload his progress due to some personal issues that the professor has been updated about.
     our team mate had a chat with the professor where she gave us an extention till Friday on Compassionate grounds!-->


<?php
session_start();

// Checking if the user is logged in or not to determine the functionality that is just for the logged in user!
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

//DB Connect
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";
$conn = new mysqli($servername, $username, $password, $dbname);
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
    <script src="javascript/mainpage.js" defer></script>
</head>

<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>" data-is-admin="<?= $isAdmin ? 'true' : 'false' ?>">
    <header>

        <!-- Search Bar -->
        <a href="mainpage.php" id="fsheader">
            <h1>FreshStart</h1>
        </a>
        <input type="text" id="searchQuery" placeholder="Search posts..." class="searchQuery">
        <button onclick="searchPosts()">Search</button>

        <!--Giving links to userProfile and Login feature each defined differently since we have different functionality for users who are logged in and users who are not-->
        <?php if ($isLoggedIn): ?>
            <a href="userSettings.php"><button class="searchQuery">Profile</button></a>
        <?php else: ?>
            <a href="login.php"><button class="searchQuery">Login</button></a>
        <?php endif; ?>

    </header>
    <div id="container">
        <div id="main-content">
            <div id="topics-list">
                <h1 id="disc-header">Discussion Topics</h1>
                <button onclick="filterByTopic('Job Search')">Job Search</button>
                <button onclick="filterByTopic('Lab Switches')">Lab Switches</button>
                <button onclick="filterByTopic('UBCO Activities')">UBCO Activities</button>
                <button onclick="filterByTopic('HELP')">HELP</button>
                <button onclick="filterByTopic('Marketplace')">Marketplace</button>
                <button onclick="filterByTopic('Campus Resources')">Campus Resources</button>
            </div>
            <?php if ($isLoggedIn): ?>
            <div id="postCreationForm">
                <form action="createPost.php" method="post" id="createPostForm" enctype="multipart/form-data">
                    <h1>Create a Post</h1>
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
        </div>
        <main id="post-container">
            <!-- We did Dynamic placement of posts here using the mainpage.js file -->
        </main>
    </div>

</body>

</html>