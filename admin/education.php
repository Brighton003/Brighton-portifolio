<?php
// admin/education.php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

requireLogin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$error = '';
$success = '';

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM education WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: education.php?success=Deleted successfully');
    exit();
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $institution = sanitize($_POST['institution']);
    $course = sanitize($_POST['course']);
    $duration = sanitize($_POST['duration']);
    $description = sanitize($_POST['description']);
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if (!empty($institution) && !empty($course) && !empty($duration)) {
        if ($id) {
            // Update
            $stmt = $pdo->prepare("UPDATE education SET institution = ?, course = ?, duration = ?, description = ? WHERE id = ?");
            $stmt->execute([$institution, $course, $duration, $description, $id]);
            $success = "Education entry updated successfully.";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO education (institution, course, duration, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$institution, $course, $duration, $description]);
            $success = "Education entry added successfully.";
        }
        $action = 'list';
    } else {
        $error = "Please fill in all required fields.";
    }
}

// Fetch data
$education = getAll($pdo, 'education', 'duration DESC');
$editItem = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editItem = getById($pdo, 'education', $_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Management | Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../profile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reusing common admin layout styles ... */
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); border-right: 1px solid rgba(255, 255, 255, 0.1); padding: 2rem; position: fixed; height: 100vh; }
        .admin-main { margin-left: 250px; flex: 1; padding: 2rem; background: var(--bg-color); }
        .nav-sidebar { list-style: none; margin-top: 3rem; }
        .nav-sidebar li { margin-bottom: 1rem; }
        .nav-sidebar a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 8px; color: var(--text-secondary); transition: all 0.3s ease; }
        .nav-sidebar a:hover, .nav-sidebar a.active { background: rgba(255, 255, 255, 0.05); color: var(--accent-color); }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        .data-table th, .data-table td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .data-table th { color: var(--accent-color); font-weight: 600; }
        
        .form-container { max-width: 600px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 8px; font-size: 0.9rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: white; }
        
        .action-btns { display: flex; gap: 8px; }
        .btn-edit { color: #3498db; }
        .btn-delete { color: #e74c3c; }
        .msg-success { background: rgba(46, 204, 113, 0.1); color: #2ecc71; padding: 10px; border-radius: 8px; margin-bottom: 1rem; }
        .msg-error { background: rgba(231, 76, 60, 0.1); color: #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body data-theme="dark">
    <div class="background-animation"></div>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="logo">DBP<span class="accent">.</span> Admin</div>
            <ul class="nav-sidebar">
                <li><a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="education.php" class="active"><i class="fas fa-graduation-cap"></i> Education</a></li>
                <li><a href="skills.php"><i class="fas fa-tools"></i> Skills</a></li>
                <li><a href="projects.php"><i class="fas fa-laptop-code"></i> Projects</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li style="margin-top: 2rem;"><a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Education Management</h1>
                <?php if ($action === 'list'): ?>
                <a href="education.php?action=add" class="btn primary-btn btn-sm">Add New Entry</a>
                <?php else: ?>
                <a href="education.php" class="btn secondary-btn btn-sm">Back to List</a>
                <?php endif; ?>
            </header>

            <?php if ($success || isset($_GET['success'])): ?>
                <div class="msg-success"><?php echo $success ?: $_GET['success']; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="msg-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <div class="glass slide-up" style="padding: 1.5rem;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                <th>Course</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($education as $item): ?>
                            <tr>
                                <td><?php echo $item['institution']; ?></td>
                                <td><?php echo $item['course']; ?></td>
                                <td><?php echo $item['duration']; ?></td>
                                <td class="action-btns">
                                    <a href="education.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="education.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="glass slide-up" style="padding: 2rem;">
                    <form action="education.php" method="POST" class="form-container">
                        <?php if ($editItem): ?>
                            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="institution">Institution Name *</label>
                            <input type="text" id="institution" name="institution" value="<?php echo $editItem ? $editItem['institution'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="course">Course / Degree *</label>
                            <input type="text" id="course" name="course" value="<?php echo $editItem ? $editItem['course'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">Year(s) (e.g. 2023 - 2027) *</label>
                            <input type="text" id="duration" name="duration" value="<?php echo $editItem ? $editItem['duration'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4"><?php echo $editItem ? $editItem['description'] : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn primary-btn"><?php echo $editItem ? 'Update' : 'Add'; ?> Entry</button>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
