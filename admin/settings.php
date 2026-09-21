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
    } elseif (($_POST['action'] ?? '') === 'send_test_email') {
        require_once __DIR__ . '/../includes/mailer.php';
        $testTo = cleanInput($_POST['test_recipient'] ?? '');
        if (empty($testTo) || !filter_var($testTo, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('error', 'Please specify a valid recipient email address for the diagnostic test.');
        } else {
            $mailer = new SMTPMailer();
            $subject = "Mahin Travel & Tours - Live SMTP Mailer Verification";
            $message = "<div style='font-family: Arial, sans-serif; padding: 24px; color: #0A2240; background: #FDFBF7; border: 1px solid #DFB892; border-radius: 8px;'>" .
                       "<h2 style='color: #B98B62; margin-top: 0;'>Outbound Authenticated SMTP Test Passed!</h2>" .
                       "<p>Congratulations! Your <strong>Mahin Travel & Tours</strong> cPanel authenticated SMTP mailer is fully operational on <strong>mail.mahintravelandtours.com:465 (SSL)</strong>.</p>" .
                       "<p>Diagnostic details:<br>" .
                       "&bull; Outbound Server: <code>mail.mahintravelandtours.com:465</code><br>" .
                       "&bull; Authenticated User: <code>support@mahintravelandtours.com</code><br>" .
                       "&bull; Security: <code>SSL Socket Handshake</code><br>" .
                       "&bull; Sent at: <strong>" . date('r') . "</strong></p>" .
                       "<hr style='border: none; border-top: 1px solid #ECE7E1; margin: 16px 0;'>" .
                       "<p style='font-size: 12px; color: #8A98A8;'>Mahin Travel & Tours &bull; Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh</p>" .
                       "</div>";
            $sent = $mailer->send($testTo, $subject, $message);
            if ($sent) {
                setFlashMessage('success', "Live SMTP test email successfully delivered to {$testTo} via mail.mahintravelandtours.com:465!");
            } else {
                setFlashMessage('error', "SMTP Delivery Failed: " . e($mailer->getLastError()));
            }
        }
        header("Location: " . ADMIN_URL . "/settings.php");
        exit;
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

    <!-- 2. Authenticated Outbound SMTP Mail Configuration (cPanel Shared Hosting) -->
    <div class="admin-card">
        <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="admin-card-title">Authenticated SMTP Email (cPanel Shared Hosting)</h2>
                <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">
                    Configured for cPanel mail server with SSL on Port 465. Bypasses restricted PHP <code>mail()</code> on shared hosting.
                </p>
            </div>
            <span style="background: rgba(37,211,102,0.15); color: #128C7E; font-weight: 700; padding: 4px 12px; border-radius: 20px; font-size: 12px; white-space: nowrap;">
                SSL Port 465
            </span>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>SMTP Host</label>
                <input type="text" name="settings[smtp_host]" class="admin-input" value="<?= e($settingsMap['smtp_host'] ?? SMTP_HOST) ?>" required>
                <span class="help-text">Outgoing server (e.g. mail.mahintravelandtours.com)</span>
            </div>

            <div class="admin-form-group">
                <label>SMTP Port</label>
                <input type="number" name="settings[smtp_port]" class="admin-input" value="<?= e($settingsMap['smtp_port'] ?? (string)SMTP_PORT) ?>" required>
                <span class="help-text">465 for SSL (recommended) or 587 for TLS</span>
            </div>

            <div class="admin-form-group">
                <label>Encryption Protocol</label>
                <select name="settings[smtp_secure]" class="admin-input">
                    <option value="ssl" <?= ($settingsMap['smtp_secure'] ?? SMTP_SECURE) === 'ssl' ? 'selected' : '' ?>>SSL (Port 465 - Recommended)</option>
                    <option value="tls" <?= ($settingsMap['smtp_secure'] ?? SMTP_SECURE) === 'tls' ? 'selected' : '' ?>>TLS / STARTTLS (Port 587)</option>
                </select>
                <span class="help-text">Socket transport encryption</span>
            </div>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>SMTP Username / Account</label>
                <input type="text" name="settings[smtp_user]" class="admin-input" value="<?= e($settingsMap['smtp_user'] ?? SMTP_USER) ?>" required>
                <span class="help-text">cPanel mailbox user (e.g. support@mahintravelandtours.com)</span>
            </div>

            <div class="admin-form-group">
                <label>SMTP Password</label>
                <input type="password" name="settings[smtp_pass]" class="admin-input" value="<?= e($settingsMap['smtp_pass'] ?? SMTP_PASS) ?>" required>
                <span class="help-text">cPanel mailbox authentication password</span>
            </div>

            <div class="admin-form-group">
                <label>Staff Lead Notification Recipient</label>
                <input type="email" name="settings[mail_notification_recipient]" class="admin-input" value="<?= e($settingsMap['mail_notification_recipient'] ?? MAIL_NOTIFICATION_RECIPIENT) ?>" required>
                <span class="help-text">Receives real-time lead alerts when visitors submit inquiries</span>
            </div>
        </div>

        <div class="form-row">
            <div class="admin-form-group">
                <label>Outbound 'From' Email</label>
                <input type="email" name="settings[smtp_from_email]" class="admin-input" value="<?= e($settingsMap['smtp_from_email'] ?? SMTP_FROM_EMAIL) ?>" required>
                <span class="help-text">Sender address on customer receipts</span>
            </div>

            <div class="admin-form-group">
                <label>Outbound 'From' Name</label>
                <input type="text" name="settings[smtp_from_name]" class="admin-input" value="<?= e($settingsMap['smtp_from_name'] ?? SMTP_FROM_NAME) ?>" required>
                <span class="help-text">Company name shown in inbox (e.g. Mahin Travel & Tours)</span>
            </div>
        </div>
    </div>

    <!-- 3. Brand Identity -->
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

<!-- 5. Standalone Live SMTP Delivery Diagnostic Test -->
<div class="admin-card" style="margin-top: 2rem; border-left: 4px solid var(--gold); background: #FFFFFF;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="admin-card-title">Live SMTP Server Diagnostic Test</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">
                Dispatch a real-time probe via <strong>mail.mahintravelandtours.com:465 (SSL)</strong> to test socket connectivity, authentication handshake, and inbox delivery.
            </p>
        </div>
        <span style="font-size: 12px; font-weight: 600; color: var(--gold); background: rgba(185,139,98,0.12); padding: 4px 10px; border-radius: 4px;">
            Handshake Diagnostic
        </span>
    </div>

    <form action="<?= ADMIN_URL ?>/settings.php" method="POST" style="margin-top: 1rem; display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="send_test_email">

        <div class="admin-form-group" style="flex: 1; min-width: 280px; margin-bottom: 0;">
            <label>Recipient Email Address for Test Delivery</label>
            <input type="email" name="test_recipient" class="admin-input" value="<?= e($settingsMap['mail_notification_recipient'] ?? MAIL_NOTIFICATION_RECIPIENT) ?>" placeholder="e.g. support@mahintravelandtours.com" required>
            <span class="help-text">Where the verification email should be delivered</span>
        </div>

        <button type="submit" class="btn-admin btn-admin-primary" style="padding: 0.75rem 1.5rem; height: 42px; display: inline-flex; align-items: center; gap: 8px;">
            <?= renderIcon('mail', '', 18) ?>
            <span>Send Live Test Email</span>
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
