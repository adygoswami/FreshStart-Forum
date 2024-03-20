<?php
$host = "cosc360.ok.ubc.ca";
$dbname = 'db_47130992';
$dbuser = '47130992';
$dbpass = 'Yarvp04117.';
$conn = mysqli_connection($host, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>