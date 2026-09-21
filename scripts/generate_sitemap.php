<?php
/**
 * Mahin Travel & Tours - Static Sitemap XML Generator
 */
ob_start();
require __DIR__ . '/../sitemap.php';
$xml = ob_get_clean();
file_put_contents(__DIR__ . '/../sitemap.xml', $xml);
echo "sitemap.xml generated successfully.\n";
