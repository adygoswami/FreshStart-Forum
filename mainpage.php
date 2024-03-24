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
            <div class="user-menu">
              <div class="username">Username
                <div class="login-dropdown">
                  <a href="#login">Profile</a>
                </div>
              </div>
            </div>
        </div>
        <div>
            <a href="post-page.html" class="post-tab">Create Post</a>
        </div>
    </header>

    <main class="main-content">
           
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

        <?php
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

        // SQL query to fetch all posts
        $sql = "SELECT title, text FROM posts ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['text']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }
        
        // Close the connection
        $conn->close();
        ?>
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

    </script>
</body>
</html>
