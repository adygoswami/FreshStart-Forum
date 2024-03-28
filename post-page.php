<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Post</title>
    <link rel="stylesheet" href="css/stylepost.css">
</head>
<body>
    <body>

        <a href="mainpage.php" class="home-button">Home</a> <!-- Go Home button -->
    
        <div class="post-container">
            <h1>Create a post</h1>
            <div class="post-types">
                <button type="button" class="post-type active">Post</button>
                <button type="button" class="post-type" id="image-video-btn">Image & Video</button>
            </div>
    
            <form id="postForm" action="submit-post.php" method="post">
                <!-- drop down list for where to post-->
                <label for="community">Choose where to post</label>
                <select id="community" name="community">
                    <option value="job-search">Job Search</option>
                    <option value="lab-switches">Lab Switches</option>
                    <option value="ubco-activities">UBCO Activities</option>
                    <option value="marketplace">Marketplace</option>
                </select>
                <input type="text" id="title" name="title" placeholder="Title" required>
                <textarea id="content" name="content" ></textarea>
            
                <!-- Image upload section, shown/hidden -->
                <div class="image-upload-container" id="image-upload-container" style="display: none;">
                    <input type="file" id="image-upload" name="image-upload" accept="image/*,video/*">
                </div>        
                <button type="button" id="postSubmit">Post</button>
            </form>
        </div>
    
        <!--image upload button -->
        <script>

document.getElementById('postSubmit').addEventListener('click', function() {
    var form = document.getElementById('postForm');
    var formData = new FormData(form);

    fetch('submit-post.php', {
        method: 'POST',
        body: formData
    }) 
    .then(response => response.text())
    .then(data => {
        // If the post was successful, redirect to the main page
        window.location.href = 'mainpage.php';
    })
    .catch(error => console.error('Error:', error));
});

            document.getElementById('image-video-btn').addEventListener('click', function() {
                var imageUploadContainer = document.getElementById('image-upload-container');
                imageUploadContainer.style.display = imageUploadContainer.style.display === 'none' ? 'block' : 'none';
            });
            document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('postSubmit').addEventListener('click', function() {
            var form = document.getElementById('postForm');
            var formData = new FormData(form);

            fetch('submit-post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); 
                // If you want to show the post on the main page without reloading:
                // need id="postContainer" on the main page
                var postContainer = document.getElementById('postContainer');
                postContainer.innerHTML += data; // data should be the HTML of the new post
            })
            .catch(error => console.error('Error:', error));
        });
    });


        </script>
</body>
</html>
