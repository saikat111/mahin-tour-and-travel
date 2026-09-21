<?php
/**
 * Mahin Travel & Tours - Frequently Asked Questions
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Fetch All Active FAQs
$stmt = $pdo->query("SELECT * FROM faqs WHERE status = 1 ORDER BY sort_order ASC, id ASC");
$faqs = $stmt->fetchAll();

// Group FAQs by category
$grouped = [];
foreach ($faqs as $f) {
    $cat = ucfirst($f['category'] ?: 'General');
    $grouped[$cat][] = $f;
}

$pageTitle = "Frequently Asked Questions (FAQ)";
$pageDescription = "Answers to common traveler questions regarding visa processing, flight ticketing, baggage allowances, and Umrah packages at Mahin Travel & Tours.";
$currentPage = 'faq';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-faq.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>FAQs</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('file-text', '', 16) ?>
            <span>Knowledge Base</span>
        </div>
        <h1 class="page-hero-title">
            Frequently Asked Questions
        </h1>
        <p class="page-hero-desc">
            Transparent answers to common questions about international visas, flight bookings, passport validity, and pilgrimage travel.
        </p>
    </div>
</section>

<!-- FAQs Content -->
<section style="padding: 5rem 0 6rem 0;">
    <div class="container">
        <div style="max-width: 860px; margin: 0 auto;">
            <?php foreach ($grouped as $categoryName => $items): ?>
                <div style="margin-bottom: 3.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--border-light);">
                        <span class="visa-badge" style="background: var(--bronze-tint); color: var(--bronze); font-size: 0.85rem; font-weight: 700;">
                            <?= e($categoryName) ?> Inquiries
                        </span>
                    </div>

                    <div class="faq-accordion">
                        <?php foreach ($items as $idx => $faq): ?>
                            <div class="faq-item <?= $idx === 0 ? 'active' : '' ?>">
                                <button class="faq-question" type="button">
                                    <span><?= e($faq['question']) ?></span>
                                    <span class="faq-toggle-icon"><?= renderIcon('chevron-down', '', 18) ?></span>
                                </button>
                                <div class="faq-answer" style="<?= $idx === 0 ? 'max-height: 250px;' : '' ?>">
                                    <div class="faq-answer-inner">
                                        <p><?= e($faq['answer']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div style="text-align: center; margin-top: 3.5rem; padding: 2.5rem; background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-light);">
                <h3 style="font-size: 1.35rem; color: var(--navy-primary); margin-bottom: 0.5rem;">Still Have Questions?</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
                    Our travel consultants in Gazipur are available to answer your specific questions.
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm">
                        <?= renderIcon('whatsapp', '', 16) ?>
                        <span>Ask via WhatsApp</span>
                    </a>
                    <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-sm">
                        <?= renderIcon('mail', '', 16) ?>
                        <span>Send an Inquiry</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
