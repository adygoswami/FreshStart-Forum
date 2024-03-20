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

        <div class="left">
            <div id="welcometext">
                <p id="welcome">Welcome to</p>
                <h1 id="fs">FreshStart</h1>

            </div>

        </div>

        <div class="right">

            <div class="login">

                <!-- <h2>New to UBCO? Use FreshStart to:</h2>
                <ul>
                    <li>Participate in UBCO Activities.</li>
                    <li>Seek Academic and Mental Health Support</li>
                    <li>Access Campus Resources</li>
                    <li>Explore Job Opportunities</li>
                    <li>Coordinate Lab Schedules</li>
                    <li>Trade in the Marketplace</li>
                    <li>Engage with the Community</li>
                </ul> -->

                <form action="loginAction.php" method="post">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <button id="loginsubmit" type="submit">Log In</button>

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