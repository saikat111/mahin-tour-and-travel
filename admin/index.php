<?php
/**
 * Mahin Travel & Tours - Admin Dashboard
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();

// Fetch KPI statistics
$totalInquiries = (int)($pdo->query("SELECT COUNT(*) as c FROM contact_messages")->fetch()['c'] ?? 0);
$unreadInquiries = (int)($pdo->query("SELECT COUNT(*) as c FROM contact_messages WHERE is_read = 0")->fetch()['c'] ?? 0);
$totalServices = (int)($pdo->query("SELECT COUNT(*) as c FROM services WHERE status = 1")->fetch()['c'] ?? 0);
$totalVisas = (int)($pdo->query("SELECT COUNT(*) as c FROM visa_categories WHERE status = 1")->fetch()['c'] ?? 0);
$totalTours = (int)($pdo->query("SELECT COUNT(*) as c FROM tours WHERE status = 1")->fetch()['c'] ?? 0);
$totalDestinations = (int)($pdo->query("SELECT COUNT(*) as c FROM destinations WHERE status = 1")->fetch()['c'] ?? 0);

// Fetch Latest Inquiries
$recentStmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC, id DESC LIMIT 6");
$recentInquiries = $recentStmt->fetchAll();

$activeAdminPage = 'dashboard';
$adminPageTitle = 'Dashboard Overview';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Executive Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?= e($currentAdminName) ?>. Here is the operational summary for Mahin Travel & Tours.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="<?= ADMIN_URL ?>/tours.php?action=create" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Tour Package</span>
        </a>
        <a href="<?= ADMIN_URL ?>/visa.php?action=create" class="btn-admin btn-admin-primary">
            <?= renderIcon('plus', '', 14) ?>
            <span>Add Visa Guide</span>
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div>
            <div class="stat-val"><?= $unreadInquiries ?></div>
            <div class="stat-lbl">Unread Leads / Inquiries</div>
        </div>
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.12); color: #EF4444;">
            <?= renderIcon('mail', '', 24) ?>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-val"><?= $totalInquiries ?></div>
            <div class="stat-lbl">Total Contact Messages</div>
        </div>
        <div class="stat-icon">
            <?= renderIcon('users', '', 24) ?>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-val"><?= $totalVisas ?></div>
            <div class="stat-lbl">Active Visa Checklists</div>
        </div>
        <div class="stat-icon">
            <?= renderIcon('passport', '', 24) ?>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-val"><?= $totalTours ?></div>
            <div class="stat-lbl">Active Tour & Umrah Packages</div>
        </div>
        <div class="stat-icon">
            <?= renderIcon('map', '', 24) ?>
        </div>
    </div>
</div>

<!-- Recent Inquiries Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-card-title">Recent Client Inquiries & Leads</h2>
            <p class="page-subtitle">Latest messages received through website consultation forms.</p>
        </div>
        <a href="<?= ADMIN_URL ?>/messages.php" class="btn-admin btn-admin-outline btn-admin-sm">
            <span>View All Inquiries &rarr;</span>
        </a>
    </div>

    <?php if (empty($recentInquiries)): ?>
        <p style="color: var(--admin-muted); text-align: center; padding: 2rem 0;">No inquiries received yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Client Name</th>
                        <th>Phone / WhatsApp</th>
                        <th>Service Required</th>
                        <th>Date Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentInquiries as $msg): ?>
                        <tr>
                            <td>
                                <?php if ($msg['is_read'] == 0): ?>
                                    <span class="badge-status badge-unread">New Lead</span>
                                <?php else: ?>
                                    <span class="badge-status badge-read">Read</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= e($msg['name']) ?></strong><br>
                                <span style="font-size: 0.78rem; color: var(--admin-muted);"><?= e($msg['email'] ?: 'No email') ?></span>
                            </td>
                            <td>
                                <a href="<?= getTelUrl($msg['phone']) ?>" style="color: var(--admin-navy); font-weight: 600;"><?= e($msg['phone']) ?></a>
                            </td>
                            <td>
                                <span class="badge-status" style="background: rgba(185,139,98,0.15); color: var(--admin-bronze);">
                                    <?= e($msg['service_type'] ?: 'General') ?>
                                </span>
                            </td>
                            <td style="color: var(--admin-muted); font-size: 0.82rem;">
                                <?= date('d M Y, h:i A', strtotime($msg['created_at'])) ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <?php
                                    $cleanMsgPhone = preg_replace('/[^0-9]/', '', $msg['phone']);
                                    $waReply = "https://wa.me/{$cleanMsgPhone}?text=" . urlencode("Hello {$msg['name']}! Thank you for reaching out to Mahin Travel & Tours regarding {$msg['service_type']}.");
                                    ?>
                                    <a href="<?= $waReply ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-gold btn-admin-sm" title="Reply on WhatsApp">
                                        <?= renderIcon('whatsapp', '', 14) ?>
                                        <span>WhatsApp</span>
                                    </a>
                                    <a href="<?= ADMIN_URL ?>/messages.php?id=<?= $msg['id'] ?>" class="btn-admin btn-admin-outline btn-admin-sm">
                                        <span>View</span>
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

<!-- System Status Bar -->
<div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.8rem; color: var(--admin-muted); padding: 0.5rem 0;">
    <div><strong>Database Engine:</strong> <?= strtoupper(Database::getDriverType()) ?></div>
    <div><strong>PHP Version:</strong> <?= PHP_VERSION ?></div>
    <div><strong>Registered Office:</strong> South Salna, Ward No-19, Zone-5, Gazipur</div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
