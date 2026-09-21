<?php
/**
 * Mahin Travel & Tours - Admin Header Layout
 */
require_once __DIR__ . '/auth_check.php';

$pdo = getDB();
$unreadCount = 0;
try {
    $unreadStmt = $pdo->query("SELECT COUNT(*) as unread FROM contact_messages WHERE is_read = 0");
    $unreadCount = (int)($unreadStmt->fetch()['unread'] ?? 0);
} catch (Exception $e) {}

$activePage = $activeAdminPage ?? 'dashboard';
$adminSiteName = getSetting('site_name', DEFAULT_SITE_NAME);
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminPageTitle ?? 'Dashboard') ?> | <?= e($adminSiteName) ?> Management</title>
    <link rel="icon" type="image/png" href="<?= url('assets/images/favicon.png') ?>">
    <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
</head>
<body>

<div class="admin-layout">
    <!-- 1. Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <img src="<?= url('assets/images/logo.png') ?>" alt="Logo">
            <div>
                <h3>MAHIN TRAVEL</h3>
                <span>Admin CMS</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Core Management</div>
            <a href="<?= ADMIN_URL ?>/index.php" class="admin-nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                <?= renderIcon('compass', '', 18) ?>
                <span>Dashboard</span>
            </a>
            <a href="<?= ADMIN_URL ?>/messages.php" class="admin-nav-item <?= $activePage === 'messages' ? 'active' : '' ?>">
                <?= renderIcon('mail', '', 18) ?>
                <span>Inquiries & Leads</span>
                <?php if ($unreadCount > 0): ?>
                    <span class="badge"><?= $unreadCount ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= ADMIN_URL ?>/settings.php" class="admin-nav-item <?= $activePage === 'settings' ? 'active' : '' ?>">
                <?= renderIcon('shield-check', '', 18) ?>
                <span>Site Settings & Info</span>
            </a>

            <div class="nav-section-title">Travel Modules</div>
            <a href="<?= ADMIN_URL ?>/services.php" class="admin-nav-item <?= $activePage === 'services' ? 'active' : '' ?>">
                <?= renderIcon('plane', '', 18) ?>
                <span>Services</span>
            </a>
            <a href="<?= ADMIN_URL ?>/visa.php" class="admin-nav-item <?= $activePage === 'visa' ? 'active' : '' ?>">
                <?= renderIcon('passport', '', 18) ?>
                <span>Visa Hub & Countries</span>
            </a>
            <a href="<?= ADMIN_URL ?>/visa_steps.php" class="admin-nav-item <?= $activePage === 'visa_steps' ? 'active' : '' ?>">
                <?= renderIcon('folder-check', '', 18) ?>
                <span>Visa Process Steps</span>
            </a>
            <a href="<?= ADMIN_URL ?>/tours.php" class="admin-nav-item <?= $activePage === 'tours' ? 'active' : '' ?>">
                <?= renderIcon('map', '', 18) ?>
                <span>Tours & Umrah</span>
            </a>
            <a href="<?= ADMIN_URL ?>/destinations.php" class="admin-nav-item <?= $activePage === 'destinations' ? 'active' : '' ?>">
                <?= renderIcon('map-pin', '', 18) ?>
                <span>Destinations</span>
            </a>

            <div class="nav-section-title">Content & Media</div>
            <a href="<?= ADMIN_URL ?>/faqs.php" class="admin-nav-item <?= $activePage === 'faqs' ? 'active' : '' ?>">
                <?= renderIcon('file-text', '', 18) ?>
                <span>FAQs</span>
            </a>
            <a href="<?= ADMIN_URL ?>/testimonials.php" class="admin-nav-item <?= $activePage === 'testimonials' ? 'active' : '' ?>">
                <?= renderIcon('users', '', 18) ?>
                <span>Testimonials</span>
            </a>
            <a href="<?= ADMIN_URL ?>/media.php" class="admin-nav-item <?= $activePage === 'media' ? 'active' : '' ?>">
                <?= renderIcon('folder-check', '', 18) ?>
                <span>Media Library</span>
            </a>
            <a href="<?= ADMIN_URL ?>/profile.php" class="admin-nav-item <?= $activePage === 'profile' ? 'active' : '' ?>">
                <?= renderIcon('shield-check', '', 18) ?>
                <span>Security / Password</span>
            </a>

            <div style="margin-top: auto; padding: 1.5rem 1.25rem;">
                <a href="<?= ADMIN_URL ?>/logout.php" class="btn-admin btn-admin-outline" style="width: 100%; justify-content: center; color: #EF4444; border-color: rgba(239,68,68,0.3);" data-confirm="Sign out of administrative session?">
                    <span>Log Out</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- 2. Main Content Wrapper -->
    <main class="admin-main">
        <!-- Top Bar -->
        <header class="admin-header">
            <div class="admin-header-left">
                <button id="sidebarToggle" class="btn-admin btn-admin-outline btn-admin-sm" style="display: none;" aria-label="Toggle Sidebar">
                    ☰
                </button>
                <a href="<?= url() ?>" target="_blank" rel="noopener" class="btn-admin btn-admin-outline btn-admin-sm" title="Open Frontend">
                    <?= renderIcon('plane', '', 14) ?>
                    <span>View Public Website &rarr;</span>
                </a>
            </div>

            <div class="admin-header-right">
                <div class="admin-user-pill">
                    <div class="admin-avatar">
                        <?= strtoupper(substr($currentAdminName, 0, 1)) ?>
                    </div>
                    <span><?= e($currentAdminName) ?></span>
                </div>
            </div>
        </header>

        <!-- Dynamic Body Content Container -->
        <div class="admin-content">
            <?php if ($flash): ?>
                <div class="admin-alert admin-alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>">
                    <?= renderIcon($flash['type'] === 'success' ? 'check' : 'shield-check', '', 20) ?>
                    <span><?= e($flash['message']) ?></span>
                </div>
            <?php endif; ?>
