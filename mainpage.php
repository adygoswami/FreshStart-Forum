<?php

//php start

session_start();

$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

// Store posts in a variable
$posts = [];
if ($result && $result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$posts[] = $row;
}
}
$sql = "SELECT id, title, text, votes FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
// Output data of each row
while($row = $result->fetch_assoc()) {
// Each post will have its own unique ID and vote count
echo "<div class='post' id='post-" . $row['id'] . "'>";
echo "<div class='vote-system'>";
echo "<button class='vote-button upvote' onclick='vote(" . $row['id'] . ", \"up\")'>Like</button>";
echo "<div class='vote-count'>" . $row['votes'] . "</div>";
echo "<button class='vote-button downvote' onclick='vote(" . $row['id'] . ", \"down\")'>Dislike</button>";
echo "</div>";
echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
echo "<p>" . htmlspecialchars($row['text']) . "</p>";
echo "<div class='post-footer'>";
echo "<a href='#' class='comments-link'>Comments</a>";
echo "</div>";
echo "</div>";
}
} else {
echo "0 results";
}



// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshStart Forum for UBCO Students</title>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="site-name">FreshStart</div>
            <div class="search-container">
              <input type="search" id="site-search" placeholder="Search FreshStart">
              <button class="search-button">Search</button>
            </div>
        <!-- profile stuff -->
            <div class="user-menu">
    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="username">Profile
            <div class="login-dropdown">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    <?php else: ?>
        <div class="username" onclick="window.location.href='login.php';">Login / Register</div>
    <?php endif; ?>
</div>
        </div>
        <div>
            <a href="post-page.html" class="post-tab">Create Post</a>
        </div>
    </header>

    <main class="main-content">
    <div id="postContainer">
            <!-- live posts -->

    </div>

    <!-- dummy posts -->

<article class="reddit-post">
<div class="vote-system">
        <button class="vote-button upvote">Like</button>
        <span class="vote-count">15</span>
        <button class="vote-button downvote">Dislike</button>
    </div>

    <div class="post-content">
        <h2 class="post-title">How is everyone's midterms???</h2>
        <p class="post-text">I think I did really bad... help!!</p>
        <div class="post-footer">
            <a href="#" class="comments-link">Comments</a>
        </div>
    </div>
</article>

<article class="reddit-post">
    <div class="vote-system">
        <button class="vote-button upvote">Like</button>
        <span class="vote-count">-2</span>
        <button class="vote-button downvote">Dislike</button>
    </div>

    <div class="post-content">
        <h2 class="post-title">I hate coffee!!</h2>
        <p class="post-text">Is it just me or does anyone just can't stand coffee?</p>
        <div class="post-footer">
            <a href="#" class="comments-link">Comments</a>
        </div>
    </div>
</article>

<article class="reddit-post">
<div class="vote-system">
        <button class="vote-button upvote">Like</button>
        <span class="vote-count">4</span>
        <button class="vote-button downvote">Dislike</button>
    </div>

    <div class="post-content">
        <h2 class="post-title">Does anyone want to switch labs for Cosc111?</h2>
        <p class="post-text">Send me an email on johndoe@gmail.com pls if you want to swap labs to Monday 10am.</p>
        <div class="post-footer">
            <a href="#" class="comments-link">Comments</a>
        </div>
    </div>

    
</article>

<!-- testing vote count -->

<div class='post' id='post-1'>
<div class="vote-system">
    <button class="vote-button upvote" onclick="vote(1, 'up')">Like</button>
    <div class="vote-count">0</div>
    <button class="vote-button downvote" onclick="vote(1, 'down')">Dislike</button>
  </div>
  
  <h2>How is everyone's midterms???</h3>
  <p>I think I did really bad... help!!</p>
  <div class="post-footer">
            <a href="#" class="comments-link">Comments</a>
        </div>
  

</div>

<div id="postContainer">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <!--  buttons and other content  -->
            <div class="vote-system">
    <button class="vote-button upvote" onclick="vote(1, 'up')">Like</button>
    <div class="vote-count">0</div>
    <button class="vote-button downvote" onclick="vote(1, 'down')">Dislike</button>
  </div>
        </div>
    <?php endforeach; ?>
</div>

       
    </main>

    <script>
        document.querySelector('.username').addEventListener('mouseover', function() {
            document.querySelector('.login-dropdown').style.display = 'block';
        });

        document.querySelector('.username').addEventListener('mouseout', function() {
            document.querySelector('.login-dropdown').style.display = 'none';
        });

        //test code for vote

        function vote(postId, direction) {
  // Data to be sent to the server
  let data = {
    postId: postId,
    vote: direction
  };

  // Send the data using the Fetch API
  fetch('vote_handler.php', {
    method: 'POST', // or 'PUT'
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
    updateVoteCount(postId, data.newVoteCount);
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}

function updateVoteCount(postId, newVoteCount) {
  // Update the vote count in the DOM
  document.querySelector(`#post-${postId} .vote-count`).textContent = newVoteCount;
}

document.getElementById('postSubmit').addEventListener('click', function() {
    var form = document.getElementById('postForm');
    var formData = new FormData(form);

    fetch('submit-post.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Find the post container and append the new post
        var postContainer = document.getElementById('postContainer');
        postContainer.innerHTML += data; // Append the new post
    })
    .catch(error => console.error('Error:', error));
});

    </script>
</body>
</html>
