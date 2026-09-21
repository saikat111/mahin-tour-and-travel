<?php
/**
 * Mahin Travel & Tours - Master Footer Component
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);
$bengaliName = getSetting('bengali_name', DEFAULT_BENGALI_NAME);
$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$secondaryPhone = getSetting('phone_secondary', DEFAULT_PHONE_SECONDARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
$contactEmail = getSetting('contact_email', DEFAULT_EMAIL);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);
$businessHours = getSetting('business_hours', 'Sat - Thu: 9:30 AM - 8:30 PM');
?>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- 1. Brand Info -->
            <div class="footer-brand">
                <a href="<?= url() ?>" class="brand-logo" style="margin-bottom: 0.5rem;">
                    <img src="<?= url('assets/images/logo.png') ?>" alt="<?= e($siteName) ?>" width="60" height="60">
                    <div class="brand-text">
                        <span class="footer-brand-title">MAHIN</span>
                        <span class="footer-brand-bengali"><?= e($bengaliName) ?></span>
                    </div>
                </a>
                <p class="footer-desc">
                    A premier travel agency established in Gazipur, Bangladesh, dedicated to authentic visa consultation, reliable international air ticketing, and meticulously organized Umrah and holiday journeys worldwide.
                </p>
                <div style="margin-top: 0.5rem;">
                    <span class="visa-badge" style="background: rgba(255,255,255,0.06); color: var(--champagne); border-color: rgba(255,255,255,0.12);">
                        Procedural Diligence & Ethical Advisory
                    </span>
                </div>
            </div>

            <!-- 2. Quick Links -->
            <div class="footer-col">
                <h4>Explore</h4>
                <div class="footer-links">
                    <a href="<?= url() ?>">Home</a>
                    <a href="<?= url('about.php') ?>">About Our Agency</a>
                    <a href="<?= url('services.php') ?>">Our Services</a>
                    <a href="<?= url('visa-services.php') ?>">Visa Assistance Hub</a>
                    <a href="<?= url('tours.php') ?>">Tours & Umrah</a>
                    <a href="<?= url('destinations.php') ?>">Destinations</a>
                    <a href="<?= url('faq.php') ?>">Common FAQs</a>
                    <a href="<?= url('contact.php') ?>">Contact & Location</a>
                </div>
            </div>

            <!-- 3. Key Services -->
            <div class="footer-col">
                <h4>Services</h4>
                <div class="footer-links">
                    <a href="<?= url('service-detail.php?slug=air-ticketing') ?>">Air Ticketing</a>
                    <a href="<?= url('visa-detail.php?slug=dubai-tourist-visa') ?>">Dubai E-Visa</a>
                    <a href="<?= url('visa-detail.php?slug=saudi-arabia-visa') ?>">Saudi & Umrah Visa</a>
                    <a href="<?= url('visa-detail.php?slug=malaysia-tourist-visa') ?>">Malaysia Tourist Visa</a>
                    <a href="<?= url('visa-detail.php?slug=thailand-tourist-visa') ?>">Thailand Visa Filing</a>
                    <a href="<?= url('service-detail.php?slug=umrah-pilgrimage-services') ?>">Umrah Pilgrimage</a>
                    <a href="<?= url('service-detail.php?slug=document-attestation-advisory') ?>">Document Attestation</a>
                </div>
            </div>

            <!-- 4. Office & Contact -->
            <div class="footer-col">
                <h4>Gazipur Office</h4>
                <div class="footer-contact-item">
                    <?= renderIcon('map-pin', '', 18) ?>
                    <span><?= e($officeAddress) ?></span>
                </div>
                <div class="footer-contact-item">
                    <?= renderIcon('phone', '', 18) ?>
                    <div>
                        <a href="<?= getTelUrl($primaryPhone) ?>"><?= e($primaryPhone) ?></a><br>
                        <a href="<?= getTelUrl($secondaryPhone) ?>"><?= e($secondaryPhone) ?></a>
                    </div>
                </div>
                <div class="footer-contact-item">
                    <?= renderIcon('whatsapp', '', 18) ?>
                    <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener">WhatsApp: <?= e($whatsappNumber) ?></a>
                </div>
                <div class="footer-contact-item">
                    <?= renderIcon('mail', '', 18) ?>
                    <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
                </div>
                <div class="footer-contact-item">
                    <?= renderIcon('clock', '', 18) ?>
                    <span><?= e($businessHours) ?></span>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="footer-bottom">
            <div>
                &copy; <?= date('Y') ?> <?= e($siteName) ?> (<?= e($bengaliName) ?>). All rights reserved.
            </div>
            <div class="footer-legal">
                <a href="<?= url('privacy.php') ?>">Privacy Policy</a>
                <a href="<?= url('terms.php') ?>">Terms of Service</a>
                <a href="<?= url('sitemap.xml') ?>">XML Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<!-- 5. Persistent Floating WhatsApp Trigger -->
<a href="<?= getWhatsAppUrl() ?>" class="floating-whatsapp" target="_blank" rel="noopener" aria-label="Direct WhatsApp Consultation">
    <span class="whatsapp-pulse"></span>
    <?= renderIcon('whatsapp', '', 24) ?>
    <span class="whatsapp-label">Chat on WhatsApp</span>
</a>

<!-- 6. Reusable Quick Consultation / Booking Modal -->
<div id="inquiryModal" class="drawer-overlay" style="display:flex; align-items:center; justify-content:center;" aria-hidden="true">
    <div class="contact-form-panel" style="max-width:540px; width:92%; margin:20px; position:relative; max-height:90vh; overflow-y:auto;">
        <button class="modal-close" style="position:absolute; top:1.25rem; right:1.25rem; background:transparent; font-size:1.6rem; cursor:pointer; color:var(--text-muted);">&times;</button>
        <h3 class="form-title" style="font-size:1.35rem;">Request Consultation</h3>
        <p class="form-subtitle" style="margin-bottom:1.5rem;">Connect with our specialists for personalized visa and travel advisory.</p>
        
        <form action="<?= url('contact.php') ?>" method="POST" class="ajax-contact-form">
            <?= csrf_field() ?>
            <input type="hidden" name="service_type" id="modalServiceInput" value="General Inquiry">
            
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Your Full Name *</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Mohammad Rahim" required>
            </div>
            
            <div class="form-group" style="margin-bottom:1rem;">
                <label>Phone / WhatsApp Number *</label>
                <input type="tel" name="phone" class="form-control" placeholder="e.g. 017xxxxxxxx" required>
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="yourname@gmail.com">
            </div>

            <div class="form-group" style="margin-bottom:1.25rem;">
                <label>Your Inquiry / Travel Details *</label>
                <textarea name="message" class="form-control" style="min-height:90px;" placeholder="Describe your travel dates, destination, or visa questions..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                <?= renderIcon('check', '', 18) ?>
                <span>Submit Inquiry</span>
            </button>
        </form>
    </div>
</div>

<style>
#inquiryModal {
    display: none !important;
}
#inquiryModal.show {
    display: flex !important;
    opacity: 1 !important;
    visibility: visible !important;
}
</style>

<!-- Master Frontend JavaScript -->
<script src="<?= url('assets/js/main.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
