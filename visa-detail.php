<?php
/**
 * Mahin Travel & Tours - Country Visa Specification & Checklist
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$slug = cleanInput($_GET['slug'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM visa_categories WHERE slug = ? AND status = 1 LIMIT 1");
$stmt->execute([$slug]);
$visa = $stmt->fetch();

if (!$visa) {
    header("Location: " . url('visa-services.php'));
    exit;
}

// Fetch other country visas for sidebar
$otherStmt = $pdo->prepare("SELECT id, country_name, visa_type, slug FROM visa_categories WHERE id != ? AND status = 1 LIMIT 6");
$otherStmt->execute([$visa['id']]);
$otherVisas = $otherStmt->fetchAll();

$pageTitle = !empty($visa['meta_title']) ? $visa['meta_title'] : ($visa['country_name'] . " Visa Requirements & Assistance");
$pageDescription = !empty($visa['meta_desc']) ? $visa['meta_desc'] : ($visa['country_name'] . " " . $visa['visa_type'] . " requirements checklist, processing timeline, and assistance in Gazipur.");
$currentPage = 'visa';

$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);

require_once __DIR__ . '/includes/header.php';
?>

<?php
$visaHeroImg = !empty($visa['image']) ? url($visa['image']) : url('assets/images/hero-visa.jpg');
?>
<!-- Detail Hero -->
<section class="page-hero" style="background-image: url('<?= $visaHeroImg ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; 
            <a href="<?= url('visa-services.php') ?>">Visa Services</a> &nbsp;/&nbsp; 
            <span><?= e($visa['country_name']) ?></span>
        </div>
        <span class="visa-badge" style="background: rgba(185,139,98,0.25); border-color: rgba(223,184,146,0.45); color: var(--champagne); margin-bottom: 1rem; display: inline-block;">
            <?= e($visa['visa_type']) ?>
        </span>
        <h1 class="page-hero-title">
            <?= e($visa['country_name']) ?> Visa Assistance
        </h1>
        <p class="page-hero-desc">
            Complete documentation verification, appointment scheduling, and procedural guidelines for travelers from Bangladesh.
        </p>
    </div>
</section>

<!-- Visa Main Body & Consultation Form -->
<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <div class="contact-wrapper" style="grid-template-columns: 1.8fr 1fr; gap: 3.5rem;">
            <!-- Left: Detailed Requirements & Guidance -->
            <div>
                <?php if (!empty($visa['image'])): ?>
                    <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2rem; box-shadow: var(--shadow-md); height: 280px;">
                        <img src="<?= url($visa['image']) ?>" alt="<?= e($visa['country_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                <?php endif; ?>
                <!-- Key Facts Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem;">
                    <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                        <span style="font-size: 0.78rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.35rem;">
                            Processing Time
                        </span>
                        <strong style="font-size: 1.15rem; color: var(--navy-primary);"><?= e($visa['processing_time']) ?></strong>
                    </div>

                    <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                        <span style="font-size: 0.78rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.35rem;">
                            Visa Validity
                        </span>
                        <strong style="font-size: 1.15rem; color: var(--navy-primary);"><?= e($visa['validity'] ?: 'Per Consular Discretion') ?></strong>
                    </div>

                    <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                        <span style="font-size: 0.78rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 0.35rem;">
                            Application Category
                        </span>
                        <strong style="font-size: 1.15rem; color: var(--bronze);"><?= e($visa['visa_type']) ?></strong>
                    </div>
                </div>

                <!-- Detailed Checklist Card -->
                <div style="background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-card); padding: 2.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem;">
                    <div style="font-size: 1.05rem; color: var(--text-muted); line-height: 1.8;">
                        <?= $visa['detailed_requirements'] ?>
                    </div>

                    <?php if (!empty($visa['fees_note'])): ?>
                        <div style="margin-top: 2rem; padding: 1.25rem 1.5rem; background: var(--canvas-alt); border-left: 4px solid var(--bronze); border-radius: var(--radius-sm);">
                            <strong style="color: var(--navy-primary);">Fees & Charges Note:</strong>
                            <p style="font-size: 0.95rem; color: var(--text-muted); margin-top: 0.25rem;"><?= e($visa['fees_note']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Ethical Disclaimer Notice -->
                <div class="alert" style="background: #FFF9F0; border-color: #F7E4CB; color: #8A5B20; margin-bottom: 2.5rem;">
                    <?= renderIcon('shield-check', '', 24) ?>
                    <div style="font-size: 0.9rem; line-height: 1.5;">
                        <strong>Important Legal Disclaimer:</strong> Mahin Travel & Tours provides procedural and administrative file preparation. We do not issue visas and cannot influence consular verdicts. Visa grants remain under the sovereign authority of the destination government.
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?= getWhatsAppUrl('Hello! I would like to consult regarding ' . $visa['country_name'] . ' visa application.') ?>" target="_blank" rel="noopener" class="btn btn-whatsapp">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>WhatsApp Specialist</span>
                    </a>
                    <a href="<?= getTelUrl($primaryPhone) ?>" class="btn btn-navy">
                        <?= renderIcon('phone', '', 18) ?>
                        <span>Call <?= e($primaryPhone) ?></span>
                    </a>
                </div>
            </div>

            <!-- Right: Dedicated Visa Consultation Form -->
            <div>
                <div class="contact-form-panel" style="padding: 2.25rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.35rem; color: var(--navy-primary); margin-bottom: 0.5rem;">File Consultation</h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                        Submit your details to evaluate your eligibility for <strong><?= e($visa['country_name']) ?></strong>.
                    </p>

                    <form action="<?= url('contact.php') ?>" method="POST" class="ajax-contact-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="service_type" value="Visa: <?= e($visa['country_name']) ?>">

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Mohammad Rahim" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Phone / WhatsApp Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. 017xxxxxxxx" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="yourname@gmail.com">
                        </div>

                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label>Travel Plans & Inquiries *</label>
                            <textarea name="message" class="form-control" style="min-height: 90px;" placeholder="Tell us your target departure date, previous travel history, or specific questions..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <?= renderIcon('check', '', 18) ?>
                            <span>Request File Assessment</span>
                        </button>
                    </form>
                </div>

                <!-- Office Contact Card -->
                <div style="background: var(--navy-primary); color: #FFFFFF; border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem;">
                    <h4 style="color: #FFFFFF; font-size: 1.15rem; margin-bottom: 1rem;">Gazipur Consultation Desk</h4>
                    <p style="color: #C0D0E4; font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        <?= e($officeAddress) ?>
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem; font-size: 0.88rem; color: var(--champagne);">
                        <span>📞 <?= e($primaryPhone) ?></span>
                        <span>💬 WhatsApp: <?= e($whatsappNumber) ?></span>
                    </div>
                </div>

                <!-- Other Visa Destinations -->
                <?php if (!empty($otherVisas)): ?>
                    <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 1.75rem;">
                        <h4 style="font-size: 1.1rem; color: var(--navy-primary); margin-bottom: 1rem;">Other Visa Destinations</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                            <?php foreach ($otherVisas as $ov): ?>
                                <a href="<?= url('visa-detail.php?slug=' . urlencode($ov['slug'])) ?>" style="font-size: 0.9rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid var(--border-light);">
                                    <span><?= e($ov['country_name']) ?></span>
                                    <span style="font-size: 0.78rem; color: var(--bronze); font-weight: 600;"><?= e($ov['visa_type']) ?></span>
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
