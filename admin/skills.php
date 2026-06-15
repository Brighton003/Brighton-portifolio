<?php
// admin/skills.php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

requireLogin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$error = '';
$success = '';

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: skills.php?success=Deleted successfully');
    exit();
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $proficiency = (int)$_POST['proficiency'];
    $category = sanitize($_POST['category']);
    $icon_class = sanitize($_POST['icon_class']);
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if (!empty($name)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE skills SET name = ?, proficiency = ?, category = ?, icon_class = ? WHERE id = ?");
            $stmt->execute([$name, $proficiency, $category, $icon_class, $id]);
            $success = "Skill updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO skills (name, proficiency, category, icon_class) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $proficiency, $category, $icon_class]);
            $success = "Skill added successfully.";
        }
        $action = 'list';
    } else {
        $error = "Skill name is required.";
    }
}

$skills = getAll($pdo, 'skills', 'category ASC, name ASC');
$editItem = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editItem = getById($pdo, 'skills', $_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills Management | Admin</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../profile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
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
        .form-group input, .form-group select { width: 100%; padding: 10px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: white; }
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
                <li><a href="education.php"><i class="fas fa-graduation-cap"></i> Education</a></li>
                <li><a href="skills.php" class="active"><i class="fas fa-tools"></i> Skills</a></li>
                <li><a href="projects.php"><i class="fas fa-laptop-code"></i> Projects</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li style="margin-top: 2rem;"><a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Skills Management</h1>
                <?php if ($action === 'list'): ?>
                <a href="skills.php?action=add" class="btn primary-btn btn-sm">Add New Skill</a>
                <?php else: ?>
                <a href="skills.php" class="btn secondary-btn btn-sm">Back to List</a>
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
                                <th>Skill Name</th>
                                <th>Category</th>
                                <th>Proficiency</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skills as $item): ?>
                            <tr>
                                <td><i class="<?php echo $item['icon_class']; ?>" style="margin-right: 10px; color: var(--accent-color);"></i> <?php echo $item['name']; ?></td>
                                <td><?php echo $item['category']; ?></td>
                                <td><?php echo $item['proficiency']; ?>%</td>
                                <td class="action-btns">
                                    <a href="skills.php?action=edit&id=<?php echo $item['id']; ?>" class="btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="skills.php?action=delete&id=<?php echo $item['id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="glass slide-up" style="padding: 2rem;">
                    <form action="skills.php" method="POST" class="form-container">
                        <?php if ($editItem): ?>
                            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="name">Skill Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo $editItem ? $editItem['name'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="Programming" <?php echo ($editItem && $editItem['category'] == 'Programming') ? 'selected' : ''; ?>>Programming & Web</option>
                                <option value="Tools" <?php echo ($editItem && $editItem['category'] == 'Tools') ? 'selected' : ''; ?>>Data & Tools</option>
                                <option value="IT Operations" <?php echo ($editItem && $editItem['category'] == 'IT Operations') ? 'selected' : ''; ?>>IT Operations</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="proficiency">Proficiency (%)</label>
                            <input type="number" id="proficiency" name="proficiency" min="0" max="100" value="<?php echo $editItem ? $editItem['proficiency'] : '0'; ?>">
                        </div>
                        <div class="form-group">
                            <label for="icon_class">FontAwesome Icon Class (e.g. fab fa-java)</label>
                            <input type="text" id="icon_class" name="icon_class" value="<?php echo $editItem ? $editItem['icon_class'] : 'fas fa-code'; ?>">
                        </div>
                        <button type="submit" class="btn primary-btn"><?php echo $editItem ? 'Update' : 'Add'; ?> Skill</button>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
