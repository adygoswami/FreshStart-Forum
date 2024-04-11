document.addEventListener('DOMContentLoaded', function() {
    loadPosts(); // Initial load of posts
    setupTopicFilters(); // Setup topic filter buttons

    const createPostForm = document.getElementById('createPostForm');
    if (createPostForm) {
        createPostForm.addEventListener('submit', handleCreatePost);
    }
});

function setupTopicFilters() {
    document.querySelectorAll('#topics-list button').forEach(button => {
        button.addEventListener('click', function() {
            filterByTopic(this.textContent);
        });
    });
}

function handleCreatePost(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(event.target);

    fetch('createPost.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) 
    .then(data => {
        alert(data); 
        loadPosts(); // Reload the posts to include the new one
    })
    .catch(error => console.error('Error creating post:', error));
}

// Check if the user is logged in
let isLoggedIn = document.body.dataset.loggedIn === 'true';
let isAdmin = document.body.dataset.isAdmin === 'true';

// Load all posts
function loadPosts() {
    fetch('fetchPosts.php')
    .then(response => response.json())
    .then(posts => {
        displayPosts(posts);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Display posts on the page
function displayPosts(posts) {
    const postContainer = document.getElementById('post-container');
    postContainer.innerHTML = ''; // Clear existing posts

    posts.forEach(post => {
        const postDiv = document.createElement('div');
        postDiv.className = 'post';

        let imageHtml = post.image ? `<img src="data:image/jpeg;base64,${post.image}" alt="Post Image" style="max-width: 100%; height: auto;">` : '';
        let commentsHtml = '';
        let likeButtonClass = post.userInteraction === 'like' ? 'liked' : '';
        let dislikeButtonClass = post.userInteraction === 'dislike' ? 'disliked' : '';
        if (post.comments && post.comments.length > 0) {
            commentsHtml = post.comments.map(comment => `<div class="comment"><p>${comment.userID}: ${comment.commentText}</p></div>`).join('');
        }

        postDiv.innerHTML = `
            <h3>${post.title}</h3>
            <p>${post.content}</p>
            ${imageHtml}
            <p>Likes: ${post.likes}</p>
            <p>Dislikes: ${post.dislikes}</p>
            <div class="comments">${commentsHtml}</div>
            <button class="${likeButtonClass}" onclick="updateLikes(${post.postID})">Like</button>
            <button class="${dislikeButtonClass}" onclick="updateDislikes(${post.postID})">Dislike</button>
            ${isLoggedIn ? `<textarea class="commentText-${post.postID}"></textarea><button onclick="addComment(${post.postID})">Comment</button>` : ''}
            ${isAdmin ? `<button onclick="deletePost(${post.postID})">Delete</button>` : ''}
        `;
        postContainer.appendChild(postDiv);
    });
}

// Search posts
function searchPosts() {
    const query = document.getElementById('searchQuery').value.trim();
    fetch('searchPosts.php?query=' + encodeURIComponent(query))
    .then(response => response.json())
    .then(posts => {
        displayPosts(posts);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Filter posts by topic
function filterByTopic(topic) {
    fetch('filterPosts.php?topic=' + encodeURIComponent(topic))
    .then(response => response.json())
    .then(posts => {
        displayPosts(posts);
    })
    .catch(error => {
        console.error('Error:', error);
    });
    var buttons = document.querySelectorAll('#topics-list button');

    // Remove the 'active' class from all buttons
    buttons.forEach(function(button) {
        button.classList.remove('active');
    });

    // Add the 'active' class to the clicked button
    // The 'this' keyword refers to the button that was clicked
    this.classList.add('active');
}

// Update likes for a post
function updateLikes(postID) {
    fetch('updateLikes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `postID=${postID}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPosts(); // Refresh posts to reflect updated likes
        }
    })
    .catch(error => console.error('Error updating likes:', error));
}

// Update dislikes for a post
function updateDislikes(postID) {
    fetch('updateDislikes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `postID=${postID}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPosts(); // Refresh posts to reflect updated dislikes
        }
    })
    .catch(error => console.error('Error updating dislikes:', error));
} 

function addComment(postId) {
    const commentText = document.querySelector(`.commentText-${postId}`).value;
    if (!commentText) return;

    fetch('addComment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `postID=${postId}&commentText=${encodeURIComponent(commentText)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPosts(); // Reload posts to show the new comment
        } else {
            alert('Failed to add comment.');
        }
    })
    .catch(error => console.error('Error adding comment:', error));
}

// Delete a post (Admin only)
function deletePost(postId) {
    if (!checkLoggedIn() || !isAdmin) return;
    if (!confirm('Are you sure you want to delete this post?')) return;
    fetch('deletePost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `postId=${postId}`,
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            console.log(`Post ${postId} deleted successfully.`);
            loadPosts(); // Reload posts after deletion
        } else {
            console.error(`Failed to delete post ${postId}.`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Check if the user is logged in
function checkLoggedIn() {
    if (!isLoggedIn) {
        alert('Please log in to interact with posts.');
        window.location.href = 'login.php'; // Redirect to login
        return false;
    }
    return true;
}

// Initial load of posts
loadPosts();