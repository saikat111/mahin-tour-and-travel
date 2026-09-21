<?php
/**
 * Mahin Travel & Tours - Master Header Component
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);
$bengaliName = getSetting('bengali_name', DEFAULT_BENGALI_NAME);
$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$secondaryPhone = getSetting('phone_secondary', DEFAULT_PHONE_SECONDARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);
$businessHours = getSetting('business_hours', 'Sat - Thu: 9:30 AM - 8:30 PM');

$currentMetaTitle = !empty($pageTitle) ? $pageTitle . " | " . $siteName : getSetting('default_meta_title', "$siteName | Trusted Visa Assistance & Global Tour Operator");
$currentMetaDesc = !empty($pageDescription) ? $pageDescription : getSetting('default_meta_desc', "Mahin Travel & Tours in Gazipur offers professional visa assistance, international air tickets, Umrah packages, and curated holiday tours.");
$currentPage = $currentPage ?? 'home';

// Current Canonical URL
$canonicalUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$ogImage = url('assets/images/logo.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($currentMetaTitle) ?></title>
    <meta name="description" content="<?= e($currentMetaDesc) ?>">
    <meta name="keywords" content="<?= e(getSetting('default_keywords', 'travel agency gazipur, visa processing bangladesh, air tickets, umrah package, mahin travel and tours')) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($currentMetaTitle) ?>">
    <meta property="og:description" content="<?= e($currentMetaDesc) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($currentMetaTitle) ?>">
    <meta name="twitter:description" content="<?= e($currentMetaDesc) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= url('assets/images/favicon.png') ?>">

    <!-- Master Stylesheet -->
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>?v=<?= time() ?>">

    <!-- LocalBusiness & TravelAgency Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TravelAgency",
      "name": "<?= e($siteName) ?>",
      "alternateName": "<?= e($bengaliName) ?>",
      "image": "<?= e($ogImage) ?>",
      "url": "<?= url() ?>",
      "telephone": ["<?= e($primaryPhone) ?>", "<?= e($secondaryPhone) ?>"],
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Holding no-1492, South Salna, Ward No-19, Zone-5",
        "addressLocality": "Gazipur",
        "addressRegion": "Dhaka Division",
        "addressCountry": "BD"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 24.0384,
        "longitude": 90.4132
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Saturday",
          "Sunday",
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday"
        ],
        "opens": "09:30",
        "closes": "20:30"
      }
    }
    </script>
</head>
<body>

<!-- 1. Luxury Topbar -->
<header class="topbar">
    <div class="container">
        <div class="topbar-left">
            <div class="topbar-item">
                <?= renderIcon('map-pin', '', 14) ?>
                <span>Holding no-1492, South Salna, Gazipur</span>
            </div>
            <div class="topbar-item">
                <?= renderIcon('clock', '', 14) ?>
                <span><?= e($businessHours) ?></span>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-item">
                <?= renderIcon('phone', '', 14) ?>
                <a href="<?= getTelUrl($primaryPhone) ?>"><?= e($primaryPhone) ?></a>
            </div>
            <div class="topbar-item">
                <?= renderIcon('whatsapp', '', 14) ?>
                <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener">WhatsApp Us</a>
            </div>
        </div>
    </div>
</header>

<!-- 2. Sticky Master Navigation -->
<nav class="navbar">
    <div class="container">
        <a href="<?= url() ?>" class="brand-logo" title="<?= e($siteName) ?>">
            <img src="<?= url('assets/images/logo.png') ?>" alt="<?= e($siteName) ?> Logo" width="56" height="56">
            <div class="brand-text">
                <span class="brand-title">MAHIN</span>
                <span class="brand-subtitle">TRAVEL AND TOURS</span>
                <span class="brand-bengali"><?= e($bengaliName) ?></span>
            </div>
        </a>

        <!-- Desktop Nav Menu -->
        <div class="nav-menu">
            <a href="<?= url() ?>" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">Home</a>
            <a href="<?= url('about.php') ?>" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>">About Us</a>
            <a href="<?= url('services.php') ?>" class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>">Services</a>
            <a href="<?= url('visa-services.php') ?>" class="nav-link <?= $currentPage === 'visa' ? 'active' : '' ?>">Visa Assistance</a>
            <a href="<?= url('tours.php') ?>" class="nav-link <?= $currentPage === 'tours' ? 'active' : '' ?>">Tours & Umrah</a>
            <a href="<?= url('destinations.php') ?>" class="nav-link <?= $currentPage === 'destinations' ? 'active' : '' ?>">Destinations</a>
            <a href="<?= url('faq.php') ?>" class="nav-link <?= $currentPage === 'faq' ? 'active' : '' ?>">FAQ</a>
            <a href="<?= url('contact.php') ?>" class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>">Contact</a>
        </div>

        <!-- Header Actions -->
        <div class="nav-actions">
            <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-sm btn-consultation">
                <?= renderIcon('calendar-check', '', 16) ?>
                <span>Book Consultation</span>
            </a>
            <button class="mobile-toggle" aria-label="Toggle navigation menu">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</nav>

<!-- 3. Mobile Drawer Navigation -->
<div class="drawer-overlay"></div>
<aside class="mobile-drawer" aria-label="Mobile Navigation">
    <div>
        <div class="mobile-drawer-header">
            <div class="brand-text">
                <span class="brand-title" style="color:#FFFFFF;">MAHIN</span>
                <span class="brand-subtitle"><?= e($bengaliName) ?></span>
            </div>
            <button class="mobile-close" aria-label="Close menu">&times;</button>
        </div>
        <div class="mobile-nav-links">
            <a href="<?= url() ?>" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Home</a>
            <a href="<?= url('about.php') ?>" class="<?= $currentPage === 'about' ? 'active' : '' ?>">About Us</a>
            <a href="<?= url('services.php') ?>" class="<?= $currentPage === 'services' ? 'active' : '' ?>">Services</a>
            <a href="<?= url('visa-services.php') ?>" class="<?= $currentPage === 'visa' ? 'active' : '' ?>">Visa Assistance</a>
            <a href="<?= url('tours.php') ?>" class="<?= $currentPage === 'tours' ? 'active' : '' ?>">Tours & Umrah Packages</a>
            <a href="<?= url('destinations.php') ?>" class="<?= $currentPage === 'destinations' ? 'active' : '' ?>">Destinations</a>
            <a href="<?= url('faq.php') ?>" class="<?= $currentPage === 'faq' ? 'active' : '' ?>">FAQ</a>
            <a href="<?= url('contact.php') ?>" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Contact & Location</a>
        </div>
    </div>

    <div class="mobile-drawer-footer">
        <a href="<?= getTelUrl($primaryPhone) ?>" class="btn btn-outline-white btn-sm" style="width:100%; justify-content:center;">
            <?= renderIcon('phone', '', 16) ?>
            <span>Call: <?= e($primaryPhone) ?></span>
        </a>
        <a href="<?= getWhatsAppUrl() ?>" class="btn btn-whatsapp btn-sm" style="width:100%; justify-content:center;" target="_blank" rel="noopener">
            <?= renderIcon('whatsapp', '', 16) ?>
            <span>WhatsApp Consultation</span>
        </a>
        <p style="text-align:center; font-size:0.75rem; color:#859BB2; margin-top:0.5rem;">
            <?= e($officeAddress) ?>
        </p>
    </div>
</aside>
