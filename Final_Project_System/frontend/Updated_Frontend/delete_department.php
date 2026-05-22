<?php include('db.php');
$id = intval($_GET['id']);
$conn->query("DELETE FROM Departments WHERE dept_id = $id");
header("Location: dashboard.php?role=Admin&tab=departments");
