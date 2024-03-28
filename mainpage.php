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

            
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="vote-system">
                        <button class="vote-button upvote" onclick="vote(<?= $post['id']; ?>, 'up')">Like</button>
                        <span class="vote-count"><?= $post['votes']; ?></span>
                        <button class="vote-button downvote" onclick="vote(<?= $post['id']; ?>, 'down')">Dislike</button>
                    </div>
                    <div class="post-content">
                        <h2 class="post-title"><?= htmlspecialchars($post['title']); ?></h2>
                        <p class="post-text"><?= htmlspecialchars($post['text']); ?></p>
                        <!-- a comments section can be linked if u want-->
                        <div class="post-footer">
                            <a href="comments.php?post_id=<?= $post['id']; ?>" class="comments-link">Comments</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        

    </div>

    <!-- dummy posts -->





    
</article>

<!-- post 1 -->

<div class='post' id='post-1'>
<div class="vote-system">
<button class="vote-button upvote" onclick="increaseLikeCount(1)">Like</button>
    <div class="vote-count">0</div>
    <button class="vote-button downvote" onclick="vote(1, 'down')">Dislike</button>
  </div>
  
  <h2>How is everyone's midterms???</h3>
  <p>I think I did really bad... help!!</p>
  <div class="post-footer">
            <a href="#" class="comments-link">Comments</a>
        </div>
  

</div>

</article>

<!-- post 2 -->

<div class='post' id='post-2'>
<div class="vote-system">
<button class="vote-button upvote" onclick="increaseLikeCount(1)">Like</button>
    <div class="vote-count">0</div>
    <button class="vote-button downvote" onclick="vote(1, 'down')">Dislike</button>
  </div>
  
  <h2>Does anyone want to switch labs for Cosc 360?</h3>
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
    function vote(postId, direction) {
  let data = { postId, vote: direction };
  fetch('vote_handler.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data),
  })
  .then(response => response.json())
  .then(data => {
    if(data.newVoteCount !== undefined) {
      document.querySelector(`#post-${postId} .vote-count`).textContent = data.newVoteCount;
    } else if(data.error) {
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
