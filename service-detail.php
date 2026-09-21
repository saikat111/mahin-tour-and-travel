<?php
/**
 * Mahin Travel & Tours - Single Service Detail
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$slug = cleanInput($_GET['slug'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM services WHERE slug = ? AND status = 1 LIMIT 1");
$stmt->execute([$slug]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: " . url('services.php'));
    exit;
}

// Fetch other services for sidebar
$relatedStmt = $pdo->prepare("SELECT id, title, slug FROM services WHERE id != ? AND status = 1 LIMIT 5");
$relatedStmt->execute([$service['id']]);
$relatedServices = $relatedStmt->fetchAll();

$pageTitle = !empty($service['meta_title']) ? $service['meta_title'] : $service['title'];
$pageDescription = !empty($service['meta_desc']) ? $service['meta_desc'] : $service['short_desc'];
$currentPage = 'services';

$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);

require_once __DIR__ . '/includes/header.php';
?>

<?php
$serviceHeroImg = !empty($service['featured_image']) ? url($service['featured_image']) : url('assets/images/hero-services.jpg');
?>
<!-- Detail Hero -->
<section class="page-hero" style="background-image: url('<?= $serviceHeroImg ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; 
            <a href="<?= url('services.php') ?>">Services</a> &nbsp;/&nbsp; 
            <span><?= e($service['title']) ?></span>
        </div>
        <span class="visa-badge" style="background: rgba(185,139,98,0.25); border-color: rgba(223,184,146,0.45); color: var(--champagne); margin-bottom: 1rem; display: inline-block;">
            <?= e($service['category']) ?>
        </span>
        <h1 class="page-hero-title">
            <?= e($service['title']) ?>
        </h1>
        <p class="page-hero-desc">
            <?= e($service['short_desc']) ?>
        </p>
    </div>
</section>

<!-- Main Detail Content & Sidebar -->
<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <div class="contact-wrapper" style="grid-template-columns: 1.8fr 1fr; gap: 3.5rem;">
            <!-- Left: Main Detail Content -->
            <div>
                <?php if (!empty($service['featured_image'])): ?>
                    <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2.5rem; box-shadow: var(--shadow-md);">
                        <img src="<?= url($service['featured_image']) ?>" alt="<?= e($service['title']) ?>" style="width: 100%; height: auto;">
                    </div>
                <?php endif; ?>

                <div style="background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-card); padding: 2.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem;">
                    <h2 style="font-size: 1.6rem; color: var(--navy-primary); margin-bottom: 1.25rem;">Overview & Service Details</h2>
                    <div style="font-size: 1.05rem; color: var(--text-muted); line-height: 1.8;">
                        <p style="margin-bottom: 1.5rem;"><?= nl2br(e($service['full_desc'])) ?></p>
                    </div>

                    <div style="margin-top: 2rem; padding-top: 1.75rem; border-top: 1px solid var(--border-light);">
                        <h4 style="font-size: 1.2rem; color: var(--navy-primary); margin-bottom: 1rem;">Why Consult With Mahin Travel & Tours?</h4>
                        <ul style="display: flex; flex-direction: column; gap: 0.85rem;">
                            <li style="display: flex; gap: 0.75rem; align-items: flex-start; font-size: 0.95rem; color: var(--text-muted);">
                                <?= renderIcon('check', '', 18) ?>
                                <span><strong>Procedural Compliance:</strong> Strict adherence to current embassy and airline mandates.</span>
                            </li>
                            <li style="display: flex; gap: 0.75rem; align-items: flex-start; font-size: 0.95rem; color: var(--text-muted);">
                                <?= renderIcon('check', '', 18) ?>
                                <span><strong>Transparent Pricing:</strong> No unexpected fees or hidden markups.</span>
                            </li>
                            <li style="display: flex; gap: 0.75rem; align-items: flex-start; font-size: 0.95rem; color: var(--text-muted);">
                                <?= renderIcon('check', '', 18) ?>
                                <span><strong>In-Person & Remote Support:</strong> Available at our Gazipur office or directly on WhatsApp.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?= getWhatsAppUrl('Hello! I would like to consult regarding ' . $service['title'] . '.') ?>" target="_blank" rel="noopener" class="btn btn-whatsapp">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>WhatsApp Inquiry</span>
                    </a>
                    <a href="<?= getTelUrl($primaryPhone) ?>" class="btn btn-navy">
                        <?= renderIcon('phone', '', 18) ?>
                        <span>Call <?= e($primaryPhone) ?></span>
                    </a>
                </div>
            </div>

            <!-- Right: Direct Inquiry Form & Sidebar -->
            <div>
                <!-- Consultation Form Card -->
                <div class="contact-form-panel" style="padding: 2.25rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.35rem; color: var(--navy-primary); margin-bottom: 0.5rem;">Request Consultation</h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                        Submit your details to discuss this service with our consultants.
                    </p>

                    <form action="<?= url('contact.php') ?>" method="POST" class="ajax-contact-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="service_type" value="<?= e($service['title']) ?>">

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Full Name *</label>
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
                            <label>Your Message / Requirements *</label>
                            <textarea name="message" class="form-control" style="min-height: 90px;" placeholder="Describe your travel dates, passport details, or specific queries..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <?= renderIcon('check', '', 18) ?>
                            <span>Send Consultation Request</span>
                        </button>
                    </form>
                </div>

                <!-- Office Contact Card -->
                <div style="background: var(--navy-primary); color: #FFFFFF; border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem;">
                    <h4 style="color: #FFFFFF; font-size: 1.15rem; margin-bottom: 1rem;">Visit Our Gazipur Office</h4>
                    <p style="color: #C0D0E4; font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        <?= e($officeAddress) ?>
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.88rem; color: var(--champagne);">
                        <span>📞 <?= e($primaryPhone) ?></span>
                        <span>💬 WhatsApp: <?= e($whatsappNumber) ?></span>
                    </div>
                </div>

                <!-- Other Services Navigation -->
                <?php if (!empty($relatedServices)): ?>
                    <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 1.75rem;">
                        <h4 style="font-size: 1.1rem; color: var(--navy-primary); margin-bottom: 1rem;">Other Travel Services</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                            <?php foreach ($relatedServices as $rel): ?>
                                <a href="<?= url('service-detail.php?slug=' . urlencode($rel['slug'])) ?>" style="font-size: 0.9rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid var(--border-light);">
                                    <span><?= e($rel['title']) ?></span>
                                    <?= renderIcon('arrow-right', '', 14) ?>
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
