<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link rel="stylesheet" href="css/log-reg.css"> -->
    <link rel="stylesheet" href="css/login-register.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshStart - UBCO</title>
</head>

<body>
    <div class="container">
        <!-- LEFT SIDE -->
        <div class="left">
            <div id="welcometext">
                <p id="welcome">Welcome to</p>
                <h1 id="freshStartText">FreshStart</h1>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="right">

            <div class="reg">

                <!-- <form action="main page.html" method="get"> -->
                <form action="registerAction.php" method="post" onsubmit="return validateForm()"
                    enctype="multipart/form-data">
                    <h2>Register</h2>
                    <!-- First name -->

                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname">

                    <!-- Last name -->

                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname">

                    <!-- Email -->

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">

                    <!-- Username -->

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">

                    <!-- Username is invalid : Login Error:-->

                    <div id="regError">
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        }
                        ?>
                    </div>

                    <!-- Password -->

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">

                    <!-- Password is invalid -->

                    <div class="pwe">
                        <p id="pwErrorLength" class="pwError" style="display: none;"> The password must be at least 8
                            characters</p>
                        <p id="pwErrorSpecChar" class="pwError" style="display: none;"> The password must contain a
                            special character {!, @, #, $, %, ^, &, *}</p>
                    </div>

                    <!-- Confirm Password -->

                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" id="confirmpassword" name="confirmpassword">

                    <!-- Passwords do not match -->

                    <div class="pwe">
                        <p id="pwErrorMatch" class="pwError" style="display: none;"> The passwords do not match</p>
                    </div>

                    <!-- Profile Picture -->

                    <label for="profilePicture">Upload a profile picture:</label>
                    <input type="file" id="profilePicture" name="profilePicture" accept="image/*">

                    <!-- Register and Log in button -->

                    <button type="submit">Register</button>

                </form>

            </div>

        </div>

    </div>
    <script>
        function validateForm() {
            var pw = document.getElementById("password").value;
            var cpw = document.getElementById("confirmpassword").value;

            var pwErrorMatch = document.getElementById('pwErrorMatch');
            var pwErrorLength = document.getElementById('pwErrorLength');
            var pwErrorSpecChar = document.getElementById('pwErrorSpecChar');

            //for the length
            if (pw.length < 8) {
                pwErrorLength.style.display = 'block';
                pwErrorMatch.display = 'none';
                pwErrorSpecChar.display = 'none';
                return false;
            }
            //for the special character
            if (!pw.match(/[!@#$%^&*]/)) {
                pwErrorSpecChar.style.display = 'block';
                pwErrorLength.style.display = 'none';
                pwErrorMatch.display = 'none';
                return false;
            }
            // for confirming password
            if (pw != cpw) {
                pwErrorMatch.style.display = 'block';
                pwErrorSpecChar.style.display = 'none';
                pwErrorLength.style.display = 'none';
                return false;
            }
            return true;
        }
    </script>
</body>

</html>