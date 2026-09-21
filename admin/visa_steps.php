<?php
/**
 * Mahin Travel & Tours - Visa Process Steps Manager
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();
$action = cleanInput($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);

// Handle POST: Create or Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $stepNumber = (int)($_POST['step_number'] ?? 1);
        $title = cleanInput($_POST['title'] ?? '');
        $desc = cleanInput($_POST['description'] ?? '');
        $icon = cleanInput($_POST['icon_name'] ?? 'file-text');
        $status = isset($_POST['status']) ? 1 : 0;

        if (empty($title) || empty($desc)) {
            setFlashMessage('error', 'Step title and description are required.');
        } else {
            if ($action === 'create') {
                $stmt = $pdo->prepare("INSERT INTO visa_process_steps (step_number, title, description, icon_name, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$stepNumber, $title, $desc, $icon, $status]);
                setFlashMessage('success', 'Process step created.');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE visa_process_steps SET step_number = ?, title = ?, description = ?, icon_name = ?, status = ? WHERE id = ?");
                $stmt->execute([$stepNumber, $title, $desc, $icon, $status, $id]);
                setFlashMessage('success', 'Process step updated.');
            }
            header("Location: " . ADMIN_URL . "/visa_steps.php");
            exit;
        }
    }
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM visa_process_steps WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'Step deleted.');
    header("Location: " . ADMIN_URL . "/visa_steps.php");
    exit;
}

$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM visa_process_steps WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$steps = $pdo->query("SELECT * FROM visa_process_steps ORDER BY step_number ASC, id ASC")->fetchAll();

$activeAdminPage = 'visa_steps';
$adminPageTitle = 'Visa Process Steps';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">5-Step Visa Process Roadmap</h1>
        <p class="page-subtitle">Configure the procedural roadmap steps shown on the homepage and visa portal.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/visa_steps.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Roadmap Step</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/visa_steps.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to Steps List</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <div class="admin-card">
        <form action="<?= ADMIN_URL ?>/visa_steps.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Step Number *</label>
                    <input type="number" name="step_number" class="admin-input" value="<?= e($editItem['step_number'] ?? 1) ?>" min="1" max="20" required>
                </div>

                <div class="admin-form-group">
                    <label>Step Headline Title *</label>
                    <input type="text" name="title" class="admin-input" value="<?= e($editItem['title'] ?? '') ?>" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Step Description *</label>
                <textarea name="description" class="admin-textarea" style="min-height: 80px;" required><?= e($editItem['description'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="status" id="stStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <label for="stStatus" style="cursor: pointer;">Active and visible on roadmap</label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span>Save Step</span>
                </button>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Step #</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($steps as $st): ?>
                        <tr>
                            <td style="font-weight: 800; color: var(--admin-navy); font-size: 1.1rem; width: 80px;">
                                Step <?= e($st['step_number']) ?>
                            </td>
                            <td>
                                <strong><?= e($st['title']) ?></strong>
                            </td>
                            <td style="color: var(--admin-muted); font-size: 0.85rem; max-width: 420px;">
                                <?= e($st['description']) ?>
                            </td>
                            <td>
                                <?php if ($st['status'] == 1): ?>
                                    <span class="badge-status badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/visa_steps.php?action=edit&id=<?= $st['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">
                                        Edit
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/visa_steps.php?action=delete&id=<?= $st['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this step?">
                                        &times;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
