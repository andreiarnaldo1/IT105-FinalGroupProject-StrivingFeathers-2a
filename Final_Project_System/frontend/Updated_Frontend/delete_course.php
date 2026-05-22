<?php include('db.php');
$id = intval($_GET['id']);
$conn->query("DELETE FROM Courses WHERE course_id = $id");
header("Location: dashboard.php?role=Admin&tab=courses");
