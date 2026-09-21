<?php
/**
 * Mahin Travel & Tours - Privacy Policy
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);
$contactEmail = getSetting('contact_email', DEFAULT_EMAIL);

$pageTitle = "Privacy Policy";
$pageDescription = "Privacy Policy and Client Data Protection Standards of Mahin Travel & Tours.";
$currentPage = '';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-bg.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Privacy Policy</span>
        </div>
        <h1 class="page-hero-title">Privacy Policy</h1>
        <p class="page-hero-desc">Last Updated: <?= date('F Y') ?> &bull; Client Data Protection & Confidentiality Standards</p>
    </div>
</section>

<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <div style="max-width: 840px; margin: 0 auto; background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-light); padding: 3rem; box-shadow: var(--shadow-sm); line-height: 1.8; color: var(--text-muted);">
            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">1. Commitment to Client Confidentiality</h3>
            <p style="margin-bottom: 1.75rem;">
                At <?= e($siteName) ?>, we recognize the sensitive nature of international travel documentation. When assisting you with visa filings, flight bookings, and tour coordination, we collect personal information solely for the purpose of fulfilling your requested travel arrangements.
            </p>

            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">2. Information We Collect</h3>
            <p style="margin-bottom: 1.75rem;">
                Depending on the service requested, we may collect:
                <ul style="list-style: disc; margin-left: 1.5rem; margin-top: 0.5rem;">
                    <li>Full Name, Contact Telephone, WhatsApp number, and Email Address.</li>
                    <li>Passport biodata information, date of birth, and nationality.</li>
                    <li>Travel dates, flight routing preferences, and hotel accommodation requests.</li>
                    <li>Supporting documents required by sovereign embassies (e.g. employment letters, bank statements, photographs) provided voluntarily for visa application dossiers.</li>
                </ul>
            </p>

            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">3. How We Use and Protect Your Data</h3>
            <p style="margin-bottom: 1.75rem;">
                Your documentation is strictly used for application form processing, authorized VFS/embassy submission, airline ticket issuance, and hotel vouchers. We do not sell, rent, or monetize personal client information to third-party marketing companies. Physical documents entrusted to our Gazipur office are kept under secure administrative supervision.
            </p>

            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">4. Contact Regarding Data Privacy</h3>
            <p>
                If you have questions regarding our data retention practices or wish to review information held on file, contact our office at:
                <br><strong><?= e($siteName) ?></strong><br>
                <?= e($officeAddress) ?><br>
                Email: <?= e($contactEmail) ?>
            </p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
