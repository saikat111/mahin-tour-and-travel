<?php
/**
 * Mahin Travel & Tours - Real High-Resolution Photographic Asset Downloader
 * Downloads curated, authentic, royalty-free photography from Unsplash CDN
 */

$assets = [
    // 1. Cinematic Hero Slider Slides (1920x1080)
    'assets/images/hero-dubai.jpg' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-makkah.jpg' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-asia.jpg' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-europe.jpg' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-bg.jpg' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1920&q=80',

    // 2. Subpage Hero Banners (1920x800)
    'assets/images/hero-about.jpg' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-services.jpg' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-visa.jpg' => 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-tours.jpg' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-destinations.jpg' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-contact.jpg' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80',
    'assets/images/hero-faq.jpg' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1920&q=80',

    // 3. Core Services Images (800x530)
    'assets/images/services/air-ticketing.jpg' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=800&q=80',
    'assets/images/services/tourist-visa.jpg' => 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=800&q=80',
    'assets/images/services/umrah-services.jpg' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?auto=format&fit=crop&w=800&q=80',
    'assets/images/services/student-visa.jpg' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80',
    'assets/images/services/holiday-tours.jpg' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
    'assets/images/services/document-attestation.jpg' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=800&q=80',

    // 4. Visa Destination Images (800x530)
    'assets/images/visas/dubai-visa.jpg' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80',
    'assets/images/visas/saudi-visa.jpg' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=800&q=80',
    'assets/images/visas/malaysia-visa.jpg' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=800&q=80',
    'assets/images/visas/thailand-visa.jpg' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=800&q=80',
    'assets/images/visas/singapore-visa.jpg' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=800&q=80',
    'assets/images/visas/uk-schengen-visa.jpg' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=800&q=80',

    // 5. Featured Destination Images (800x600)
    'assets/images/destinations/dubai.jpg' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=800&q=80',
    'assets/images/destinations/malaysia.jpg' => 'https://images.unsplash.com/photo-1596422846543-75c6fc197f07?auto=format&fit=crop&w=800&q=80',
    'assets/images/destinations/thailand.jpg' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?auto=format&fit=crop&w=800&q=80',
    'assets/images/destinations/saudi.jpg' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?auto=format&fit=crop&w=800&q=80',
    'assets/images/destinations/singapore.jpg' => 'https://images.unsplash.com/photo-1525625293386-3f8f99389edd?auto=format&fit=crop&w=800&q=80',
    'assets/images/destinations/sylhet.jpg' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80',

    // 6. Curated Tour Packages Images (800x530)
    'assets/images/tours/dubai-package.jpg' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=800&q=80',
    'assets/images/tours/malaysia-thailand.jpg' => 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?auto=format&fit=crop&w=800&q=80',
    'assets/images/tours/umrah-package.jpg' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=800&q=80',
    'assets/images/tours/sylhet-package.jpg' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80',

    // 7. Client Testimonial Avatars (300x300)
    'assets/images/testimonials/avatar1.jpg' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
    'assets/images/testimonials/avatar2.jpg' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
    'assets/images/testimonials/avatar3.jpg' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
    'assets/images/testimonials/avatar4.jpg' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80'
];

$baseDir = dirname(__DIR__);
$successCount = 0;
$failCount = 0;

echo "Starting download of " . count($assets) . " high-resolution travel photography assets...\n";

$opts = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
        'timeout' => 15
    ]
];
$context = stream_context_create($opts);

foreach ($assets as $relPath => $url) {
    $fullPath = $baseDir . '/' . $relPath;
    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    echo "Downloading {$relPath}... ";
    $data = @file_get_contents($url, false, $context);
    if ($data !== false && strlen($data) > 1000) {
        file_put_contents($fullPath, $data);
        echo "OK (" . round(strlen($data) / 1024) . " KB)\n";
        $successCount++;
    } else {
        echo "FAILED (Using fallback)\n";
        $failCount++;
    }
}

echo "\nDownload Summary: {$successCount} downloaded successfully, {$failCount} failed.\n";
