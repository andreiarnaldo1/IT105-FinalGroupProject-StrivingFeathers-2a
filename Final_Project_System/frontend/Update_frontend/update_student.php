<?php include('db.php');
$id = intval($_GET['id']);
$role = isset($_GET['role']) ? $_GET['role'] : 'Admin';

// Safely fetch student + enrollment (student may have no enrollment yet)
$res = $conn->query("SELECT s.first_name, s.last_name, e.grade, e.enrollment_id 
                     FROM Students s 
                     LEFT JOIN Enrollments e ON s.student_id = e.student_id 
                     WHERE s.student_id = $id 
                     LIMIT 1");
$data = $res ? $res->fetch_assoc() : null;

$error = '';
$success = '';

if (!$data) {
    die("Student not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($data['enrollment_id'])) {
        $error = "This student has no enrollment record. Please add an enrollment first before updating a grade.";
    } else {
        $new_grade = floatval($_POST['grade']);
        $eid = intval($data['enrollment_id']);
        if ($conn->query("UPDATE Enrollments SET grade = $new_grade WHERE enrollment_id = $eid")) {
            header("Location: dashboard.php?role=$role&tab=enrollments");
            exit;
        } else {
            $error = "Update failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html><html><head><title>Update Grade</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#0f1117;color:#e2e8f0;min-height:100vh;display:flex;justify-content:center;align-items:center;padding:40px}
.card{background:#181c27;border:1px solid #2a2f3f;border-radius:16px;padding:36px;width:100%;max-width:480px;position:relative}
.card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#4f8ef7,#a78bfa);border-radius:16px 16px 0 0}
h2{font-family:'Syne',sans-serif;font-size:20px;color:#e2e8f0;margin-bottom:4px}
.sub{font-size:13px;color:#64748b;margin-bottom:24px}
.trigger-note{background:rgba(167,139,250,0.08);border:1px solid rgba(167,139,250,0.2);border-radius:8px;padding:12px 14px;font-size:13px;color:#a78bfa;margin-bottom:20px;display:flex;align-items:center;gap:8px}
.student-info{background:#1e2330;border:1px solid #2a2f3f;border-radius:8px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:#94a3b8}
.student-info strong{color:#e2e8f0}
label{display:block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#64748b;margin-bottom:8px;margin-top:16px}
input{width:100%;padding:11px 14px;background:#1e2330;border:1px solid #2a2f3f;border-radius:8px;color:#e2e8f0;font-family:'DM Sans',sans-serif;font-size:14px;outline:none;transition:border-color 0.15s,box-shadow 0.15s}
input:focus{border-color:#4f8ef7;box-shadow:0 0 0 3px rgba(79,142,247,0.12)}
.actions{display:flex;gap:12px;margin-top:24px}
.btn-save{background:linear-gradient(135deg,#4f8ef7,#6b7cf7);color:white;border:none;padding:11px 24px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer;transition:all 0.15s}
.btn-save:hover{opacity:0.85;transform:translateY(-1px)}
.btn-back{background:#1e2330;color:#64748b;border:1px solid #2a2f3f;padding:11px 24px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all 0.15s}
.btn-back:hover{color:#e2e8f0}
.alert-error{background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.25);color:#f87171;border-radius:8px;padding:12px 14px;font-size:13px;margin-bottom:16px}
.no-enrollment{text-align:center;padding:20px 0}
.no-enrollment p{color:#64748b;font-size:14px;margin-bottom:16px}
.btn-enroll{background:rgba(52,211,153,0.1);color:#34d399;border:1px solid rgba(52,211,153,0.25);padding:10px 20px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
</style></head><body>
<div class="card">
    <h2>Update Grade</h2>
    <p class="sub">for <?php echo htmlspecialchars($data['first_name'] . ' ' . $data['last_name']); ?></p>

    <?php if($error): ?>
    <div class="alert-error">⚠ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if(empty($data['enrollment_id'])): ?>
    <div class="no-enrollment">
        <div style="font-size:40px;margin-bottom:12px">📋</div>
        <p>This student has no enrollment record yet.<br>Add an enrollment before updating a grade.</p>
        <a href="add_enrollment.php?role=<?php echo $role; ?>" class="btn-enroll">+ Add Enrollment</a>
    </div>
    <?php else: ?>
    <div class="trigger-note">⚡ This update will automatically fire the <strong>Audit Trigger</strong></div>
    <div class="student-info">
        Current Grade: <strong><?php echo $data['grade'] !== null ? $data['grade'] : 'Not yet graded'; ?></strong>
        &nbsp;&nbsp;|&nbsp;&nbsp; Enrollment ID: <strong>#<?php echo $data['enrollment_id']; ?></strong>
    </div>
    <form method="POST">
        <label>New Grade (0.00 – 5.00)</label>
        <input name="grade" type="number" step="0.01" min="0" max="5" value="<?php echo htmlspecialchars($data['grade'] ?? ''); ?>" placeholder="e.g. 1.75" required>
        <div class="actions">
            <button type="submit" class="btn-save">Update & Fire Trigger</button>
            <a href="dashboard.php?role=<?php echo $role; ?>&tab=enrollments" class="btn-back">← Back</a>
        </div>
    </form>
    <?php endif; ?>

    <?php if(empty($data['enrollment_id'])): ?>
    <div style="margin-top:16px">
        <a href="dashboard.php?role=<?php echo $role; ?>&tab=students" class="btn-back">← Back to Students</a>
    </div>
    <?php endif; ?>
</div>
</body></html>
