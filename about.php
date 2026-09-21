<?php
/**
 * Mahin Travel & Tours - About Us Page
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "About Our Travel & Visa Agency in Gazipur";
$pageDescription = "Learn about Mahin Travel & Tours—our Gazipur roots, transparent visa advisory, curated pilgrimage and holiday planning, and dedication to authentic client service.";
$currentPage = 'about';

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);
$bengaliName = getSetting('bengali_name', DEFAULT_BENGALI_NAME);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);
$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$secondaryPhone = getSetting('phone_secondary', DEFAULT_PHONE_SECONDARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-about.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>About Us</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('compass', '', 16) ?>
            <span>Our Heritage & Philosophy</span>
        </div>
        <h1 class="page-hero-title">
            Crafting Reliable Global Journeys with Authenticity & Procedural Rigor
        </h1>
        <p class="page-hero-desc">
            Mahin Travel & Tours (<?= e($bengaliName) ?>) is a client-first travel and visa consultancy founded on the belief that international travel should be accessible, transparent, and completely stress-free.
        </p>
    </div>
</section>

<!-- Company Introduction & Story -->
<section style="padding: 5rem 0;">
    <div class="container">
        <div class="why-wrapper" style="align-items: flex-start;">
            <div>
                <span class="section-tag">Who We Are</span>
                <h2 class="section-title">A Dedicated Travel Consultancy Rooted in Gazipur</h2>
                <p style="font-size: 1.05rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.25rem;">
                    Headquartered at <strong><?= e($officeAddress) ?></strong>, Mahin Travel & Tours serves individuals, families, students, and corporate travelers seeking verified, trustworthy assistance for international visas, airline reservations, and curated tour packages.
                </p>
                <p style="font-size: 1rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem;">
                    In an environment where prospective travelers are frequently confronted with vague promises, fluctuating quotes, and bewildering consular guidelines, our mission is straightforward: <strong>to offer complete clarity, strict compliance with immigration authorities, and personalized human guidance from start to finish.</strong>
                </p>

                <div class="office-card" style="background: var(--canvas-alt); border-color: var(--border-light); margin-top: 2rem;">
                    <h3 style="font-size: 1.35rem; margin-bottom: 0.75rem;">Our Ethical Advisory Commitment</h3>
                    <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;">
                        We believe that trust is earned through truthfulness. We will never make unfounded approval claims or sell deceptive shortcuts. When you consult with Mahin Travel & Tours, you receive honest eligibility assessments, verified document checklists, and thorough file preparation tailored to your profile.
                    </p>
                </div>
            </div>

            <!-- Visual Feature / Highlights -->
            <div>
                <div style="background: var(--card-bg); border-radius: var(--radius-lg); border: 1px solid var(--border-light); overflow: hidden; box-shadow: var(--shadow-md);">
                    <div style="height: 180px; position: relative; overflow: hidden;">
                        <img src="<?= url('assets/images/hero-bg.jpg') ?>" alt="Mahin Travel & Tours Global Network" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, rgba(6,21,43,0.2) 0%, rgba(6,21,43,0.85) 100%);"></div>
                        <div style="position: absolute; bottom: 1rem; left: 1.5rem; right: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                            <span style="color: #FFFFFF; font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Gazipur Headquarters</span>
                            <span style="background: var(--bronze); color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: var(--radius-full);">Direct Consultation</span>
                        </div>
                    </div>
                    <div style="padding: 2rem;">
                        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.75rem;">
                            <img src="<?= url('assets/images/logo.png') ?>" alt="<?= e($siteName) ?>" style="height: 56px; width: auto;">
                            <div>
                                <h4 style="font-size: 1.25rem; color: var(--navy-primary);">MAHIN TRAVEL & TOURS</h4>
                                <span style="font-family: var(--font-bengali); color: var(--bronze); font-size: 0.85rem; font-weight: 600;"><?= e($bengaliName) ?></span>
                            </div>
                        </div>

                    <div style="display:flex; flex-direction:column; gap:1.5rem;">
                        <div style="display:flex; gap:1rem;">
                            <div class="trust-icon-box" style="width:44px; height:44px;">
                                <?= renderIcon('shield-check', '', 20) ?>
                            </div>
                            <div>
                                <h5 style="color:var(--navy-primary); font-size:1.05rem; margin-bottom:0.25rem;">Our Mission</h5>
                                <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.5;">To demystify international travel through accurate visa advisory, transparent pricing, and dependable end-to-end customer support.</p>
                            </div>
                        </div>

                        <div style="display:flex; gap:1rem;">
                            <div class="trust-icon-box" style="width:44px; height:44px;">
                                <?= renderIcon('compass', '', 20) ?>
                            </div>
                            <div>
                                <h5 style="color:var(--navy-primary); font-size:1.05rem; margin-bottom:0.25rem;">Our Vision</h5>
                                <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.5;">To become the foremost and most trusted travel agency in Gazipur and Dhaka Division, celebrated for ethical practices and service excellence.</p>
                            </div>
                        </div>

                        <div style="display:flex; gap:1rem;">
                            <div class="trust-icon-box" style="width:44px; height:44px;">
                                <?= renderIcon('kaaba', '', 20) ?>
                            </div>
                            <div>
                                <h5 style="color:var(--navy-primary); font-size:1.05rem; margin-bottom:0.25rem;">Pilgrimage Philosophy</h5>
                                <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.5;">Treating every Umrah pilgrim with dignity, ensuring transparent hotel proximity, reliable ground transfers, and attentive religious orientation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section style="background: var(--canvas-alt); padding: 5rem 0; border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Core Principles</span>
            <h2 class="section-title">The Four Pillars of Our Client Service</h2>
            <p class="section-subtitle">Guiding every consultation, file review, and itinerary we formulate.</p>
        </div>

        <div class="trust-grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
            <div class="trust-card" style="flex-direction: column; align-items: flex-start; text-align: left; padding: 2rem;">
                <div class="trust-icon-box" style="margin-bottom: 1rem;">
                    <?= renderIcon('shield-check', '', 24) ?>
                </div>
                <h4 style="font-size: 1.15rem; margin-bottom: 0.5rem;">Authenticity & Truthfulness</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    We outline actual consular rules, genuine processing timeframes, and realistic requirements so our clients are always prepared.
                </p>
            </div>

            <div class="trust-card" style="flex-direction: column; align-items: flex-start; text-align: left; padding: 2rem;">
                <div class="trust-icon-box" style="margin-bottom: 1rem;">
                    <?= renderIcon('folder-check', '', 24) ?>
                </div>
                <h4 style="font-size: 1.15rem; margin-bottom: 0.5rem;">Procedural Rigor</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    Every bank statement, sponsorship proof, and application form is cross-checked to ensure zero compliance gaps prior to embassy submission.
                </p>
            </div>

            <div class="trust-card" style="flex-direction: column; align-items: flex-start; text-align: left; padding: 2rem;">
                <div class="trust-icon-box" style="margin-bottom: 1rem;">
                    <?= renderIcon('users', '', 24) ?>
                </div>
                <h4 style="font-size: 1.15rem; margin-bottom: 0.5rem;">One-on-One Human Touch</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    Every client receives direct access to their assigned travel consultant via phone, WhatsApp, or in-person meetings at our South Salna office.
                </p>
            </div>

            <div class="trust-card" style="flex-direction: column; align-items: flex-start; text-align: left; padding: 2rem;">
                <div class="trust-icon-box" style="margin-bottom: 1rem;">
                    <?= renderIcon('plane', '', 24) ?>
                </div>
                <h4 style="font-size: 1.15rem; margin-bottom: 0.5rem;">Seamless Execution</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    From flight ticketing and hotel vouchers to airport pickup coordination, we ensure all components interconnect harmoniously.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Gazipur Office & Physical Welcome -->
<section style="padding: 5rem 0;">
    <div class="container">
        <div class="why-wrapper" style="align-items: center;">
            <div class="office-card" style="box-shadow: var(--shadow-xl);">
                <span class="office-badge">Physical Headquarters</span>
                <h3 style="font-size: 1.75rem;">Welcome to Our Gazipur Office</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.65;">
                    Nothing replaces the clarity of a direct, face-to-face consultation. We encourage travelers and families from Gazipur, Joydebpur, Tongi, and neighboring regions to visit our office to discuss their visa dossiers and upcoming travel aspirations.
                </p>

                <div class="office-detail-row">
                    <?= renderIcon('map-pin', '', 20) ?>
                    <div>
                        <strong>Address:</strong><br>
                        <?= e($officeAddress) ?>
                    </div>
                </div>

                <div class="office-detail-row">
                    <?= renderIcon('phone', '', 20) ?>
                    <div>
                        <strong>Contact Numbers:</strong><br>
                        <?= e($primaryPhone) ?> &nbsp;|&nbsp; <?= e($secondaryPhone) ?>
                    </div>
                </div>

                <div class="office-detail-row">
                    <?= renderIcon('whatsapp', '', 20) ?>
                    <div>
                        <strong>WhatsApp Direct Line:</strong><br>
                        <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener" style="color:var(--whatsapp-color); font-weight:600;">
                            <?= e($whatsappNumber) ?> (Click to Chat)
                        </a>
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <a href="<?= url('contact.php') ?>" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <?= renderIcon('calendar-check', '', 18) ?>
                        <span>Schedule a Consultation or View Map</span>
                    </a>
                </div>
            </div>

            <div>
                <span class="section-tag">Direct Human Connection</span>
                <h2 class="section-title">We Treat Every Journey as a Significant Life Milestone</h2>
                <p style="font-size: 1.05rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.25rem;">
                    Whether it is a sacred pilgrimage for your elderly parents, an educational milestone for your son or daughter, or an essential business expansion abroad, we understand that travel involves personal hopes, significant investments, and deep trust.
                </p>
                <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.75rem;">
                    That is why Mahin Travel & Tours is built upon deliberate, responsible advisory. When you consult with us, you are in safe, respectful, and capable hands.
                </p>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?= url('services.php') ?>" class="btn btn-navy">
                        <?= renderIcon('arrow-right', '', 16) ?>
                        <span>View Our Services</span>
                    </a>
                    <a href="<?= getWhatsAppUrl() ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                        <?= renderIcon('whatsapp', '', 16) ?>
                        <span>WhatsApp Chat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
