<?php
/**
 * Mahin Travel & Tours - Site Settings Manager
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();

// Handle Settings Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $settingsData = $_POST['settings'] ?? [];
        try {
            foreach ($settingsData as $key => $val) {
                $chk = $pdo->prepare("SELECT id FROM site_settings WHERE setting_key = ?");
                $chk->execute([$key]);
                if ($chk->fetch()) {
                    $upd = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
                    $upd->execute([trim((string)$val), $key]);
                } else {
                    $ins = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, label) VALUES (?, ?, ?)");
                    $ins->execute([$key, trim((string)$val), ucfirst(str_replace('_', ' ', $key))]);
                }
            }
            setFlashMessage('success', 'Site settings updated successfully.');
            header("Location: " . ADMIN_URL . "/settings.php");
            exit;
        } catch (Exception $e) {
            setFlashMessage('error', 'Error updating settings: ' . $e->getMessage());
        }
    }
}

// Fetch all settings
$settingsStmt = $pdo->query("SELECT * FROM site_settings ORDER BY setting_group ASC, id ASC");
$allSettings = $settingsStmt->fetchAll();
$settingsMap = [];
foreach ($allSettings as $s) {
    $settingsMap[$s['setting_key']] = $s['setting_value'];
}

$activeAdminPage = 'settings';
$adminPageTitle = 'Site Settings';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Website Settings & Brand Configuration</h1>
        <p class="page-subtitle">Configure contact details, address, homepage headlines, and SEO metadata without editing code.</p>
    </div>
</div>

<form action="<?= ADMIN_URL ?>/settings.php" method="POST">
    <?= csrf_field() ?>

    <!-- 1. Contact & Location Information -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Official Contact Details & Gazipur Address</h2>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>Primary Phone Number</label>
                <input type="text" name="settings[phone_primary]" class="admin-input" value="<?= e($settingsMap['phone_primary'] ?? DEFAULT_PHONE_PRIMARY) ?>" required>
                <span class="help-text">Displayed on header, top bar, and footer</span>
            </div>

            <div class="admin-form-group">
                <label>Secondary Phone Number</label>
                <input type="text" name="settings[phone_secondary]" class="admin-input" value="<?= e($settingsMap['phone_secondary'] ?? DEFAULT_PHONE_SECONDARY) ?>">
                <span class="help-text">Alternative contact line</span>
            </div>

            <div class="admin-form-group">
                <label>WhatsApp Consultation Number</label>
                <input type="text" name="settings[whatsapp_number]" class="admin-input" value="<?= e($settingsMap['whatsapp_number'] ?? DEFAULT_WHATSAPP) ?>" required>
                <span class="help-text">Used for floating WhatsApp button and click-to-chat links</span>
            </div>

            <div class="admin-form-group">
                <label>Official Email Address</label>
                <input type="email" name="settings[contact_email]" class="admin-input" value="<?= e($settingsMap['contact_email'] ?? DEFAULT_EMAIL) ?>" required>
                <span class="help-text">Email address displayed to website visitors</span>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Registered Office Physical Address</label>
            <textarea name="settings[office_address]" class="admin-textarea" style="min-height: 80px;" required><?= e($settingsMap['office_address'] ?? DEFAULT_ADDRESS) ?></textarea>
            <span class="help-text">Official registered location in Gazipur, Bangladesh</span>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>Business / Consultation Hours</label>
                <input type="text" name="settings[business_hours]" class="admin-input" value="<?= e($settingsMap['business_hours'] ?? 'Sat - Thu: 9:30 AM - 8:30 PM') ?>">
            </div>

            <div class="admin-form-group">
                <label>Google Maps Embed URL</label>
                <input type="text" name="settings[google_maps_embed]" class="admin-input" value="<?= e($settingsMap['google_maps_embed'] ?? '') ?>">
                <span class="help-text">Embed iframe source URL for the contact page</span>
            </div>
        </div>
    </div>

    <!-- 2. Brand Identity -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Brand Identity & Trading Name</h2>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>Company / Brand Name</label>
                <input type="text" name="settings[site_name]" class="admin-input" value="<?= e($settingsMap['site_name'] ?? DEFAULT_SITE_NAME) ?>" required>
            </div>

            <div class="admin-form-group">
                <label>Bengali Brand Name</label>
                <input type="text" name="settings[bengali_name]" class="admin-input" value="<?= e($settingsMap['bengali_name'] ?? DEFAULT_BENGALI_NAME) ?>">
                <span class="help-text">Bengali typography subtitle (e.g. মাহিন ট্রাভেল এন্ড ট্যুরস)</span>
            </div>

            <div class="admin-form-group">
                <label>Brand Tagline</label>
                <input type="text" name="settings[tagline]" class="admin-input" value="<?= e($settingsMap['tagline'] ?? DEFAULT_TAGLINE) ?>">
            </div>

            <div class="admin-form-group">
                <label>Authorized Contact / Owner Name</label>
                <input type="text" name="settings[owner_name]" class="admin-input" value="<?= e($settingsMap['owner_name'] ?? 'Managing Director') ?>">
            </div>
        </div>
    </div>

    <!-- 3. Homepage Content -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Homepage Hero Content</h2>
        </div>

        <div class="admin-form-group">
            <label>Hero Badge Text</label>
            <input type="text" name="settings[hero_badge]" class="admin-input" value="<?= e($settingsMap['hero_badge'] ?? 'Certified Travel & Visa Consultants') ?>">
        </div>

        <div class="admin-form-group">
            <label>Hero Main Headline (H1)</label>
            <input type="text" name="settings[hero_title]" class="admin-input" value="<?= e($settingsMap['hero_title'] ?? 'Seamless Journeys, Authentic Visa Advisory & Curated Global Tours') ?>" required>
        </div>

        <div class="admin-form-group">
            <label>Hero Subtitle Description</label>
            <textarea name="settings[hero_subtitle]" class="admin-textarea"><?= e($settingsMap['hero_subtitle'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- 4. Default SEO Settings -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Default Search Engine Optimization (SEO)</h2>
        </div>

        <div class="admin-form-group">
            <label>Default Page Title (Browser Tab)</label>
            <input type="text" name="settings[default_meta_title]" class="admin-input" value="<?= e($settingsMap['default_meta_title'] ?? '') ?>">
            <span class="help-text">Recommended length: 50–65 characters</span>
        </div>

        <div class="admin-form-group">
            <label>Default Meta Description</label>
            <textarea name="settings[default_meta_desc]" class="admin-textarea" style="min-height: 80px;"><?= e($settingsMap['default_meta_desc'] ?? '') ?></textarea>
            <span class="help-text">Recommended length: 140–160 characters</span>
        </div>

        <div class="admin-form-group">
            <label>Meta Keywords</label>
            <input type="text" name="settings[default_keywords]" class="admin-input" value="<?= e($settingsMap['default_keywords'] ?? '') ?>">
        </div>
    </div>

    <div style="position: sticky; bottom: 1.5rem; z-index: 50; background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); padding: 1rem 1.5rem; border: 1px solid var(--admin-border); border-radius: var(--radius); box-shadow: var(--shadow-md); display: flex; justify-content: flex-end;">
        <button type="submit" class="btn-admin btn-admin-gold" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
            <?= renderIcon('check', '', 18) ?>
            <span>Save All Settings</span>
        </button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
