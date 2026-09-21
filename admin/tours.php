<?php
/**
 * Mahin Travel & Tours - Tour Packages & Umrah CRUD
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();
$action = cleanInput($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);

// Fetch destinations for dropdown
$destinations = $pdo->query("SELECT id, title FROM destinations WHERE status = 1 ORDER BY title ASC")->fetchAll();

// Handle POST: Create or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $title = cleanInput($_POST['title'] ?? '');
        $slug = cleanInput($_POST['slug'] ?? '');
        if (empty($slug)) $slug = slugify($title);
        $destId = !empty($_POST['destination_id']) ? (int)$_POST['destination_id'] : null;
        $days = (int)($_POST['duration_days'] ?? 4);
        $nights = (int)($_POST['duration_nights'] ?? 3);
        $priceText = cleanInput($_POST['price_text'] ?? 'Contact for Price');
        $featuredImage = cleanInput($_POST['featured_image'] ?? 'assets/images/tours/dubai-package.jpg');
        $overview = trim($_POST['overview'] ?? '');
        $inclusions = trim($_POST['inclusions'] ?? '');
        $exclusions = trim($_POST['exclusions'] ?? '');
        $itineraryJson = trim($_POST['itinerary_json'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        $metaTitle = cleanInput($_POST['meta_title'] ?? '');
        $metaDesc = cleanInput($_POST['meta_desc'] ?? '');

        if (empty($title) || empty($overview)) {
            setFlashMessage('error', 'Tour package title and overview are required.');
        } else {
            try {
                if ($action === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO tours (title, slug, destination_id, duration_days, duration_nights, price_text, featured_image, overview, itinerary_json, inclusions, exclusions, is_featured, sort_order, status, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $slug, $destId, $days, $nights, $priceText, $featuredImage, $overview, $itineraryJson, $inclusions, $exclusions, $isFeatured, $sortOrder, $status, $metaTitle, $metaDesc]);
                    setFlashMessage('success', 'Tour package created successfully.');
                } elseif ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare("UPDATE tours SET title = ?, slug = ?, destination_id = ?, duration_days = ?, duration_nights = ?, price_text = ?, featured_image = ?, overview = ?, itinerary_json = ?, inclusions = ?, exclusions = ?, is_featured = ?, sort_order = ?, status = ?, meta_title = ?, meta_desc = ? WHERE id = ?");
                    $stmt->execute([$title, $slug, $destId, $days, $nights, $priceText, $featuredImage, $overview, $itineraryJson, $inclusions, $exclusions, $isFeatured, $sortOrder, $status, $metaTitle, $metaDesc, $id]);
                    setFlashMessage('success', 'Tour package updated successfully.');
                }
                header("Location: " . ADMIN_URL . "/tours.php");
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Database error: ' . $e->getMessage());
            }
        }
    }
}

// Handle Status Toggle & Delete
if ($action === 'toggle' && $id > 0) {
    $cur = $pdo->prepare("SELECT status FROM tours WHERE id = ?");
    $cur->execute([$id]);
    $st = $cur->fetchColumn();
    $newSt = $st == 1 ? 0 : 1;
    $pdo->prepare("UPDATE tours SET status = ? WHERE id = ?")->execute([$newSt, $id]);
    setFlashMessage('success', 'Tour status updated.');
    header("Location: " . ADMIN_URL . "/tours.php");
    exit;
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM tours WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'Tour package deleted.');
    header("Location: " . ADMIN_URL . "/tours.php");
    exit;
}

$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$tours = $pdo->query("SELECT * FROM tours ORDER BY is_featured DESC, sort_order ASC, id ASC")->fetchAll();

$activeAdminPage = 'tours';
$adminPageTitle = 'Tour Packages';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Tours & Umrah Packages Management</h1>
        <p class="page-subtitle">Configure holiday packages, itineraries, duration, inclusions, and custom pricing labels.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/tours.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Tour Package</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/tours.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to Packages List</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><?= $action === 'create' ? 'Create New Tour Package' : 'Edit Tour: ' . e($editItem['title']) ?></h2>
        </div>

        <form action="<?= ADMIN_URL ?>/tours.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Tour Package Title *</label>
                    <input type="text" name="title" class="admin-input" value="<?= e($editItem['title'] ?? '') ?>" required>
                </div>

                <div class="admin-form-group">
                    <label>URL Slug</label>
                    <input type="text" name="slug" class="admin-input" value="<?= e($editItem['slug'] ?? '') ?>" placeholder="leave blank to auto-generate">
                </div>

                <div class="admin-form-group">
                    <label>Destination Hub</label>
                    <select name="destination_id" class="admin-select">
                        <option value="">-- General / Multi-Destination --</option>
                        <?php foreach ($destinations as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= (($editItem['destination_id'] ?? 0) == $d['id']) ? 'selected' : '' ?>>
                                <?= e($d['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Duration Days</label>
                    <input type="number" name="duration_days" class="admin-input" value="<?= e($editItem['duration_days'] ?? 5) ?>" min="1">
                </div>

                <div class="admin-form-group">
                    <label>Duration Nights</label>
                    <input type="number" name="duration_nights" class="admin-input" value="<?= e($editItem['duration_nights'] ?? 4) ?>" min="0">
                </div>

                <div class="admin-form-group">
                    <label>Price Display Label</label>
                    <input type="text" name="price_text" class="admin-input" value="<?= e($editItem['price_text'] ?? 'Contact for Price') ?>">
                    <span class="help-text">e.g. "Contact for Price", "Request Details", or "Starting BDT 45,000"</span>
                </div>

                <div class="admin-form-group">
                    <label>Featured Image Asset Path</label>
                    <input type="text" name="featured_image" class="admin-input" value="<?= e($editItem['featured_image'] ?? 'assets/images/tours/dubai-package.jpg') ?>">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Package Overview & Summary *</label>
                <textarea name="overview" class="admin-textarea" style="min-height: 90px;" required><?= e($editItem['overview'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Package Inclusions (One per line)</label>
                    <textarea name="inclusions" class="admin-textarea" style="min-height: 120px;"><?= e($editItem['inclusions'] ?? '') ?></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Package Exclusions (One per line)</label>
                    <textarea name="exclusions" class="admin-textarea" style="min-height: 120px;"><?= e($editItem['exclusions'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Day-by-Day Itinerary (JSON Format)</label>
                <textarea name="itinerary_json" class="admin-textarea" style="min-height: 140px; font-family: monospace; font-size: 0.85rem;"><?= e($editItem['itinerary_json'] ?? '') ?></textarea>
                <span class="help-text">Format: [{"day":1,"title":"Arrival","desc":"Hotel transfer..."},{"day":2,"title":"City Tour","desc":"Guided sightseeing..."}]</span>
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

            <div style="display: flex; gap: 2rem; margin-top: 0.5rem;">
                <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="tFeatured" value="1" <?= (!isset($editItem) || ($editItem['is_featured'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    <label for="tFeatured" style="cursor: pointer;">Feature on homepage showcase</label>
                </div>

                <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="status" id="tStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    <label for="tStatus" style="cursor: pointer;">Publish on live site</label>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span><?= $action === 'create' ? 'Create Tour Package' : 'Save Package' ?></span>
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
                        <th>Duration</th>
                        <th>Package Title</th>
                        <th>Rate Label</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tours as $t): ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--admin-navy);">
                                <?= e($t['duration_days']) ?>D / <?= e($t['duration_nights']) ?>N
                            </td>
                            <td>
                                <strong><?= e($t['title']) ?></strong><br>
                                <span style="font-size: 0.78rem; color: var(--admin-muted); font-family: monospace;"><?= e($t['slug']) ?></span>
                            </td>
                            <td>
                                <span class="badge-status" style="background: rgba(185,139,98,0.15); color: var(--admin-bronze); font-weight: 700;">
                                    <?= e($t['price_text']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['is_featured'] == 1): ?>
                                    <span style="color: #F5A623; font-weight: 700;">★ Featured</span>
                                <?php else: ?>
                                    <span style="color: var(--admin-muted);">Standard</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= ADMIN_URL ?>/tours.php?action=toggle&id=<?= $t['id'] ?>" title="Toggle status">
                                    <?php if ($t['status'] == 1): ?>
                                        <span class="badge-status badge-active">Published</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-inactive">Draft</span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/tours.php?action=edit&id=<?= $t['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">
                                        Edit
                                    </a>
                                    <a href="<?= url('tour-detail.php?slug=' . urlencode($t['slug'])) ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-outline btn-admin-sm" title="View Public Page">
                                        <?= renderIcon('plane', '', 12) ?>
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/tours.php?action=delete&id=<?= $t['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this package?">
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
