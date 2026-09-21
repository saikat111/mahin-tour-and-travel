<?php
/**
 * Mahin Travel & Tours - Master Configuration
 * Domain: mahintravelandtours.com
 * Location: Gazipur, Bangladesh
 */

// 0. Load .env Environment File if Available
$envFilePath = dirname(__DIR__) . '/.env';
if (file_exists($envFilePath) && is_readable($envFilePath)) {
    $envLines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$envKey, $envVal] = explode('=', $line, 2);
            $envKey = trim($envKey);
            $envVal = trim($envVal, " \t\n\r\0\x0B\"'");
            putenv("{$envKey}={$envVal}");
            $_ENV[$envKey] = $envVal;
            $_SERVER[$envKey] = $envVal;
        }
    }
}

// 1. Session Setup with Secure Defaults
if (session_status() === PHP_SESSION_NONE) {
    // Only set cookie params if headers haven't been sent
    if (!headers_sent()) {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                    (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        
        session_set_cookie_params([
            'lifetime' => 86400 * 7, // 7 days
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    session_start();
}

// 2. Timezone & Locale
date_default_timezone_set('Asia/Dhaka');

// 3. Error Reporting (Safe for production, detailed for local dev)
$isLocalDev = (
    in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || 
    (php_sapi_name() === 'cli-server') || 
    (php_sapi_name() === 'cli')
);

if ($isLocalDev) {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../error_log.log');
}

// 4. Base Paths
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', __DIR__);
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// 5. Dynamic Base URL Detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? '') == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$baseDir = rtrim(preg_replace('#/(admin|pages|scripts)$#', '', $scriptDir), '/');
$baseUrl = $protocol . $host . ($baseDir === '/' ? '' : $baseDir);

define('BASE_URL', rtrim($baseUrl, '/'));
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');
define('ADMIN_URL', BASE_URL . '/admin');

// 6. Database Credentials (MySQL Primary for cPanel)
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'mahintravel_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', 'utf8mb4');

// 7. Site Defaults
define('DEFAULT_SITE_NAME', 'Mahin Travel & Tours');
define('DEFAULT_TAGLINE', 'Your Trusted Gateway to the World');
define('DEFAULT_PHONE_PRIMARY', '+8801924713765');
define('DEFAULT_PHONE_SECONDARY', '+8801722203033');
define('DEFAULT_WHATSAPP', '+8801924713765');
define('DEFAULT_EMAIL', 'info@mahintravelandtours.com');
define('DEFAULT_ADDRESS', 'Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh');
define('DEFAULT_BENGALI_NAME', 'মাহিন ট্রাভেল এন্ড ট্যুরস');
