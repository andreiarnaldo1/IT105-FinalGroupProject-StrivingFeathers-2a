<?php include('db.php'); 
$id = $_GET['id'];
// Get current student/enrollment info
$res = $conn->query("SELECT s.first_name, e.grade, e.enrollment_id FROM Students s JOIN Enrollments e ON s.student_id = e.student_id WHERE s.student_id = $id LIMIT 1");
$data = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_grade = $_POST['grade'];
    $eid = $data['enrollment_id'];
    // This UPDATE will fire the database TRIGGER
    $conn->query("UPDATE Enrollments SET grade = $new_grade WHERE enrollment_id = $eid");
    header("Location: dashboard.php?role=Admin");
}
?>
<body style="font-family: Arial; padding: 40px;">
    <h2>Update Grade for <?php echo $data['first_name']; ?></h2>
    <p>This update will automatically fire the <strong>Audit Trigger</strong>.</p>
    <form method="POST">
        <label>New Grade:</label>
        <input name="grade" type="number" step="0.01" value="<?php echo $data['grade']; ?>" required>
        <button type="submit">Update & Fire Trigger</button>
    </form>
</body>