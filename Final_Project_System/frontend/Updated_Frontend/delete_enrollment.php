<?php include('db.php');
$id = intval($_GET['id']);
$conn->query("DELETE FROM Enrollments WHERE enrollment_id = $id");
header("Location: dashboard.php?role=Admin&tab=enrollments");
