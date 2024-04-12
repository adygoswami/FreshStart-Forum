<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: login.php'); // Redirect to login if not authenticated as a valid user.
    exit();
}

//DB connect
$servername = "localhost";
$username = "47130992";
$password = "freshstart360";
$dbname = "db_47130992";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching the user details
$stmt = $conn->prepare("SELECT username, profile_picture, bio FROM user_details WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) 
{
    echo "User not found";
    exit();
}

$profilePicture = $user['profile_picture'] ? 'data:image/jpeg;base64,' . base64_encode($user['profile_picture']) : 'path/to/default/profile_picture.jpg';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="css/user_settings.css">
    <script>
        function updateProfilePicture() 
        {
            var formData = new FormData(document.getElementById('profilePictureForm'));
            fetch('updateProfilePicture.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) 
                    {
                        document.getElementById('profilePic').src = data.profilePicture;
                        alert('Profile picture updated successfully!');
                    } 
                    else 
                    {
                        alert('Failed to update profile picture.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updatePassword() 
        {
            var formData = new FormData(document.getElementById('passwordForm'));
            fetch('updatePassword.php', 
            {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateBio() 
        {
            var formData = new FormData(document.getElementById('bioForm'));
            fetch('updateBio.php', 
            {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => 
                {
                    alert(data.message);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateUsername() 
        {
            var formData = new FormData(document.getElementById('usernameForm'));
            fetch('updateUsername.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) 
                {
                    alert('Username updated successfully!');
                } 
                else 
                {
                    alert('Failed to update username.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>

<body>
    <header class="user-settings-header">
        <a href="mainpage.php" id="fsheader">
            <h1>FreshStart</h1>
        </a>
    </header>
    <div class="user-settings">
        
        <div class="profile-picture">
            <img id="profilePic" src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="100" height="100">
            <form id="profilePictureForm">
                <label for="profilePicture">Update Profile Picture:</label>
                <input type="file" name="profilePicture" accept="image/*">
                <input type="button" value="Update Picture" onclick="updateProfilePicture()">
            </form>
        </div>

        <div class="user-info">
        <form id="usernameForm">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <button type="button" onclick="updateUsername()">Change Username</button>
            </form>
            <form id="passwordForm">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <button type="button" onclick="updatePassword()">Change Password</button>
            </form>
            <form id="bioForm">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                <button type="button" onclick="updateBio()">Update Bio</button>
            </form>
        </div>

        <div class="logout">
            <form action="logout.php" method="POST">
                <button type="submit">Logout</button>
            </form>
        </div>

    </div>
</body>

</html>