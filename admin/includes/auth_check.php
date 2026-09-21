<?php
/**
 * Mahin Travel & Tours - Admin Authentication Guard
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: " . ADMIN_URL . "/login.php");
    exit;
}

// Fetch current admin profile info
$currentAdminId = $_SESSION['admin_id'] ?? 0;
$currentAdminName = $_SESSION['admin_name'] ?? 'Administrator';
$currentAdminRole = $_SESSION['admin_role'] ?? 'superadmin';
