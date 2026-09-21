<?php
/**
 * Mahin Travel & Tours - Tour & Umrah Packages Directory
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Fetch Active Tour Packages
$stmt = $pdo->query("SELECT * FROM tours WHERE status = 1 ORDER BY is_featured DESC, sort_order ASC, id ASC");
$tours = $stmt->fetchAll();

$pageTitle = "Holiday & Umrah Packages Gazipur";
$pageDescription = "Explore carefully organized holiday tour packages to Dubai, Malaysia, Thailand, and sacred Umrah pilgrimage journeys with Mahin Travel & Tours.";
$currentPage = 'tours';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-tours.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Tour Packages</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('map-marked-alt', '', 16) ?>
            <span>Curated Journeys & Pilgrimages</span>
        </div>
        <h1 class="page-hero-title">
            Curated Holiday & Umrah Packages
        </h1>
        <p class="page-hero-desc">
            Thoughtfully planned itineraries combining verified accommodations, reliable transfers, and expert guidance. Travel with complete peace of mind.
        </p>
    </div>
</section>

<!-- Tours Grid -->
<section style="padding: 5rem 0 6rem 0;">
    <div class="container">
        <?php if (empty($tours)): ?>
            <div style="text-align: center; padding: 4rem 2rem; background: var(--card-bg); border-radius: var(--radius-md); border: 1px solid var(--border-light);">
                <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 1.5rem;">New custom tour packages are currently being updated. Contact our office for personalized itineraries.</p>
                <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-sm">Contact Our Office</a>
            </div>
        <?php else: ?>
            <div class="tours-grid">
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="tour-image-wrap">
                            <img src="<?= url($tour['featured_image']) ?>" alt="<?= e($tour['title']) ?>" loading="lazy">
                            <span class="tour-duration-tag">
                                <?= e($tour['duration_days']) ?> Days / <?= e($tour['duration_nights']) ?> Nights
                            </span>
                        </div>

                        <div class="tour-body">
                            <div>
                                <h3 class="tour-title"><?= e($tour['title']) ?></h3>
                                <p class="tour-overview"><?= e($tour['overview']) ?></p>
                            </div>

                            <div class="tour-footer">
                                <div class="tour-price-box">
                                    <span class="tour-price-label">Package Rate</span>
                                    <span class="tour-price-val"><?= e($tour['price_text']) ?></span>
                                </div>
                                <div style="display:flex; gap:0.5rem;">
                                    <a href="<?= url('tour-detail.php?slug=' . urlencode($tour['slug'])) ?>" class="btn btn-outline btn-sm">
                                        <span>Itinerary</span>
                                    </a>
                                    <a href="#" data-inquiry-modal data-service-name="Tour: <?= e($tour['title']) ?>" class="btn btn-primary btn-sm">
                                        <span>Inquire</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
