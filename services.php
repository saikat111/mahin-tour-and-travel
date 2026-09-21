<?php
/**
 * Mahin Travel & Tours - Services Directory
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// Fetch Category Filter
$selectedCategory = cleanInput($_GET['cat'] ?? 'all');

if ($selectedCategory !== 'all' && !empty($selectedCategory)) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE status = 1 AND category = ? ORDER BY sort_order ASC, id ASC");
    $stmt->execute([$selectedCategory]);
} else {
    $stmt = $pdo->query("SELECT * FROM services WHERE status = 1 ORDER BY sort_order ASC, id ASC");
}
$services = $stmt->fetchAll();

// Fetch All Unique Categories for Filter
$catsStmt = $pdo->query("SELECT DISTINCT category FROM services WHERE status = 1 ORDER BY category ASC");
$categories = $catsStmt->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = "Travel & Visa Services in Gazipur";
$pageDescription = "Comprehensive travel services: international air ticketing, tourist visa assistance, Umrah pilgrimage packages, student visa guidance, and holiday tours.";
$currentPage = 'services';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-services.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Services</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('plane-departure', '', 16) ?>
            <span>Comprehensive Travel Solutions</span>
        </div>
        <h1 class="page-hero-title">
            Our Travel & Visa Services
        </h1>
        <p class="page-hero-desc">
            From accurate visa processing and competitive flight ticketing to spiritual Umrah arrangements and bespoke holiday packages, discover our full spectrum of services.
        </p>
    </div>
</section>

<!-- Category Filter & Services Grid -->
<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <!-- Category Filter Tabs -->
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center; margin-bottom: 3.5rem;">
            <a href="<?= url('services.php') ?>" class="btn btn-sm <?= $selectedCategory === 'all' ? 'btn-primary' : 'btn-outline' ?>">
                <span>All Services</span>
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('services.php?cat=' . urlencode($cat)) ?>" class="btn btn-sm <?= $selectedCategory === $cat ? 'btn-primary' : 'btn-outline' ?>">
                    <span><?= e($cat) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($services)): ?>
            <div style="text-align: center; padding: 4rem 2rem; background: var(--card-bg); border-radius: var(--radius-md); border: 1px solid var(--border-light);">
                <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 1.5rem;">No services currently available under this category.</p>
                <a href="<?= url('services.php') ?>" class="btn btn-primary btn-sm">View All Services</a>
            </div>
        <?php else: ?>
            <div class="services-grid">
                <?php foreach ($services as $srv): ?>
                    <div class="service-card">
                        <div>
                            <?php if (!empty($srv['featured_image'])): ?>
                                <div style="height: 180px; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1.5rem; position: relative;">
                                    <img src="<?= url($srv['featured_image']) ?>" alt="<?= e($srv['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" loading="lazy">
                                    <div style="position: absolute; top: 0.75rem; left: 0.75rem; width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(6,21,43,0.85); backdrop-filter: blur(4px); color: var(--champagne); display: flex; align-items: center; justify-content: center;">
                                        <?= renderIcon($srv['icon_name'], '', 20) ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="service-icon-wrap">
                                    <?= renderIcon($srv['icon_name'], '', 28) ?>
                                </div>
                            <?php endif; ?>
                            <span class="visa-badge" style="margin-bottom: 0.75rem; display: inline-block;">
                                <?= e($srv['category']) ?>
                            </span>
                            <h3 class="service-title"><?= e($srv['title']) ?></h3>
                            <p class="service-desc"><?= e($srv['short_desc']) ?></p>
                        </div>
                        <div style="display: flex; gap: 0.75rem; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-light); padding-top: 1.25rem;">
                            <a href="<?= url('service-detail.php?slug=' . urlencode($srv['slug'])) ?>" class="service-link">
                                <span>Learn More</span>
                                <?= renderIcon('arrow-right', '', 16) ?>
                            </a>
                            <a href="#" data-inquiry-modal data-service-name="<?= e($srv['title']) ?>" class="btn btn-primary btn-sm">
                                <span>Inquire</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
