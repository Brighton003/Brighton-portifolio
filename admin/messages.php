<?php
// admin/messages.php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

requireLogin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$success = '';

// Handle Mark as Read
if ($action === 'read' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: messages.php');
    exit();
}

// Handle Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: messages.php?success=Deleted successfully');
    exit();
}

$messages = getAll($pdo, 'messages', 'created_at DESC');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | Admin</title>
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
        
        .message-card { margin-bottom: 1.5rem; padding: 1.5rem; position: relative; }
        .message-card.unread { border-left: 4px solid var(--accent-color); }
        .message-header { display: flex; justify-content: space-between; margin-bottom: 1rem; }
        .sender-info h4 { margin: 0; }
        .sender-info span { font-size: 0.8rem; color: var(--text-secondary); }
        .message-body { color: var(--text-secondary); line-height: 1.6; }
        .message-actions { margin-top: 1rem; display: flex; gap: 15px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1rem; }
        .badge-unread { background: var(--accent-color); color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; margin-left: 10px; }
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
                <li><a href="skills.php"><i class="fas fa-tools"></i> Skills</a></li>
                <li><a href="projects.php"><i class="fas fa-laptop-code"></i> Projects</a></li>
                <li><a href="messages.php" class="active"><i class="fas fa-envelope"></i> Messages</a></li>
                <li style="margin-top: 2rem;"><a href="logout.php" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <header style="margin-bottom: 2rem;">
                <h1>Messages</h1>
                <p style="color: var(--text-secondary);">Contact form submissions from your website.</p>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="msg-success" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71; padding: 10px; border-radius: 8px; margin-bottom: 1rem;"><?php echo $_GET['success']; ?></div>
            <?php endif; ?>

            <div class="messages-list">
                <?php if (empty($messages)): ?>
                    <div class="glass" style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                        No messages yet.
                    </div>
                <?php endif; ?>

                <?php foreach ($messages as $msg): ?>
                <div class="message-card glass slide-up <?php echo !$msg['is_read'] ? 'unread' : ''; ?>">
                    <div class="message-header">
                        <div class="sender-info">
                            <h4><?php echo $msg['name']; ?> <?php if(!$msg['is_read']): ?><span class="badge-unread">NEW</span><?php endif; ?></h4>
                            <span><i class="fas fa-envelope"></i> <?php echo $msg['email']; ?> | <i class="fas fa-clock"></i> <?php echo date('M j, Y H:i', strtotime($msg['created_at'])); ?></span>
                        </div>
                        <div class="subject">
                            <strong>Subject:</strong> <?php echo $msg['subject'] ?: 'No Subject'; ?>
                        </div>
                    </div>
                    <div class="message-body">
                        <?php echo nl2br($msg['message']); ?>
                    </div>
                    <div class="message-actions">
                        <?php if(!$msg['is_read']): ?>
                        <a href="messages.php?action=read&id=<?php echo $msg['id']; ?>" style="color: var(--accent-color); font-size: 0.9rem;"><i class="fas fa-check"></i> Mark as Read</a>
                        <?php endif; ?>
                        <a href="messages.php?action=delete&id=<?php echo $msg['id']; ?>" style="color: #ff4d4d; font-size: 0.9rem;" onclick="return confirm('Delete this message?')"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
