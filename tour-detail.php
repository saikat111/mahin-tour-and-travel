<?php
/**
 * Mahin Travel & Tours - Tour Package Itinerary & Inclusions
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$slug = cleanInput($_GET['slug'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM tours WHERE slug = ? AND status = 1 LIMIT 1");
$stmt->execute([$slug]);
$tour = $stmt->fetch();

if (!$tour) {
    header("Location: " . url('tours.php'));
    exit;
}

// Decode JSON Itinerary
$itinerary = [];
if (!empty($tour['itinerary_json'])) {
    $itinerary = json_decode($tour['itinerary_json'], true) ?: [];
}

// Fetch other packages
$otherStmt = $pdo->prepare("SELECT id, title, slug, duration_days, duration_nights, price_text FROM tours WHERE id != ? AND status = 1 LIMIT 4");
$otherStmt->execute([$tour['id']]);
$otherTours = $otherStmt->fetchAll();

$pageTitle = !empty($tour['meta_title']) ? $tour['meta_title'] : $tour['title'];
$pageDescription = !empty($tour['meta_desc']) ? $tour['meta_desc'] : substr(strip_tags($tour['overview']), 0, 160);
$currentPage = 'tours';

$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);

require_once __DIR__ . '/includes/header.php';
?>

<?php
$tourHeroImg = !empty($tour['featured_image']) ? url($tour['featured_image']) : url('assets/images/hero-tours.jpg');
?>
<!-- Tour Detail Hero -->
<section class="page-hero" style="background-image: url('<?= $tourHeroImg ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; 
            <a href="<?= url('tours.php') ?>">Tour Packages</a> &nbsp;/&nbsp; 
            <span><?= e($tour['title']) ?></span>
        </div>
        <span class="tour-duration-tag" style="position: static; display: inline-block; margin-bottom: 1rem;">
            <?= e($tour['duration_days']) ?> Days / <?= e($tour['duration_nights']) ?> Nights
        </span>
        <h1 class="page-hero-title">
            <?= e($tour['title']) ?>
        </h1>
        <p class="page-hero-desc">
            <?= e($tour['overview']) ?>
        </p>
    </div>
</section>

<!-- Tour Details & Itinerary Grid -->
<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <div class="contact-wrapper" style="grid-template-columns: 1.8fr 1fr; gap: 3.5rem;">
            <!-- Left: Tour Details, Itinerary & Inclusions -->
            <div>
                <?php if (!empty($tour['featured_image'])): ?>
                    <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2.5rem; box-shadow: var(--shadow-md);">
                        <img src="<?= url($tour['featured_image']) ?>" alt="<?= e($tour['title']) ?>" style="width: 100%; height: auto;">
                    </div>
                <?php endif; ?>

                <!-- Day by Day Itinerary -->
                <?php if (!empty($itinerary)): ?>
                    <div style="margin-bottom: 3.5rem;">
                        <span class="section-tag" style="margin-bottom: 0.75rem;">Daily Roadmap</span>
                        <h2 style="font-size: 1.65rem; color: var(--navy-primary); margin-bottom: 1.5rem;">Day-by-Day Itinerary</h2>

                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            <?php foreach ($itinerary as $day): ?>
                                <div style="background: var(--card-bg); border-radius: var(--radius-md); border: 1px solid var(--border-card); padding: 1.75rem; box-shadow: var(--shadow-sm);">
                                    <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 0.75rem;">
                                        <span class="step-number" style="width: 32px; height: 32px; font-size: 0.9rem; margin: 0; box-shadow: none;">
                                            <?= e($day['day'] ?? '') ?>
                                        </span>
                                        <h4 style="font-size: 1.15rem; color: var(--navy-primary);"><?= e($day['title'] ?? '') ?></h4>
                                    </div>
                                    <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.65; padding-left: 2.75rem;">
                                        <?= e($day['desc'] ?? '') ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Inclusions & Exclusions -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
                    <!-- Inclusions -->
                    <div style="background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-light); padding: 2rem;">
                        <h4 style="font-size: 1.2rem; color: #12633C; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                            <?= renderIcon('check', '', 20) ?>
                            <span>Package Inclusions</span>
                        </h4>
                        <div style="font-size: 0.925rem; color: var(--text-muted); line-height: 1.8;">
                            <?= nl2br(e($tour['inclusions'] ?? '')) ?>
                        </div>
                    </div>

                    <!-- Exclusions -->
                    <div style="background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-light); padding: 2rem;">
                        <h4 style="font-size: 1.2rem; color: #8A4020; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                            <?= renderIcon('file-text', '', 20) ?>
                            <span>Package Exclusions</span>
                        </h4>
                        <div style="font-size: 0.925rem; color: var(--text-muted); line-height: 1.8;">
                            <?= nl2br(e($tour['exclusions'] ?? '')) ?>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?= getWhatsAppUrl('Hello! I would like to book or inquire about the package: ' . $tour['title']) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>Inquire on WhatsApp</span>
                    </a>
                    <a href="<?= getTelUrl($primaryPhone) ?>" class="btn btn-navy">
                        <?= renderIcon('phone', '', 18) ?>
                        <span>Call <?= e($primaryPhone) ?></span>
                    </a>
                </div>
            </div>

            <!-- Right: Quote Request & Pricing -->
            <div>
                <!-- Pricing & Inquiry Box -->
                <div class="contact-form-panel" style="padding: 2.25rem; margin-bottom: 2rem;">
                    <div style="margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border-light);">
                        <span style="font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">
                            Package Rate
                        </span>
                        <div style="font-family: var(--font-heading); font-size: 1.85rem; font-weight: 800; color: var(--bronze); margin-top: 0.2rem;">
                            <?= e($tour['price_text']) ?>
                        </div>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Customized based on travel dates and room requirements</span>
                    </div>

                    <h3 style="font-size: 1.25rem; color: var(--navy-primary); margin-bottom: 0.5rem;">Book or Request Quote</h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                        Our team will contact you with exact airfares and hotel availability.
                    </p>

                    <form action="<?= url('contact.php') ?>" method="POST" class="ajax-contact-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="service_type" value="Tour: <?= e($tour['title']) ?>">

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Your Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Mohammad Rahim" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Phone / WhatsApp Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. +88017xxxxxxxx" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="yourname@gmail.com">
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label>Travel Dates & Travelers *</label>
                            <textarea name="message" class="form-control" style="min-height: 80px;" placeholder="Estimated travel date, number of adults/children..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <?= renderIcon('check', '', 18) ?>
                            <span>Request Booking Details</span>
                        </button>
                    </form>
                </div>

                <!-- Gazipur Office Card -->
                <div style="background: var(--navy-primary); color: #FFFFFF; border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem;">
                    <h4 style="color: #FFFFFF; font-size: 1.15rem; margin-bottom: 1rem;">Consult Our Travel Planners</h4>
                    <p style="color: #C0D0E4; font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        <?= e($officeAddress) ?>
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.88rem; color: var(--champagne);">
                        <span>📞 <?= e($primaryPhone) ?></span>
                        <span>💬 WhatsApp: <?= e($whatsappNumber) ?></span>
                    </div>
                </div>

                <!-- Other Tour Packages -->
                <?php if (!empty($otherTours)): ?>
                    <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 1.75rem;">
                        <h4 style="font-size: 1.1rem; color: var(--navy-primary); margin-bottom: 1rem;">Other Featured Packages</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                            <?php foreach ($otherTours as $ot): ?>
                                <a href="<?= url('tour-detail.php?slug=' . urlencode($ot['slug'])) ?>" style="font-size: 0.9rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid var(--border-light);">
                                    <span><?= e($ot['title']) ?></span>
                                    <span style="font-size: 0.78rem; color: var(--bronze); font-weight: 600;"><?= e($ot['duration_days']) ?>D</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
