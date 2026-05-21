<?php include('db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sid = intval($_POST['student_id']);
    $cid = intval($_POST['course_id']);
    $iid = intval($_POST['instructor_id']);
    $sem = $conn->real_escape_string($_POST['semester']);
    $yr = intval($_POST['academic_year']);
    $conn->begin_transaction();
    try {
        $conn->query("INSERT INTO Enrollments (student_id, course_id, instructor_id, semester, academic_year) VALUES ($sid,$cid,$iid,'$sem',$yr)");
        $conn->commit();
        header("Location: dashboard.php?role=Admin&tab=enrollments");
    } catch (Exception $e) { $conn->rollback(); echo "Error: ".$e->getMessage(); }
}
$students = $conn->query("SELECT student_id, CONCAT(first_name,' ',last_name) name FROM Students ORDER BY first_name");
$courses = $conn->query("SELECT course_id, course_title FROM Courses");
$instructors = $conn->query("SELECT instructor_id, CONCAT(first_name,' ',last_name) name FROM Instructors");
?>
<!DOCTYPE html><html><head><title>Add Enrollment</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'DM Sans',sans-serif;background:#0f1117;color:#e2e8f0;min-height:100vh;display:flex;justify-content:center;align-items:center;padding:40px}
.card{background:#181c27;border:1px solid #2a2f3f;border-radius:16px;padding:36px;width:100%;max-width:480px}
h2{font-family:'Syne',sans-serif;font-size:22px;color:#4f8ef7;margin-bottom:24px}
label{display:block;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-bottom:6px;margin-top:16px}
input,select{width:100%;padding:11px 14px;background:#1e2330;border:1px solid #2a2f3f;border-radius:8px;color:#e2e8f0;font-family:'DM Sans',sans-serif;font-size:14px;outline:none}
input:focus,select:focus{border-color:#4f8ef7}
.actions{display:flex;gap:12px;margin-top:24px}
.btn-save{background:#4f8ef7;color:white;border:none;padding:11px 24px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer}
.btn-back{background:#1e2330;color:#64748b;border:1px solid #2a2f3f;padding:11px 24px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center}
</style></head><body>
<div class="card">
<h2>📋 Add Enrollment</h2>
<form method="POST">
<label>Student</label>
<select name="student_id">
<?php while($r=$students->fetch_assoc()) echo "<option value='{$r['student_id']}'>{$r['name']}</option>"; ?>
</select>
<label>Course</label>
<select name="course_id">
<?php while($r=$courses->fetch_assoc()) echo "<option value='{$r['course_id']}'>{$r['course_title']}</option>"; ?>
</select>
<label>Instructor</label>
<select name="instructor_id">
<?php while($r=$instructors->fetch_assoc()) echo "<option value='{$r['instructor_id']}'>{$r['name']}</option>"; ?>
</select>
<label>Semester</label>
<select name="semester">
<option value="1st Semester">1st Semester</option>
<option value="2nd Semester">2nd Semester</option>
<option value="Summer">Summer</option>
</select>
<label>Academic Year</label>
<input name="academic_year" type="number" value="<?php echo date('Y'); ?>" required>
<div class="actions">
<button type="submit" class="btn-save">Save Enrollment</button>
<a href="dashboard.php?role=Admin&tab=enrollments" class="btn-back">← Back</a>
</div>
</form>
</div>
</body></html>
