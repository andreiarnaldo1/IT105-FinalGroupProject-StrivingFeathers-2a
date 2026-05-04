<?php 
include('db.php');
$id = $_GET['id'];
// Triggering a Database Delete
$conn->query("DELETE FROM Students WHERE student_id = $id");
header("Location: dashboard.php");
?>