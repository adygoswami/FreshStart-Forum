<?php
session_start();
// The user ID is stored in session when they log in
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) 
{
    header('Location: login.php'); // Redirect to login if not authenticated as a valid user.
    exit();
}

//DB connect
$servername = "localhost";
$username = "47130992"; 
$password = "freshstart360"; 
$dbname = "db_47130992"; 
$conn = new mysqli($servername, $username, $password, $dbname);

//Checking Connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$stmt = $conn->prepare("SELECT username, profile_picture, bio FROM user_details WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found"; 
    exit();
}

$profilePicture = $user['profile_picture'] ? 'data:image/jpeg;base64,'.base64_encode($user['profile_picture']) : 'path/to/default/profile_picture.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="css/style.css"> 
    <script>
    function updateProfilePicture() {
        var formData = new FormData(document.getElementById('profilePictureForm'));
        fetch('updateProfilePicture.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profilePic').src = data.profilePicture;
                alert('Profile picture updated successfully!');
            } else {
                alert('Failed to update profile picture.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updatePassword() {
        var formData = new FormData(document.getElementById('passwordForm'));
        fetch('updatePassword.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => console.error('Error:', error));
    }

    function updateBio() {
        var formData = new FormData(document.getElementById('bioForm'));
        fetch('updateBio.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</head>
<body>
    <div class="user-settings">
        <div class="profile-picture">
            <img id="profilePic" src="<?php echo $profilePicture; ?>" alt="Profile Picture" width="100" height="100">
            <form id="profilePictureForm">
                <input type="file" name="profilePicture" accept="image/*">
                <input type="button" value="Update Picture" onclick="updateProfilePicture()">
            </form>
        </div>
        <div class="user-info">
            <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
            <form id="passwordForm">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <input type="button" value="Change Password" onclick="updatePassword()">
            </form>
            <form id="bioForm">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                <input type="button" value="Update Bio" onclick="updateBio()">
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