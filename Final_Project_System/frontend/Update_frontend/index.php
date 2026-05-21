<!DOCTYPE html>
<html>
<head>
    <title>EduTrack - Login</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #dde1e9;
            border-radius: 12px;
            padding: 44px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 52px; height: 52px;
            background: #1877f2;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 24px;
            color: white;
        }
        .logo h1 {
            font-family: Arial, sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.3px;
        }
        .logo p {
            font-size: 13px;
            color: #6b7280;
            margin-top: 5px;
        }

        .field { margin-bottom: 14px; }
        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #374151;
            margin-bottom: 7px;
            font-family: Arial, sans-serif;
        }
        input {
            width: 100%;
            padding: 11px 14px;
            background: #ffffff;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            color: #111827;
            font-family: Arial, sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            border-color: #1877f2;
            box-shadow: 0 0 0 3px rgba(24,119,242,0.1);
        }
        input::placeholder { color: #9ca3af; }

        /* Role pills */
        .role-pills {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 22px;
        }
        .role-pill input[type="radio"] { display: none; }
        .role-pill label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            padding: 14px 10px;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s;
            text-transform: none;
            letter-spacing: 0;
        }
        .role-pill label .pill-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: #e5e7eb;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 4px;
        }
        .role-pill label .pill-icon svg { color: #6b7280; }
        .role-pill label .pill-title {
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
        }
        .role-pill label .pill-sub {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #9ca3af;
        }
        .role-pill input[type="radio"]:checked + label {
            border-color: #1877f2;
            background: #eff6ff;
        }
        .role-pill input[type="radio"]:checked + label .pill-icon {
            background: #1877f2;
        }
        .role-pill input[type="radio"]:checked + label .pill-icon svg { color: #ffffff; }
        .role-pill input[type="radio"]:checked + label .pill-title { color: #1877f2; }
        .role-pill input[type="radio"]:checked + label .pill-sub { color: #93c5fd; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: #1877f2;
            color: white;
            border: none;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: background 0.15s, box-shadow 0.15s;
            margin-top: 2px;
        }
        .btn-login:hover {
            background: #1565d8;
            box-shadow: 0 4px 14px rgba(24,119,242,0.3);
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 24px 0 16px;
        }

        .footer-note {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <div class="logo-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <h1>EduTrack Portal</h1>
            <p>Student Management System</p>
        </div>

        <form action="dashboard.php" method="POST">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>

            <div class="field" style="margin-bottom:16px;">
                <label>Access Level</label>
            </div>
            <div class="role-pills">
                <div class="role-pill">
                    <input type="radio" name="role" id="role-admin" value="Admin" checked>
                    <label for="role-admin">
                        <span class="pill-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <span class="pill-title">Admin</span>
                        <span class="pill-sub">Full Access</span>
                    </label>
                </div>
                <div class="role-pill">
                    <input type="radio" name="role" id="role-student" value="Student">
                    <label for="role-student">
                        <span class="pill-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <span class="pill-title">Student</span>
                        <span class="pill-sub">View Only</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-login">LOGIN</button>
        </form>

        <hr class="divider">
        <p class="footer-note">EduTrack &copy; 2025 &mdash; Academic Portal</p>
    </div>
</body>
</html>
