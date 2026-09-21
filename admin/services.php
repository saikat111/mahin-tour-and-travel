<?php
/**
 * Mahin Travel & Tours - Services Management CRUD
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();
$action = cleanInput($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);

// Handle POST: Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $title = cleanInput($_POST['title'] ?? '');
        $slug = cleanInput($_POST['slug'] ?? '');
        if (empty($slug)) $slug = slugify($title);
        $shortDesc = cleanInput($_POST['short_desc'] ?? '');
        $fullDesc = trim($_POST['full_desc'] ?? '');
        $iconName = cleanInput($_POST['icon_name'] ?? 'plane');
        $featuredImage = cleanInput($_POST['featured_image'] ?? '');
        $category = cleanInput($_POST['category'] ?? 'General');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        $metaTitle = cleanInput($_POST['meta_title'] ?? '');
        $metaDesc = cleanInput($_POST['meta_desc'] ?? '');

        if (empty($title) || empty($shortDesc)) {
            setFlashMessage('error', 'Service title and short description are required.');
        } else {
            try {
                if ($action === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO services (title, slug, short_desc, full_desc, icon_name, featured_image, category, sort_order, status, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $slug, $shortDesc, $fullDesc, $iconName, $featuredImage, $category, $sortOrder, $status, $metaTitle, $metaDesc]);
                    setFlashMessage('success', 'Service created successfully.');
                } elseif ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare("UPDATE services SET title = ?, slug = ?, short_desc = ?, full_desc = ?, icon_name = ?, featured_image = ?, category = ?, sort_order = ?, status = ?, meta_title = ?, meta_desc = ? WHERE id = ?");
                    $stmt->execute([$title, $slug, $shortDesc, $fullDesc, $iconName, $featuredImage, $category, $sortOrder, $status, $metaTitle, $metaDesc, $id]);
                    setFlashMessage('success', 'Service updated successfully.');
                }
                header("Location: " . ADMIN_URL . "/services.php");
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Database error: ' . $e->getMessage());
            }
        }
    }
}

// Handle Status Toggle & Delete
if ($action === 'toggle' && $id > 0) {
    $cur = $pdo->prepare("SELECT status FROM services WHERE id = ?");
    $cur->execute([$id]);
    $st = $cur->fetchColumn();
    $newSt = $st == 1 ? 0 : 1;
    $pdo->prepare("UPDATE services SET status = ? WHERE id = ?")->execute([$newSt, $id]);
    setFlashMessage('success', 'Service status updated.');
    header("Location: " . ADMIN_URL . "/services.php");
    exit;
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'Service deleted successfully.');
    header("Location: " . ADMIN_URL . "/services.php");
    exit;
}

// Fetch Item for Edit
$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC")->fetchAll();

$activeAdminPage = 'services';
$adminPageTitle = 'Services Management';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Travel & Visa Services Management</h1>
        <p class="page-subtitle">Add, update, publish or reorganize services offered to clients.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/services.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add New Service</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/services.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to Services List</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <!-- Create / Edit Form -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><?= $action === 'create' ? 'Create New Service' : 'Edit Service: ' . e($editItem['title']) ?></h2>
        </div>

        <form action="<?= ADMIN_URL ?>/services.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Service Title *</label>
                    <input type="text" name="title" class="admin-input" value="<?= e($editItem['title'] ?? '') ?>" required>
                </div>

                <div class="admin-form-group">
                    <label>URL Slug</label>
                    <input type="text" name="slug" class="admin-input" value="<?= e($editItem['slug'] ?? '') ?>" placeholder="leave blank to auto-generate">
                </div>

                <div class="admin-form-group">
                    <label>Category</label>
                    <input type="text" name="category" class="admin-input" value="<?= e($editItem['category'] ?? 'General') ?>" placeholder="e.g. Visas, Ticketing, Pilgrimage, Tours">
                </div>

                <div class="admin-form-group">
                    <label>Icon Style</label>
                    <select name="icon_name" class="admin-select">
                        <?php
                        $icons = ['plane-departure' => 'Flight / Air Travel', 'passport' => 'Visa / Passport', 'kaaba' => 'Kaaba / Pilgrimage', 'graduation-cap' => 'Student Visa', 'map-marked-alt' => 'Tour / Map', 'stamp' => 'Attestation / Notary', 'shield-check' => 'Security / Shield'];
                        $curIcon = $editItem['icon_name'] ?? 'plane-departure';
                        foreach ($icons as $k => $lbl):
                        ?>
                            <option value="<?= $k ?>" <?= $curIcon === $k ? 'selected' : '' ?>><?= $lbl ?> (<?= $k ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Short Description (Card Summary) *</label>
                <textarea name="short_desc" class="admin-textarea" style="min-height: 70px;" required><?= e($editItem['short_desc'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group">
                <label>Full Specification & Detailed Overview</label>
                <textarea name="full_desc" class="admin-textarea" style="min-height: 160px;"><?= e($editItem['full_desc'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Featured Image Asset Path</label>
                    <input type="text" name="featured_image" class="admin-input" value="<?= e($editItem['featured_image'] ?? 'assets/images/services/air-ticketing.jpg') ?>">
                </div>

                <div class="admin-form-group">
                    <label>Sort Order Priority</label>
                    <input type="number" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? 0) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>SEO Meta Title</label>
                    <input type="text" name="meta_title" class="admin-input" value="<?= e($editItem['meta_title'] ?? '') ?>">
                </div>

                <div class="admin-form-group">
                    <label>SEO Meta Description</label>
                    <input type="text" name="meta_desc" class="admin-input" value="<?= e($editItem['meta_desc'] ?? '') ?>">
                </div>
            </div>

            <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                <input type="checkbox" name="status" id="srvStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <label for="srvStatus" style="cursor: pointer;">Publish and make visible on live website</label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span><?= $action === 'create' ? 'Create Service' : 'Save Changes' ?></span>
                </button>
            </div>
        </form>
    </div>
<?php else: ?>
    <!-- Services List Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Icon</th>
                        <th>Title & Category</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $srv): ?>
                        <tr>
                            <td style="width: 60px; color: var(--admin-muted); font-weight: 600;">
                                #<?= e($srv['sort_order']) ?>
                            </td>
                            <td style="width: 50px;">
                                <div style="width: 34px; height: 34px; border-radius: 6px; background: rgba(185,139,98,0.15); color: var(--admin-bronze); display: flex; align-items: center; justify-content: center;">
                                    <?= renderIcon($srv['icon_name'], '', 18) ?>
                                </div>
                            </td>
                            <td>
                                <strong><?= e($srv['title']) ?></strong><br>
                                <span class="badge-status" style="background: #F1F5F9; color: var(--admin-muted); font-size: 0.72rem;"><?= e($srv['category']) ?></span>
                            </td>
                            <td style="font-family: monospace; font-size: 0.8rem; color: var(--admin-muted);">
                                <?= e($srv['slug']) ?>
                            </td>
                            <td>
                                <a href="<?= ADMIN_URL ?>/services.php?action=toggle&id=<?= $srv['id'] ?>" title="Click to toggle status">
                                    <?php if ($srv['status'] == 1): ?>
                                        <span class="badge-status badge-active">Published</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-inactive">Draft</span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/services.php?action=edit&id=<?= $srv['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">
                                        Edit
                                    </a>
                                    <a href="<?= url('service-detail.php?slug=' . urlencode($srv['slug'])) ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-outline btn-admin-sm" title="View Public Page">
                                        <?= renderIcon('plane', '', 12) ?>
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/services.php?action=delete&id=<?= $srv['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this service?">
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
