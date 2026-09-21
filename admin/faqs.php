<?php
/**
 * Mahin Travel & Tours - FAQs Management CRUD
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();
$action = cleanInput($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $category = cleanInput($_POST['category'] ?? 'general');
        $question = cleanInput($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            setFlashMessage('error', 'Question and answer are required.');
        } else {
            try {
                if ($action === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO faqs (category, question, answer, sort_order, status) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$category, $question, $answer, $sortOrder, $status]);
                    setFlashMessage('success', 'FAQ question created.');
                } elseif ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare("UPDATE faqs SET category = ?, question = ?, answer = ?, sort_order = ?, status = ? WHERE id = ?");
                    $stmt->execute([$category, $question, $answer, $sortOrder, $status, $id]);
                    setFlashMessage('success', 'FAQ updated.');
                }
                header("Location: " . ADMIN_URL . "/faqs.php");
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Database error: ' . $e->getMessage());
            }
        }
    }
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM faqs WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'FAQ deleted.');
    header("Location: " . ADMIN_URL . "/faqs.php");
    exit;
}

$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY category ASC, sort_order ASC, id ASC")->fetchAll();

$activeAdminPage = 'faqs';
$adminPageTitle = 'FAQs Management';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Frequently Asked Questions (FAQ)</h1>
        <p class="page-subtitle">Add, edit, and organize customer questions displayed on the homepage and FAQ portal.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/faqs.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add New FAQ</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/faqs.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to FAQ List</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <div class="admin-card">
        <form action="<?= ADMIN_URL ?>/faqs.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Category *</label>
                    <select name="category" class="admin-select">
                        <?php
                        $cats = ['visa' => 'Visa Processing', 'tickets' => 'Air Ticketing', 'tours' => 'Tours & Packages', 'general' => 'General / Office'];
                        $curCat = $editItem['category'] ?? 'general';
                        foreach ($cats as $k => $c):
                        ?>
                            <option value="<?= $k ?>" <?= $curCat === $k ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? 0) ?>">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Question *</label>
                <input type="text" name="question" class="admin-input" value="<?= e($editItem['question'] ?? '') ?>" required>
            </div>

            <div class="admin-form-group">
                <label>Answer *</label>
                <textarea name="answer" class="admin-textarea" style="min-height: 120px;" required><?= e($editItem['answer'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="status" id="faqStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <label for="faqStatus" style="cursor: pointer;">Active and visible on site</label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span>Save FAQ</span>
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
                        <th>Category</th>
                        <th>Question</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $f): ?>
                        <tr>
                            <td>
                                <span class="badge-status" style="background: rgba(185,139,98,0.15); color: var(--admin-bronze); text-transform: uppercase; font-size: 0.72rem;">
                                    <?= e($f['category']) ?>
                                </span>
                            </td>
                            <td><strong><?= e($f['question']) ?></strong></td>
                            <td>
                                <?php if ($f['status'] == 1): ?>
                                    <span class="badge-status badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-inactive">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/faqs.php?action=edit&id=<?= $f['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">Edit</a>
                                    <a href="<?= ADMIN_URL ?>/faqs.php?action=delete&id=<?= $f['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this question?">&times;</a>
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
