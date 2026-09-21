<?php
/**
 * Mahin Travel & Tours - Dynamic XML Sitemap
 */

header('Content-Type: application/xml; charset=utf-8');
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDB();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Core Pages -->
    <url>
        <loc><?= url() ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= url('about.php') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= url('services.php') ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= url('visa-services.php') ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= url('tours.php') ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= url('destinations.php') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= url('faq.php') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?= url('contact.php') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Dynamic Services -->
    <?php
    $srvs = $pdo->query("SELECT slug, created_at FROM services WHERE status = 1")->fetchAll();
    foreach ($srvs as $s):
    ?>
    <url>
        <loc><?= url('service-detail.php?slug=' . urlencode($s['slug'])) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <?php endforeach; ?>

    <!-- Dynamic Visas -->
    <?php
    $visas = $pdo->query("SELECT slug, created_at FROM visa_categories WHERE status = 1")->fetchAll();
    foreach ($visas as $v):
    ?>
    <url>
        <loc><?= url('visa-detail.php?slug=' . urlencode($v['slug'])) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <?php endforeach; ?>

    <!-- Dynamic Tours -->
    <?php
    $tours = $pdo->query("SELECT slug, created_at FROM tours WHERE status = 1")->fetchAll();
    foreach ($tours as $t):
    ?>
    <url>
        <loc><?= url('tour-detail.php?slug=' . urlencode($t['slug'])) ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    <?php endforeach; ?>
</urlset>
