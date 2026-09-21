<?php
/**
 * Mahin Travel & Tours - Visa Assistance Hub
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Fetch All Active Visa Categories
$stmt = $pdo->query("SELECT * FROM visa_categories WHERE status = 1 ORDER BY sort_order ASC, id ASC");
$visas = $stmt->fetchAll();

// Fetch Visa Process Steps
$stepsStmt = $pdo->query("SELECT * FROM visa_process_steps WHERE status = 1 ORDER BY step_number ASC");
$steps = $stepsStmt->fetchAll();

// Fetch Visa Specific FAQs
$faqStmt = $pdo->query("SELECT * FROM faqs WHERE status = 1 AND category = 'visa' ORDER BY sort_order ASC");
$visaFaqs = $faqStmt->fetchAll();

$pageTitle = "Visa Assistance & Advisory Services Gazipur";
$pageDescription = "Professional tourist, visit, and Umrah visa consultation in Gazipur. Complete document verification and embassy procedural support for UAE, Saudi Arabia, Malaysia, Thailand, Singapore, and UK/Schengen.";
$currentPage = 'visa';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-visa.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Visa Services</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('passport', '', 16) ?>
            <span>Dedicated Visa Advisory Hub</span>
        </div>
        <h1 class="page-hero-title">
            Comprehensive Visa Assistance & Procedural Advisory
        </h1>
        <p class="page-hero-desc">
            Meticulous document compilation, eligibility evaluation, and embassy submission guidance. We eliminate procedural errors so your application is presented with maximum clarity and credibility.
        </p>
    </div>
</section>

<!-- Compliance & Disclaimer Bar -->
<section style="background: var(--canvas-alt); padding: 1.5rem 0; border-bottom: 1px solid var(--border-light);">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="background: var(--bronze-tint); color: var(--bronze); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <?= renderIcon('shield-check', '', 20) ?>
            </div>
            <div style="flex: 1; min-width: 280px; font-size: 0.9rem; color: var(--text-muted); line-height: 1.5;">
                <strong>Consular Integrity Disclosure:</strong> Mahin Travel & Tours is a professional travel consultancy. We are not an embassy or consulate. Final visa decisions rest solely with sovereign immigration authorities. We strictly prepare compliant, verifiable documentation without false approval guarantees.
            </div>
        </div>
    </div>
</section>

<!-- Country Visa Directory Grid -->
<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Destination Directory</span>
            <h2 class="section-title">Popular Visa Destinations & Requirements</h2>
            <p class="section-subtitle">
                Select your intended travel destination to view processing timelines, validity details, and required document checklists.
            </p>
        </div>

        <div class="visa-countries-grid">
            <?php foreach ($visas as $visa): ?>
                <div class="visa-card">
                    <div>
                        <?php if (!empty($visa['image'])): ?>
                            <div style="height: 160px; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1.25rem;">
                                <img src="<?= url($visa['image']) ?>" alt="<?= e($visa['country_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                            </div>
                        <?php endif; ?>

                        <div class="visa-card-header">
                            <div class="visa-flag-title">
                                <span class="visa-type-tag"><?= e($visa['visa_type']) ?></span>
                                <h3 class="visa-country"><?= e($visa['country_name']) ?></h3>
                            </div>
                            <span class="visa-badge"><?= e($visa['processing_time']) ?></span>
                        </div>

                        <p class="visa-requirements">
                            <strong>Summary:</strong> <?= e($visa['requirement_summary']) ?>
                        </p>
                    </div>

                    <div>
                        <div class="visa-meta">
                            <span>Validity: <strong><?= e($visa['validity'] ?: 'Per Embassy') ?></strong></span>
                            <span style="color:var(--bronze); font-weight:600;">Full Checklist Available</span>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <a href="<?= url('visa-detail.php?slug=' . urlencode($visa['slug'])) ?>" class="btn btn-outline btn-sm" style="flex:1;">
                                <span>Detailed Guidelines</span>
                            </a>
                            <a href="<?= getWhatsAppUrl('Hello! I would like to inquire about visa requirements for ' . $visa['country_name'] . '.') ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm" title="Consult via WhatsApp">
                                <?= renderIcon('whatsapp', '', 16) ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 5-Step Process Roadmap -->
        <div class="section-header" style="margin-top: 5rem; margin-bottom: 3rem;">
            <span class="section-tag">Step-by-Step Procedure</span>
            <h2 class="section-title">Our Transparent Visa Processing Roadmap</h2>
            <p class="section-subtitle">
                We handle each case with systematic care to ensure every requirement is met before formal embassy filing.
            </p>
        </div>

        <div class="process-grid">
            <?php foreach ($steps as $st): ?>
                <div class="process-step">
                    <div class="step-number"><?= e($st['step_number']) ?></div>
                    <h4 class="step-title"><?= e($st['title']) ?></h4>
                    <p class="step-desc"><?= e($st['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Visa FAQs -->
<?php if (!empty($visaFaqs)): ?>
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Visa Questions</span>
            <h2 class="section-title">Frequently Asked Visa Questions</h2>
            <p class="section-subtitle">Key facts regarding passport validity, approval jurisdiction, and document standards.</p>
        </div>

        <div class="faq-accordion">
            <?php foreach ($visaFaqs as $idx => $faq): ?>
                <div class="faq-item <?= $idx === 0 ? 'active' : '' ?>">
                    <button class="faq-question" type="button">
                        <span><?= e($faq['question']) ?></span>
                        <span class="faq-toggle-icon"><?= renderIcon('chevron-down', '', 18) ?></span>
                    </button>
                    <div class="faq-answer" style="<?= $idx === 0 ? 'max-height: 200px;' : '' ?>">
                        <div class="faq-answer-inner">
                            <p><?= e($faq['answer']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
