<?php
/**
 * Mahin Travel & Tours - Inquiries & Lead Management
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();

// Handle Actions (Toggle Read, Delete)
$action = cleanInput($_GET['action'] ?? '');
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    if ($action === 'toggle_read') {
        $cur = $pdo->prepare("SELECT is_read FROM contact_messages WHERE id = ?");
        $cur->execute([$id]);
        $val = $cur->fetchColumn();
        $newVal = $val == 1 ? 0 : 1;

        $upd = $pdo->prepare("UPDATE contact_messages SET is_read = ? WHERE id = ?");
        $upd->execute([$newVal, $id]);
        setFlashMessage('success', 'Inquiry status updated.');
        header("Location: " . ADMIN_URL . "/messages.php");
        exit;
    }

    if ($action === 'delete') {
        $del = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $del->execute([$id]);
        setFlashMessage('success', 'Inquiry deleted successfully.');
        header("Location: " . ADMIN_URL . "/messages.php");
        exit;
    }
}

// Filter
$filter = cleanInput($_GET['filter'] ?? 'all');
if ($filter === 'unread') {
    $stmt = $pdo->query("SELECT * FROM contact_messages WHERE is_read = 0 ORDER BY created_at DESC, id DESC");
} else {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC, id DESC");
}
$messages = $stmt->fetchAll();

// If viewing a single message detail
$viewId = (int)($_GET['view'] ?? 0);
$viewMessage = null;
if ($viewId > 0) {
    $vStmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $vStmt->execute([$viewId]);
    $viewMessage = $vStmt->fetch();
    if ($viewMessage && $viewMessage['is_read'] == 0) {
        $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$viewId]);
    }
}

$activeAdminPage = 'messages';
$adminPageTitle = 'Inquiries & Leads';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Client Inquiries & Consultation Leads</h1>
        <p class="page-subtitle">Track, review, and reply directly to customer travel inquiries received via website forms.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= ADMIN_URL ?>/messages.php?filter=all" class="btn-admin <?= $filter === 'all' ? 'btn-admin-primary' : 'btn-admin-outline' ?> btn-admin-sm">
            All Inquiries
        </a>
        <a href="<?= ADMIN_URL ?>/messages.php?filter=unread" class="btn-admin <?= $filter === 'unread' ? 'btn-admin-primary' : 'btn-admin-outline' ?> btn-admin-sm">
            Unread Leads Only
        </a>
    </div>
</div>

<?php if ($viewMessage): ?>
    <!-- Single Message Detail View Card -->
    <div class="admin-card" style="border-left: 4px solid var(--admin-bronze);">
        <div class="admin-card-header">
            <div>
                <h3 class="admin-card-title">Inquiry from <?= e($viewMessage['name']) ?></h3>
                <span style="font-size: 0.8rem; color: var(--admin-muted);">
                    Received on <?= date('d M Y, h:i A', strtotime($viewMessage['created_at'])) ?>
                </span>
            </div>
            <a href="<?= ADMIN_URL ?>/messages.php" class="btn-admin btn-admin-outline btn-admin-sm">&larr; Back to List</a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <p><strong>Client Name:</strong> <?= e($viewMessage['name']) ?></p>
                <p><strong>Phone / WhatsApp:</strong> <a href="<?= getTelUrl($viewMessage['phone']) ?>" style="color: var(--admin-navy); font-weight: 600;"><?= e($viewMessage['phone']) ?></a></p>
                <p><strong>Email Address:</strong> <?= e($viewMessage['email'] ?: 'Not provided') ?></p>
            </div>
            <div>
                <p><strong>Service Inquired:</strong> <span class="badge-status" style="background: rgba(185,139,98,0.15); color: var(--admin-bronze); font-weight: 700;"><?= e($viewMessage['service_type'] ?: 'General') ?></span></p>
                <p><strong>Subject:</strong> <?= e($viewMessage['subject'] ?: 'N/A') ?></p>
                <p><strong>IP Address:</strong> <?= e($viewMessage['ip_address'] ?: 'N/A') ?></p>
            </div>
        </div>

        <div style="background: var(--admin-bg); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--admin-border); margin-bottom: 1.5rem;">
            <strong style="color: var(--admin-navy); display: block; margin-bottom: 0.5rem;">Message / Travel Requirements:</strong>
            <p style="font-size: 0.95rem; color: var(--admin-text); line-height: 1.7; white-space: pre-wrap;"><?= e($viewMessage['message']) ?></p>
        </div>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <?php
            $cleanPhone = preg_replace('/[^0-9]/', '', $viewMessage['phone']);
            $waReply = "https://wa.me/{$cleanPhone}?text=" . urlencode("Hello {$viewMessage['name']}! Thank you for inquiring with Mahin Travel & Tours regarding {$viewMessage['service_type']}. How may we assist you today?");
            ?>
            <a href="<?= $waReply ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-gold">
                <?= renderIcon('whatsapp', '', 16) ?>
                <span>Reply on WhatsApp</span>
            </a>
            <?php if (!empty($viewMessage['email'])): ?>
                <a href="mailto:<?= e($viewMessage['email']) ?>?subject=Inquiry Reply: Mahin Travel %26 Tours" class="btn-admin btn-admin-primary">
                    <?= renderIcon('mail', '', 16) ?>
                    <span>Reply via Email</span>
                </a>
            <?php endif; ?>
            <a href="<?= ADMIN_URL ?>/messages.php?action=toggle_read&id=<?= $viewMessage['id'] ?>" class="btn-admin btn-admin-outline">
                <span>Mark as Unread</span>
            </a>
            <a href="<?= ADMIN_URL ?>/messages.php?action=delete&id=<?= $viewMessage['id'] ?>" class="btn-admin btn-admin-danger" data-confirm="Permanently delete this inquiry?">
                <span>Delete</span>
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Inquiries List Table -->
<div class="admin-card">
    <?php if (empty($messages)): ?>
        <p style="text-align: center; color: var(--admin-muted); padding: 2.5rem 0;">No inquiries matching your criteria.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Client</th>
                        <th>Phone</th>
                        <th>Service / Topic</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $m): ?>
                        <tr style="<?= $m['is_read'] == 0 ? 'background: #FFFDF9; font-weight: 500;' : '' ?>">
                            <td>
                                <?php if ($m['is_read'] == 0): ?>
                                    <span class="badge-status badge-unread">New</span>
                                <?php else: ?>
                                    <span class="badge-status badge-read">Read</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= e($m['name']) ?></strong><br>
                                <span style="font-size: 0.78rem; color: var(--admin-muted);"><?= e($m['email'] ?: 'No email') ?></span>
                            </td>
                            <td>
                                <a href="<?= getTelUrl($m['phone']) ?>" style="color: var(--admin-navy); font-weight: 600;"><?= e($m['phone']) ?></a>
                            </td>
                            <td>
                                <span class="badge-status" style="background: rgba(185,139,98,0.15); color: var(--admin-bronze);">
                                    <?= e($m['service_type'] ?: 'General') ?>
                                </span>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--admin-muted);">
                                <?= date('d M Y, h:i A', strtotime($m['created_at'])) ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.35rem;">
                                    <?php
                                    $cp = preg_replace('/[^0-9]/', '', $m['phone']);
                                    $wa = "https://wa.me/{$cp}?text=" . urlencode("Hello {$m['name']}! Thank you for contacting Mahin Travel & Tours regarding {$m['service_type']}.");
                                    ?>
                                    <a href="<?= $wa ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-gold btn-admin-sm" title="WhatsApp">
                                        <?= renderIcon('whatsapp', '', 14) ?>
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/messages.php?view=<?= $m['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">
                                        View
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/messages.php?action=delete&id=<?= $m['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete inquiry?">
                                        &times;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
