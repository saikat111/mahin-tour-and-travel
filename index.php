<?php
/**
 * Mahin Travel & Tours - Flagship Homepage
 * Website: mahintravelandtours.com
 * Features: Cinematic Multi-Slide Hero Carousel, Photo Showcase Strip & Real Travel Photography
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

// 1. Fetch Dynamic Active Services
$servicesStmt = $pdo->query("SELECT * FROM services WHERE status = 1 ORDER BY sort_order ASC, id ASC LIMIT 6");
$services = $servicesStmt->fetchAll();

// 2. Fetch Featured Visa Categories
$visasStmt = $pdo->query("SELECT * FROM visa_categories WHERE status = 1 ORDER BY sort_order ASC, id ASC LIMIT 6");
$visas = $visasStmt->fetchAll();

// 3. Fetch Visa Process Steps
$stepsStmt = $pdo->query("SELECT * FROM visa_process_steps WHERE status = 1 ORDER BY step_number ASC");
$steps = $stepsStmt->fetchAll();

// 4. Fetch Featured Tours
$toursStmt = $pdo->query("SELECT * FROM tours WHERE status = 1 ORDER BY is_featured DESC, sort_order ASC LIMIT 4");
$tours = $toursStmt->fetchAll();

// 5. Fetch Featured Destinations
$destStmt = $pdo->query("SELECT * FROM destinations WHERE status = 1 ORDER BY sort_order ASC LIMIT 6");
$destinations = $destStmt->fetchAll();

// 6. Fetch Homepage FAQs
$faqStmt = $pdo->query("SELECT * FROM faqs WHERE status = 1 ORDER BY sort_order ASC LIMIT 6");
$faqs = $faqStmt->fetchAll();

// 7. Fetch Testimonials
$testStmt = $pdo->query("SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order ASC LIMIT 4");
$testimonials = $testStmt->fetchAll();

$pageTitle = "Trusted Visa Assistance & Global Tour Operator in Gazipur";
$pageDescription = "Mahin Travel & Tours in Gazipur offers authentic visa consultation, international air tickets, Umrah packages, and curated global holidays with dedicated human support.";
$currentPage = 'home';

require_once __DIR__ . '/includes/header.php';
?>

<!-- ==============================================================================
     1. Cinematic Multi-Slide Hero Carousel
     ============================================================================== -->
<section class="hero-slider-container">
    <!-- Progress Indicator Line -->
    <div class="slider-progress">
        <div class="slider-progress-fill"></div>
    </div>

    <!-- Navigation Arrows -->
    <button class="slider-arrow prev" aria-label="Previous Slide">
        <?= renderIcon('arrow-right', '', 20) ?>
    </button>
    <button class="slider-arrow next" aria-label="Next Slide">
        <?= renderIcon('arrow-right', '', 20) ?>
    </button>

    <!-- Slide 1: Dubai & Middle East Hub -->
    <div class="hero-slide active">
        <div class="hero-slide-bg" style="background-image: url('<?= url('assets/images/hero-dubai.jpg') ?>');"></div>
        <div class="hero-slide-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <?= renderIcon('shield-check', '', 16) ?>
                    <span>Middle East Luxury & Visa Hub</span>
                </div>
                
                <h1 class="hero-title">
                    Experience the Wonder of <span class="highlight">Dubai & Arabian Horizons</span>
                </h1>

                <p class="hero-subtitle">
                    From expedited 30 & 60-day tourist e-visas to luxury 4-star packages, desert safaris, and optimal flights, Mahin Travel & Tours brings Dubai within seamless reach.
                </p>

                <div class="hero-actions">
                    <a href="<?= url('visa-detail.php?slug=dubai-tourist-visa') ?>" class="btn btn-primary">
                        <?= renderIcon('passport', '', 18) ?>
                        <span>Explore Dubai Visas</span>
                    </a>
                    <a href="<?= url('tour-detail.php?slug=grand-dubai-luxury-desert') ?>" class="btn btn-outline-white">
                        <?= renderIcon('map', '', 18) ?>
                        <span>View Dubai Package</span>
                    </a>
                    <a href="<?= getWhatsAppUrl('Hello! I would like to consult regarding Dubai visa and holiday packages.') ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>Direct WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2: Spiritual Umrah & Pilgrimage -->
    <div class="hero-slide">
        <div class="hero-slide-bg" style="background-image: url('<?= url('assets/images/hero-makkah.jpg') ?>');"></div>
        <div class="hero-slide-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <?= renderIcon('kaaba', '', 16) ?>
                    <span>Spiritual Umrah & Pilgrimage Care</span>
                </div>
                
                <h2 class="hero-title">
                    Sacred Umrah Journeys with <span class="highlight">Proximity to the Holy Haram</span>
                </h2>

                <p class="hero-subtitle">
                    Fast Saudi e-visas, verified walking-distance hotels in Makkah & Madinah, air-conditioned VIP ground transfers, and respectful Muallim religious guidance.
                </p>

                <div class="hero-actions">
                    <a href="<?= url('service-detail.php?slug=umrah-pilgrimage-services') ?>" class="btn btn-primary">
                        <?= renderIcon('kaaba', '', 18) ?>
                        <span>Umrah Services</span>
                    </a>
                    <a href="<?= url('tour-detail.php?slug=spiritual-umrah-journey') ?>" class="btn btn-outline-white">
                        <?= renderIcon('calendar-check', '', 18) ?>
                        <span>View Umrah Itinerary</span>
                    </a>
                    <a href="<?= getWhatsAppUrl('Hello! I would like to inquire about Umrah packages and pilgrim visa support.') ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>Consult on WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 3: Southeast Asian Paradise -->
    <div class="hero-slide">
        <div class="hero-slide-bg" style="background-image: url('<?= url('assets/images/hero-asia.jpg') ?>');"></div>
        <div class="hero-slide-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <?= renderIcon('compass', '', 16) ?>
                    <span>Southeast Asian Escapes</span>
                </div>
                
                <h2 class="hero-title">
                    Tropical Splendor & Vibrant Metropolises: <span class="highlight">Malaysia, Thailand & Singapore</span>
                </h2>

                <p class="hero-subtitle">
                    Meticulous document compilation for Malaysia e-visa, Royal Thai Embassy submission, and Singapore entry clearance. Handcrafted twin-city holiday itineraries.
                </p>

                <div class="hero-actions">
                    <a href="<?= url('visa-services.php') ?>" class="btn btn-primary">
                        <?= renderIcon('passport', '', 18) ?>
                        <span>Visa Hub</span>
                    </a>
                    <a href="<?= url('tour-detail.php?slug=malaysia-thailand-dual-escape') ?>" class="btn btn-outline-white">
                        <?= renderIcon('map', '', 18) ?>
                        <span>Twin City Tour</span>
                    </a>
                    <a href="<?= getWhatsAppUrl('Hello! I would like to plan a vacation to Malaysia and Thailand.') ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>WhatsApp Chat</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 4: Global Gateways & UK/Europe -->
    <div class="hero-slide">
        <div class="hero-slide-bg" style="background-image: url('<?= url('assets/images/hero-europe.jpg') ?>');"></div>
        <div class="hero-slide-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <?= renderIcon('graduation-cap', '', 16) ?>
                    <span>Global Gateways & Higher Education</span>
                </div>
                
                <h2 class="hero-title">
                    Worldwide Gateways: <span class="highlight">United Kingdom, Schengen & Beyond</span>
                </h2>

                <p class="hero-subtitle">
                    Authentic procedural orientation for European visitor visas and international student dossiers. Strict document verification without false approval guarantees.
                </p>

                <div class="hero-actions">
                    <a href="<?= url('visa-detail.php?slug=uk-schengen-visitor-visa') ?>" class="btn btn-primary">
                        <?= renderIcon('file-text', '', 18) ?>
                        <span>Visitor Visa Guidance</span>
                    </a>
                    <a href="<?= url('service-detail.php?slug=air-ticketing') ?>" class="btn btn-outline-white">
                        <?= renderIcon('plane', '', 18) ?>
                        <span>Flight Reservations</span>
                    </a>
                    <a href="<?= getWhatsAppUrl('Hello! I would like guidance for UK/Schengen visa documentation.') ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                        <?= renderIcon('whatsapp', '', 18) ?>
                        <span>Direct WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination Indicator Pills -->
    <div class="slider-pills">
        <button class="slider-pill active" type="button">01 Dubai</button>
        <button class="slider-pill" type="button">02 Umrah</button>
        <button class="slider-pill" type="button">03 Asia</button>
        <button class="slider-pill" type="button">04 Europe</button>
    </div>
</section>

<!-- Quick Inquiry / Service Finder Floating Strip -->
<div class="container quick-finder-container">
    <div class="quick-finder">
        <form action="<?= url('contact.php') ?>" method="GET" class="quick-finder-form">
            <div class="finder-field">
                <label>What service do you need?</label>
                <select name="service_type">
                    <option value="Tourist Visa Assistance">Tourist / Visit Visa Assistance</option>
                    <option value="International Air Ticketing">International Air Ticketing</option>
                    <option value="Umrah Pilgrimage Package">Umrah Pilgrimage Package</option>
                    <option value="Curated Holiday Tour">Holiday / Leisure Tour</option>
                    <option value="Student / Work Visa Guidance">Student / Work Visa Guidance</option>
                    <option value="Document Attestation">Document Attestation & Translation</option>
                </select>
            </div>

            <div class="finder-field">
                <label>Target Destination</label>
                <select name="destination">
                    <option value="Dubai UAE">Dubai / UAE</option>
                    <option value="Saudi Arabia (Umrah/Visit)">Saudi Arabia (Umrah / Visit)</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Singapore">Singapore</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Schengen Europe">Schengen Europe</option>
                    <option value="Domestic Bangladesh">Domestic (Sylhet / Cox's Bazar)</option>
                    <option value="Other">Other Global Destination</option>
                </select>
            </div>

            <div class="finder-field">
                <label>Departure Timeline</label>
                <select name="timeline">
                    <option value="Immediate (Within 15 days)">Immediate (Within 15 days)</option>
                    <option value="Next 30 Days">Next 30 Days</option>
                    <option value="Next 2-3 Months">Next 2-3 Months</option>
                    <option value="Flexible / Just Inquiring">Flexible / Just Inquiring</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px;">
                    <?= renderIcon('arrow-right', '', 18) ?>
                    <span>Get Consultation</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==============================================================================
     2. Trust / Quick Information Section
     ============================================================================== -->
<section class="trust-bar" style="margin-top: 3.5rem;">
    <div class="container">
        <div class="trust-grid">
            <div class="trust-card">
                <div class="trust-icon-box">
                    <?= renderIcon('shield-check', '', 26) ?>
                </div>
                <div class="trust-info">
                    <h4>Meticulous Visa Advisory</h4>
                    <p>Rigorous dossier review strictly aligned with official embassy regulations.</p>
                </div>
            </div>

            <div class="trust-card">
                <div class="trust-icon-box">
                    <?= renderIcon('plane-departure', '', 26) ?>
                </div>
                <div class="trust-info">
                    <h4>Optimal Flight Routing</h4>
                    <p>Transparent international airfares with baggage and date-change assistance.</p>
                </div>
            </div>

            <div class="trust-card">
                <div class="trust-icon-box">
                    <?= renderIcon('kaaba', '', 26) ?>
                </div>
                <div class="trust-info">
                    <h4>Tailored Umrah Packages</h4>
                    <p>Close proximity hotels in Makkah & Madinah with respectful ground support.</p>
                </div>
            </div>

            <div class="trust-card">
                <div class="trust-icon-box">
                    <?= renderIcon('phone', '', 26) ?>
                </div>
                <div class="trust-info">
                    <h4>Personal Human Care</h4>
                    <p>Speak directly with our Gazipur consultants via phone, WhatsApp, or office visit.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     3. Photo Showcase Strip (Real Global Experiences)
     ============================================================================== -->
<section class="photo-showcase-section">
    <div class="container">
        <div class="photo-showcase-grid">
            <div class="showcase-photo-item">
                <img src="<?= url('assets/images/destinations/dubai.jpg') ?>" alt="Dubai Desert Dunes" loading="lazy">
                <div class="showcase-photo-caption">Dubai & Abu Dhabi</div>
            </div>
            <div class="showcase-photo-item">
                <img src="<?= url('assets/images/destinations/saudi.jpg') ?>" alt="Sacred Makkah Haram" loading="lazy">
                <div class="showcase-photo-caption">Holy Makkah & Madinah</div>
            </div>
            <div class="showcase-photo-item">
                <img src="<?= url('assets/images/destinations/malaysia.jpg') ?>" alt="Kuala Lumpur Towers" loading="lazy">
                <div class="showcase-photo-caption">Malaysia Skylines</div>
            </div>
            <div class="showcase-photo-item">
                <img src="<?= url('assets/images/destinations/thailand.jpg') ?>" alt="Thailand Islands" loading="lazy">
                <div class="showcase-photo-caption">Thailand Temples & Islands</div>
            </div>
            <div class="showcase-photo-item">
                <img src="<?= url('assets/images/destinations/singapore.jpg') ?>" alt="Singapore Marina Bay" loading="lazy">
                <div class="showcase-photo-caption">Singapore Marina Bay</div>
            </div>
            <div class="showcase-photo-item">
                <img src="<?= url('assets/images/destinations/sylhet.jpg') ?>" alt="Sylhet Tea Gardens" loading="lazy">
                <div class="showcase-photo-caption">Sylhet Tea Valleys</div>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     4. Core Services Showcase (With Real Photography)
     ============================================================================== -->
<section class="services-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Comprehensive Travel Solutions</span>
            <h2 class="section-title">End-to-End International & Domestic Travel Services</h2>
            <p class="section-subtitle">
                Whether you require visa filing, air ticketing, pilgrimage packages, or family holidays, Mahin Travel & Tours handles every logistical detail with utmost professionalism.
            </p>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $srv): ?>
                <div class="service-card">
                    <div>
                        <?php if (!empty($srv['featured_image'])): ?>
                            <div style="height: 160px; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1.5rem; position: relative;">
                                <img src="<?= url($srv['featured_image']) ?>" alt="<?= e($srv['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" loading="lazy">
                                <div style="position: absolute; top: 0.75rem; left: 0.75rem; width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(6,21,43,0.85); backdrop-filter: blur(4px); color: var(--champagne); display: flex; align-items: center; justify-content: center;">
                                    <?= renderIcon($srv['icon_name'], '', 20) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <span class="visa-badge" style="margin-bottom: 0.75rem; display: inline-block;">
                            <?= e($srv['category']) ?>
                        </span>
                        <h3 class="service-title"><?= e($srv['title']) ?></h3>
                        <p class="service-desc"><?= e($srv['short_desc']) ?></p>
                    </div>
                    <div>
                        <a href="<?= url('service-detail.php?slug=' . urlencode($srv['slug'])) ?>" class="service-link">
                            <span>Service Specifications</span>
                            <?= renderIcon('arrow-right', '', 16) ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==============================================================================
     5. Visa Assistance Hub & 5-Step Roadmap
     ============================================================================== -->
<section class="visa-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Visa Advisory & Processing</span>
            <h2 class="section-title">Popular Visa Destinations & Clear Procedural Standards</h2>
            <p class="section-subtitle">
                We bridge the gap between complex embassy requirements and prospective travelers. No deceptive promises—only genuine, step-by-step procedural compliance.
            </p>
        </div>

        <!-- Featured Country Visa Cards with Real Landmarks -->
        <div class="visa-countries-grid">
            <?php foreach ($visas as $visa): ?>
                <div class="visa-card">
                    <div>
                        <?php if (!empty($visa['image'])): ?>
                            <div style="height: 150px; border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1.25rem;">
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
                            <strong>Key Checklist:</strong> <?= e($visa['requirement_summary']) ?>
                        </p>
                    </div>

                    <div>
                        <div class="visa-meta">
                            <span>Validity: <strong><?= e($visa['validity'] ?: 'Per Embassy') ?></strong></span>
                            <span style="color:var(--bronze); font-weight:600;">Verified Guidance</span>
                        </div>
                        <div style="display:flex; gap:0.75rem;">
                            <a href="<?= url('visa-detail.php?slug=' . urlencode($visa['slug'])) ?>" class="btn btn-outline btn-sm" style="flex:1;">
                                <span>Requirements</span>
                            </a>
                            <a href="<?= getWhatsAppUrl('Hello Mahin Travel! I need consultation for ' . $visa['country_name'] . ' visa.') ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm" title="Instant WhatsApp">
                                <?= renderIcon('whatsapp', '', 16) ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 5-Step Procedural Roadmap -->
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <span class="section-tag">Transparent Methodology</span>
            <h3 class="section-title" style="font-size: 2rem;">How We Process Your Visa Application</h3>
            <p class="section-subtitle">A structured, 5-step roadmap eliminating guesswork from your travel preparations.</p>
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

<!-- ==============================================================================
     6. Popular Destinations Showcase (Real Photos)
     ============================================================================== -->
<section class="destinations-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag" style="background:rgba(185,139,98,0.18); border-color:rgba(223,184,146,0.3); color:var(--champagne);">
                Worldwide Horizons
            </span>
            <h2 class="section-title">Inspiring Global & Regional Destinations</h2>
            <p class="section-subtitle">
                From the dazzling skylines of the Middle East to tropical retreats across Southeast Asia and spiritual pilgrimages to holy lands.
            </p>
        </div>

        <div class="destinations-grid">
            <?php foreach ($destinations as $dst): ?>
                <div class="dest-card">
                    <img src="<?= url($dst['featured_image']) ?>" alt="<?= e($dst['title']) ?>" loading="lazy">
                    <div class="dest-content">
                        <span class="dest-continent"><?= e($dst['continent']) ?> • <?= e($dst['country']) ?></span>
                        <h3 class="dest-title"><?= e($dst['title']) ?></h3>
                        <p class="dest-popular"><?= e($dst['popular_for']) ?></p>
                        <a href="<?= url('destinations.php') ?>" class="service-link" style="color:var(--champagne);">
                            <span>Explore Guide</span>
                            <?= renderIcon('arrow-right', '', 14) ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==============================================================================
     7. Curated Tour Packages (Real Photography)
     ============================================================================== -->
<section class="tours-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Curated Experiences</span>
            <h2 class="section-title">Handcrafted Holiday & Umrah Packages</h2>
            <p class="section-subtitle">
                Complete itineraries featuring verified hotels, comfortable transfers, and guided sightseeing. Transparent pricing with no hidden charges.
            </p>
        </div>

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
                                <span class="tour-price-label">Pricing</span>
                                <span class="tour-price-val"><?= e($tour['price_text']) ?></span>
                            </div>
                            <div style="display:flex; gap:0.5rem;">
                                <a href="<?= url('tour-detail.php?slug=' . urlencode($tour['slug'])) ?>" class="btn btn-outline btn-sm">
                                    <span>Details</span>
                                </a>
                                <a href="#" data-inquiry-modal data-service-name="Tour: <?= e($tour['title']) ?>" class="btn btn-primary btn-sm">
                                    <span>Book</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 3.5rem;">
            <a href="<?= url('tours.php') ?>" class="btn btn-navy">
                <?= renderIcon('map', '', 18) ?>
                <span>Browse All Tour & Umrah Packages</span>
            </a>
        </div>
    </div>
</section>

<!-- ==============================================================================
     8. Why Choose Mahin Travel & Tours (Trust & Local HQ)
     ============================================================================== -->
<section class="why-section" style="background: var(--canvas-alt); border-top:1px solid var(--border-light); border-bottom:1px solid var(--border-light);">
    <div class="container">
        <div class="why-wrapper">
            <div>
                <span class="section-tag">Trust & Integrity</span>
                <h2 class="section-title">Why Discerning Travelers Choose Mahin Travel</h2>
                <p class="section-subtitle" style="text-align: left;">
                    In an industry often clouded by ambiguous claims, Mahin Travel & Tours stands on principles of strict procedural accuracy, authentic communication, and real human dedication.
                </p>

                <div class="why-features">
                    <div class="why-feature-item">
                        <div class="why-icon">
                            <?= renderIcon('shield-check', '', 24) ?>
                        </div>
                        <div class="why-info">
                            <h4>Zero Misleading Promises</h4>
                            <p>We respect international immigration law. We never make false approval guarantees; we prepare bulletproof, compliant dossiers that command consular respect.</p>
                        </div>
                    </div>

                    <div class="why-feature-item">
                        <div class="why-icon">
                            <?= renderIcon('users', '', 24) ?>
                        </div>
                        <div class="why-info">
                            <h4>Dedicated Personal Consultants</h4>
                            <p>You never deal with impersonal automated bots. Our dedicated Gazipur team manages your case file directly, giving you clear status updates.</p>
                        </div>
                    </div>

                    <div class="why-feature-item">
                        <div class="why-icon">
                            <?= renderIcon('map-pin', '', 24) ?>
                        </div>
                        <div class="why-info">
                            <h4>Physical Registered Presence</h4>
                            <p>Our permanent headquarters in South Salna, Gazipur provides a safe, welcoming space for face-to-face consultation, document handover, and receipt issuance.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gazipur Office Card -->
            <div class="office-card">
                <span class="office-badge">Gazipur Headquarters</span>
                <h3>Visit Our Physical Office</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.75rem; font-size: 0.95rem; line-height: 1.6;">
                    Conveniently accessible for residents of Gazipur, Joydebpur, Tongi, and greater Dhaka. Drop in for a direct consultation regarding your upcoming travel, visa file, or Umrah journey.
                </p>

                <div class="office-detail-row">
                    <?= renderIcon('map-pin', '', 20) ?>
                    <div>
                        <strong>Physical Address:</strong><br>
                        Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh
                    </div>
                </div>

                <div class="office-detail-row">
                    <?= renderIcon('phone', '', 20) ?>
                    <div>
                        <strong>Direct Support Lines:</strong><br>
                        <a href="tel:+8801924713765">+880 1924-713765</a> &nbsp;|&nbsp; <a href="tel:+8801722203033">+880 1722-203033</a>
                    </div>
                </div>

                <div class="office-detail-row">
                    <?= renderIcon('whatsapp', '', 20) ?>
                    <div>
                        <strong>Direct WhatsApp Line:</strong><br>
                        <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener" style="color:var(--whatsapp-color); font-weight:600;">+880 1924-713765 (Click to Chat)</a>
                    </div>
                </div>

                <div class="office-detail-row">
                    <?= renderIcon('clock', '', 20) ?>
                    <div>
                        <strong>Consultation Hours:</strong><br>
                        Saturday – Thursday: 9:30 AM – 8:30 PM (Friday: By Appointment)
                    </div>
                </div>

                <div style="margin-top: 1.5rem; display: flex; gap: 0.85rem;">
                    <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-sm" style="flex:1;">
                        <?= renderIcon('map', '', 16) ?>
                        <span>Office Directions & Map</span>
                    </a>
                    <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm">
                        <?= renderIcon('whatsapp', '', 16) ?>
                        <span>Chat Now</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==============================================================================
     9. Testimonials Section (With Real Client Avatars)
     ============================================================================== -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Client Feedback</span>
            <h2 class="section-title">Experiences Shared by Our Travelers</h2>
            <p class="section-subtitle">
                Genuine feedback from individuals and families who trusted our visa assistance, air ticketing, and tour planning.
            </p>
        </div>

        <div class="testimonials-grid">
            <?php foreach ($testimonials as $tst): ?>
                <div class="testimonial-card">
                    <div>
                        <?= renderRatingStars((int)$tst['rating']) ?>
                        <p class="testimonial-text">"<?= e($tst['comment']) ?>"</p>
                    </div>

                    <div class="testimonial-author">
                        <?php if (!empty($tst['avatar']) && file_exists(ROOT_PATH . '/' . $tst['avatar'])): ?>
                            <img src="<?= url($tst['avatar']) ?>" alt="<?= e($tst['client_name']) ?>" class="author-avatar-img">
                        <?php else: ?>
                            <div class="author-initials">
                                <?php
                                $words = explode(' ', $tst['client_name']);
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $w) {
                                    $initials .= strtoupper($w[0] ?? '');
                                }
                                echo e($initials ?: 'MT');
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="author-info">
                            <h5><?= e($tst['client_name']) ?></h5>
                            <span><?= e($tst['client_role_or_location']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==============================================================================
     10. Frequently Asked Questions (FAQ)
     ============================================================================== -->
<section class="faq-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Clear Answers</span>
            <h2 class="section-title">Frequently Asked Travel & Visa Questions</h2>
            <p class="section-subtitle">
                Transparent guidance regarding visa processing rules, flight booking policies, and pilgrimage preparation.
            </p>
        </div>

        <div class="faq-accordion">
            <?php foreach ($faqs as $idx => $faq): ?>
                <div class="faq-item <?= $idx === 0 ? 'active' : '' ?>">
                    <button class="faq-question" type="button" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>">
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

        <div style="text-align:center; margin-top: 2.5rem;">
            <p style="color:var(--text-muted); font-size:0.95rem;">
                Have a specific question not addressed above?
                <a href="<?= url('contact.php') ?>" style="color:var(--navy-primary); font-weight:700; text-decoration:underline;">
                    Speak with our Gazipur consultants directly &rarr;
                </a>
            </p>
        </div>
    </div>
</section>

<!-- ==============================================================================
     11. Conversion CTA Banner
     ============================================================================== -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
