<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/log-reg.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshStart - UBCO </title>
</head>

<body>
    <div class="container">

        <!-- left hand side ubco background and header --> 

        <div class="left">
            <div id="welcometext">
                <p id="welcome">Welcome to</p>
                <h1 id="fs">FreshStart</h1>
            </div>
        </div>

        <!-- right hand side login form -->

        <div class="right">
            <div class="login">
                <form action="loginAction.php" method="post">

                    <!-- username --> 

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>

                    <!-- password -->

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>

                    <!-- log in button  -->

                    <button id="loginsubmit" type="submit">Log In</button>

                    <!-- Login Error -->

                    <div id="logError">
                        <?php
                        if (isset ($_SESSION['error'])) {
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        }
                        ?>
                    </div>

                    <p> Not a member?</a></p>
                    <p> <a href="register.php"> Sign up</a> or <a href="main page.html"> Continue as a Guest</a></p>

                </form>

            </div>

        </div>

    </div>
</body>