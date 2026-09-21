<?php
/**
 * Mahin Travel & Tours - Contact & Inquiry Management
 * Website: mahintravelandtours.com
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();
$feedbackMessage = '';
$feedbackType = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($submittedToken)) {
        $feedbackType = 'error';
        $feedbackMessage = 'Security validation failed. Please refresh the page and try again.';
    } else {
        $name = cleanInput($_POST['name'] ?? '');
        $phone = cleanInput($_POST['phone'] ?? '');
        $email = cleanInput($_POST['email'] ?? '');
        $subject = cleanInput($_POST['subject'] ?? 'Travel Inquiry');
        $serviceType = cleanInput($_POST['service_type'] ?? 'General Consultation');
        $message = cleanInput($_POST['message'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        if (empty($name) || empty($phone) || empty($message)) {
            $feedbackType = 'error';
            $feedbackMessage = 'Please provide your name, phone number, and a description of your travel inquiry.';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, service_type, message, ip_address, is_read) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
                $stmt->execute([$name, $email, $phone, $subject, $serviceType, $message, $ip]);

                // Set flash and redirect (PRG pattern)
                setFlashMessage('success', "Thank you, {$name}! Your inquiry has been received. Our Gazipur travel consultants will review your request and connect with you via phone or WhatsApp shortly.");
                header("Location: " . url('contact.php?submitted=1'));
                exit;
            } catch (Exception $e) {
                error_log("Contact form error: " . $e->getMessage());
                $feedbackType = 'error';
                $feedbackMessage = 'An unexpected error occurred while saving your inquiry. Please contact us directly via phone or WhatsApp.';
            }
        }
    }
}

$flash = getFlashMessage();
if ($flash) {
    $feedbackType = $flash['type'];
    $feedbackMessage = $flash['message'];
}

$siteName = getSetting('site_name', DEFAULT_SITE_NAME);
$bengaliName = getSetting('bengali_name', DEFAULT_BENGALI_NAME);
$officeAddress = getSetting('office_address', DEFAULT_ADDRESS);
$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$secondaryPhone = getSetting('phone_secondary', DEFAULT_PHONE_SECONDARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
$contactEmail = getSetting('contact_email', DEFAULT_EMAIL);
$businessHours = getSetting('business_hours', 'Sat - Thu: 9:30 AM - 8:30 PM (Friday: By Prior Appointment)');
$mapsEmbed = getSetting('google_maps_embed', 'https://maps.google.com/maps?q=South+Salna+Gazipur+Bangladesh&t=&z=14&ie=UTF8&iwloc=&output=embed');

$pageTitle = "Contact Mahin Travel & Tours | Gazipur Office & WhatsApp";
$pageDescription = "Connect with Mahin Travel & Tours at our South Salna Gazipur office. Call +8801924713765 or chat on WhatsApp for fast visa and tour consultation.";
$currentPage = 'contact';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header Hero -->
<section class="page-hero" style="background-image: url('<?= url('assets/images/hero-contact.jpg') ?>');">
    <div class="page-hero-overlay"></div>
    <div class="container">
        <div class="page-hero-breadcrumbs">
            <a href="<?= url() ?>">Home</a> &nbsp;/&nbsp; <span>Contact Us</span>
        </div>
        <div class="hero-badge" style="margin-bottom: 1rem;">
            <?= renderIcon('phone', '', 16) ?>
            <span>Direct Human Assistance</span>
        </div>
        <h1 class="page-hero-title">
            Contact Our Travel Consultants
        </h1>
        <p class="page-hero-desc">
            Reach us via phone, WhatsApp, online inquiry, or drop by our physical headquarters in South Salna, Gazipur for in-person consultation.
        </p>
    </div>
</section>

<!-- Contact Form & Details Section -->
<section style="padding: 4.5rem 0 6rem 0;">
    <div class="container">
        <?php if (!empty($feedbackMessage)): ?>
            <div class="alert alert-<?= $feedbackType === 'success' ? 'success' : 'error' ?>">
                <?= renderIcon($feedbackType === 'success' ? 'check' : 'shield-check', '', 22) ?>
                <div><?= e($feedbackMessage) ?></div>
            </div>
        <?php endif; ?>

        <div class="contact-wrapper">
            <!-- Left Column: Official Contact & Location Details -->
            <div class="contact-info-panel">
                <div>
                    <span class="visa-badge" style="background: rgba(185,139,98,0.25); color: var(--champagne); border-color: rgba(223,184,146,0.4); margin-bottom: 1rem; display: inline-block;">
                        Gazipur Registered Office
                    </span>
                    <h3>Mahin Travel & Tours</h3>
                    <p style="font-family: var(--font-bengali); color: var(--champagne); font-size: 1rem; margin-top: -0.5rem; margin-bottom: 1.5rem;">
                        <?= e($bengaliName) ?>
                    </p>
                    <p>
                        We welcome clients for face-to-face dossier reviews, passport submission guidance, and customized itinerary planning.
                    </p>
                </div>

                <div class="contact-block">
                    <?= renderIcon('map-pin', '', 22) ?>
                    <div class="contact-block-text">
                        <h5>Physical Address</h5>
                        <p><?= e($officeAddress) ?></p>
                    </div>
                </div>

                <div class="contact-block">
                    <?= renderIcon('phone', '', 22) ?>
                    <div class="contact-block-text">
                        <h5>Direct Call Lines</h5>
                        <p>
                            <a href="<?= getTelUrl($primaryPhone) ?>"><?= e($primaryPhone) ?></a><br>
                            <a href="<?= getTelUrl($secondaryPhone) ?>"><?= e($secondaryPhone) ?></a>
                        </p>
                    </div>
                </div>

                <div class="contact-block">
                    <?= renderIcon('whatsapp', '', 22) ?>
                    <div class="contact-block-text">
                        <h5>WhatsApp Consultation</h5>
                        <p>
                            <a href="<?= getWhatsAppUrl() ?>" target="_blank" rel="noopener" style="color: var(--champagne); font-weight: 600;">
                                <?= e($whatsappNumber) ?> (Click to Chat)
                            </a>
                        </p>
                    </div>
                </div>

                <div class="contact-block">
                    <?= renderIcon('mail', '', 22) ?>
                    <div class="contact-block-text">
                        <h5>Official Email</h5>
                        <p>
                            <a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a>
                        </p>
                    </div>
                </div>

                <div class="contact-block">
                    <?= renderIcon('clock', '', 22) ?>
                    <div class="contact-block-text">
                        <h5>Office Hours</h5>
                        <p><?= e($businessHours) ?></p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Lead Capture Form -->
            <div class="contact-form-panel">
                <h2 class="form-title">Send Us a Direct Inquiry</h2>
                <p class="form-subtitle">
                    Fill out the form below. We treat all travel inquiries with utmost confidentiality.
                </p>

                <form action="<?= url('contact.php') ?>" method="POST" class="ajax-contact-form">
                    <?= csrf_field() ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Your Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Mohammad Rahim" required>
                        </div>

                        <div class="form-group">
                            <label>Phone / WhatsApp Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. +88019xxxxxxxx" required>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="yourname@gmail.com">
                        </div>

                        <div class="form-group">
                            <label>Service Required</label>
                            <select name="service_type" class="form-control">
                                <option value="Tourist Visa Assistance">Tourist / Visit Visa Assistance</option>
                                <option value="Air Ticketing">International Air Ticketing</option>
                                <option value="Umrah Package">Umrah Pilgrimage Package</option>
                                <option value="Holiday Tour">Curated Holiday Tour</option>
                                <option value="Student / Work Visa Guidance">Student / Work Visa Guidance</option>
                                <option value="Document Attestation">Document Attestation & Translation</option>
                                <option value="General Inquiry">General Travel Question</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Dubai Visa Consultation or Flight to London">
                        </div>

                        <div class="form-group full-width">
                            <label>Your Message / Travel Requirements *</label>
                            <textarea name="message" class="form-control" placeholder="Please describe your intended destination, travel dates, number of travelers, or any specific questions..." required></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; height: 50px;">
                        <?= renderIcon('check', '', 18) ?>
                        <span>Submit Travel Inquiry</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Google Maps Location Section -->
        <div style="margin-top: 5rem;">
            <div class="section-header" style="margin-bottom: 2rem;">
                <span class="section-tag">Locate Our Office</span>
                <h3 class="section-title" style="font-size: 2rem;">Holding no-1492, South Salna, Gazipur</h3>
                <p class="section-subtitle">Convenient road access from Dhaka-Mymensingh highway and surrounding Gazipur areas.</p>
            </div>

            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-light); box-shadow: var(--shadow-lg); height: 420px; background: #E5E9F0;">
                <iframe 
                    src="<?= e($mapsEmbed) ?>" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Mahin Travel & Tours Office Location">
                </iframe>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
