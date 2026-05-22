<?php include('db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $conn->real_escape_string($_POST['fname']);
    $ln = $conn->real_escape_string($_POST['lname']);
    $em = $conn->real_escape_string($_POST['email']);
    $dept = intval($_POST['dept_id']);
    $conn->begin_transaction();
    try {
        $conn->query("INSERT INTO Instructors (first_name, last_name, email, dept_id) VALUES ('$fn','$ln','$em',$dept)");
        $conn->commit();
        header("Location: dashboard.php?role=Admin&tab=instructors");
    } catch (Exception $e) { $conn->rollback(); echo "Error: ".$e->getMessage(); }
}
$depts = $conn->query("SELECT * FROM Departments");
?>
<!DOCTYPE html><html><head><title>Add Instructor</title>
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
<h2>👨‍🏫 Add Instructor</h2>
<form method="POST">
<label>First Name</label><input name="fname" placeholder="First Name" required>
<label>Last Name</label><input name="lname" placeholder="Last Name" required>
<label>Email</label><input name="email" type="email" placeholder="Email" required>
<label>Department</label>
<select name="dept_id">
<?php while($d=$depts->fetch_assoc()) echo "<option value='{$d['dept_id']}'>{$d['dept_name']}</option>"; ?>
</select>
<div class="actions">
<button type="submit" class="btn-save">Save Instructor</button>
<a href="dashboard.php?role=Admin&tab=instructors" class="btn-back">← Back</a>
</div>
</form>
</div>
</body></html>
