<?php
$host = "127.0.0.1";
$user = "root";
$pass = "REDACTED_FOR_SECURITY"; // Enter your local password here
$dbname = "edutrack_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>