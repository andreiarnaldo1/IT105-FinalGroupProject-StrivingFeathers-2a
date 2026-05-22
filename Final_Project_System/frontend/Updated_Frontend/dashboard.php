<?php 
include('db.php'); 
$role = isset($_POST['role']) ? $_POST['role'] : (isset($_GET['role']) ? $_GET['role'] : 'Student');
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'students';
?>
<!DOCTYPE html>
<html>
<head>
    <title>EduTrack Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f0f2f5;
            --surface: #ffffff;
            --surface2: #f9fafb;
            --border: #e5e7eb;
            --accent: #3b82f6;
            --accent2: #6b7cf7;
            --green: #10b981;
            --red: #ef4444;
            --amber: #f59e0b;
            --text: #111827;
            --muted: #6b7280;
            --sidebar-w: 260px;
            /* Sidebar dark navy theme */
            --sb-bg: #0f172a;
            --sb-bg2: #1e293b;
            --sb-border: rgba(255,255,255,0.07);
            --sb-text: #94a3b8;
            --sb-text-active: #ffffff;
            --sb-active-bg: #1d3a6e;
            --sb-hover-bg: rgba(255,255,255,0.06);
            --sb-accent: #3b82f6;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* SIDEBAR — dark navy/blue theme */
        .sidebar {
            width: var(--sidebar-w); background: var(--sb-bg);
            height: 100vh; position: fixed; top: 0; left: 0; display: flex; flex-direction: column;
            padding: 0; z-index: 100; overflow: hidden;
            box-shadow: 4px 0 20px rgba(0,0,0,0.25);
        }
        .sidebar-logo {
            padding: 30px 24px 22px;
            border-bottom: 1px solid var(--sb-border);
        }
        .sidebar-logo h1 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 24px; font-weight: 800; color: var(--sb-accent); letter-spacing: -0.5px; }
        .sidebar-logo .role-badge {
            display: inline-flex; align-items: center; gap: 6px; margin-top: 10px;
            background: rgba(59,130,246,0.18);
            color: #93c5fd;
            border: 1px solid rgba(59,130,246,0.35);
            padding: 5px 13px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
        }

        .sidebar-nav { flex: 1; padding: 18px 12px; overflow-y: auto; }
        .nav-section-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; color: #475569; padding: 8px 12px 6px; }
        .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 10px;
            color: var(--sb-text); text-decoration: none; font-size: 14px; font-weight: 500;
            margin-bottom: 3px; transition: all 0.15s; cursor: pointer; border: none; background: none; width: 100%; text-align: left;
        }
        .nav-item:hover { background: var(--sb-hover-bg); color: var(--sb-text-active); }
        .nav-item.active { background: var(--sb-active-bg); color: var(--sb-text-active); box-shadow: inset 3px 0 0 var(--sb-accent); }
        .nav-item .icon { font-size: 16px; width: 22px; text-align: center; display:flex; align-items:center; justify-content:center; flex-shrink:0; color: inherit; }
        .sidebar-footer { padding: 18px; border-top: 1px solid var(--sb-border); }
        .logout-btn {
            display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 10px;
            color: var(--sb-text); text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all 0.15s; width: 100%;
        }
        .logout-btn:hover { color: #fca5a5; background: rgba(239,68,68,0.1); }

        /* MAIN */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 20px 36px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .topbar h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 22px; font-weight: 800; color: #111827; }
        .topbar .sub { font-size: 13px; color: var(--muted); margin-top: 3px; font-family: 'Inter', sans-serif; }
        .topbar-actions { display: flex; gap: 10px; align-items: center; }

        .content { padding: 32px 36px; flex: 1; }

        /* STAT CARDS — bigger */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
            padding: 28px 26px 24px; position: relative; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s;
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.09); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--card-accent, var(--accent)); border-radius: 16px 16px 0 0; }
        .stat-card .label { font-size: 11px; text-transform: uppercase; letter-spacing: 1.2px; color: #6b7280; font-weight: 700; font-family: 'Inter', sans-serif; }
        .stat-card .value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 38px; font-weight: 800; color: #111827; margin-top: 10px; line-height: 1.1; letter-spacing: -1px; }
        .stat-card .icon-bg { position: absolute; right: 18px; top: 18px; opacity: 0.09; color: var(--text); }

        /* TABLE SECTION */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; }
        .search-box {
            display: flex; align-items: center; gap: 0; background: var(--surface); border: 1.5px solid var(--border);
            border-radius: 8px; overflow: hidden;
        }
        .search-box input {
            background: transparent; border: none; outline: none; padding: 9px 14px; color: var(--text);
            font-family: 'Inter', sans-serif; font-size: 14px; width: 240px;
        }
        .search-box input::placeholder { color: var(--muted); }
        .search-box button {
            background: var(--accent); border: none; padding: 9px 16px; color: white; cursor: pointer;
            font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; transition: opacity 0.15s;
        }
        .search-box button:hover { opacity: 0.85; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px;
            border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600;
            text-decoration: none; cursor: pointer; border: none; transition: all 0.15s;
        }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: #1565d8; box-shadow: 0 4px 14px rgba(24,119,242,0.3); }
        .btn-purple { background: rgba(107,124,247,0.1); color: var(--accent2); border: 1px solid rgba(107,124,247,0.3); }
        .btn-purple:hover { background: rgba(107,124,247,0.2); }
        .btn-danger { background: rgba(239,68,68,0.08); color: var(--red); border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger:hover { background: rgba(239,68,68,0.15); }
        .btn-edit { background: rgba(24,119,242,0.08); color: var(--accent); border: 1px solid rgba(24,119,242,0.2); padding: 5px 10px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 600; transition: all 0.15s; }
        .btn-edit:hover { background: rgba(24,119,242,0.15); }
        .btn-del { background: rgba(239,68,68,0.08); color: var(--red); border: 1px solid rgba(239,68,68,0.2); padding: 5px 10px; font-size: 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 600; transition: all 0.15s; }
        .btn-del:hover { background: rgba(239,68,68,0.15); }

        /* TABLE */
        .table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: var(--surface2); }
        th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); border-bottom: 1px solid var(--border); white-space: nowrap; }
        td { padding: 13px 16px; font-size: 14px; border-bottom: 1px solid var(--border); vertical-align: middle; color: #374151; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.1s; }
        tbody tr:hover { background: #f9fafb; }

        .badge { display: inline-flex; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-green { background: rgba(16,185,129,0.1); color: #059669; }
        .badge-blue { background: rgba(24,119,242,0.1); color: var(--accent); }
        .badge-amber { background: rgba(245,158,11,0.1); color: #d97706; }

        .empty-row td { text-align: center; padding: 40px; color: var(--muted); font-size: 14px; }

        /* PAGINATION */
        .pagination { display: flex; align-items: center; gap: 8px; padding: 16px; border-top: 1px solid var(--border); justify-content: space-between; flex-wrap: wrap; background: var(--surface2); }
        .pagination .page-info { font-size: 13px; color: var(--muted); }
        .page-btns { display: flex; gap: 4px; }
        .page-btn { background: var(--surface); border: 1.5px solid var(--border); color: #374151; padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none; font-weight: 500; transition: all 0.15s; }
        .page-btn:hover, .page-btn.active { background: var(--accent); border-color: var(--accent); color: white; }
        .page-btn.disabled { opacity: 0.35; pointer-events: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <h1>⚡ EduTrack</h1>
        <span class="role-badge"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><?php echo $role == 'Admin' ? 'Admin' : 'Student'; ?></span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Tables</div>
        <a href="dashboard.php?role=<?php echo $role; ?>&tab=students" class="nav-item <?php echo $tab=='students'?'active':''; ?>"><span class="icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Students</a>
        <a href="dashboard.php?role=<?php echo $role; ?>&tab=instructors" class="nav-item <?php echo $tab=='instructors'?'active':''; ?>"><span class="icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span> Instructors</a>
        <a href="dashboard.php?role=<?php echo $role; ?>&tab=enrollments" class="nav-item <?php echo $tab=='enrollments'?'active':''; ?>"><span class="icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span> Enrollments</a>
        <a href="dashboard.php?role=<?php echo $role; ?>&tab=courses" class="nav-item <?php echo $tab=='courses'?'active':''; ?>"><span class="icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></span> Courses</a>
        <a href="dashboard.php?role=<?php echo $role; ?>&tab=departments" class="nav-item <?php echo $tab=='departments'?'active':''; ?>"><span class="icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg></span> Departments</a>
        <?php if($role=='Admin'): ?>
        <div class="nav-section-label" style="margin-top:12px;">Admin</div>
        <a href="logs.php?role=Admin" class="nav-item"><span class="icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span> Audit Logs</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <a href="index.php" class="logout-btn"><span>🚪</span> Logout</a>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <div>
            <h2><?php
                $titles = ['students'=>'Students','instructors'=>'Instructors','enrollments'=>'Enrollments','courses'=>'Courses','departments'=>'Departments'];
                echo $titles[$tab] ?? 'Dashboard';
            ?></h2>
            <div class="sub">EduTrack Management System</div>
        </div>
        <div class="topbar-actions">
            <?php if($role=='Admin'): ?>
                <?php if($tab=='students'): ?>
                    <a href="add_student.php?role=Admin" class="btn btn-primary">+ Add Student</a>
                <?php elseif($tab=='instructors'): ?>
                    <a href="add_instructor.php?role=Admin" class="btn btn-primary">+ Add Instructor</a>
                <?php elseif($tab=='enrollments'): ?>
                    <a href="add_enrollment.php?role=Admin" class="btn btn-primary">+ Add Enrollment</a>
                <?php elseif($tab=='courses'): ?>
                    <a href="add_course.php?role=Admin" class="btn btn-primary">+ Add Course</a>
                <?php elseif($tab=='departments'): ?>
                    <a href="add_department.php?role=Admin" class="btn btn-primary">+ Add Department</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="content">
        <?php
        // --- STATS ---
        $s_count = $conn->query("SELECT COUNT(*) c FROM Students")->fetch_assoc()['c'];
        $i_count = $conn->query("SELECT COUNT(*) c FROM Instructors")->fetch_assoc()['c'];
        $e_count = $conn->query("SELECT COUNT(*) c FROM Enrollments")->fetch_assoc()['c'];
        $c_count = $conn->query("SELECT COUNT(*) c FROM Courses")->fetch_assoc()['c'];
        $d_count = $conn->query("SELECT COUNT(*) c FROM Departments")->fetch_assoc()['c'];
        ?>
        <div class="stats-grid">
            <div class="stat-card" style="--card-accent: var(--accent)"><svg class="icon-bg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg><div class="label">Students</div><div class="value"><?php echo $s_count; ?></div></div>
            <div class="stat-card" style="--card-accent: var(--accent2)"><svg class="icon-bg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg><div class="label">Instructors</div><div class="value"><?php echo $i_count; ?></div></div>
            <div class="stat-card" style="--card-accent: var(--green)"><svg class="icon-bg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg><div class="label">Enrollments</div><div class="value"><?php echo $e_count; ?></div></div>
            <div class="stat-card" style="--card-accent: var(--amber)"><svg class="icon-bg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg><div class="label">Courses</div><div class="value"><?php echo $c_count; ?></div></div>
            <div class="stat-card" style="--card-accent: var(--red)"><svg class="icon-bg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg><div class="label">Departments</div><div class="value"><?php echo $d_count; ?></div></div>
        </div>

        <?php
        // --- PAGINATION SETUP ---
        $per_page = 20;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if($page < 1) $page = 1;
        $offset = ($page - 1) * $per_page;
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
        ?>

        <?php if($tab == 'students'): ?>
        <div class="section-header">
            <form method="GET" style="display:flex; gap:0;">
                <input type="hidden" name="role" value="<?php echo $role; ?>">
                <input type="hidden" name="tab" value="students">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Search by first name..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </div>
            </form>
        </div>
        <?php
        $where = $search ? "WHERE first_name LIKE '$search%'" : "";
        $total = $conn->query("SELECT COUNT(*) c FROM Students $where")->fetch_assoc()['c'];
        $total_pages = max(1, ceil($total / $per_page));
        $result = $conn->query("SELECT * FROM Students $where ORDER BY student_id DESC LIMIT $per_page OFFSET $offset");
        ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Date of Birth</th><th>Enrolled</th><?php if($role=='Admin') echo '<th>Actions</th>'; ?></tr></thead>
                <tbody>
                <?php if($result->num_rows == 0): ?>
                    <tr class="empty-row"><td colspan="7">No students found.</td></tr>
                <?php else: while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge badge-blue"><?php echo $row['student_id']; ?></span></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo $row['date_of_birth']; ?></td>
                    <td><span class="badge badge-green"><?php echo $row['enrollment_date']; ?></span></td>
                    <?php if($role=='Admin'): ?>
                    <td style="display:flex; gap:6px;">
                        <a href="update_student.php?id=<?php echo $row['student_id']; ?>&role=Admin" class="btn-edit">✏ Edit</a>
                        <a href="delete_student.php?id=<?php echo $row['student_id']; ?>&role=Admin" class="btn-del" onclick="return confirm('Delete this student?')">🗑 Del</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span class="page-info">Showing <?php echo min($offset+1,$total); ?>–<?php echo min($offset+$per_page,$total); ?> of <?php echo $total; ?> students</span>
                <div class="page-btns">
                    <a href="?role=<?php echo $role; ?>&tab=students&page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>" class="page-btn <?php echo $page<=1?'disabled':''; ?>">← Prev</a>
                    <?php for($p=max(1,$page-2);$p<=min($total_pages,$page+2);$p++): ?>
                    <a href="?role=<?php echo $role; ?>&tab=students&page=<?php echo $p; ?>&search=<?php echo urlencode($search); ?>" class="page-btn <?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
                    <?php endfor; ?>
                    <a href="?role=<?php echo $role; ?>&tab=students&page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>" class="page-btn <?php echo $page>=$total_pages?'disabled':''; ?>">Next →</a>
                </div>
            </div>
        </div>

        <?php elseif($tab == 'instructors'): ?>
        <?php
        $total = $conn->query("SELECT COUNT(*) c FROM Instructors")->fetch_assoc()['c'];
        $total_pages = max(1, ceil($total / $per_page));
        $result = $conn->query("SELECT i.*, d.dept_name FROM Instructors i LEFT JOIN Departments d ON i.dept_id = d.dept_id ORDER BY i.instructor_id DESC LIMIT $per_page OFFSET $offset");
        ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Department</th><?php if($role=='Admin') echo '<th>Actions</th>'; ?></tr></thead>
                <tbody>
                <?php if($result->num_rows == 0): ?>
                    <tr class="empty-row"><td colspan="6">No instructors found.</td></tr>
                <?php else: while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge badge-blue"><?php echo $row['instructor_id']; ?></span></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><span class="badge badge-amber"><?php echo htmlspecialchars($row['dept_name'] ?? 'N/A'); ?></span></td>
                    <?php if($role=='Admin'): ?>
                    <td style="display:flex; gap:6px;">
                        <a href="delete_instructor.php?id=<?php echo $row['instructor_id']; ?>&role=Admin" class="btn-del" onclick="return confirm('Delete this instructor?')">🗑 Del</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span class="page-info">Showing <?php echo min($offset+1,$total); ?>–<?php echo min($offset+$per_page,$total); ?> of <?php echo $total; ?> instructors</span>
                <div class="page-btns">
                    <a href="?role=<?php echo $role; ?>&tab=instructors&page=<?php echo $page-1; ?>" class="page-btn <?php echo $page<=1?'disabled':''; ?>">← Prev</a>
                    <?php for($p=max(1,$page-2);$p<=min($total_pages,$page+2);$p++): ?>
                    <a href="?role=<?php echo $role; ?>&tab=instructors&page=<?php echo $p; ?>" class="page-btn <?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
                    <?php endfor; ?>
                    <a href="?role=<?php echo $role; ?>&tab=instructors&page=<?php echo $page+1; ?>" class="page-btn <?php echo $page>=$total_pages?'disabled':''; ?>">Next →</a>
                </div>
            </div>
        </div>

        <?php elseif($tab == 'enrollments'): ?>
        <?php
        $total = $conn->query("SELECT COUNT(*) c FROM Enrollments")->fetch_assoc()['c'];
        $total_pages = max(1, ceil($total / $per_page));
        $result = $conn->query("SELECT e.*, CONCAT(s.first_name,' ',s.last_name) AS student_name, c.course_title, CONCAT(i.first_name,' ',i.last_name) AS instructor_name FROM Enrollments e JOIN Students s ON e.student_id=s.student_id JOIN Courses c ON e.course_id=c.course_id JOIN Instructors i ON e.instructor_id=i.instructor_id ORDER BY e.enrollment_id DESC LIMIT $per_page OFFSET $offset");
        ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Student</th><th>Course</th><th>Instructor</th><th>Semester</th><th>Year</th><th>Grade</th><?php if($role=='Admin') echo '<th>Actions</th>'; ?></tr></thead>
                <tbody>
                <?php if($result->num_rows == 0): ?>
                    <tr class="empty-row"><td colspan="8">No enrollments found.</td></tr>
                <?php else: while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge badge-blue"><?php echo $row['enrollment_id']; ?></span></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['instructor_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['semester']); ?></td>
                    <td><?php echo $row['academic_year']; ?></td>
                    <td><?php echo $row['grade'] !== null ? '<span class="badge badge-green">'.$row['grade'].'</span>' : '<span style="color:var(--muted)">—</span>'; ?></td>
                    <?php if($role=='Admin'): ?>
                    <td style="display:flex; gap:6px;">
                        <a href="update_student.php?id=<?php echo $row['student_id']; ?>&role=Admin" class="btn-edit">✏ Grade</a>
                        <a href="delete_enrollment.php?id=<?php echo $row['enrollment_id']; ?>&role=Admin" class="btn-del" onclick="return confirm('Delete this enrollment?')">🗑 Del</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span class="page-info">Showing <?php echo min($offset+1,$total); ?>–<?php echo min($offset+$per_page,$total); ?> of <?php echo $total; ?> enrollments</span>
                <div class="page-btns">
                    <a href="?role=<?php echo $role; ?>&tab=enrollments&page=<?php echo $page-1; ?>" class="page-btn <?php echo $page<=1?'disabled':''; ?>">← Prev</a>
                    <?php for($p=max(1,$page-2);$p<=min($total_pages,$page+2);$p++): ?>
                    <a href="?role=<?php echo $role; ?>&tab=enrollments&page=<?php echo $p; ?>" class="page-btn <?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
                    <?php endfor; ?>
                    <a href="?role=<?php echo $role; ?>&tab=enrollments&page=<?php echo $page+1; ?>" class="page-btn <?php echo $page>=$total_pages?'disabled':''; ?>">Next →</a>
                </div>
            </div>
        </div>

        <?php elseif($tab == 'courses'): ?>
        <?php
        $total = $conn->query("SELECT COUNT(*) c FROM Courses")->fetch_assoc()['c'];
        $total_pages = max(1, ceil($total / $per_page));
        $result = $conn->query("SELECT c.*, d.dept_name FROM Courses c LEFT JOIN Departments d ON c.dept_id=d.dept_id ORDER BY c.course_id DESC LIMIT $per_page OFFSET $offset");
        ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Code</th><th>Title</th><th>Credits</th><th>Department</th><?php if($role=='Admin') echo '<th>Actions</th>'; ?></tr></thead>
                <tbody>
                <?php if($result->num_rows == 0): ?>
                    <tr class="empty-row"><td colspan="6">No courses found.</td></tr>
                <?php else: while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge badge-blue"><?php echo $row['course_id']; ?></span></td>
                    <td><span class="badge badge-amber"><?php echo htmlspecialchars($row['course_code']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td><?php echo $row['credits']; ?></td>
                    <td><?php echo htmlspecialchars($row['dept_name'] ?? 'N/A'); ?></td>
                    <?php if($role=='Admin'): ?>
                    <td>
                        <a href="delete_course.php?id=<?php echo $row['course_id']; ?>&role=Admin" class="btn-del" onclick="return confirm('Delete this course?')">🗑 Del</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span class="page-info">Showing <?php echo min($offset+1,$total); ?>–<?php echo min($offset+$per_page,$total); ?> of <?php echo $total; ?> courses</span>
                <div class="page-btns">
                    <a href="?role=<?php echo $role; ?>&tab=courses&page=<?php echo $page-1; ?>" class="page-btn <?php echo $page<=1?'disabled':''; ?>">← Prev</a>
                    <?php for($p=max(1,$page-2);$p<=min($total_pages,$page+2);$p++): ?>
                    <a href="?role=<?php echo $role; ?>&tab=courses&page=<?php echo $p; ?>" class="page-btn <?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
                    <?php endfor; ?>
                    <a href="?role=<?php echo $role; ?>&tab=courses&page=<?php echo $page+1; ?>" class="page-btn <?php echo $page>=$total_pages?'disabled':''; ?>">Next →</a>
                </div>
            </div>
        </div>

        <?php elseif($tab == 'departments'): ?>
        <?php
        $total = $conn->query("SELECT COUNT(*) c FROM Departments")->fetch_assoc()['c'];
        $total_pages = max(1, ceil($total / $per_page));
        $result = $conn->query("SELECT d.*, COUNT(DISTINCT i.instructor_id) AS instructor_count, COUNT(DISTINCT c.course_id) AS course_count FROM Departments d LEFT JOIN Instructors i ON d.dept_id=i.dept_id LEFT JOIN Courses c ON d.dept_id=c.dept_id GROUP BY d.dept_id ORDER BY d.dept_id DESC LIMIT $per_page OFFSET $offset");
        ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Department Name</th><th>Instructors</th><th>Courses</th><?php if($role=='Admin') echo '<th>Actions</th>'; ?></tr></thead>
                <tbody>
                <?php if($result->num_rows == 0): ?>
                    <tr class="empty-row"><td colspan="5">No departments found.</td></tr>
                <?php else: while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><span class="badge badge-blue"><?php echo $row['dept_id']; ?></span></td>
                    <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                    <td><span class="badge badge-amber"><?php echo $row['instructor_count']; ?></span></td>
                    <td><span class="badge badge-green"><?php echo $row['course_count']; ?></span></td>
                    <?php if($role=='Admin'): ?>
                    <td>
                        <a href="delete_department.php?id=<?php echo $row['dept_id']; ?>&role=Admin" class="btn-del" onclick="return confirm('Delete this department?')">🗑 Del</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span class="page-info">Showing <?php echo min($offset+1,$total); ?>–<?php echo min($offset+$per_page,$total); ?> of <?php echo $total; ?> departments</span>
                <div class="page-btns">
                    <a href="?role=<?php echo $role; ?>&tab=departments&page=<?php echo $page-1; ?>" class="page-btn <?php echo $page<=1?'disabled':''; ?>">← Prev</a>
                    <?php for($p=max(1,$page-2);$p<=min($total_pages,$page+2);$p++): ?>
                    <a href="?role=<?php echo $role; ?>&tab=departments&page=<?php echo $p; ?>" class="page-btn <?php echo $p==$page?'active':''; ?>"><?php echo $p; ?></a>
                    <?php endfor; ?>
                    <a href="?role=<?php echo $role; ?>&tab=departments&page=<?php echo $page+1; ?>" class="page-btn <?php echo $page>=$total_pages?'disabled':''; ?>">Next →</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
