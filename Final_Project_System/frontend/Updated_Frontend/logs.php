<?php include('db.php'); ?>
<!DOCTYPE html><html><head><title>Audit Logs</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'DM Sans',sans-serif;background:#0f1117;color:#e2e8f0;padding:40px}
h1{font-family:'Syne',sans-serif;font-size:24px;color:#a78bfa;margin-bottom:8px}
.sub{font-size:13px;color:#64748b;margin-bottom:24px}
.back{display:inline-flex;align-items:center;gap:6px;background:#181c27;border:1px solid #2a2f3f;color:#64748b;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;margin-bottom:24px;transition:all 0.15s}
.back:hover{color:#e2e8f0}
.table-wrap{background:#181c27;border:1px solid #2a2f3f;border-radius:12px;overflow:hidden}
table{width:100%;border-collapse:collapse}
thead tr{background:#1e2330}
th{padding:12px 16px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#64748b;border-bottom:1px solid #2a2f3f}
td{padding:13px 16px;font-size:14px;border-bottom:1px solid #2a2f3f}
tbody tr:last-child td{border-bottom:none}
tbody tr:hover{background:#1e2330}
.badge{display:inline-flex;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
.badge-purple{background:rgba(167,139,250,0.12);color:#a78bfa}
.badge-green{background:rgba(52,211,153,0.12);color:#34d399}
</style></head><body>
<h1>🔍 Audit Logs</h1>
<p class="sub">Automatically recorded by database trigger on grade updates</p>
<a href="dashboard.php?role=Admin" class="back">← Back to Dashboard</a>
<div class="table-wrap">
<table>
<thead><tr><th>Log ID</th><th>Student ID</th><th>Old Grade</th><th>New Grade</th><th>Action</th><th>Timestamp</th></tr></thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM audit_logs ORDER BY changed_at DESC");
if($result && $result->num_rows > 0):
    while($row = $result->fetch_assoc()):
?>
<tr>
<td><span class="badge badge-purple"><?php echo $row['log_id']; ?></span></td>
<td><?php echo $row['student_id']; ?></td>
<td><?php echo $row['old_grade'] ?? '—'; ?></td>
<td><?php echo $row['new_grade'] ?? '—'; ?></td>
<td><span class="badge badge-green"><?php echo htmlspecialchars($row['action_type']); ?></span></td>
<td style="color:#64748b"><?php echo $row['changed_at']; ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6" style="text-align:center;padding:40px;color:#64748b">No audit logs yet.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</body></html>
