<?php
// admin/index.php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

requireLogin();

// Fetch counts for dashboard
$eduCount = $pdo->query("SELECT COUNT(*) FROM education")->fetchColumn();
$skillCount = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$projectCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$msgCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | DBP Portfolio</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../profile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            position: fixed;
            height: 100vh;
        }
        .admin-main {
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
            background: var(--bg-color);
        }
        .nav-sidebar {
            list-style: none;
            margin-top: 3rem;
        }
        .nav-sidebar li {
            margin-bottom: 1rem;
        }
        .nav-sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }
        .nav-sidebar a:hover, .nav-sidebar a.active {
            background: rgba(255, 255, 255, 0.05);
            color: var(--accent-color);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .stat-card {
            padding: 1.5rem;
            text-align: center;
        }
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }
        .stat-card .label {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .header-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .btn-logout {
            color: #ff4d4d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body data-theme="dark">
    <div class="background-animation"></div>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="logo">DBP<span class="accent">.</span> Admin</div>
            <ul class="nav-sidebar">
                <li><a href="index.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="education.php"><i class="fas fa-graduation-cap"></i> Education</a></li>
                <li><a href="skills.php"><i class="fas fa-tools"></i> Skills</a></li>
                <li><a href="projects.php"><i class="fas fa-laptop-code"></i> Projects</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages <?php if($msgCount > 0): ?><span class="badge" style="background: var(--accent-color); font-size: 0.7rem; padding: 2px 6px; border-radius: 10px;"><?php echo $msgCount; ?></span><?php endif; ?></a></li>
                <li style="margin-top: 2rem;"><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <header class="header-admin">
                <div>
                    <h1>Dashboard</h1>
                    <p style="color: var(--text-secondary);">Welcome back, <?php echo $_SESSION['admin_username']; ?>!</p>
                </div>
                <a href="../index.php" class="btn secondary-btn btn-sm" target="_blank">View Site <i class="fas fa-external-link-alt"></i></a>
            </header>

            <div class="stats-grid">
                <div class="stat-card glass slide-up">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="number"><?php echo $eduCount; ?></span>
                    <span class="label">Education Entries</span>
                </div>
                <div class="stat-card glass slide-up delay-1">
                    <i class="fas fa-tools"></i>
                    <span class="number"><?php echo $skillCount; ?></span>
                    <span class="label">Skills Listed</span>
                </div>
                <div class="stat-card glass slide-up delay-2">
                    <i class="fas fa-laptop-code"></i>
                    <span class="number"><?php echo $projectCount; ?></span>
                    <span class="label">Total Projects</span>
                </div>
                <div class="stat-card glass slide-up delay-3">
                    <i class="fas fa-envelope"></i>
                    <span class="number"><?php echo $msgCount; ?></span>
                    <span class="label">New Messages</span>
                </div>
            </div>

            <div style="margin-top: 3rem;" class="glass slide-up delay-4">
                <div style="padding: 2rem;">
                    <h3>Quick Actions</h3>
                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <a href="projects.php?action=add" class="btn primary-btn btn-sm">Add New Project</a>
                        <a href="skills.php?action=add" class="btn secondary-btn btn-sm">Add New Skill</a>
                        <a href="education.php?action=add" class="btn secondary-btn btn-sm">Add Education</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
