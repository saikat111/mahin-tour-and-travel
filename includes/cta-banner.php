<?php
/**
 * Mahin Travel & Tours - Conversion CTA Banner Component
 */
$primaryPhone = getSetting('phone_primary', DEFAULT_PHONE_PRIMARY);
$whatsappNumber = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
?>
<section class="container">
    <div class="cta-banner">
        <div class="cta-inner">
            <div class="cta-text">
                <span class="section-tag" style="background: rgba(223, 184, 146, 0.15); border-color: rgba(223, 184, 146, 0.35); color: var(--champagne); margin-bottom: 0.75rem;">
                    Personalized Travel & Visa Consultation
                </span>
                <h2 class="cta-title">Ready to Plan Your Next Journey with Absolute Peace of Mind?</h2>
                <p class="cta-desc">
                    Connect directly with our experienced consultants in Gazipur. We evaluate your requirements, verify your documents, and craft flawless travel solutions.
                </p>
            </div>
            <div class="cta-buttons">
                <a href="<?= getWhatsAppUrl() ?>" class="btn btn-whatsapp" target="_blank" rel="noopener">
                    <?= renderIcon('whatsapp', '', 18) ?>
                    <span>Chat on WhatsApp</span>
                </a>
                <a href="<?= getTelUrl($primaryPhone) ?>" class="btn btn-outline-white">
                    <?= renderIcon('phone', '', 18) ?>
                    <span>Call: <?= e($primaryPhone) ?></span>
                </a>
                <a href="<?= url('contact.php') ?>" class="btn btn-primary">
                    <?= renderIcon('calendar-check', '', 18) ?>
                    <span>Visit Our Office</span>
                </a>
            </div>
        </div>
    </div>
</section>
