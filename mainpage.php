<?php

// Start the session and establish a database connection
session_start();
$conn = new mysqli('localhost', '47130992', 'freshstart360', 'db_47130992');

// Check the connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch posts from the database
$sql = 'SELECT id, title, content, votes FROM posts ORDER BY created_at DESC';
$result = $conn->query($sql);

// Initialize an array to store the posts
$posts = [];

// Check if there are any posts and store them in the $posts array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
} else {
    echo '0 results';
}

// Close the database connection
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
            <a href="post-page.php" class="post-tab">Create Post</a>
        </div>
    </header>

    <main class="main-content">

        <!-- live posts -->

    <div id="postContainer">

    <?php foreach ($posts as $post): ?>
    <div class="post" id="post-<?php echo $post['id']; ?>">
        <div class="vote-system">
            <button class="vote-button upvote">Like</button>
            <div class="vote-count"><?php echo $post['votes']; ?></div>
            <button class="vote-button downvote">Dislike</button>
        </div>
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <p><?php echo htmlspecialchars($post['content']); ?></p>
        <div class="post-footer">
            <a href="#" class="comments-link">Comments</a>
        </div>
    </div>
    <?php endforeach; ?>

</div>

    <!-- dummy posts -->

    
<!-- post 1 -->
<div class='post' id='post-1'>
  <div class="vote-system">
    <button class="vote-button upvote" onclick="increaseLikeCount(1)">Like</button>
    <div class="vote-count">0</div>
    <button class="vote-button downvote" onclick="decreaseLikeCount(1)">Dislike</button>
  </div>
  <h2>How is everyone's midterms???</h2>
  <p>I think I did really bad... help!!</p>
  <div class="post-footer">
    <a href="#" class="comments-link">Comments</a>
  </div>
</div>


<!-- post 2 -->
<div class='post' id='post-2'>
  <div class="vote-system">
    <button class="vote-button upvote" onclick="increaseLikeCount(2)">Like</button>
    <div class="vote-count">0</div>
    <button class="vote-button downvote" onclick="decreaseLikeCount(2)">Dislike</button>
  </div>
  <h2>Does anyone want to switch labs for Cosc 360?</h2>
  <p>I really need to switch to any other lab. Mine is at 11am Monday. Send me an email at johndoe@gmail.com if u want to switch pls.</p>
  <div class="post-footer">
    <a href="#" class="comments-link">Comments</a>
  </div>
</div>



       
    </main>

    <script>
        //like function

let likedPosts = {};
let dislikedPosts = {};

function increaseLikeCount(postId) {
  if (likedPosts[postId]) {
    console.log('You have already liked this post.');
    return;
  }

  var voteCountElement = document.querySelector(`#post-${postId} .vote-count`);
  var currentCount = parseInt(voteCountElement.textContent, 10);
  voteCountElement.textContent = currentCount + 1;
  likedPosts[postId] = true;
}

function decreaseLikeCount(postId) {
  if (dislikedPosts[postId]) {
    console.log('You have already disliked this post.');
    return;
  }

  var voteCountElement = document.querySelector(`#post-${postId} .vote-count`);
  var currentCount = parseInt(voteCountElement.textContent, 10);
  voteCountElement.textContent = currentCount - 1;
  dislikedPosts[postId] = true;
}


//like dislike for dummy post end

        document.querySelector('.username').addEventListener('mouseover', function() {
            document.querySelector('.login-dropdown').style.display = 'block';
        });

        document.querySelector('.username').addEventListener('mouseout', function() {
            document.querySelector('.login-dropdown').style.display = 'none';
        });

//vote function

document.addEventListener('DOMContentLoaded', function()) {
        var upvoteButtons = document.querySelectorAll('.vote-button.upvote');
        var downvoteButtons = document.querySelectorAll('.vote-button.downvote');

        upvoteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var postId = this.parentNode.parentNode.id.split('-')[1];
                vote(postId, 'up');
            });
        });

        downvoteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var postId = this.parentNode.parentNode.id.split('-')[1];
                vote(postId, 'down');
            });
        });
    }
function vote(postId, direction) {
  let data = { postId, vote: direction };
  fetch('vote_handler.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  })
  .then(response => response.json())
  .then(data => {
    if (data.newVoteCount !== undefined) {
      document.querySelector(`#post-${postId} .vote-count`).textContent = data.newVoteCount;
    } else if (data.error) {
      console.error('Voting error:', data.error);
    }
  })
  .catch((error) => {
    console.error('Fetch error:', error);
  });
}


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


function updateVoteCount(postId, newVoteCount) {
  // Update the vote count in the DOM
  document.querySelector(`#post-${postId} .vote-count`).textContent = newVoteCount;
}

document.getElementById('postForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    var form = this;
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
        form.reset(); // Reset the form after successful submission
    })
    .catch(error => console.error('Error:', error));
});

    </script>
</body>
</html>
