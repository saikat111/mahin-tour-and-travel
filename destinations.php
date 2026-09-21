<?php
/**
 * Mahin Travel & Tours - Worldwide & Regional Destinations
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Fetch Active Destinations
$stmt = $pdo->query("SELECT * FROM destinations WHERE status = 1 ORDER BY sort_order ASC, id ASC");
$destinations = $stmt->fetchAll();

$pageTitle = "Global & Domestic Destinations Guide";
$pageDescription = "Explore inspiring travel destinations across the Middle East, Southeast Asia, Europe, and Bangladesh curated by Mahin Travel & Tours.";
$currentPage = 'destinations';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-destinations.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Destinations</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('compass', '', 16) ?>
            <span>Global Horizons</span>
        </div>
        <h1 class="page-hero-title">
            Explore Our Featured Destinations
        </h1>
        <p class="page-hero-desc">
            Discover top travel hubs, key attractions, optimal visiting seasons, and visa guidelines across our curated travel network.
        </p>
    </div>
</section>

<!-- Destinations Grid -->
<section style="padding: 5rem 0 6rem 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 2rem;">
            <?php foreach ($destinations as $dst): ?>
                <div style="background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-card); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; transition: var(--transition-smooth);">
                    <div style="height: 220px; overflow: hidden; position: relative;">
                        <img src="<?= url($dst['featured_image']) ?>" alt="<?= e($dst['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <span class="visa-badge" style="position: absolute; top: 1rem; right: 1rem; background: rgba(6,21,43,0.85); color: var(--champagne); border-color: rgba(255,255,255,0.2);">
                            <?= e($dst['continent']) ?>
                        </span>
                    </div>

                    <div style="padding: 2rem; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between;">
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 700; color: var(--bronze); text-transform: uppercase; letter-spacing: 0.08em; display: block; margin-bottom: 0.35rem;">
                                <?= e($dst['country']) ?>
                            </span>
                            <h3 style="font-size: 1.4rem; color: var(--navy-primary); margin-bottom: 0.75rem;">
                                <?= e($dst['title']) ?>
                            </h3>
                            <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 1.25rem;">
                                <?= e($dst['overview']) ?>
                            </p>

                            <div style="margin-bottom: 1.25rem; font-size: 0.88rem;">
                                <div style="margin-bottom: 0.35rem; color: var(--navy-light);">
                                    <strong>Popular Attractions:</strong> <?= e($dst['popular_for']) ?>
                                </div>
                                <?php if (!empty($dst['best_time'])): ?>
                                    <div style="color: var(--text-muted);">
                                        <strong>Best Time:</strong> <?= e($dst['best_time']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.75rem; border-top: 1px solid var(--border-light); padding-top: 1.25rem;">
                            <a href="<?= url('visa-services.php') ?>" class="btn btn-outline btn-sm" style="flex: 1;">
                                <span>Visa Guide</span>
                            </a>
                            <a href="<?= getWhatsAppUrl('Hello! I would like to plan a tour to ' . $dst['title'] . '.') ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm" title="Consult on WhatsApp">
                                <?= renderIcon('whatsapp', '', 16) ?>
                                <span>Inquire</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
