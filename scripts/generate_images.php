<?php
/**
 * Mahin Travel & Tours - Asset Generator
 * Creates premium branded images for hero background, destinations, visas, and tours
 */

function createBrandedImage(string $path, int $width, int $height, string $title, string $subtitle, string $bgDark = '#0A2240', string $bgAccent = '#B98B62'): void {
    $img = imagecreatetruecolor($width, $height);
    
    // Parse hex colors
    list($r1, $g1, $b1) = sscanf($bgDark, "#%02x%02x%02x");
    list($r2, $g2, $b2) = sscanf($bgAccent, "#%02x%02x%02x");

    // Draw rich vertical gradient
    for ($y = 0; $y < $height; $y++) {
        $factor = $y / $height;
        $r = (int)($r1 + ($r2 - $r1) * $factor * 0.45);
        $g = (int)($g1 + ($g2 - $g1) * $factor * 0.45);
        $b = (int)($b1 + ($b2 - $b1) * $factor * 0.45);
        $col = imagecolorallocate($img, $r, $g, $b);
        imageline($img, 0, $y, $width, $y, $col);
    }

    // Add subtle ambient circles/mesh
    $glowCol = imagecolorallocatealpha($img, $r2, $g2, $b2, 105);
    imagefilledellipse($img, (int)($width * 0.8), (int)($height * 0.3), (int)($width * 0.7), (int)($height * 0.8), $glowCol);
    
    $navyGlow = imagecolorallocatealpha($img, 6, 21, 43, 85);
    imagefilledellipse($img, (int)($width * 0.2), (int)($height * 0.8), (int)($width * 0.6), (int)($height * 0.6), $navyGlow);

    // Subtle modern grid lines
    $gridCol = imagecolorallocatealpha($img, 255, 255, 255, 118);
    for ($x = 40; $x < $width; $x += 80) {
        imageline($img, $x, 0, $x, $height, $gridCol);
    }
    for ($y = 40; $y < $height; $y += 80) {
        imageline($img, 0, $y, $width, $y, $gridCol);
    }

    // Border line
    $borderCol = imagecolorallocatealpha($img, $r2, $g2, $b2, 70);
    imagerectangle($img, 15, 15, $width - 16, $height - 16, $borderCol);

    // Text rendering using built-in fonts (or fallback)
    $white = imagecolorallocate($img, 255, 255, 255);
    $gold = imagecolorallocate($img, 223, 184, 146);
    $slate = imagecolorallocate($img, 180, 200, 225);

    // Header badge
    imagestring($img, 3, 35, 35, "MAHIN TRAVEL & TOURS", $gold);
    
    // Title & subtitle
    imagestring($img, 5, 35, $height - 85, strtoupper($title), $white);
    imagestring($img, 3, 35, $height - 55, $subtitle, $slate);

    imagejpeg($img, $path, 90);
    imagedestroy($img);
}

// 1. Hero Background (1920x900)
createBrandedImage(__DIR__ . '/../assets/images/hero-bg.jpg', 1920, 900, "Global Horizons & Bespoke Expeditions", "Meticulous Visa Consultation & International Journeys", "#06152B", "#B98B62");

// 2. Services Images
$services = [
    ['air-ticketing.jpg', 'International Air Ticketing', 'Global Airline Routing & Special Fares'],
    ['tourist-visa.jpg', 'Tourist & Visit Visa Assistance', 'Meticulous Dossier Compilation & Embassy Advisory'],
    ['umrah-services.jpg', 'Umrah & Pilgrimage Services', 'Walking-Distance Hotels, Visas & Muallim Guidance'],
    ['student-visa.jpg', 'Student & Work Visa Guidance', 'Academic Verification & Employment Filing'],
    ['holiday-tours.jpg', 'Curated Holiday Packages', 'Southeast Asia, Dubai & Domestic Escapes'],
    ['document-attestation.jpg', 'Document Attestation & Advisory', 'Certified Translation & Embassy Insurance']
];
foreach ($services as $s) {
    createBrandedImage(__DIR__ . "/../assets/images/services/{$s[0]}", 800, 500, $s[1], $s[2], "#0A2240", "#B98B62");
}

// 3. Visa Destination Images
$visas = [
    ['dubai-visa.jpg', 'United Arab Emirates', 'Dubai 30/60 Days Tourist E-Visa'],
    ['saudi-visa.jpg', 'Saudi Arabia', 'Umrah & Tourist E-Visa Assistance'],
    ['malaysia-visa.jpg', 'Malaysia', 'eVISA & eNTRI Entry Filing'],
    ['thailand-visa.jpg', 'Thailand', 'Royal Thai Embassy Tourist Visa'],
    ['singapore-visa.jpg', 'Singapore', 'ICA Authorized Entry E-Visa'],
    ['uk-schengen-visa.jpg', 'UK & Schengen Area', 'Standard Visitor Visa Orientation']
];
foreach ($visas as $v) {
    createBrandedImage(__DIR__ . "/../assets/images/visas/{$v[0]}", 800, 500, $v[1], $v[2], "#081F3D", "#C89D76");
}

// 4. Destination Images
$destinations = [
    ['dubai.jpg', 'Dubai & Abu Dhabi', 'Modern Megacity & Desert Safaris'],
    ['malaysia.jpg', 'Kuala Lumpur & Genting', 'Iconic Towers & Rainforest Escapes'],
    ['thailand.jpg', 'Bangkok & Phuket', 'Temples, Floating Markets & Islands'],
    ['saudi.jpg', 'Makkah & Madinah', 'The Sacred Sanctuaries of Islam'],
    ['singapore.jpg', 'Singapore City', 'Gardens by the Bay & Marina Bay'],
    ['sylhet.jpg', 'Sylhet & Sreemangal', 'Tea Valley, Swamp Forest & Hills']
];
foreach ($destinations as $d) {
    createBrandedImage(__DIR__ . "/../assets/images/destinations/{$d[0]}", 800, 600, $d[1], $d[2], "#06152B", "#B98B62");
}

// 5. Tour Images
$tours = [
    ['dubai-package.jpg', 'Grand Dubai 5D4N', 'Luxury Hotel, Safari & Marina Cruise'],
    ['malaysia-thailand.jpg', 'Malaysia & Thailand 7D6N', 'Kuala Lumpur & Bangkok Twin City Tour'],
    ['umrah-package.jpg', 'Spiritual Umrah 10D9N', 'Makkah & Madinah Proximity Hotels'],
    ['sylhet-package.jpg', 'Sylhet Tea Valley 3D2N', 'Sreemangal, Lawachara & Ratargul']
];
foreach ($tours as $t) {
    createBrandedImage(__DIR__ . "/../assets/images/tours/{$t[0]}", 800, 500, $t[1], $t[2], "#0A2240", "#DFB892");
}

echo "All branded visual assets created successfully.\n";
