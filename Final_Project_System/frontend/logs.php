<?php include('db.php'); ?>
<body style="font-family: Arial; padding: 40px;">
    <h1>Audit Logs (Result of Database Trigger)</h1>
    <a href="dashboard.php?role=Admin">Back to Dashboard</a>
    <table border="1" cellpadding="10" style="width:100%; margin-top:20px; border-collapse:collapse;">
        <tr style="background:#eee;">
            <th>Log ID</th><th>Student ID</th><th>Old Grade</th><th>New Grade</th><th>Action</th><th>Timestamp</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM audit_logs ORDER BY changed_at DESC");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['log_id']}</td>
                <td>{$row['student_id']}</td>
                <td>{$row['old_grade']}</td>
                <td>{$row['new_grade']}</td>
                <td>{$row['action_type']}</td>
                <td>{$row['changed_at']}</td>
            </tr>";
        }
        ?>
    </table>
</body>