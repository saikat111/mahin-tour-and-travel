-- ==============================================================================
-- Mahin Travel & Tours - MySQL Database Schema
-- Website: mahintravelandtours.com
-- Location: Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(60) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'superadmin',
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Site Settings
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(60) NOT NULL,
  `setting_value` TEXT NULL,
  `setting_group` VARCHAR(40) NOT NULL DEFAULT 'general',
  `field_type` VARCHAR(30) NOT NULL DEFAULT 'text',
  `label` VARCHAR(100) NOT NULL,
  `help_text` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `field_type`, `label`, `help_text`) VALUES
('site_name', 'Mahin Travel & Tours', 'general', 'text', 'Company / Brand Name', 'Official trading name'),
('bengali_name', 'মাহিন ট্রাভেল এন্ড ট্যুরস', 'general', 'text', 'Bengali Brand Name', 'Official Bengali script name'),
('tagline', 'Your Trusted Gateway to the World', 'general', 'text', 'Brand Tagline', 'Primary marketing slogan'),
('owner_name', 'Managing Director', 'general', 'text', 'Contact / Authorized Person', 'Primary business representative'),
('phone_primary', '+8801924713765', 'contact', 'text', 'Primary Phone Number', 'Main customer support line'),
('phone_secondary', '+8801722203033', 'contact', 'text', 'Secondary Phone Number', 'Alternative contact number'),
('whatsapp_number', '+8801924713765', 'contact', 'text', 'WhatsApp Number', 'Direct WhatsApp consultation line'),
('contact_email', 'info@mahintravelandtours.com', 'contact', 'email', 'Official Email Address', 'General inquiries email'),
('office_address', 'Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh', 'contact', 'textarea', 'Office Address', 'Physical registered headquarters'),
('business_hours', 'Saturday - Thursday: 9:30 AM - 8:30 PM (Friday: Closed / By Prior Appointment)', 'contact', 'text', 'Business Hours', 'Customer operating hours'),
('google_maps_embed', 'https://maps.google.com/maps?q=South+Salna+Gazipur+Bangladesh&t=&z=14&ie=UTF8&iwloc=&output=embed', 'contact', 'textarea', 'Google Maps Embed URL', 'Embed URL for the contact page'),
('hero_badge', 'Certified Travel & Visa Consultants', 'appearance', 'text', 'Hero Badge Text', 'Small badge above hero headline'),
('hero_title', 'Seamless Journeys, Authentic Visa Advisory & Curated Global Tours', 'appearance', 'text', 'Hero Main Title', 'H1 on the homepage'),
('hero_subtitle', 'From our Gazipur headquarters to destinations across the globe, Mahin Travel & Tours delivers meticulous visa processing, international air ticketing, and bespoke holiday packages with personal human dedication.', 'appearance', 'textarea', 'Hero Subtitle', 'Descriptive copy in hero section'),
('default_meta_title', 'Mahin Travel & Tours | Trusted Visa Assistance & Global Tour Operator Gazipur', 'seo', 'text', 'Default Meta Title', 'Browser title for pages without custom title'),
('default_meta_desc', 'Mahin Travel & Tours in Gazipur offers professional visa assistance, international air tickets, Umrah packages, and curated global holiday tours. Transparent advisory and dedicated support.', 'seo', 'textarea', 'Default Meta Description', 'Search engine snippet'),
('default_keywords', 'travel agency gazipur, visa processing bangladesh, air tickets gazipur, umrah package bangladesh, mahin travel and tours, holiday tours', 'seo', 'text', 'Default Meta Keywords', 'Comma-separated keywords'),
('smtp_host', 'mail.mahintravelandtours.com', 'email', 'text', 'SMTP Host', 'cPanel outgoing mail server'),
('smtp_port', '465', 'email', 'number', 'SMTP Port', 'Port 465 (SSL) or 587 (TLS)'),
('smtp_user', 'support@mahintravelandtours.com', 'email', 'text', 'SMTP Username', 'Full email address'),
('smtp_pass', 'zzAD%1G}IeGz8l5J', 'email', 'password', 'SMTP Password', 'cPanel mailbox password'),
('smtp_secure', 'ssl', 'email', 'text', 'Encryption', 'ssl or tls'),
('smtp_from_email', 'support@mahintravelandtours.com', 'email', 'email', 'From Email', 'Sender email address'),
('smtp_from_name', 'Mahin Travel & Tours', 'email', 'text', 'From Name', 'Sender display name'),
('mail_notification_recipient', 'support@mahintravelandtours.com', 'email', 'email', 'Admin Notification Email', 'Inquiry notification recipient');

-- 3. Services Table
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `short_desc` VARCHAR(350) NOT NULL,
  `full_desc` MEDIUMTEXT NOT NULL,
  `icon_name` VARCHAR(50) NOT NULL DEFAULT 'plane',
  `featured_image` VARCHAR(255) NULL,
  `category` VARCHAR(60) NOT NULL DEFAULT 'general',
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  `meta_title` VARCHAR(180) NULL,
  `meta_desc` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_service_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Visa Categories & Guides
CREATE TABLE IF NOT EXISTS `visa_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_name` VARCHAR(100) NOT NULL,
  `country_code` VARCHAR(10) NOT NULL,
  `visa_type` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `processing_time` VARCHAR(100) NOT NULL,
  `validity` VARCHAR(100) NULL,
  `requirement_summary` TEXT NOT NULL,
  `detailed_requirements` MEDIUMTEXT NOT NULL,
  `fees_note` VARCHAR(255) NULL,
  `image` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  `meta_title` VARCHAR(180) NULL,
  `meta_desc` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_visa_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Visa Process Steps
CREATE TABLE IF NOT EXISTS `visa_process_steps` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `step_number` INT NOT NULL,
  `title` VARCHAR(120) NOT NULL,
  `description` TEXT NOT NULL,
  `icon_name` VARCHAR(50) NOT NULL DEFAULT 'file-text',
  `status` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Destinations Table
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `country` VARCHAR(100) NOT NULL,
  `continent` VARCHAR(60) NOT NULL DEFAULT 'Asia',
  `featured_image` VARCHAR(255) NULL,
  `overview` MEDIUMTEXT NOT NULL,
  `popular_for` VARCHAR(255) NULL,
  `best_time` VARCHAR(100) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_destination_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tours Table
CREATE TABLE IF NOT EXISTS `tours` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `destination_id` INT UNSIGNED NULL,
  `duration_days` INT NOT NULL DEFAULT 4,
  `duration_nights` INT NOT NULL DEFAULT 3,
  `price_text` VARCHAR(80) NOT NULL DEFAULT 'Contact for Price',
  `price_type` VARCHAR(40) NOT NULL DEFAULT 'custom',
  `featured_image` VARCHAR(255) NULL,
  `overview` MEDIUMTEXT NOT NULL,
  `itinerary_json` MEDIUMTEXT NULL,
  `inclusions` MEDIUMTEXT NULL,
  `exclusions` MEDIUMTEXT NULL,
  `is_featured` TINYINT NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  `meta_title` VARCHAR(180) NULL,
  `meta_desc` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_tour_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tour Gallery
CREATE TABLE IF NOT EXISTS `tour_gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tour_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(150) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. FAQs Table
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(60) NOT NULL DEFAULT 'general',
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Testimonials Table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(100) NOT NULL,
  `client_role_or_location` VARCHAR(100) NOT NULL DEFAULT 'Traveler',
  `comment` TEXT NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `avatar` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Contact Messages & Inquiries Table
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `subject` VARCHAR(180) NULL,
  `service_type` VARCHAR(100) NULL,
  `message` TEXT NOT NULL,
  `ip_address` VARCHAR(50) NULL,
  `is_read` TINYINT NOT NULL DEFAULT 0,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_msg_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Media Library Table
CREATE TABLE IF NOT EXISTS `media_library` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(200) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT UNSIGNED NOT NULL DEFAULT 0,
  `mime_type` VARCHAR(80) NOT NULL,
  `alt_text` VARCHAR(200) NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
