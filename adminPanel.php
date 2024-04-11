<!-- admin_username = admin
     admin_password = admin@Freshstart360 -->


<?php
session_start();

// Access control: Only allow admins and if you try to jump to the admin page through URL it throws you back to login!!!
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) 
{
    header('Location: login.php'); // Redirect non-admins to the login page
    exit();
}

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);

//Checking Connection 
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}
// Fetch weekly login data
$loginDataQuery = "SELECT week, SUM(login_count) AS total_logins FROM weekly_logins GROUP BY week ORDER BY week ASC";
$loginDataResult = $conn->query($loginDataQuery);
$loginData = $loginDataResult->fetch_all(MYSQLI_ASSOC);

// Fetch weekly interactions data
$interactionDataQuery = "SELECT week, SUM(post_count) AS total_posts, SUM(comment_count) AS total_comments FROM weekly_interactions GROUP BY week ORDER BY week ASC";
$interactionDataResult = $conn->query($interactionDataQuery);
$interactionData = $interactionDataResult->fetch_all(MYSQLI_ASSOC);

// Admin's ability to search for specific Users and their Posts
$searchResults = [];
if (isset($_POST['search']) && !empty($_POST['searchQuery'])) 
{
    $searchQuery = "%" . $conn->real_escape_string($_POST['searchQuery']) . "%";

    // Searching in both user_details and posts tables from the database....

    $stmt = $conn->prepare("SELECT 'User' as Type, username as Name, email as Email FROM user_details WHERE username LIKE ? OR email LIKE ? UNION SELECT 'Post' as Type, title as Name, '' as Email FROM posts WHERE title LIKE ?");
    $stmt->bind_param("sss", $searchQuery, $searchQuery, $searchQuery);
    $stmt->execute();
    $searchResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetching all users for Enabling and Disabling the user (BAN/UNBAN)!
$users = $conn->query("SELECT user_id, username, is_enabled FROM user_details")->fetch_all(MYSQLI_ASSOC);

// Fetching all posts for editing/removing inappropriate posts in admin's POV
$posts = $conn->query("SELECT postID, title, userID, title, content FROM posts")->fetch_all(MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/adminstyle.css"> 

    <script>  //incorporating AJAX functionality so that reloading the page is not required after any chnages are made by the admin!
    function toggleUserStatus(userId, isEnabled) 
    {
        var formData = new FormData();
        formData.append('userId', userId);
        formData.append('isEnabled', isEnabled);   //NEED TO VERIFY THE COLUMN NAME, CAN't CHECK SINCE THE SERVER IS DOWN. // server came back up everything working

        fetch('toggleUserStatus.php', 
        {
            method: 'POST',
            body: formData
        })

        .then(response => response.text())
        .then(data => 
        {
            console.log(data); 
            location.reload(); 
        })
        .catch(error => console.error('Error:', error));
    }

    function removePost(postId) 
    {
        var formData = new FormData();
        formData.append('postId', postId);

        fetch('removePost.php', 
        {
            method: 'POST',
            body: formData
        })

        .then(response => response.text())
        .then(data => 
        {
            console.log(data); 
            location.reload(); 
        })
        .catch(error => console.error('Error:', error));
    }
    // var loginData = <?php echo json_encode($loginData); ?>;
    // var interactionData = <?php echo json_encode($interactionData); ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare data for Login Chart
        var loginWeeks = loginData.map(data => "Week " + data.week);
         var loginCounts = loginData.map(data => data.total_logins);
        document.addEventListener('DOMContentLoaded', function() {
            

            // Prepare data for Interaction Chart
            var interactionWeeks = interactionData.map(data => "Week " + data.week);
            var postCounts = interactionData.map(data => data.total_posts);
            var commentCounts = interactionData.map(data => data.total_comments);

            // Login Chart
            var ctxLogin = document.getElementById('loginChart').getContext('2d');
            var loginChart = new Chart(ctxLogin, {
                type: 'line',
                data: {
                    labels: loginWeeks,
                    datasets: [{
                        label: 'Total Logins',
                        data: loginCounts,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });

            // Interaction Chart
            var ctxInteraction = document.getElementById('interactionChart').getContext('2d');
            var interactionChart = new Chart(ctxInteraction, {
                type: 'bar',
                data: {
                    labels: interactionWeeks,
                    datasets: [{
                        label: 'Total Posts',
                        data: postCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Comments',
                        data: commentCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>


</head>
<body>
    <div class="admin-container">
        <div class="chart-container">
        <h2>Weekly Logins</h2>
        <canvas id="loginChart"></canvas>
        <h2>Weekly Interactions</h2>
        <canvas id="interactionChart"></canvas>
    </div>
        <div class="search-section">
            <div class="chart-container">
            <h2>Weekly Logins</h2>
            <canvas id="loginChart"></canvas>
            <h2>Weekly Interactions</h2>
            <canvas id="interactionChart"></canvas>
        </div>

            <div class="search-results">
                <?php if (!empty($searchResults)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Name/Title</th>
                                <th>Email (Users only)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchResults as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['Type']); ?></td>
                                    <td><?php echo htmlspecialchars($item['Name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['Email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="users-section">
            <h2>Toggle User Status</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo $user['is_enabled'] ? 'Enabled' : 'Disabled'; ?></td>
                        <td>
                            <button type="button" onclick="toggleUserStatus(<?php echo $user['user_id']; ?>, <?php echo $user['is_enabled'] ? 0 : 1; ?>)">
                                <?php echo $user['is_enabled'] ? 'Disable' : 'Enable'; ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="posts-section">
            <h2>Make Changes to Posts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>User ID</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo $post['userID']; ?></td>
                        <td>
                            <button type="button" onclick="removePost(<?php echo $post['postID']; ?>)">Remove</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>