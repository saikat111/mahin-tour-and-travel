<?php
/**
 * Mahin Travel & Tours - Core Functions & Helpers
 * Security, sanitization, SVG rendering, and settings access.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

/**
 * Escape HTML output to prevent XSS attacks
 */
function e(?string $string): string {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Fetch a site setting by its key with in-memory caching
 */
function getSetting(string $key, string $default = ''): string {
    static $settingsCache = null;
    
    if ($settingsCache === null) {
        $settingsCache = [];
        try {
            $pdo = getDB();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            while ($row = $stmt->fetch()) {
                $settingsCache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            error_log("Error loading site settings: " . $e->getMessage());
        }
    }
    
    return $settingsCache[$key] ?? $default;
}

/**
 * Generate or get existing CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render a hidden CSRF token input field
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify a submitted CSRF token
 */
function verify_csrf_token(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Clean and trim user input
 */
function cleanInput($data): string {
    if (is_array($data)) return '';
    return trim(strip_tags((string)$data));
}

/**
 * Convert text to a URL-friendly slug
 */
function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Set flash alert message
 */
function setFlashMessage(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // success, error, warning, info
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlashMessage(): ?array {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Generate sanitized WhatsApp click-to-chat URL
 */
function getWhatsAppUrl(string $customMessage = ''): string {
    $rawPhone = getSetting('whatsapp_number', DEFAULT_WHATSAPP);
    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
    
    $message = !empty($customMessage) ? $customMessage : getSetting('whatsapp_link_message', 'Hello Mahin Travel & Tours! I would like to inquire about your travel services.');
    return "https://wa.me/{$cleanPhone}?text=" . urlencode($message);
}

/**
 * Generate tel: link from phone string
 */
function getTelUrl(string $phone): string {
    $clean = preg_replace('/[^\+0-9]/', '', $phone);
    return "tel:" . $clean;
}

/**
 * Render crisp, zero-dependency inline SVG icons
 */
function renderIcon(string $name, string $extraClass = '', int $size = 20): string {
    $classAttr = 'class="svg-icon ' . e($extraClass) . '"';
    $dimAttr = 'width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';

    switch ($name) {
        case 'plane':
        case 'plane-departure':
            return "<svg $dimAttr $classAttr><path d=\"M2 12h20\"/><path d=\"M20 12l-8-8\"/><path d=\"M20 12l-8 8\"/><path d=\"M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3.5c-.5-.5-2.5 0-4 1.5L13.5 8.5 5.3 6.7c-.8-.2-1.6.1-2.1.7l-.7.7 5.7 3.4-3.6 3.6-2.1-.7-1.4 1.4 3.5 2.1 2.1 3.5 1.4-1.4-.7-2.1 3.6-3.6 3.4 5.7.7-.7c.6-.5.9-1.3.7-2.1z\"/></svg>";

        case 'passport':
            return "<svg $dimAttr $classAttr><rect x=\"4\" y=\"2\" width=\"16\" height=\"20\" rx=\"2\" ry=\"2\"/><circle cx=\"12\" cy=\"10\" r=\"3\"/><path d=\"M7 17h10\"/><path d=\"M7 14h6\"/></svg>";

        case 'kaaba':
            return "<svg $dimAttr $classAttr><polygon points=\"12 2 2 7 12 12 22 7 12 2\"/><polyline points=\"2 17 12 22 22 17\"/><polyline points=\"2 12 12 17 22 12\"/></svg>";

        case 'graduation-cap':
            return "<svg $dimAttr $classAttr><path d=\"M22 10v6M2 10l10-5 10 5-10 5z\"/><path d=\"M6 12v5c3 3 9 3 12 0v-5\"/></svg>";

        case 'map-marked-alt':
        case 'map':
            return "<svg $dimAttr $classAttr><polygon points=\"1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6\"/><line x1=\"8\" y1=\"2\" x2=\"8\" y2=\"18\"/><line x1=\"16\" y1=\"6\" x2=\"16\" y2=\"22\"/></svg>";

        case 'stamp':
        case 'shield-check':
            return "<svg $dimAttr $classAttr><path d=\"M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z\"/><polyline points=\"9 12 11 14 15 10\"/></svg>";

        case 'phone':
            return "<svg $dimAttr $classAttr><path d=\"M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z\"/></svg>";

        case 'whatsapp':
            return "<svg $dimAttr $classAttr fill=\"currentColor\" stroke=\"none\" viewBox=\"0 0 24 24\"><path d=\"M17.472 14.382c-.301-.15-1.78-.878-2.056-.978-.275-.1-.476-.15-.676.15-.2.301-.777.978-.953 1.179-.176.2-.351.226-.652.075-.301-.15-1.27-.468-2.42-1.493-.895-.798-1.5-1.784-1.676-2.085-.176-.301-.019-.464.132-.614.136-.135.301-.351.452-.527.15-.176.2-.301.301-.502.101-.2.05-.376-.025-.526-.075-.15-.677-1.632-.928-2.235-.245-.588-.493-.508-.677-.518-.175-.008-.376-.01-.577-.01s-.527.076-.803.376c-.276.301-1.054 1.03-1.054 2.512s1.08 2.914 1.23 3.115c.15.2 2.126 3.246 5.15 4.554.719.311 1.28.497 1.719.636.723.23 1.381.197 1.902.119.58-.087 1.78-.727 2.031-1.43.25-.702.25-1.304.175-1.43-.075-.125-.276-.2-.577-.35zM12.042 21.998c-1.802 0-3.568-.485-5.121-1.405l-.367-.218-3.805.998 1.016-3.71-.24-.382a9.923 9.923 0 0 1-1.524-5.263c0-5.498 4.473-9.971 9.975-9.971 2.664 0 5.168 1.038 7.05 2.923 1.883 1.884 2.92 4.39 2.918 7.056 0 5.5-4.473 9.972-9.887 9.972zm8.414-18.39A11.895 11.895 0 0 0 12.042 0C5.402 0 .004 5.398.004 12.039a11.95 11.95 0 0 0 1.84 6.388L0 24l5.727-1.503a11.97 11.97 0 0 0 6.315 1.768h.005c6.64 0 12.038-5.398 12.038-12.039 0-3.216-1.252-6.24-3.535-8.524z\"/></svg>";

        case 'mail':
            return "<svg $dimAttr $classAttr><path d=\"M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z\"/><polyline points=\"22,6 12,13 2,6\"/></svg>";

        case 'map-pin':
            return "<svg $dimAttr $classAttr><path d=\"M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z\"/><circle cx=\"12\" cy=\"10\" r=\"3\"/></svg>";

        case 'clock':
            return "<svg $dimAttr $classAttr><circle cx=\"12\" cy=\"12\" r=\"10\"/><polyline points=\"12 6 12 12 16 14\"/></svg>";

        case 'calendar-check':
            return "<svg $dimAttr $classAttr><rect x=\"3\" y=\"4\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"/><line x1=\"16\" y1=\"2\" x2=\"16\" y2=\"6\"/><line x1=\"8\" y1=\"2\" x2=\"8\" y2=\"6\"/><line x1=\"3\" y1=\"10\" x2=\"21\" y2=\"10\"/><path d=\"M9 16l2 2 4-4\"/></svg>";

        case 'folder-check':
            return "<svg $dimAttr $classAttr><path d=\"M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z\"/><polyline points=\"9 13 11 15 15 11\"/></svg>";

        case 'compass':
            return "<svg $dimAttr $classAttr><circle cx=\"12\" cy=\"12\" r=\"10\"/><polygon points=\"16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76\"/></svg>";

        case 'check':
            return "<svg $dimAttr $classAttr><polyline points=\"20 6 9 17 4 12\"/></svg>";

        case 'arrow-right':
            return "<svg $dimAttr $classAttr><line x1=\"5\" y1=\"12\" x2=\"19\" y2=\"12\"/><polyline points=\"12 5 19 12 12 19\"/></svg>";

        case 'chevron-down':
            return "<svg $dimAttr $classAttr><polyline points=\"6 9 12 15 18 9\"/></svg>";

        case 'users':
            return "<svg $dimAttr $classAttr><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"/><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"/></svg>";

        case 'file-text':
        default:
            return "<svg $dimAttr $classAttr><path d=\"M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z\"/><polyline points=\"14 2 14 8 20 8\"/><line x1=\"16\" y1=\"13\" x2=\"8\" y2=\"13\"/><line x1=\"16\" y1=\"17\" x2=\"8\" y2=\"17\"/><polyline points=\"10 9 9 9 8 9\"/></svg>";
    }
}

/**
 * Render 5-star rating SVG
 */
function renderRatingStars(int $rating = 5): string {
    $html = '<div class="star-rating" aria-label="' . $rating . ' out of 5 stars">';
    for ($i = 1; $i <= 5; $i++) {
        $fill = $i <= $rating ? 'currentColor' : 'none';
        $html .= '<svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="' . $fill . '" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
    }
    $html .= '</div>';
    return $html;
}

/**
 * Build relative clean URL or fallback
 */
function url(string $path = ''): string {
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}
