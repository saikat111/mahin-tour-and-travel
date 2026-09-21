<?php
$srcPath = __DIR__ . '/../assets/images/logo.png';
if (!file_exists($srcPath)) {
    die("Logo not found at $srcPath\n");
}
$src = imagecreatefrompng($srcPath);
$w = imagesx($src);
$h = imagesy($src);

// Extract the emblem portion for a crisp square favicon
$emblemW = 460;
$emblemH = 380;
$emblemX = 154;
$emblemY = 120;

$fav = imagecreatetruecolor(128, 128);
imagealphablending($fav, false);
imagesavealpha($fav, true);
$transparent = imagecolorallocatealpha($fav, 255, 255, 255, 127);
imagefilledrectangle($fav, 0, 0, 128, 128, $transparent);
imagecopyresampled($fav, $src, 0, 0, $emblemX, $emblemY, 128, 128, $emblemW, $emblemH);
imagepng($fav, __DIR__ . '/../assets/images/favicon.png');
echo "Favicon generated: assets/images/favicon.png\n";
