<?php
/**
 * Mahin Travel & Tours - Secure Administrative Login
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: " . ADMIN_URL . "/index.php");
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errorMessage = 'Security validation failed. Please reload and try again.';
    } else {
        $username = cleanInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $errorMessage = 'Please provide both username and password.';
        } else {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = $admin['role'];

                // Update last login
                try {
                    $upd = $pdo->prepare("UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                    $upd->execute([$admin['id']]);
                } catch (Exception $e) {}

                header("Location: " . ADMIN_URL . "/index.php");
                exit;
            } else {
                $errorMessage = 'Invalid administrative credentials. Please verify your username and password.';
            }
        }
    }
}

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);
$bengaliName = getSetting('bengali_name', DEFAULT_BENGALI_NAME);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | <?= e($siteName) ?></title>
    <link rel="icon" type="image/png" href="<?= url('assets/images/favicon.png') ?>">
    <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap">
    <style>
        body {
            background: #06152B;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .login-box {
            background: #FFFFFF;
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

<div class="login-box">
    <div style="text-align: center; margin-bottom: 2rem;">
        <img src="<?= url('assets/images/logo.png') ?>" alt="Logo" style="height: 60px; margin: 0 auto 0.75rem auto; display: block;">
        <h2 style="font-family: 'Outfit', sans-serif; color: var(--admin-navy); font-size: 1.4rem;">MAHIN TRAVEL & TOURS</h2>
        <span style="font-size: 0.8rem; color: var(--admin-muted); text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600;">
            Administrative CMS Portal
        </span>
    </div>

    <?php if (!empty($errorMessage)): ?>
        <div class="admin-alert admin-alert-error" style="font-size: 0.85rem; padding: 0.65rem 1rem;">
            <?= e($errorMessage) ?>
        </div>
    <?php endif; ?>

    <form action="<?= ADMIN_URL ?>/login.php" method="POST">
        <?= csrf_field() ?>

        <div class="admin-form-group">
            <label>Username</label>
            <input type="text" name="username" class="admin-input" placeholder="admin" required autofocus>
        </div>

        <div class="admin-form-group" style="margin-bottom: 1.75rem;">
            <label>Password</label>
            <input type="password" name="password" class="admin-input" placeholder="••••••••••••" required>
        </div>

        <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; justify-content: center; height: 44px; font-size: 0.95rem;">
            <span>Sign In to Admin Panel</span>
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.75rem; font-size: 0.8rem; color: var(--admin-muted);">
        <a href="<?= url() ?>" style="color: var(--admin-bronze); text-decoration: underline;">&larr; Return to Public Website</a>
    </div>
</div>

</body>
</html>
