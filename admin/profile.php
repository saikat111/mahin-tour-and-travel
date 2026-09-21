<?php
/**
 * Mahin Travel & Tours - Admin Profile & Password Security
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $fullName = cleanInput($_POST['full_name'] ?? '');
        $email = cleanInput($_POST['email'] ?? '');
        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($fullName) || empty($email)) {
            setFlashMessage('error', 'Name and email address are required.');
        } else {
            try {
                // Fetch current admin hash
                $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
                $stmt->execute([$currentAdminId]);
                $curHash = $stmt->fetchColumn();

                if (!empty($newPass)) {
                    if (!password_verify($currentPass, $curHash)) {
                        setFlashMessage('error', 'The current password you entered is incorrect.');
                    } elseif (strlen($newPass) < 8) {
                        setFlashMessage('error', 'New password must be at least 8 characters long.');
                    } elseif ($newPass !== $confirmPass) {
                        setFlashMessage('error', 'New password and confirmation do not match.');
                    } else {
                        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                        $upd = $pdo->prepare("UPDATE admins SET full_name = ?, email = ?, password_hash = ? WHERE id = ?");
                        $upd->execute([$fullName, $email, $newHash, $currentAdminId]);
                        $_SESSION['admin_name'] = $fullName;
                        setFlashMessage('success', 'Profile and password updated successfully.');
                    }
                } else {
                    $upd = $pdo->prepare("UPDATE admins SET full_name = ?, email = ? WHERE id = ?");
                    $upd->execute([$fullName, $email, $currentAdminId]);
                    $_SESSION['admin_name'] = $fullName;
                    setFlashMessage('success', 'Profile details updated.');
                }
                header("Location: " . ADMIN_URL . "/profile.php");
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Database error: ' . $e->getMessage());
            }
        }
    }
}

// Fetch current admin
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$currentAdminId]);
$admin = $stmt->fetch();

$activeAdminPage = 'profile';
$adminPageTitle = 'Security Profile';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Admin Account & Security</h1>
        <p class="page-subtitle">Manage administrative login credentials, email address, and access security.</p>
    </div>
</div>

<div class="admin-card" style="max-width: 680px;">
    <form action="<?= ADMIN_URL ?>/profile.php" method="POST">
        <?= csrf_field() ?>

        <div class="admin-form-group">
            <label>Username</label>
            <input type="text" class="admin-input" value="<?= e($admin['username'] ?? 'admin') ?>" disabled style="background: #F1F5F9; cursor: not-allowed;">
            <span class="help-text">Username is fixed for primary security</span>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" class="admin-input" value="<?= e($admin['full_name'] ?? '') ?>" required>
            </div>

            <div class="admin-form-group">
                <label>Admin Notification Email *</label>
                <input type="email" name="email" class="admin-input" value="<?= e($admin['email'] ?? '') ?>" required>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid var(--admin-border); margin: 1.5rem 0;">

        <h3 style="font-size: 1.15rem; color: var(--admin-navy); margin-bottom: 0.5rem;">Change Password (Optional)</h3>
        <p style="font-size: 0.85rem; color: var(--admin-muted); margin-bottom: 1.25rem;">Leave blank if you do not wish to change your password.</p>

        <div class="admin-form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="admin-input" placeholder="Required only if changing password">
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>New Password (Min 8 Characters)</label>
                <input type="password" name="new_password" class="admin-input">
            </div>

            <div class="admin-form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="admin-input">
            </div>
        </div>

        <div style="margin-top: 1.75rem;">
            <button type="submit" class="btn-admin btn-admin-gold">
                <?= renderIcon('check', '', 16) ?>
                <span>Update Profile & Security</span>
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
