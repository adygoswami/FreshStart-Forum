<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshStart Forum for UBCO Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <aside class="sidebar">

        <nav class="navbar">
            <div class="search-container">
                <input type="search" id="site-search" name="q"
                       aria-label="Search through site content">
                <button>Search</button>
            </div>
            <ul>
                <li><a href="#job-search" class="active">Job Search</a></li>
                <li><a href="#lab-switches">Lab Switches</a></li>
                <li><a href="#ubco-activities">UBCO Activities</a></li>
                <li><a href="#marketplace">Marketplace</a></li>
                <li><a href="#help">Help</a></li>
                <li><a href="#campus-resources">Campus Resources</a></li>
                <li><a href="post-page.html" class="post-tab">Post</a></li> 

            </ul>
        </nav>

    </aside>

        
    </header>

    <main class="main-content">     
        
    <?php
        /* database connection
        $servername = "localhost";
        $username = "your_username";
        $password = "your_password";
        $dbname = "your_dbname"; */

        // database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Checking connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // fetch and display posts for a given category
        function displayPosts($conn, $category) {
            $sql = "SELECT * FROM posts WHERE community = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<article class='post'>";
                    echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                    echo "<p>" . htmlspecialchars($row["text"]) . "</p>";
                    echo "</article>";
                }
            } else {
                echo "<p>No posts found in " . htmlspecialchars($category) . ".</p>";
            }
        }

        // posts for "Job Search"
        echo '<section id="job-search">';
        echo '<h2>Job Search</h2>';
        echo '<div class="posts-container">';
        displayPosts($conn, 'job-search');
        echo '</div>';
        echo '</section>';

         // posts for "Lab Switch"
         echo '<section id="lab-switches">';
         echo '<h2>Lab Switches</h2>';
         echo '<div class="posts-container">';
         displayPosts($conn, 'lab-switches');
         echo '</div>';
         echo '</section>';
 
        // posts for "UBCO Activities"
        echo '<section id="ubco-activities">';
        echo '<h2>UBCO Activities</h2>';
        echo '<div class="posts-container">';
        displayPosts($conn, 'ubco-activities');
        echo '</div>';
        echo '</section>';

        // posts for "Market Place"
        echo '<section id="marketplace">';
        echo '<h2>Market Place</h2>';
        echo '<div class="posts-container">';
        displayPosts($conn, 'marketplace');
        echo '</div>';
        echo '</section>';

      

    
        
        $conn->close();
        ?>
    </main>
</body>
</html>
