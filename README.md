# Mahin Travel & Tours - Deployment & Technical Documentation

**Official Website:** [mahintravelandtours.com](https://mahintravelandtours.com)  
**Brand Identity:** Mahin Travel And Tours (*মাহিন ট্রাভেল এন্ড ট্যুরস*)  
**Registered Headquarters:** Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh  
**Direct Contacts:** +8801924713765, +8801722203033 | **WhatsApp:** +8801924713765  
**Technology Stack:** Native PHP 8.0 - 8.5, MySQL PDO (with automatic zero-config SQLite local development fallback), Vanilla ES6 JavaScript, Bespoke CSS3 Custom Properties, Apache `.htaccess`.

---

## 1. Project Architecture Overview

```
├── .htaccess                   # Apache security headers, caching, clean URL rewrites
├── robots.txt                  # Search engine crawl directives & sitemap location
├── sitemap.xml                 # XML sitemap for Google/Bing indexing
├── sitemap.php                 # Dynamic sitemap generator
├── 404.php                     # Branded luxury 404 error page
├── index.php                   # Flagship Homepage (Hero, Services, Visa Hub, Tours, FAQs)
├── about.php                   # About Agency, Gazipur presence, ethical philosophy
├── services.php                # Filterable travel services directory
├── service-detail.php          # Detailed service specifications & custom inquiry modal
├── visa-services.php           # Visa Assistance Hub & 5-step roadmap
├── visa-detail.php             # Country visa requirements, checklists & guidelines
├── tours.php                   # Curated Holiday & Umrah Packages directory
├── tour-detail.php             # Day-by-day itineraries, inclusions/exclusions & quote request
├── destinations.php            # Worldwide & regional destination guides
├── contact.php                 # Working PHP inquiry form, direct dialers, Gazipur map
├── faq.php                     # Categorized travel & visa knowledge base
├── privacy.php                 # Privacy policy & data protection standards
├── terms.php                   # Terms of service & consular authority disclaimer
├── config/
│   ├── config.php              # Global environment paths, URLs, and database constants
│   ├── database.php            # PDO singleton (MySQL primary + SQLite local fallback)
│   └── database.sqlite         # Local zero-config database (for local CLI preview)
├── includes/
│   ├── functions.php           # Security sanitizers, CSRF, SVG rendering, settings helpers
│   ├── header.php              # Master header, navigation, schema.org JSON-LD, drawer
│   ├── footer.php              # Master footer, Gazipur address, floating WhatsApp widget
│   └── cta-banner.php          # Reusable high-conversion consultation banner
├── assets/
│   ├── css/
│   │   ├── style.css           # Master responsive design system (Navy & Bronze)
│   │   └── admin.css           # Executive CMS back-office stylesheet
│   ├── js/
│   │   ├── main.js             # Drawer toggle, scroll effects, accordions, modals
│   │   └── admin.js            # Confirmation dialogs, clipboard URL copy
│   └── images/
│       ├── logo.png            # Official Mahin Travel & Tours brand identity
│       ├── favicon.png         # Crisp 128x128 brand emblem favicon
│       ├── hero-dubai.jpg      # Slide 1: Burj Khalifa & Dubai skyline
│       ├── hero-makkah.jpg     # Slide 2: Sacred Kaaba & Holy Haram
│       ├── hero-asia.jpg       # Slide 3: Southeast Asian paradise & Petronas Towers
│       ├── hero-europe.jpg     # Slide 4: London Big Ben & UK/Europe travel
│       ├── hero-bg.jpg         # Panoramic aircraft wing & global horizon
│       ├── hero-about.jpg      # Subpage Banner: Agency story & Gazipur headquarters
│       ├── hero-services.jpg   # Subpage Banner: Comprehensive travel solutions
│       ├── hero-visa.jpg       # Subpage Banner: Visa processing & embassy advisory
│       ├── hero-tours.jpg      # Subpage Banner: Curated holidays & group packages
│       ├── hero-destinations.jpg# Subpage Banner: Global travel destinations guide
│       ├── hero-contact.jpg    # Subpage Banner: Customer care & office location
│       ├── hero-faq.jpg        # Subpage Banner: Knowledge base & FAQs
│       ├── services/           # Real photography for air ticketing, Umrah, visas, holidays
│       ├── visas/              # Authentic landmarks: Dubai, Saudi, Malaysia, Thailand, UK
│       ├── destinations/       # Global photography: Dubai, KL, Bangkok, Singapore, Sylhet
│       ├── tours/              # Curated tour photography (Dubai, Umrah, Thailand, Sylhet)
│       └── testimonials/       # Authentic traveler client avatars
├── uploads/                    # User-uploaded media via CMS
│   └── .htaccess               # Hardened security: PHP script execution disabled
├── admin/                      # Executive CMS Back-Office
│   ├── index.php               # Dashboard with KPI statistics & recent leads
│   ├── login.php               # Secure authentication with password hashing & CSRF
│   ├── logout.php              # Session termination
│   ├── settings.php            # Site Settings (Phones, WhatsApp, Address, SEO, Hero)
│   ├── messages.php            # Inquiries inbox with 1-click WhatsApp/email reply
│   ├── services.php            # Services CRUD & status toggles
│   ├── visa.php                # Visa categories & country checklists CRUD
│   ├── visa_steps.php          # 5-step roadmap manager
│   ├── tours.php               # Tour packages CRUD with itinerary editor
│   ├── destinations.php        # Destinations guide CRUD
│   ├── faqs.php                # FAQs manager
│   ├── testimonials.php        # Client feedback & star ratings CRUD
│   ├── media.php               # Secure file upload & media library
│   ├── profile.php             # Admin password change & credentials
│   └── includes/
│       ├── auth_check.php      # Route security guard
│       ├── admin_header.php    # Admin sidebar & navigation
│       └── admin_footer.php    # Admin footer scripts
├── sql/
│   └── database.sql            # Clean MySQL schema for cPanel phpMyAdmin
└── scripts/
    ├── download_real_images.php# Asset fetcher for 38 authentic high-res travel photos
    ├── init_sqlite.php         # SQLite initializer for local dev fallback
    ├── seed_data.php           # Master agency seed content
    ├── seed.php                # CLI migration & database seeder
    └── test_flow.php           # Full automated pipeline verification suite
```

---

## 2. Server Requirements

- **Web Server:** Apache 2.4+ (with `mod_rewrite` and `mod_headers` enabled).
- **PHP Version:** PHP 8.0, 8.1, 8.2, 8.3, 8.4, or 8.5.
- **PHP Extensions:** `pdo`, `pdo_mysql`, `gd`, `mbstring`, `fileinfo`, `session`.
- **Database:** MySQL 5.7+ or MariaDB 10.3+ (Standard on all cPanel shared hosting).

---

## 3. Step-by-Step cPanel Deployment Guide

### Quick Start: Instant Shared Hosting Upload
You can simply upload this entire folder directly into your cPanel shared hosting domain folder (e.g. `public_html/`):
- **Immediate Zero-Config Mode:** The project includes a pre-seeded, fully populated SQLite database (`config/database.sqlite`) and `.env` file. If MySQL is not configured, the website runs **immediately out-of-the-box** without any database errors!
- **Production MySQL Mode (Recommended for high traffic):** Create a database in cPanel, import `sql/database.sql`, and enter the database name, user, and password in `.env` (or `config/config.php`).

### Step 1: Upload Files
1. Log in to your cPanel control panel (`https://yourdomain.com:2083`).
2. Navigate to **File Manager** and enter the `public_html/` directory (or your target addon domain folder).
3. Upload all project files and directories to `public_html/`. The website will work immediately!

### Step 2 (Optional - for MySQL): Create Database in cPanel
1. In cPanel, click **MySQL® Databases** (or **MySQL Database Wizard**).
2. Create a new database, e.g., `cpaneluser_mahintravel`.
3. Create a new MySQL user, e.g., `cpaneluser_admin`, with a strong password.
4. Assign the user to the database with **ALL PRIVILEGES**.

### Step 3 (Optional): Import Database Schema & Content
1. In cPanel, open **phpMyAdmin**.
2. Select your newly created database (`cpaneluser_mahintravel`) from the left sidebar.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select `sql/database.sql` from your uploaded files.
5. Click **Import** (or **Go**). All 12 tables and seed data will be imported.

### Step 4: Configure Database in .env
Open `.env` in cPanel File Manager and enter your MySQL credentials:
```env
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=cpaneluser_mahintravel
DB_USER=cpaneluser_admin
DB_PASS=YourStrongPasswordHere
```
Save the file. The website will immediately connect to MySQL!

### Step 5: Verify Permissions
- Standard directories: `755`
- Standard files: `644`
- `uploads/` folder: `755` (writable by web server for CMS image uploads)

---

## 4. Default Admin Login Credentials

- **Admin Login URL:** `https://yourdomain.com/admin/login.php`
- **Username:** `admin`
- **Password:** `MahinTravel@2026!`

> [!IMPORTANT]
> Immediately upon your first login, visit **Admin Panel &rarr; Security / Password** (`admin/profile.php`) to update your administrative password and notification email.

---

## 5. Security Implementations

1. **SQL Injection Defense:** 100% of queries use PDO prepared statements with bound parameters (`?`). Emulated prepares are disabled.
2. **XSS Protection:** All user-facing outputs pass through `e()` which enforces `htmlspecialchars($val, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
3. **CSRF Mitigation:** Secure cryptographic tokens generated via `random_bytes(32)` are verified on every POST action (both public forms and CMS CRUD).
4. **Upload Hardening:** Uploads in `admin/media.php` strictly validate both file extension and binary MIME type via `finfo_file()`. Furthermore, `uploads/.htaccess` disables PHP script execution (`php_flag engine off` and `RemoveHandler`).
5. **Session Fixation Defense:** `session_regenerate_id(true)` is executed immediately upon successful authentication.

---

## 6. Local Development Zero-Configuration Preview

To run and preview the website locally without configuring an external MySQL server:
```bash
php -S localhost:8000
```
Open your browser to `http://localhost:8000`. The built-in PDO engine automatically detects offline MySQL and operates seamlessly on local SQLite with full CRUD functionality!
