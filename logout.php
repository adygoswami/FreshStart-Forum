<?php
// this page will take the user to the login page right after they use the user profile page to logout og the session and the page! and they can continue as a guest after if they want!
session_start();
session_destroy();
header("Location: login.php");
exit();