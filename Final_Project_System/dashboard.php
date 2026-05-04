<?php 
include('db.php'); 
// Role-based logic
$role = isset($_POST['role']) ? $_POST['role'] : (isset($_GET['role']) ? $_GET['role'] : 'Student');
?>
<!DOCTYPE html>
<html>
<head>
    <title>EduTrack Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; background: #f8f9fa; }
        .sidebar { width: 220px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
        .main { margin-left: 260px; padding: 30px; width: 100%; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        th { background: #3498db; color: white; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; color: white; font-size: 14px; }
        .btn-add { background: #27ae60; }
        .btn-log { background: #8e44ad; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>EduTrack</h2>
        <p>Role: <strong><?php echo $role; ?></strong></p>
        <hr>
        <a href="index.php" style="color: #ecf0f1; text-decoration:none;">Logout</a>
    </div>
    <div class="main">
        <h1>Student Management Dashboard</h1>
        
        <?php if($role == 'Admin') { ?>
            <a href="add_student.php?role=Admin" class="btn btn-add">+ Add New Student</a>
            <a href="logs.php?role=Admin" class="btn btn-log">View Audit Logs (Trigger)</a>
        <?php } ?>

        <form method="GET" style="margin-top:20px;">
            <input type="hidden" name="role" value="<?php echo $role; ?>">
            <input type="text" name="search" placeholder="Search First Name (Uses B-Tree Index)..." style="padding:10px; width:300px;" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit" style="padding:10px;">Search</button>
        </form>

        <table>
            <tr>
                <th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Actions</th>
            </tr>
            <?php
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            // Optimization: This query uses your Phase 5 index
            $sql = "SELECT * FROM Students WHERE first_name LIKE '$search%' LIMIT 10";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['student_id']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['email']}</td>
                    <td>";
                if($role == 'Admin') {
                    echo "<a href='update_student.php?id={$row['student_id']}&role=Admin' style='color:blue;'>Edit Grade</a> | 
                          <a href='delete_student.php?id={$row['student_id']}&role=Admin' style='color:red;'>Delete</a>";
                } else {
                    echo "<span style='color:gray;'>View Only</span>";
                }
                echo "</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>