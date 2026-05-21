<?php include('db.php');
$id = intval($_GET['id']);
$conn->query("DELETE FROM Instructors WHERE instructor_id = $id");
header("Location: dashboard.php?role=Admin&tab=instructors");
