<?php
$host = "127.0.0.1";
$user = "root";
$pass = "admin123"; // Enter your local password here
$dbname = "edutrack_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>