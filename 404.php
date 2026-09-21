<?php
/**
 * Mahin Travel & Tours - 404 Not Found Page
 */

http_response_code(404);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = "Page Not Found";
$pageDescription = "The requested travel page or itinerary could not be found on Mahin Travel & Tours.";
$currentPage = '';

require_once __DIR__ . '/includes/header.php';
?>

<section style="padding: 7rem 0 8rem 0; text-align: center;">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto;">
            <span class="section-tag" style="background: rgba(185,139,98,0.15); color: var(--bronze); margin-bottom: 1.5rem;">
                Error 404
            </span>
            <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); color: var(--navy-primary); margin-bottom: 1rem;">
                Destination Not Found
            </h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7; margin-bottom: 2.5rem;">
                The travel page or itinerary you are looking for may have been updated, relocated, or is no longer available.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?= url() ?>" class="btn btn-primary">
                    <?= renderIcon('plane', '', 18) ?>
                    <span>Return to Homepage</span>
                </a>
                <a href="<?= url('services.php') ?>" class="btn btn-navy">
                    <?= renderIcon('compass', '', 18) ?>
                    <span>Explore Services</span>
                </a>
                <a href="<?= url('contact.php') ?>" class="btn btn-outline">
                    <?= renderIcon('phone', '', 18) ?>
                    <span>Contact Support</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
