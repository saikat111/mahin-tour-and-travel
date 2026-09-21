<?php
/**
 * Mahin Travel & Tours - Terms and Conditions
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);

$pageTitle = "Terms and Conditions";
$pageDescription = "Terms of Service, Booking Conditions and Visa Advisory Disclaimers for Mahin Travel & Tours.";
$currentPage = '';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-bg.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Terms & Conditions</span>
        </div>
        <h1 class="page-hero-title">Terms & Conditions</h1>
        <p class="page-hero-desc">Service Terms, Booking Policies & Ethical Visa Advisory Disclaimers</p>
    </div>
</section>

<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <div style="max-width: 840px; margin: 0 auto; background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-light); padding: 3rem; box-shadow: var(--shadow-sm); line-height: 1.8; color: var(--text-muted);">
            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">1. Visa Advisory & Consular Authority</h3>
            <p style="margin-bottom: 1.75rem;">
                <?= e($siteName) ?> acts as an independent advisory agency assisting travelers with document organization, form compilation, and appointment scheduling. <strong>We do not possess authority to grant or reject visas.</strong> The final decision to issue or refuse a visa, the duration of stay permitted, and the validity granted are the exclusive sovereign prerogative of the destination country’s immigration authorities and consular officers.
            </p>

            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">2. Document Accuracy & Client Responsibility</h3>
            <p style="margin-bottom: 1.75rem;">
                Clients are responsible for the authenticity, truthfulness, and legal validity of all personal records, financial statements, trade licenses, and certificates submitted to our agency. Providing fraudulent or falsified records carries severe legal penalties under immigration law and results in immediate forfeiture of service.
            </p>

            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">3. Airline Tickets & Tour Package Conditions</h3>
            <p style="margin-bottom: 1.75rem;">
                Airfares are subject to the specific fare rules and conditions of the issuing airline. Date changes, route amendments, and refunds are processed in compliance with the carrier’s published policies. Tour package itineraries are subject to weather, local operational conditions, and force majeure events.
            </p>

            <h3 style="color: var(--navy-primary); font-size: 1.35rem; margin-bottom: 0.75rem;">4. Transparent Receipts & Invoicing</h3>
            <p>
                Formal money receipts are issued for all payments made to <?= e($siteName) ?>. Clients are advised to preserve their receipt copies for all file references.
            </p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
