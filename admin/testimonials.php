<?php
/**
 * Mahin Travel & Tours - Testimonials CRUD
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();
$action = cleanInput($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $name = cleanInput($_POST['client_name'] ?? '');
        $role = cleanInput($_POST['client_role_or_location'] ?? 'Traveler');
        $comment = trim($_POST['comment'] ?? '');
        $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;

        if (empty($name) || empty($comment)) {
            setFlashMessage('error', 'Client name and feedback comment are required.');
        } else {
            if ($action === 'create') {
                $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, client_role_or_location, comment, rating, sort_order, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $comment, $rating, $sortOrder, $status]);
                setFlashMessage('success', 'Testimonial added.');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE testimonials SET client_name = ?, client_role_or_location = ?, comment = ?, rating = ?, sort_order = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $role, $comment, $rating, $sortOrder, $status, $id]);
                setFlashMessage('success', 'Testimonial updated.');
            }
            header("Location: " . ADMIN_URL . "/testimonials.php");
            exit;
        }
    }
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'Testimonial deleted.');
    header("Location: " . ADMIN_URL . "/testimonials.php");
    exit;
}

$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll();

$activeAdminPage = 'testimonials';
$adminPageTitle = 'Testimonials';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Client Feedback & Testimonials</h1>
        <p class="page-subtitle">Manage client reviews, feedback quotes, and star ratings shown on the homepage.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/testimonials.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Testimonial</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/testimonials.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to Testimonials</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <div class="admin-card">
        <form action="<?= ADMIN_URL ?>/testimonials.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Client Name *</label>
                    <input type="text" name="client_name" class="admin-input" value="<?= e($editItem['client_name'] ?? '') ?>" required>
                </div>

                <div class="admin-form-group">
                    <label>Role / Location</label>
                    <input type="text" name="client_role_or_location" class="admin-input" value="<?= e($editItem['client_role_or_location'] ?? 'Traveler, Gazipur') ?>">
                </div>

                <div class="admin-form-group">
                    <label>Rating (1 to 5 Stars)</label>
                    <select name="rating" class="admin-select">
                        <?php for ($r = 5; $r >= 1; $r--): ?>
                            <option value="<?= $r ?>" <?= (($editItem['rating'] ?? 5) == $r) ? 'selected' : '' ?>><?= $r ?> Stars</option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Client Feedback Quote *</label>
                <textarea name="comment" class="admin-textarea" style="min-height: 100px;" required><?= e($editItem['comment'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="status" id="tstStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <label for="tstStatus" style="cursor: pointer;">Active and visible on homepage</label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span>Save Testimonial</span>
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
                        <th>Client</th>
                        <th>Location / Role</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonials as $t): ?>
                        <tr>
                            <td><strong><?= e($t['client_name']) ?></strong></td>
                            <td><?= e($t['client_role_or_location']) ?></td>
                            <td><?= renderRatingStars((int)$t['rating']) ?></td>
                            <td style="font-style: italic; color: var(--admin-muted); max-width: 380px;">"<?= e($t['comment']) ?>"</td>
                            <td>
                                <?php if ($t['status'] == 1): ?>
                                    <span class="badge-status badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-inactive">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/testimonials.php?action=edit&id=<?= $t['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">Edit</a>
                                    <a href="<?= ADMIN_URL ?>/testimonials.php?action=delete&id=<?= $t['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this testimonial?">&times;</a>
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
