<?php 
include('db.php');
$id = intval($_GET['id']);
$conn->query("DELETE FROM Students WHERE student_id = $id");
header("Location: dashboard.php?role=Admin&tab=students");
