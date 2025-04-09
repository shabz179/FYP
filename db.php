<?php
$dbhost = 'phpmyadmin.ecs.westminster.ac.uk';
$dbuser = 'w1888491';
$dbpass = 'N1GeSL6yYNLp';
$dbname = 'w1888491_0';

// Create a database connection
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check if the connection failed
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>