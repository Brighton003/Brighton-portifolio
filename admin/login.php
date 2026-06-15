<?php
// admin/login.php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | DBP Portfolio</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../profile-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-color);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            text-align: center;
        }
        .login-card h2 {
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        .error-msg {
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .login-form .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .login-form input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }
        .login-form input:focus {
            outline: none;
            border-color: var(--accent-color);
            background: rgba(255, 255, 255, 0.1);
        }
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
    </style>
</head>
<body data-theme="dark">
    <div class="background-animation"></div>
    <div class="login-container">
        <div class="login-card glass slide-up">
            <h2>Admin <span class="accent">Login</span></h2>
            
            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn primary-btn block-btn">Login</button>
            </form>
            <p style="margin-top: 2rem; font-size: 0.8rem; color: var(--text-secondary);">
                <a href="../index.php" style="color: var(--accent-color);">Back to Website</a>
            </p>
        </div>
    </div>
</body>
</html>
