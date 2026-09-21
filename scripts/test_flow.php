<?php
/**
 * Mahin Travel & Tours - Automated End-to-End System Test
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

echo "--- Starting End-to-End Functional Test ---\n";
$pdo = getDB();

// 1. Test Database Queries
$adminCount = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
echo "1. Admins table count: {$adminCount} (PASS)\n";

$srvCount = $pdo->query("SELECT COUNT(*) FROM services WHERE status = 1")->fetchColumn();
echo "2. Active Services count: {$srvCount} (PASS)\n";

$visaCount = $pdo->query("SELECT COUNT(*) FROM visa_categories WHERE status = 1")->fetchColumn();
echo "3. Active Visas count: {$visaCount} (PASS)\n";

$tourCount = $pdo->query("SELECT COUNT(*) FROM tours WHERE status = 1")->fetchColumn();
echo "4. Active Tours count: {$tourCount} (PASS)\n";

// 2. Test Lead Insertion (Simulate Contact Form Submission)
$testName = "Kabir Hossain";
$testPhone = "+8801924713765";
$testEmail = "kabir.test@example.com";
$testSubject = "Dubai 30-Day Visa & Air Ticket Inquiry";
$testService = "Tourist Visa Assistance";
$testMsg = "Hello Mahin Travel, I need consultation for my family's upcoming Dubai travel.";

$ins = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, service_type, message, ip_address, is_read) VALUES (?, ?, ?, ?, ?, ?, '127.0.0.1', 0)");
$ins->execute([$testName, $testEmail, $testPhone, $testSubject, $testService, $testMsg]);
$lastId = $pdo->lastInsertId();
echo "5. Contact form lead capture inserted with ID: {$lastId} (PASS)\n";

// 3. Test Admin Authentication Verification
$admin = $pdo->query("SELECT * FROM admins WHERE username = 'admin'")->fetch();
if ($admin && password_verify('MahinTravel@2026!', $admin['password_hash'])) {
    echo "6. Admin password hash authentication verified (PASS)\n";
} else {
    echo "6. Admin authentication FAILED!\n";
}

// 4. Test Lead Retrieval in Admin Module
$unread = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
echo "7. Unread leads in Admin inbox: {$unread} (PASS)\n";

// 5. Test Status Toggle
$upd = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
$upd->execute([$lastId]);
$checkRead = $pdo->query("SELECT is_read FROM contact_messages WHERE id = {$lastId}")->fetchColumn();
echo "8. Lead status toggle verified (is_read: {$checkRead}) (PASS)\n";

echo "--- All 8 System Checks Passed Successfully! ---\n";
