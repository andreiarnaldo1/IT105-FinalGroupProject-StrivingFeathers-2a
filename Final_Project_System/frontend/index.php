<!DOCTYPE html>
<html>
<head>
    <title>EduTrack - Login</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; width: 350px; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #1877f2; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>🪶 EduTrack Portal</h2>
        <p>Select your access level:</p>
        <form action="dashboard.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <select name="role">
                <option value="Admin">Administrator (Full Access)</option>
                <option value="Student">Student (View Only)</option>
            </select>
            <button type="submit">LOGIN</button>
        </form>
    </div>
</body>
</html>