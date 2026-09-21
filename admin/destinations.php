<?php
/**
 * Mahin Travel & Tours - Destinations Management CRUD
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();
$action = cleanInput($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $title = cleanInput($_POST['title'] ?? '');
        $slug = cleanInput($_POST['slug'] ?? '');
        if (empty($slug)) $slug = slugify($title);
        $country = cleanInput($_POST['country'] ?? '');
        $continent = cleanInput($_POST['continent'] ?? 'Asia');
        $featuredImage = cleanInput($_POST['featured_image'] ?? 'assets/images/destinations/dubai.jpg');
        $overview = trim($_POST['overview'] ?? '');
        $popularFor = cleanInput($_POST['popular_for'] ?? '');
        $bestTime = cleanInput($_POST['best_time'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;

        if (empty($title) || empty($country)) {
            setFlashMessage('error', 'Destination title and country are required.');
        } else {
            try {
                if ($action === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO destinations (title, slug, country, continent, featured_image, overview, popular_for, best_time, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $slug, $country, $continent, $featuredImage, $overview, $popularFor, $bestTime, $sortOrder, $status]);
                    setFlashMessage('success', 'Destination added.');
                } elseif ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare("UPDATE destinations SET title = ?, slug = ?, country = ?, continent = ?, featured_image = ?, overview = ?, popular_for = ?, best_time = ?, sort_order = ?, status = ? WHERE id = ?");
                    $stmt->execute([$title, $slug, $country, $continent, $featuredImage, $overview, $popularFor, $bestTime, $sortOrder, $status, $id]);
                    setFlashMessage('success', 'Destination updated.');
                }
                header("Location: " . ADMIN_URL . "/destinations.php");
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Database error: ' . $e->getMessage());
            }
        }
    }
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM destinations WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'Destination deleted.');
    header("Location: " . ADMIN_URL . "/destinations.php");
    exit;
}

$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$destinations = $pdo->query("SELECT * FROM destinations ORDER BY sort_order ASC, id ASC")->fetchAll();

$activeAdminPage = 'destinations';
$adminPageTitle = 'Destinations';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Worldwide Destinations Guide</h1>
        <p class="page-subtitle">Configure popular international and domestic destinations shown in the guide and homepage showcase.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/destinations.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Destination</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/destinations.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to Destinations</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <div class="admin-card">
        <form action="<?= ADMIN_URL ?>/destinations.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Destination Title *</label>
                    <input type="text" name="title" class="admin-input" value="<?= e($editItem['title'] ?? '') ?>" placeholder="e.g. Dubai & Abu Dhabi" required>
                </div>

                <div class="admin-form-group">
                    <label>URL Slug</label>
                    <input type="text" name="slug" class="admin-input" value="<?= e($editItem['slug'] ?? '') ?>" placeholder="leave blank to auto-generate">
                </div>

                <div class="admin-form-group">
                    <label>Country *</label>
                    <input type="text" name="country" class="admin-input" value="<?= e($editItem['country'] ?? '') ?>" placeholder="e.g. United Arab Emirates" required>
                </div>

                <div class="admin-form-group">
                    <label>Region / Continent</label>
                    <input type="text" name="continent" class="admin-input" value="<?= e($editItem['continent'] ?? 'Asia') ?>" placeholder="e.g. Middle East, Southeast Asia, Europe">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Destination Overview</label>
                <textarea name="overview" class="admin-textarea" style="min-height: 80px;"><?= e($editItem['overview'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Popular Attractions</label>
                    <input type="text" name="popular_for" class="admin-input" value="<?= e($editItem['popular_for'] ?? '') ?>" placeholder="e.g. Burj Khalifa, Desert Safari, Marina">
                </div>

                <div class="admin-form-group">
                    <label>Optimal Travel Season</label>
                    <input type="text" name="best_time" class="admin-input" value="<?= e($editItem['best_time'] ?? '') ?>" placeholder="e.g. October to April">
                </div>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Featured Image Asset Path</label>
                    <input type="text" name="featured_image" class="admin-input" value="<?= e($editItem['featured_image'] ?? 'assets/images/destinations/dubai.jpg') ?>">
                </div>

                <div class="admin-form-group">
                    <label>Sort Order Priority</label>
                    <input type="number" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? 0) ?>">
                </div>
            </div>

            <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="status" id="dStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <label for="dStatus" style="cursor: pointer;">Active and visible in destination directory</label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span>Save Destination</span>
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
                        <th>Order</th>
                        <th>Destination</th>
                        <th>Region / Country</th>
                        <th>Attractions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($destinations as $d): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--admin-muted);">#<?= e($d['sort_order']) ?></td>
                            <td><strong><?= e($d['title']) ?></strong></td>
                            <td><?= e($d['continent']) ?> • <?= e($d['country']) ?></td>
                            <td style="font-size: 0.82rem; color: var(--admin-muted);"><?= e($d['popular_for']) ?></td>
                            <td>
                                <?php if ($d['status'] == 1): ?>
                                    <span class="badge-status badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/destinations.php?action=edit&id=<?= $d['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">Edit</a>
                                    <a href="<?= ADMIN_URL ?>/destinations.php?action=delete&id=<?= $d['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete destination?">&times;</a>
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
