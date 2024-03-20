<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/log-reg.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshStart Forum for UBCO Students</title>
</head>

<body>
    <div class="container">

        <div class="left">
            <div class="welcome">
                <P>Welcome to
                <p>
                <h1>FreshStart</h1>
            </div>
        </div>

        <div class="right">

            <div class="login">

                <form action="loginAction.php" method="post">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Log In</button>

                    <div id="logError">
                        <!-- <p> Login Error: </p> -->
                        <?php
                        if (isset ($_SESSION['error'])) {
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        }
                        ?>
                    </div>
                    <!-- <p> <a href="forgot.html"> Forgot Password?</a></p> -->

                    <p> Not a member?</a></p>
                    <p> <a href="reg.html"> Sign up</a> or <a href="main page.html"> Continue as a Guest</a></p>

                </form>

            </div>

        </div>

    </div>
</body>