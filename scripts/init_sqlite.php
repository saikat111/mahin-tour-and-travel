<?php
/**
 * Mahin Travel & Tours - SQLite Schema Initializer
 * Creates equivalent SQLite schema if running local dev preview without MySQL
 */

function initializeSqliteDatabase(PDO $pdo): void {
    $schema = <<<SQL
    CREATE TABLE IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL,
        email TEXT NOT NULL,
        full_name TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'superadmin',
        last_login DATETIME NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS site_settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        setting_key TEXT NOT NULL UNIQUE,
        setting_value TEXT NULL,
        setting_group TEXT NOT NULL DEFAULT 'general',
        field_type TEXT NOT NULL DEFAULT 'text',
        label TEXT NOT NULL,
        help_text TEXT NULL
    );

    CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        short_desc TEXT NOT NULL,
        full_desc TEXT NOT NULL,
        icon_name TEXT NOT NULL DEFAULT 'plane',
        featured_image TEXT NULL,
        category TEXT NOT NULL DEFAULT 'general',
        sort_order INTEGER NOT NULL DEFAULT 0,
        status INTEGER NOT NULL DEFAULT 1,
        meta_title TEXT NULL,
        meta_desc TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS visa_categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        country_name TEXT NOT NULL,
        country_code TEXT NOT NULL,
        visa_type TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        processing_time TEXT NOT NULL,
        validity TEXT NULL,
        requirement_summary TEXT NOT NULL,
        detailed_requirements TEXT NOT NULL,
        fees_note TEXT NULL,
        image TEXT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0,
        status INTEGER NOT NULL DEFAULT 1,
        meta_title TEXT NULL,
        meta_desc TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS visa_process_steps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        step_number INTEGER NOT NULL,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        icon_name TEXT NOT NULL DEFAULT 'file-text',
        status INTEGER NOT NULL DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS destinations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        country TEXT NOT NULL,
        continent TEXT NOT NULL DEFAULT 'Asia',
        featured_image TEXT NULL,
        overview TEXT NOT NULL,
        popular_for TEXT NULL,
        best_time TEXT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0,
        status INTEGER NOT NULL DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS tours (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        destination_id INTEGER NULL,
        duration_days INTEGER NOT NULL DEFAULT 4,
        duration_nights INTEGER NOT NULL DEFAULT 3,
        price_text TEXT NOT NULL DEFAULT 'Contact for Price',
        price_type TEXT NOT NULL DEFAULT 'custom',
        featured_image TEXT NULL,
        overview TEXT NOT NULL,
        itinerary_json TEXT NULL,
        inclusions TEXT NULL,
        exclusions TEXT NULL,
        is_featured INTEGER NOT NULL DEFAULT 1,
        sort_order INTEGER NOT NULL DEFAULT 0,
        status INTEGER NOT NULL DEFAULT 1,
        meta_title TEXT NULL,
        meta_desc TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS tour_gallery (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tour_id INTEGER NOT NULL,
        image_path TEXT NOT NULL,
        caption TEXT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0
    );

    CREATE TABLE IF NOT EXISTS faqs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT NOT NULL DEFAULT 'general',
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0,
        status INTEGER NOT NULL DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS testimonials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        client_name TEXT NOT NULL,
        client_role_or_location TEXT NOT NULL DEFAULT 'Traveler',
        comment TEXT NOT NULL,
        rating INTEGER NOT NULL DEFAULT 5,
        avatar TEXT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0,
        status INTEGER NOT NULL DEFAULT 1
    );

    CREATE TABLE IF NOT EXISTS contact_messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT NOT NULL,
        subject TEXT NULL,
        service_type TEXT NULL,
        message TEXT NOT NULL,
        ip_address TEXT NULL,
        is_read INTEGER NOT NULL DEFAULT 0,
        notes TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS media_library (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        file_name TEXT NOT NULL,
        file_path TEXT NOT NULL,
        file_size INTEGER NOT NULL DEFAULT 0,
        mime_type TEXT NOT NULL,
        alt_text TEXT NULL,
        uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
SQL;

    $pdo->exec($schema);
    
    // Seed initial data
    require_once __DIR__ . '/seed_data.php';
    seedDatabase($pdo);
}
