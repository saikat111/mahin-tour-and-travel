<?php
/**
 * Mahin Travel & Tours - Visa Categories & Country Checklists CRUD
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
        $countryName = cleanInput($_POST['country_name'] ?? '');
        $countryCode = strtoupper(cleanInput($_POST['country_code'] ?? ''));
        $visaType = cleanInput($_POST['visa_type'] ?? '');
        $slug = cleanInput($_POST['slug'] ?? '');
        if (empty($slug)) $slug = slugify($countryName . '-' . $visaType);
        $processingTime = cleanInput($_POST['processing_time'] ?? '');
        $validity = cleanInput($_POST['validity'] ?? '');
        $requirementSummary = cleanInput($_POST['requirement_summary'] ?? '');
        $detailedRequirements = trim($_POST['detailed_requirements'] ?? '');
        $feesNote = cleanInput($_POST['fees_note'] ?? '');
        $image = cleanInput($_POST['image'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = isset($_POST['status']) ? 1 : 0;
        $metaTitle = cleanInput($_POST['meta_title'] ?? '');
        $metaDesc = cleanInput($_POST['meta_desc'] ?? '');

        if (empty($countryName) || empty($visaType)) {
            setFlashMessage('error', 'Country name and visa category are required.');
        } else {
            try {
                if ($action === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO visa_categories (country_name, country_code, visa_type, slug, processing_time, validity, requirement_summary, detailed_requirements, fees_note, image, sort_order, status, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$countryName, $countryCode, $visaType, $slug, $processingTime, $validity, $requirementSummary, $detailedRequirements, $feesNote, $image, $sortOrder, $status, $metaTitle, $metaDesc]);
                    setFlashMessage('success', 'Visa category created successfully.');
                } elseif ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare("UPDATE visa_categories SET country_name = ?, country_code = ?, visa_type = ?, slug = ?, processing_time = ?, validity = ?, requirement_summary = ?, detailed_requirements = ?, fees_note = ?, image = ?, sort_order = ?, status = ?, meta_title = ?, meta_desc = ? WHERE id = ?");
                    $stmt->execute([$countryName, $countryCode, $visaType, $slug, $processingTime, $validity, $requirementSummary, $detailedRequirements, $feesNote, $image, $sortOrder, $status, $metaTitle, $metaDesc, $id]);
                    setFlashMessage('success', 'Visa category updated successfully.');
                }
                header("Location: " . ADMIN_URL . "/visa.php");
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Database error: ' . $e->getMessage());
            }
        }
    }
}

// Handle Status Toggle & Delete
if ($action === 'toggle' && $id > 0) {
    $cur = $pdo->prepare("SELECT status FROM visa_categories WHERE id = ?");
    $cur->execute([$id]);
    $st = $cur->fetchColumn();
    $newSt = $st == 1 ? 0 : 1;
    $pdo->prepare("UPDATE visa_categories SET status = ? WHERE id = ?")->execute([$newSt, $id]);
    setFlashMessage('success', 'Visa guide status updated.');
    header("Location: " . ADMIN_URL . "/visa.php");
    exit;
}

if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM visa_categories WHERE id = ?")->execute([$id]);
    setFlashMessage('success', 'Visa guide deleted successfully.');
    header("Location: " . ADMIN_URL . "/visa.php");
    exit;
}

// Fetch Item for Edit
$editItem = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM visa_categories WHERE id = ?");
    $stmt->execute([$id]);
    $editItem = $stmt->fetch();
}

$visas = $pdo->query("SELECT * FROM visa_categories ORDER BY sort_order ASC, id ASC")->fetchAll();

$activeAdminPage = 'visa';
$adminPageTitle = 'Visa Categories';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Visa Guidance & Country Checklists</h1>
        <p class="page-subtitle">Manage visa destinations, required documents, turnaround times, and advisory notes.</p>
    </div>
    <?php if ($action === 'list'): ?>
        <a href="<?= ADMIN_URL ?>/visa.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Visa Destination</span>
        </a>
    <?php else: ?>
        <a href="<?= ADMIN_URL ?>/visa.php" class="btn-admin btn-admin-outline">
            <span>&larr; Back to Visa List</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($action === 'create' || ($action === 'edit' && $editItem)): ?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title"><?= $action === 'create' ? 'Create New Visa Guide' : 'Edit Visa: ' . e($editItem['country_name']) ?></h2>
        </div>

        <form action="<?= ADMIN_URL ?>/visa.php?action=<?= $action ?><?= $id ? '&id=' . $id : '' ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Destination Country *</label>
                    <input type="text" name="country_name" class="admin-input" value="<?= e($editItem['country_name'] ?? '') ?>" placeholder="e.g. United Arab Emirates (Dubai)" required>
                </div>

                <div class="admin-form-group">
                    <label>Country Code (2 Letters)</label>
                    <input type="text" name="country_code" class="admin-input" value="<?= e($editItem['country_code'] ?? '') ?>" placeholder="e.g. AE" maxlength="10">
                </div>

                <div class="admin-form-group">
                    <label>Visa Category / Type *</label>
                    <input type="text" name="visa_type" class="admin-input" value="<?= e($editItem['visa_type'] ?? '') ?>" placeholder="e.g. Tourist / Visit E-Visa (30/60 Days)" required>
                </div>

                <div class="admin-form-group">
                    <label>URL Slug</label>
                    <input type="text" name="slug" class="admin-input" value="<?= e($editItem['slug'] ?? '') ?>" placeholder="leave blank to auto-generate">
                </div>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Estimated Processing Time</label>
                    <input type="text" name="processing_time" class="admin-input" value="<?= e($editItem['processing_time'] ?? '3 to 5 Working Days') ?>">
                </div>

                <div class="admin-form-group">
                    <label>Visa Validity / Stay Duration</label>
                    <input type="text" name="validity" class="admin-input" value="<?= e($editItem['validity'] ?? '') ?>" placeholder="e.g. 30 Days Single Entry">
                </div>

                <div class="admin-form-group">
                    <label>Sort Order Priority</label>
                    <input type="number" name="sort_order" class="admin-input" value="<?= e($editItem['sort_order'] ?? 0) ?>">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Requirements Summary (Quick Card Overview)</label>
                <textarea name="requirement_summary" class="admin-textarea" style="min-height: 65px;"><?= e($editItem['requirement_summary'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group">
                <label>Detailed Checklist & Embassy Guidelines (HTML Supported)</label>
                <textarea name="detailed_requirements" class="admin-textarea" style="min-height: 180px;"><?= e($editItem['detailed_requirements'] ?? '') ?></textarea>
                <span class="help-text">You can use &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt; tags for formatting checklists.</span>
            </div>

            <div class="form-row">
                <div class="admin-form-group">
                    <label>Fees Policy & Government Charges Note</label>
                    <input type="text" name="fees_note" class="admin-input" value="<?= e($editItem['fees_note'] ?? '') ?>">
                </div>

                <div class="admin-form-group">
                    <label>Graphic Asset Path</label>
                    <input type="text" name="image" class="admin-input" value="<?= e($editItem['image'] ?? 'assets/images/visas/dubai-visa.jpg') ?>">
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

            <div class="admin-form-group" style="flex-direction: row; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="status" id="visaStatus" value="1" <?= (!isset($editItem) || ($editItem['status'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                <label for="visaStatus" style="cursor: pointer;">Publish and make visible on live website</label>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-admin btn-admin-gold">
                    <?= renderIcon('check', '', 16) ?>
                    <span><?= $action === 'create' ? 'Create Visa Guide' : 'Save Changes' ?></span>
                </button>
            </div>
        </form>
    </div>
<?php else: ?>
    <!-- Visa List Table -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Country</th>
                        <th>Visa Type</th>
                        <th>Processing Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visas as $v): ?>
                        <tr>
                            <td style="width: 60px; color: var(--admin-muted); font-weight: 600;">
                                #<?= e($v['sort_order']) ?>
                            </td>
                            <td>
                                <strong><?= e($v['country_name']) ?></strong>
                                <?php if (!empty($v['country_code'])): ?>
                                    <span style="font-size: 0.75rem; color: var(--admin-muted); font-weight: 600;">(<?= e($v['country_code']) ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-status" style="background: rgba(185,139,98,0.15); color: var(--admin-bronze);">
                                    <?= e($v['visa_type']) ?>
                                </span>
                            </td>
                            <td style="color: var(--admin-muted); font-size: 0.85rem;">
                                <?= e($v['processing_time']) ?>
                            </td>
                            <td>
                                <a href="<?= ADMIN_URL ?>/visa.php?action=toggle&id=<?= $v['id'] ?>" title="Click to toggle status">
                                    <?php if ($v['status'] == 1): ?>
                                        <span class="badge-status badge-active">Published</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-inactive">Draft</span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <a href="<?= ADMIN_URL ?>/visa.php?action=edit&id=<?= $v['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">
                                        Edit
                                    </a>
                                    <a href="<?= url('visa-detail.php?slug=' . urlencode($v['slug'])) ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-outline btn-admin-sm" title="View Public Page">
                                        <?= renderIcon('plane', '', 12) ?>
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/visa.php?action=delete&id=<?= $v['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this visa guide?">
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
