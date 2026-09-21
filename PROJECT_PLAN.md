# PROJECT_PLAN: Mahin Travel & Tours

**Official Website:** [mahintravelandtours.com](https://mahintravelandtours.com)  
**Client / Brand:** Mahin Travel And Tours (*মাহিন ট্রাভেল এন্ড ট্যুরস*)  
**Location:** Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh  
**Contact Numbers:** +8801924713765, +8801722203033 | **WhatsApp:** +8801924713765  
**Technology Stack:** Native PHP 8.x, MySQL (PDO), Semantic HTML5, Bespoke CSS3 Custom Properties, Vanilla ES6 JavaScript, Apache `.htaccess` (cPanel Shared Hosting Ready).

---

## 1. Visual Design System & Brand Identity

### Color Palette (Extracted from Logo Monogram)
- **Primary Navy:** `#0A2240` (Midnight Nautical Deep Blue - represents authority, international trust, executive prestige)
- **Primary Dark Surface:** `#06152B` (Deep Abyssal Navy - hero overlays, luxury headers, footer)
- **Champagne Bronze / Metallic Gold:** `#B98B62` & `#C89D76` (Accents, badges, CTAs, highlight borders, stars)
- **Warm Light Accent:** `#DFB892` (Subtle card glow, gradient transitions)
- **Luxury Canvas Surface:** `#FDFBF7` (Ivory Sand - high-end editorial feel, softer than harsh stark white)
- **Card Background:** `#FFFFFF` (Pristine White with subtle 1px border `rgba(10,34,64,0.08)`)
- **Primary Text:** `#0F1B2B` (High contrast, accessible slate navy)
- **Muted Text:** `#5A6878` (Balanced neutral for descriptions, timestamps, subheaders)
- **WhatsApp Green:** `#25D366` (For high-converting floating and direct WhatsApp actions)

### Typography
- **Display / Heading Font:** `Outfit`, sans-serif (Geometric, luxury aviation feel matching the "MAHIN" logo mark)
- **Body / Editorial Font:** `Plus Jakarta Sans`, sans-serif (Clean, modern readability on desktop and mobile)
- **Bengali Subtitle Typography:** `Hind Siliguri` / System Unicode Bengali for *মাহিন ট্রাভেল এন্ড ট্যুরস*

### Design Principles
1. **Clean Asymmetry & Editorial Spacing:** Avoid generic boxed templates; use generous breathing room (`clamp()` units).
2. **Micro-Interactions:** Subtle card elevations, magnetic button hovers, smooth accordion expansion, and intersection-observer scroll reveals.
3. **Floating Conversion Widget:** Persistent, non-intrusive floating WhatsApp quick-inquiry trigger with online pulse indicator.

---

## 2. Information Architecture & Sitemap

```
├── /                               (Homepage - Flagship Showcase)
├── /about.php                      (About Us - Story, Mission, Gazipur Office, Leadership)
├── /services.php                   (All Services Directory & Filter)
│   └── /service-detail.php?slug=*  (Comprehensive Service Specification)
├── /visa-services.php              (Visa Assistance Hub & 5-Step Process)
│   └── /visa-detail.php?slug=*     (Country-specific Visa Requirements & Checklist)
├── /tours.php                      (Curated Tour Packages & Umrah)
│   └── /tour-detail.php?slug=*     (Day-by-day Itinerary, Inclusions, Gallery, Inquiry)
├── /destinations.php               (Popular Worldwide & Regional Travel Hubs)
├── /contact.php                    (Direct Dial, WhatsApp, Inquiry Form, Gazipur Map)
├── /faq.php                        (Categorized Travel & Visa Knowledge Base)
├── /privacy.php                    (Data Privacy & Client Confidentiality)
├── /terms.php                      (Booking & Consultation Terms)
├── /sitemap.xml                    (Dynamic SEO XML Sitemap)
├── /robots.txt                     (Search Engine Crawling Directives)
└── /404.php                        (Luxury Custom 404 Experience)
```

---

## 3. Database Architecture (MySQL PDO)

### Tables
1. **`admins`**: Administrator accounts with Argon2id/Bcrypt password hashing, email, role, and last login tracking.
2. **`site_settings`**: Key-value settings table grouped into `general`, `contact`, `social`, `seo`, and `appearance`.
3. **`services`**: Core travel services with slugs, icons, short/full descriptions, and SEO metadata.
4. **`visa_categories`**: Country/type visa specifications (Dubai, Saudi Arabia, UK, Malaysia, Thailand, etc.), document checklists, processing time, and requirements.
5. **`visa_process_steps`**: The 5-step transparent processing roadmap.
6. **`destinations`**: Global and domestic destinations with country, highlights, and best season.
7. **`tours`**: Tour packages with destination references, duration, pricing labels ("Contact for Price" or fixed), itinerary JSON, inclusions, exclusions, and featured flags.
8. **`tour_gallery`**: Supplementary photos per tour.
9. **`faqs`**: Frequently asked questions grouped by category (Visa, Flights, Tours, General).
10. **`testimonials`**: Authentic traveler feedback and rating scores.
11. **`contact_messages`**: Lead capture table with visitor name, phone, email, subject, service inquired about, IP address, and read/unread status.
12. **`media_library`**: Uploaded images with MIME type, file size, and alt text.

---

## 4. Admin Panel Modules

The CMS is built with raw PHP, without bloated dependencies, ensuring fast page loads and simple maintenance on cPanel:

- **Dashboard:** KPI summary (Total Inquiries, Unread Leads, Active Tours, Visa Guides), quick action buttons, recent inquiry log.
- **Site Settings:** Update phone numbers, WhatsApp, Gazipur address, business hours, hero titles, social links, and SEO tags without touching code.
- **Inquiry Management:** Full inbox of inquiries submitted via the contact form or service modals. Features 1-click WhatsApp reply, 1-click email reply, mark as read/unread, and deletion.
- **Services Manager:** Full CRUD for agency services.
- **Visa Assistance Hub:** Add/edit countries, visa types, required documents checklist, and turnaround times.
- **Visa Roadmap Steps:** Manage the sequential procedural steps shown on the frontend.
- **Tour Packages Manager:** Create curated tour packages, manage day-by-day itineraries, inclusions, exclusions, and pricing notes.
- **Destinations Manager:** Manage worldwide destinations showcase.
- **FAQ Manager:** Add, reorder, and categorize customer questions.
- **Testimonial Manager:** Manage client reviews and star ratings.
- **Media Library:** Upload images with server-side validation (MIME, size, extension whitelist) and copy image links.
- **Admin Security & Profile:** Update administrative credentials and passwords securely.

---

## 5. Security & Engineering Best Practices

- **100% Prepared Statements (PDO):** Absolute protection against SQL injection.
- **CSRF Token Validation:** Cryptographically secure CSRF tokens embedded in all frontend and backend POST forms.
- **Input Sanitization & Output Escaping:** Strict HTML escaping via `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')`.
- **Upload Hardening:** Uploads restricted to `jpg, jpeg, png, webp, svg`. Server-side validation with `finfo_file()`. Upload directory protected with `.htaccess` disabling script execution (`php_flag engine off` and `RemoveHandler`).
- **Session Security:** Strict session parameters (`httponly`, `samesite=Lax`), session regeneration on authentication.
- **Shared Hosting Compatibility:** Fully compatible with Apache, PHP 8.0-8.5, and standard cPanel MySQL without requiring CLI workers, Node.js, or Redis.

---

## 6. Implementation & Development Phases

- **Phase 1: Foundation & Architecture**
  - Database schema (`database.sql`) & dual PDO connection engine.
  - Core helper libraries (`functions.php`, CSRF, security, URL routing).
  - High-res logo integration and brand asset setup.
- **Phase 2: Master Design System & Reusable Components**
  - Bespoke `style.css` with responsive fluid typography, variables, buttons, badges, navigation, and footer.
  - Reusable PHP includes: `header.php`, `footer.php`, `cta-banner.php`.
- **Phase 3: Public Frontend Development**
  - Flagship Homepage (`index.php`) with Hero, Quick Trust Bar, Services, Visa Roadmap, Tours, Destinations, FAQs, Testimonials.
  - About Us (`about.php`) focusing on Gazipur presence, transparent ethics, and human support.
  - Services Hub & Detail Pages (`services.php`, `service-detail.php`).
  - Visa Assistance Hub & Country Details (`visa-services.php`, `visa-detail.php`).
  - Tours & Package Details (`tours.php`, `tour-detail.php`).
  - Contact Page (`contact.php`) with working lead capture, phone dialers, and Gazipur location map.
  - FAQ Page (`faq.php`), Privacy (`privacy.php`), Terms (`terms.php`), 404 (`404.php`), and `sitemap.xml`.
- **Phase 4: Executive Admin CMS Development**
  - Secure login (`admin/login.php`) and session manager (`admin/logout.php`).
  - Modern, responsive admin dashboard (`admin/index.php`) and `admin.css`.
  - Site Settings editor (`admin/settings.php`).
  - Inquiries & Lead inbox (`admin/messages.php`).
  - Full CRUD modules for Services, Visa Categories, Tours, Destinations, FAQs, and Media.
- **Phase 5: Automated Seeding & Verification**
  - `scripts/seed.php` populating rich, realistic travel agency data, visa checklists, and initial admin credentials.
  - CLI syntax linting (`php -l`) across all files.
  - End-to-end testing of contact forms, inquiries, admin CRUD, and mobile layouts.
- **Phase 6: Deployment Package & Documentation**
  - cPanel deployment guide (`README.md`), database export, and `.htaccess`.

---

## 7. Future Scalability Roadmap

1. **Online Booking & Payment Gateway:** Integration with SSLCommerz, bKash, or Stripe for instant deposit payments.
2. **B2B Sub-Agent Portal:** Agent logins with tier-based commissions for flight and visa sub-dealers.
3. **Flight Search API:** Integration with Amadeus or Sabre GDS for real-time live flight pricing.
4. **Multilingual Switcher:** Native toggle for English and full Bengali language presentation.
