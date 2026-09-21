<?php
/**
 * Mahin Travel & Tours - Database Migrator & Seeder
 * Run via CLI: php scripts/seed.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/init_sqlite.php';
require_once __DIR__ . '/seed_data.php';

echo "========================================\n";
echo "Mahin Travel & Tours - Database Setup\n";
echo "========================================\n";

$pdo = getDB();
$driver = Database::getDriverType();
echo "Active Database Driver: " . strtoupper($driver) . "\n";

if ($driver === 'sqlite') {
    echo "Initializing SQLite Schema & Seeding...\n";
    initializeSqliteDatabase($pdo);
} else {
    echo "Executing MySQL Schema & Seeding...\n";
    $sql = file_get_contents(__DIR__ . '/../sql/database.sql');
    $pdo->exec($sql);
    seedDatabase($pdo);
}

echo "Database seeded successfully!\n";
echo "Default Administrator Login:\n";
echo "Username: admin\n";
echo "Password: MahinTravel@2026!\n";
echo "========================================\n";
