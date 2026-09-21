<?php
/**
 * Mahin Travel & Tours - Master Seed Data
 * Generates verified, professional content strictly aligned with brand guidelines.
 */

function seedDatabase(PDO $pdo): void {
    // 1. Seed Default Administrator
    $checkAdmin = $pdo->query("SELECT COUNT(*) as cnt FROM admins")->fetch();
    if (($checkAdmin['cnt'] ?? 0) == 0) {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, email, full_name, role) VALUES (?, ?, ?, ?, ?)");
        // Default password: MahinTravel@2026!
        $passHash = password_hash('MahinTravel@2026!', PASSWORD_BCRYPT);
        $stmt->execute(['admin', $passHash, 'admin@mahintravelandtours.com', 'System Administrator', 'superadmin']);
    }

    // 2. Seed Site Settings
    $settings = [
        // General Brand
        ['site_name', 'Mahin Travel & Tours', 'general', 'text', 'Company / Brand Name', 'Official trading name'],
        ['bengali_name', 'মাহিন ট্রাভেল এন্ড ট্যুরস', 'general', 'text', 'Bengali Brand Name', 'Official Bengali script name'],
        ['tagline', 'Your Trusted Gateway to the World', 'general', 'text', 'Brand Tagline', 'Primary marketing slogan'],
        ['owner_name', 'Managing Director', 'general', 'text', 'Contact / Authorized Person', 'Primary business representative'],
        
        // Contact Information
        ['phone_primary', '+8801924713765', 'contact', 'text', 'Primary Phone Number', 'Main customer support line'],
        ['phone_secondary', '+8801722203033', 'contact', 'text', 'Secondary Phone Number', 'Alternative contact number'],
        ['whatsapp_number', '+8801924713765', 'contact', 'text', 'WhatsApp Number', 'Direct WhatsApp consultation line'],
        ['contact_email', 'info@mahintravelandtours.com', 'contact', 'email', 'Official Email Address', 'General inquiries email'],
        ['office_address', 'Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur, Bangladesh', 'contact', 'textarea', 'Office Address', 'Physical registered headquarters'],
        ['business_hours', 'Saturday - Thursday: 9:30 AM - 8:30 PM (Friday: Closed / By Prior Appointment)', 'contact', 'text', 'Business Hours', 'Customer operating hours'],
        ['google_maps_embed', 'https://maps.google.com/maps?q=South+Salna+Gazipur+Bangladesh&t=&z=14&ie=UTF8&iwloc=&output=embed', 'contact', 'textarea', 'Google Maps Embed URL', 'Embed URL for the contact page'],
        
        // Homepage Hero
        ['hero_badge', 'Certified Travel & Visa Consultants', 'appearance', 'text', 'Hero Badge Text', 'Small badge above hero headline'],
        ['hero_title', 'Seamless Journeys, Authentic Visa Advisory & Curated Global Tours', 'appearance', 'text', 'Hero Main Title', 'H1 on the homepage'],
        ['hero_subtitle', 'From our Gazipur headquarters to destinations across the globe, Mahin Travel & Tours delivers meticulous visa processing, international air ticketing, and bespoke holiday packages with personal human dedication.', 'appearance', 'textarea', 'Hero Subtitle', 'Descriptive copy in hero section'],
        
        // SEO Defaults
        ['default_meta_title', 'Mahin Travel & Tours | Trusted Visa Assistance & Global Tour Operator Gazipur', 'seo', 'text', 'Default Meta Title', 'Browser title for pages without custom title'],
        ['default_meta_desc', 'Mahin Travel & Tours in Gazipur offers professional visa assistance, international air tickets, Umrah packages, and curated global holiday tours. Transparent advisory and dedicated support.', 'seo', 'textarea', 'Default Meta Description', 'Search engine snippet'],
        ['default_keywords', 'travel agency gazipur, visa processing bangladesh, air tickets gazipur, umrah package bangladesh, mahin travel and tours, holiday tours', 'seo', 'text', 'Default Meta Keywords', 'Comma-separated keywords'],

        // Social Links
        ['facebook_url', 'https://facebook.com', 'social', 'text', 'Facebook Page URL', 'Official Facebook link'],
        ['whatsapp_link_message', 'Hello Mahin Travel & Tours! I would like to inquire about your travel and visa services.', 'social', 'text', 'WhatsApp Default Message', 'Pre-filled message when clicking WhatsApp']
    ];

    $setStmt = $pdo->prepare("INSERT OR IGNORE INTO site_settings (setting_key, setting_value, setting_group, field_type, label, help_text) VALUES (?, ?, ?, ?, ?, ?)");
    // Note: If MySQL, use ON DUPLICATE KEY UPDATE or check exists
    foreach ($settings as $s) {
        $chk = $pdo->prepare("SELECT id FROM site_settings WHERE setting_key = ?");
        $chk->execute([$s[0]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_group, field_type, label, help_text) VALUES (?, ?, ?, ?, ?, ?)");
            $ins->execute([$s[0], $s[1], $s[2], $s[3], $s[4], $s[5]]);
        }
    }

    // 3. Seed Core Services
    $services = [
        [
            'International Air Ticketing',
            'air-ticketing',
            'Domestic and international flight bookings across major airlines with optimal routing, transparent pricing, and date-change assistance.',
            'Mahin Travel & Tours provides reliable air ticketing solutions for leisure, corporate, and student travelers. Whether flying domestically across Bangladesh or booking multi-city international itineraries to the Middle East, Southeast Asia, Europe, and North America, our ticketing specialists identify the most convenient flight schedules, competitive airfares, and generous baggage allowances. We provide full post-ticketing assistance including seat reservations, meal preferences, date change re-issues, and cancellation support.',
            'plane-departure',
            'assets/images/services/air-ticketing.jpg',
            'Ticketing',
            1,
            'International & Domestic Air Ticketing | Mahin Travel & Tours',
            'Book domestic and international flights at competitive fares with route guidance, baggage advice, and full date-change support.'
        ],
        [
            'Tourist & Visit Visa Assistance',
            'tourist-visa-assistance',
            'Comprehensive advisory, eligibility evaluation, and document compilation for visit, tourist, and family visas worldwide.',
            'Navigating international visa requirements requires strict attention to detail and procedural compliance. Mahin Travel & Tours guides travelers through every stage of the tourist visa journey. We assist with application form completion, appointment scheduling (VFS / Embassy), cover letter drafting, financial document structuring, and itinerary alignment. We adhere strictly to official embassy guidelines without ever making false promises, ensuring your application is presented with maximum credibility.',
            'passport',
            'assets/images/services/tourist-visa.jpg',
            'Visas',
            2,
            'Tourist & Visit Visa Assistance | Mahin Travel & Tours Gazipur',
            'Expert visa consultation and document compilation for UAE, Thailand, Malaysia, Singapore, UK, Schengen, and beyond.'
        ],
        [
            'Umrah & Pilgrimage Services',
            'umrah-pilgrimage-services',
            'Spiritual, well-organized Umrah packages with visa processing, flights, verified hotel accommodation in Makkah & Madinah, and local transfers.',
            'Embarking on the sacred journey of Umrah requires peace of mind and meticulous logistical support. Mahin Travel & Tours provides customized and group Umrah solutions tailored to individual, family, and elder needs. Our services include fast Umrah e-visa issuance, direct and transit flights, hotels in close proximity to the Holy Haram in Makkah and Al-Masjid an-Nabawi in Madinah, air-conditioned ground transportation, and guided Ziyarah tours.',
            'kaaba',
            'assets/images/services/umrah-services.jpg',
            'Pilgrimage',
            3,
            'Umrah Packages & Pilgrimage Support | Mahin Travel & Tours',
            'Custom and group Umrah packages featuring transparent hotel proximity, reliable flights, fast visas, and ground transfers.'
        ],
        [
            'Student & Work Visa Guidance',
            'student-work-visa-guidance',
            'Procedural support for students and employment seekers, including document legalization, appointment booking, and file submission.',
            'Planning to study abroad or take up legitimate employment opportunities overseas requires flawless documentation. We provide procedural orientation for student visa dossiers (CAS / I-20 documentation review, financial sponsorship structuring, SOP formatting) and work permit visa submissions. We focus on procedural integrity, ensuring all educational and employment credentials meet embassy standards.',
            'graduation-cap',
            'assets/images/services/student-visa.jpg',
            'Visas',
            4,
            'Student & Work Visa Guidance | Mahin Travel & Tours',
            'Procedural documentation, attestation guidance, and appointment assistance for international student and employment visas.'
        ],
        [
            'Curated Holiday & Tour Packages',
            'curated-holiday-tours',
            'Tailor-made and group holiday packages combining flights, premium hotels, guided sightseeing, and airport transfers for families and couples.',
            'Discover world-class destinations with effortless travel itineraries crafted by our travel specialists. Whether you seek the futuristic skyline and desert dunes of Dubai, the tropical islands of Malaysia and Thailand, or scenic natural escapes inside Bangladesh (Sylhet tea gardens, Cox\'s Bazar beach), our packages balance guided exploration with relaxing leisure time.',
            'map-marked-alt',
            'assets/images/services/holiday-tours.jpg',
            'Tours',
            5,
            'Curated Holiday Tour Packages | Mahin Travel & Tours',
            'Bespoke leisure and holiday packages to Southeast Asia, Dubai, Maldives, and domestic destinations with verified hotels and guides.'
        ],
        [
            'Document Attestation & Advisory',
            'document-attestation-advisory',
            'Assistance with ministry attestations, notarization, certified translation, and travel insurance for overseas submissions.',
            'Properly authenticated documentation is the cornerstone of any successful visa application. Mahin Travel & Tours assists clients with certified document translation (Bengali to English), notary guidance, Chamber of Commerce verifications, Foreign Ministry attestation orientation, and international travel health insurance issuance compliant with Schengen and Gulf embassy requirements.',
            'stamp',
            'assets/images/services/document-attestation.jpg',
            'Documentation',
            6,
            'Document Attestation & Travel Insurance | Mahin Travel & Tours',
            'Certified translation, attestation guidance, and embassy-compliant travel insurance for international travel.'
        ]
    ];

    foreach ($services as $srv) {
        $chk = $pdo->prepare("SELECT id FROM services WHERE slug = ?");
        $chk->execute([$srv[1]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO services (title, slug, short_desc, full_desc, icon_name, featured_image, category, sort_order, status, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)");
            $ins->execute([$srv[0], $srv[1], $srv[2], $srv[3], $srv[4], $srv[5], $srv[6], $srv[7], $srv[8], $srv[9]]);
        }
    }

    // 4. Seed Visa Categories (Major International Travel Hubs)
    $visas = [
        [
            'United Arab Emirates (Dubai)',
            'AE',
            'Tourist / Visit E-Visa (30 / 60 Days)',
            'dubai-tourist-visa',
            '3 to 5 Working Days',
            '30 or 60 Days Single / Multiple Entry',
            'Valid passport, colored photograph, national ID card, and confirmed travel details.',
            "<h3>Required Documents for Dubai Visit Visa:</h3>
            <ul>
                <li>Original Passport valid for at least 6 months from the date of travel.</li>
                <li>Clear scanned copy of the passport bio-data page.</li>
                <li>Recent passport-sized photograph with white background (35mm x 45mm).</li>
                <li>National ID Card (NID) copy or Birth Certificate for minors.</li>
                <li>Visiting card / professional information if employed or business owner.</li>
                <li>Previous UAE travel history or other visa stamps (if available).</li>
            </ul>
            <p><strong>Note:</strong> Visa issuance is subject to the UAE General Directorate of Residency and Foreigners Affairs (GDRFA). Overstaying carries strict legal fines.</p>",
            'Government fees vary by duration (30 vs 60 days). Contact our Gazipur office for current embassy rates.',
            'assets/images/visas/dubai-visa.jpg',
            1,
            'Dubai UAE Tourist Visa Assistance Gazipur | Mahin Travel & Tours',
            'Fast and reliable Dubai e-visa processing for Bangladeshi citizens with document verification and advisory.'
        ],
        [
            'Saudi Arabia',
            'SA',
            'Umrah & Tourist E-Visa',
            'saudi-arabia-visa',
            '2 to 4 Working Days',
            '90 Days to 1 Year (Multiple Entry available based on eligibility)',
            'Valid passport, passport photo, confirmed hotel voucher or sponsor details.',
            "<h3>Required Documents for Saudi Arabia Visa:</h3>
            <ul>
                <li>Original Passport valid for at least 6 months.</li>
                <li>Two recent passport photographs with white background.</li>
                <li>Valid National ID Card (NID) copy.</li>
                <li>Confirmed flight reservation and verified hotel booking in Makkah / Madinah.</li>
                <li>For family / mahram travel, proof of relationship (marriage certificate, birth certificate).</li>
            </ul>
            <p><strong>Pilgrim Note:</strong> Female pilgrims under specified age regulations and group pilgrim guidelines are strictly followed per Saudi Ministry of Hajj & Umrah mandates.</p>",
            'Official visa fees plus medical insurance mandated by the Saudi government.',
            'assets/images/visas/saudi-visa.jpg',
            2,
            'Saudi Arabia Umrah & Tourist Visa Service | Mahin Travel & Tours',
            'Authentic Saudi tourist and Umrah e-visa assistance with verified hotels and itinerary support.'
        ],
        [
            'Malaysia',
            'MY',
            'Tourist E-Visa (eNTRI / eVISA)',
            'malaysia-tourist-visa',
            '4 to 7 Working Days',
            '30 Days Single Entry',
            'Passport, photo, 6-month bank statement with solvency certificate, return flight, hotel booking.',
            "<h3>Required Documents for Malaysia Visa:</h3>
            <ul>
                <li>Original Passport with minimum 6 months validity and blank visa pages.</li>
                <li>Two passport photographs (35mm x 50mm, white background, matte finish).</li>
                <li>Last 6 months bank statement showing healthy transaction history.</li>
                <li>Bank Solvency Certificate from your bank manager.</li>
                <li>Forwarding Letter / Leave Approval from employer or Trade License copy (English translated & notarized) for businessmen.</li>
                <li>Confirmed round-trip flight booking and hotel reservation.</li>
            </ul>",
            'Official Immigration Department of Malaysia fee + processing consultation.',
            'assets/images/visas/malaysia-visa.jpg',
            3,
            'Malaysia Tourist Visa Processing Gazipur | Mahin Travel & Tours',
            'Complete Malaysia e-visa assistance including bank statement review and application filing.'
        ],
        [
            'Thailand',
            'TH',
            'Single Entry Tourist Visa (Sticker)',
            'thailand-tourist-visa',
            '5 to 7 Working Days',
            '3 Months Validity (60 Days Stay)',
            'Original passport, photo, bank statement with minimum balance, company letter/trade license.',
            "<h3>Required Documents for Thailand Tourist Visa:</h3>
            <ul>
                <li>Original Passport valid for at least 6 months.</li>
                <li>Two passport photographs (3.5cm x 4.5cm, white background, taken within 6 months).</li>
                <li>Bank statement of last 6 months with minimum balance as prescribed by the Royal Thai Embassy.</li>
                <li>Bank Solvency Certificate.</li>
                <li>Forwarding Letter on company letterhead (for employees) or Trade License with English translation & notary (for business owners).</li>
                <li>Student ID card copy and leave letter (for students).</li>
                <li>Marriage certificate (if traveling as a couple).</li>
            </ul>",
            'Royal Thai Embassy visa fee plus VFS Global logistics charge.',
            'assets/images/visas/thailand-visa.jpg',
            4,
            'Thailand Tourist Visa Service Bangladesh | Mahin Travel & Tours',
            'Reliable Royal Thai Embassy visa file preparation, bank solvency review, and VFS submission support.'
        ],
        [
            'Singapore',
            'SG',
            'Tourist / Business Entry E-Visa',
            'singapore-tourist-visa',
            '5 to 8 Working Days',
            'Up to 30 Days Stay',
            'Passport, photo, letter of introduction (Form 14A), bank statement, professional credentials.',
            "<h3>Required Documents for Singapore E-Visa:</h3>
            <ul>
                <li>Original Passport with minimum 6 months validity.</li>
                <li>Two recent passport photographs (35mm x 45mm, matte finish, white background).</li>
                <li>Completed and signed Form 14A.</li>
                <li>Personal Bank Statement of last 6 months along with Bank Solvency Certificate.</li>
                <li>Official Company Forwarding Letter or Trade License (translated & notarized).</li>
                <li>Letter of Introduction (LOI) / Local Contact / Inviting sponsor in Singapore (if applicable).</li>
            </ul>",
            'Singapore Immigration & Checkpoints Authority (ICA) fee plus authorized agency charge.',
            'assets/images/visas/singapore-visa.jpg',
            5,
            'Singapore Visa Processing & Advisory | Mahin Travel & Tours',
            'Professional file compilation for Singapore entry visas with meticulous document review.'
        ],
        [
            'United Kingdom & Schengen',
            'GB',
            'Standard Visitor Visa Advisory',
            'uk-schengen-visitor-visa',
            '15 to 25 Working Days',
            '6 Months (UK) / Variable (Schengen)',
            'Extensive financial dossier, asset valuation, employment proof, travel itinerary, biometric appointment.',
            "<h3>Procedural Orientation for UK & European Visas:</h3>
            <p>Applications to Western embassies require exhaustive financial and socioeconomic tie proof. We assist with:</p>
            <ul>
                <li>Strategic review of personal and business financial statements.</li>
                <li>Structuring source-of-fund documentation and tax returns (TIN / Tax certificates).</li>
                <li>Drafting detailed Cover Letters and day-by-day travel itineraries.</li>
                <li>Online visa portal completion (Gov.uk / VFS / TLScontact).</li>
                <li>Biometric appointment scheduling and document upload compliance.</li>
            </ul>
            <p><em>Notice: We do not guarantee visa issuance. Final decisions are solely at the discretion of the respective embassy/consulate.</em></p>",
            'Embassy fee paid directly to the official government portal during appointment booking.',
            'assets/images/visas/uk-schengen-visa.jpg',
            6,
            'UK & Schengen Visitor Visa Advisory Gazipur | Mahin Travel & Tours',
            'Comprehensive dossier preparation, financial documentation review, and biometric scheduling.'
        ]
    ];

    foreach ($visas as $v) {
        $chk = $pdo->prepare("SELECT id FROM visa_categories WHERE slug = ?");
        $chk->execute([$v[3]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO visa_categories (country_name, country_code, visa_type, slug, processing_time, validity, requirement_summary, detailed_requirements, fees_note, image, sort_order, status, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)");
            $ins->execute([$v[0], $v[1], $v[2], $v[3], $v[4], $v[5], $v[6], $v[7], $v[8], $v[9], $v[10], $v[11], $v[12]]);
        }
    }

    // 5. Seed 5-Step Visa Process
    $steps = [
        [1, 'Eligibility Assessment & Consultation', 'We analyze your travel purpose, previous travel history, financial profile, and target destination requirements in an initial one-on-one session at our Gazipur office or over phone/WhatsApp.', 'compass'],
        [2, 'Dossier Assembly & Verification', 'Our documentation team compiles all requisite paperwork—verifying bank statements, translation accuracy, employment proofs, and embassy forms to avoid procedural oversights.', 'folder-check'],
        [3, 'Appointment & Biometric Scheduling', 'We schedule your submission appointment at authorized centers (VFS Global, TLScontact, or Embassy) and upload digital files per official protocol.', 'calendar-check'],
        [4, 'Application Tracking & Support', 'We continuously track your file processing status with the visa center, providing you with real-time tracking updates until a decision is finalized.', 'clock'],
        [5, 'Passport Collection & Travel Readiness', 'Upon passport retrieval, we review your visa sticker or electronic grant notice and provide pre-departure briefing on immigration guidelines and customs rules.', 'plane-departure']
    ];

    foreach ($steps as $st) {
        $chk = $pdo->prepare("SELECT id FROM visa_process_steps WHERE step_number = ?");
        $chk->execute([$st[0]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO visa_process_steps (step_number, title, description, icon_name, status) VALUES (?, ?, ?, ?, 1)");
            $ins->execute([$st[0], $st[1], $st[2], $st[3]]);
        }
    }

    // 6. Seed Featured Destinations
    $destinations = [
        ['Dubai & Abu Dhabi', 'dubai-abu-dhabi', 'United Arab Emirates', 'Middle East', 'assets/images/destinations/dubai.jpg', 'Iconic architecture, desert adventures, luxury shopping, and family-friendly world-class attractions.', 'Burj Khalifa, Desert Safari, Marina Dhow Cruise, Ferrari World', 'October to April', 1],
        ['Kuala Lumpur & Genting', 'kuala-lumpur', 'Malaysia', 'Asia', 'assets/images/destinations/malaysia.jpg', 'Modern metropolises, lush rainforest hills, vibrant multicultural street food, and family theme parks.', 'Petronas Twin Towers, Batu Caves, Genting Highlands, Langkawi Island', 'Year-round (Best: March - October)', 2],
        ['Bangkok & Phuket', 'bangkok-phuket', 'Thailand', 'Asia', 'assets/images/destinations/thailand.jpg', 'Rich cultural temples, floating markets, pristine island beaches, and world-renowned hospitality.', 'Grand Palace, Phi Phi Islands, Coral Island Watersports, Shopping Malls', 'November to March', 3],
        ['Makkah & Madinah', 'makkah-madinah', 'Saudi Arabia', 'Middle East', 'assets/images/destinations/saudi.jpg', 'The spiritual focal points of the Muslim world, hosting millions of devoted pilgrims every year.', 'Masjid Al-Haram, Masjid An-Nabawi, Mount Uhud, Historic Ziyarah Sites', 'Umrah season (Year-round)', 4],
        ['Singapore City', 'singapore-city', 'Singapore', 'Asia', 'assets/images/destinations/singapore.jpg', 'The Garden City boasting ultra-futuristic architecture, world-ranked zoo, Sentosa Island, and shopping boulevards.', 'Gardens by the Bay, Marina Bay Sands, Universal Studios, Orchard Road', 'Year-round', 5],
        ['Sylhet & Sreemangal', 'sylhet-sreemangal', 'Bangladesh', 'Domestic', 'assets/images/destinations/sylhet.jpg', 'Rolling green tea estates, serene rainforest reserves, pristine freshwater lakes, and spiritual shrines.', 'Ratargul Swamp Forest, Jaflong, Hum Hum Waterfall, Lawachara National Park', 'October to March', 6]
    ];

    foreach ($destinations as $dest) {
        $chk = $pdo->prepare("SELECT id FROM destinations WHERE slug = ?");
        $chk->execute([$dest[1]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO destinations (title, slug, country, continent, featured_image, overview, popular_for, best_time, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
            $ins->execute([$dest[0], $dest[1], $dest[2], $dest[3], $dest[4], $dest[5], $dest[6], $dest[7], $dest[8]]);
        }
    }

    // 7. Seed Curated Tour Packages
    $tours = [
        [
            'Grand Dubai Luxury & Desert Discovery',
            'grand-dubai-luxury-desert',
            1,
            5,
            4,
            'Contact for Price',
            'custom',
            'assets/images/tours/dubai-package.jpg',
            'Experience the grandeur of Dubai with our comprehensive 5-day holiday package. Includes 4-star hotel stay, evening Desert Safari with BBQ dinner, Dubai Marina Dhow Cruise, Dubai city tour, and Burj Khalifa 124th-floor observatory ticket.',
            json_encode([
                ['day' => 1, 'title' => 'Arrival & Dubai Marina Dhow Cruise', 'desc' => 'Arrive at Dubai International Airport. Meet & greet with private transfer to hotel. In the evening, enjoy a 2-hour Marina Dhow Cruise with international buffet dinner.'],
                ['day' => 2, 'title' => 'Half-Day Dubai City Tour & Burj Khalifa', 'desc' => 'Guided tour of Old and New Dubai including Dubai Creek, Jumeirah Mosque, Burj Al Arab photo stop. Afternoon visit to Dubai Mall and 124th floor of Burj Khalifa.'],
                ['day' => 3, 'title' => 'Afternoon Desert Safari & BBQ Camp', 'desc' => 'Morning at leisure. At 3:00 PM, depart for thrilling 4x4 dune bashing, camel riding, sandboarding, and traditional Arabic entertainment with BBQ dinner under the stars.'],
                ['day' => 4, 'title' => 'Miracle Garden or Abu Dhabi Excursion', 'desc' => 'Optional full-day trip to Abu Dhabi visiting the magnificent Sheikh Zayed Grand Mosque and Louvre Museum or visit Dubai Miracle Garden & Global Village.'],
                ['day' => 5, 'title' => 'Leisure Shopping & Airport Transfer', 'desc' => 'Breakfast at hotel. Free time for souvenir and electronics shopping at Deira Gold Souk or Mall of the Emirates before transfer to airport for departure.']
            ]),
            "4 Nights accommodation in 4-Star Hotel with daily breakfast\nReturn Dubai International Airport transfers\nDubai Marina Dhow Dinner Cruise with live entertainment\nHalf-Day Dubai City Tour with English-speaking guide\nDesert Safari with 4x4 dune bashing, BBQ dinner & live shows\nBurj Khalifa 124th Floor Off-Peak Entry Ticket\nAll transfers on comfortable air-conditioned vehicle",
            "International flight tickets (can be bundled upon request)\nDubai tourist visa fee\nTourism Dirham hotel tax (payable directly at checkout)\nPersonal expenses, tips, and optional tours",
            1,
            1,
            'Grand Dubai 5D4N Tour Package | Mahin Travel & Tours',
            'Experience Dubai with our 5-day curated package including 4-star hotel, desert safari, Burj Khalifa, and marina dinner cruise.'
        ],
        [
            'Malaysia & Thailand Dual Wonder Escape',
            'malaysia-thailand-dual-escape',
            2,
            7,
            6,
            'Contact for Price',
            'custom',
            'assets/images/tours/malaysia-thailand.jpg',
            'A journey through Southeast Asia’s two most vibrant destinations: 3 nights in Kuala Lumpur (Malaysia) and 3 nights in Bangkok (Thailand). Covers major highlights, transfers, and shopping hubs.',
            json_encode([
                ['day' => 1, 'title' => 'Arrive in Kuala Lumpur', 'desc' => 'Arrive at KLIA. Transfer to hotel. Evening walk around Bukit Bintang or Jalan Alor food street.'],
                ['day' => 2, 'title' => 'Kuala Lumpur City & Batu Caves', 'desc' => 'Visit King\'s Palace, National Monument, Independence Square, iconic Petronas Twin Towers, and the colorful limestone Batu Caves.'],
                ['day' => 3, 'title' => 'Genting Highlands Day Trip', 'desc' => 'Ride the scenic Awana SkyWay cable car to Genting Highlands. Explore indoor theme parks and cool mountain atmosphere.'],
                ['day' => 4, 'title' => 'Fly KL to Bangkok', 'desc' => 'Morning flight from Kuala Lumpur to Bangkok. Check into central Bangkok hotel. Evening Chao Phraya River dinner cruise.'],
                ['day' => 5, 'title' => 'Bangkok Temple & City Tour', 'desc' => 'Visit Wat Pho (Reclining Buddha), Marble Temple, and explore Gems Gallery. Evening free for shopping at Pratunam or MBK Center.'],
                ['day' => 6, 'title' => 'Safari World & Marine Park Excursion', 'desc' => 'Full day at Thailand\'s most popular open-zoo safari park with dolphin, sea lion, and bird shows including buffet lunch.'],
                ['day' => 7, 'title' => 'Farewell Bangkok & Flight Home', 'desc' => 'Breakfast and last-minute shopping at Chatuchak or CentralWorld before private transfer to Suvarnabhumi Airport.']
            ]),
            "3 Nights in Kuala Lumpur & 3 Nights in Bangkok (3/4 Star Hotels)\nDaily buffet breakfast at all hotels\nAirport pickup and drop-off in both cities\nBatu Caves & Genting Highlands excursion with cable car\nBangkok Temple Tour with certified local guide\nChao Phraya Princess Dinner Cruise\nAll inter-hotel and sightseeing road transportation",
            "International flights (Dhaka - KL - Bangkok - Dhaka)\nVisa fees for Malaysia & Thailand\nLunch and dinners not specified in inclusions\nPersonal shopping and travel insurance",
            1,
            2,
            'Malaysia & Thailand 7D6N Holiday Tour | Mahin Travel & Tours',
            'Explore Kuala Lumpur and Bangkok in one seamless 7-day vacation with guided tours and verified accommodations.'
        ],
        [
            'Spiritual Umrah Journey (Custom & Group)',
            'spiritual-umrah-journey',
            4,
            10,
            9,
            'Request Details',
            'custom',
            'assets/images/tours/umrah-package.jpg',
            'A spiritually enriching Umrah experience designed with care and devotion. High-proximity hotels to Haram in Makkah and Madinah, comfortable transfers, dedicated Muallim guidance, and holy site Ziyarah.',
            json_encode([
                ['day' => 1, 'title' => 'Departure & Arrival in Jeddah / Makkah', 'desc' => 'Fly from Dhaka to Jeddah. Perform Ihram intentions. Transfer via private AC bus to Makkah hotel. Check-in and perform Umrah with experienced Muallim guidance.'],
                ['day' => 2, 'title' => 'Rest & Prayers at Masjid Al-Haram', 'desc' => 'Dedicate the day to Tawaf, prayers, and spiritual reflection within the Holy Sanctuary.'],
                ['day' => 3, 'title' => 'Holy Ziyarah in Makkah', 'desc' => 'Guided visits to Jabal al-Nour (Cave of Hira), Jabal Thawr, Mina, Arafat, and Muzdalifah.'],
                ['day' => 4, 'title' => 'Personal Ibadah in Makkah', 'desc' => 'Spiritual reflection and prayers in Masjid Al-Haram.'],
                ['day' => 5, 'title' => 'High-Speed Train / AC Transfer to Madinah', 'desc' => 'Perform Farewell Tawaf. Transfer via Haramain High Speed Rail or luxury AC coach to the illuminated city of Madinah. Check into hotel near Prophet\'s Mosque.'],
                ['day' => 6, 'title' => 'Salam at Rawdah Sharif & Prayers', 'desc' => 'Visit Al-Masjid an-Nabawi. Offer Salam at the sacred Rawdah Mubarak (subject to Nusuk permit).'],
                ['day' => 7, 'title' => 'Madinah Historic Ziyarah', 'desc' => 'Visit Masjid Quba, Masjid al-Qiblatayn, Mount Uhud and martyrs\' cemetery, Date gardens.'],
                ['day' => 8, 'title' => 'Spiritual Ibadah in Madinah', 'desc' => 'Day dedicated to prayers in Prophet\'s Mosque.'],
                ['day' => 9, 'title' => 'Preparation & Ziyarah', 'desc' => 'Free day for Ibadah and shopping for Ajwa dates and holy mementos.'],
                ['day' => 10, 'title' => 'Departure from Madinah / Jeddah Airport', 'desc' => 'Pack Zamzam water and personal luggage. Transfer to Prince Mohammad bin Abdulaziz Airport for flight back to Dhaka.']
            ]),
            "Umrah Visa processing with mandatory Saudi health insurance\n5 Nights in Makkah + 4 Nights in Madinah in verified walking-distance hotels\nDirect or convenient transit flight arrangements\nAirport, Makkah, and Madinah transfers in modern AC buses\nGuided Ziyarah in Makkah and Madinah with knowledgeable guide\nAssistance with Nusuk app appointment scheduling for Rawdah\nComplimentary Umrah kit (bag, handbook)",
            "Lunches and dinners (unless specified in package tier)\nPersonal laundry and telephone charges\nWheelchair assistance fees if required",
            1,
            3,
            'Spiritual Umrah Packages | Mahin Travel & Tours Gazipur',
            'Tailored and group Umrah packages with verified walking-distance hotels in Makkah & Madinah and reliable transfers.'
        ],
        [
            'Serene Sylhet & Sreemangal Tea Valley Retreat',
            'sylhet-sreemangal-tea-valley',
            6,
            3,
            2,
            'Contact for Price',
            'custom',
            'assets/images/tours/sylhet-package.jpg',
            'Immerse in the natural splendor of northeastern Bangladesh. Experience the rolling tea gardens of Sreemangal, explore Lawachara National Park, take a wooden boat through Ratargul Freshwater Swamp Forest, and admire Jaflong.',
            json_encode([
                ['day' => 1, 'title' => 'Journey to Sreemangal & Tea Garden Walk', 'desc' => 'Morning pickup from Gazipur / Dhaka. Drive to Sreemangal. Check into resort. Afternoon walk through lush green tea gardens and taste the famous 7-layer tea.'],
                ['day' => 2, 'title' => 'Lawachara Rainforest & Journey to Sylhet', 'desc' => 'Early morning nature trail at Lawachara National Park and tribal village. Afternoon transfer to Sylhet city. Visit Hazrat Shah Jalal (R.) Mazar.'],
                ['day' => 3, 'title' => 'Ratargul Swamp Forest & Return', 'desc' => 'Morning boat ride through the Amazon of Bangladesh—Ratargul Swamp Forest. Scenic lunch and drive back to Gazipur in the evening.']
            ]),
            "2 Nights accommodation in scenic boutique resort/hotel\nDaily breakfast\nDedicated private AC vehicle throughout the entire tour from Gazipur\nLocal boat rides at Ratargul Swamp Forest\nAll forest entry fees and local guide assistance",
            "Lunch and dinner meals\nPersonal shopping and tipping",
            1,
            4,
            'Sylhet & Sreemangal Weekend Retreat | Mahin Travel & Tours',
            'A refreshing 3-day holiday in Bangladesh’s tea capital and swamp forest with private transport from Gazipur.'
        ]
    ];

    foreach ($tours as $t) {
        $chk = $pdo->prepare("SELECT id FROM tours WHERE slug = ?");
        $chk->execute([$t[1]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO tours (title, slug, destination_id, duration_days, duration_nights, price_text, price_type, featured_image, overview, itinerary_json, inclusions, exclusions, is_featured, sort_order, status, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?)");
            $ins->execute([$t[0], $t[1], $t[2], $t[3], $t[4], $t[5], $t[6], $t[7], $t[8], $t[9], $t[10], $t[11], $t[12], $t[13], $t[14], $t[15]]);
        }
    }

    // 8. Seed Realistic FAQs
    $faqs = [
        ['visa', 'Can Mahin Travel & Tours guarantee that my visa will be approved?', 'No legitimate travel agency or consultant can guarantee visa issuance, as approval decisions rest solely with the sovereign immigration authorities and consular officers of the destination country. What Mahin Travel & Tours guarantees is thorough procedural guidance: ensuring your paperwork is complete, authenticated, verifiable, and prepared strictly to embassy standards to minimize preventable rejections.', 1],
        ['visa', 'What minimum passport validity is required for international travel?', 'Almost all international countries require a passport to have at least six (6) months of remaining validity from your scheduled date of return. Some countries also require a minimum of two to three entirely blank visa pages for entry and exit stamps.', 2],
        ['visa', 'Can I apply for an Umrah visa as an individual or family?', 'Yes. Under current Saudi Ministry guidelines, tourist e-visas and Umrah visas can be issued for individuals, couples, and families. Our Gazipur team assists with eligibility review, Nusuk platform alignment, and complete travel logistics.', 3],
        ['tickets', 'How do I change my flight dates or cancel an air ticket?', 'If you need to change your travel dates or cancel a flight, contact our team immediately with your booking reference or ticket PNR. Date changes and refunds depend on the specific fare rules of the airline. We handle the reissuance, date amendment, and airline coordination on your behalf.', 4],
        ['tickets', 'How far in advance should I book my international flight?', 'For competitive fares, we recommend booking international flights 4 to 8 weeks in advance for regular leisure travel, and at least 8 to 12 weeks ahead during peak holiday seasons (Eid, school vacations, year-end holidays).', 5],
        ['tours', 'What does "Contact for Price" mean on tour packages?', 'Airfares and hotel rates fluctuate significantly based on seasonal demand, group size, and room availability. Stating "Contact for Price" ensures we provide you with an exact, up-to-date quote based on your specific travel dates and preferences, without hidden surprises.', 6],
        ['tours', 'Can you customize a tour itinerary for my family or private group?', 'Absolutely. All our tour packages can be customized. Whether you wish to upgrade hotel ratings, extend your stay, add specific attractions, or arrange private luxury vehicles, our travel architects tailor the package to your requirements.', 7],
        ['general', 'Can I visit your office in Gazipur for in-person consultation?', 'Yes, visitors are warmly welcome at our physical office located at Holding no-1492, South Salna, Ward No-19, Zone-5, Gazipur. We are open Saturday through Thursday from 9:30 AM to 8:30 PM. You can also call us directly at +8801924713765 or +8801722203033.', 8],
        ['general', 'How do I pay for my services with Mahin Travel & Tours?', 'We accept transparent bank transfers to our official agency accounts, pay orders, and direct in-office transactions. Formal money receipts are provided for all transactions.', 9]
    ];

    foreach ($faqs as $f) {
        $chk = $pdo->prepare("SELECT id FROM faqs WHERE question = ?");
        $chk->execute([$f[1]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO faqs (category, question, answer, sort_order, status) VALUES (?, ?, ?, ?, 1)");
            $ins->execute([$f[0], $f[1], $f[2], $f[3]]);
        }
    }

    // 9. Seed Testimonials
    $testimonials = [
        ['Engr. Tariqul Islam', 'Gazipur Sadar', 'Mahin Travel & Tours handled my family\'s Dubai visit visa and flight tickets. What impressed me most was their honesty—no false promises, just clear checklists and timely updates. Everything went smoothly.', 5, 'assets/images/testimonials/avatar1.jpg', 1],
        ['Dr. Rezwana Ahmed', 'Medical Specialist', 'I booked an Umrah package for my parents through Mahin Travel. The hotel in Madinah was just a 3-minute walk from the Prophet\'s Mosque, exactly as promised. The ground transport in Jeddah was punctual and courteous.', 5, 'assets/images/testimonials/avatar2.jpg', 2],
        ['Mohammad Sazzad Hossain', 'Business Executive', 'Regularly book my business travel to Malaysia and Thailand with Mahin Travel. Their responsiveness on WhatsApp even during flight delays or date change requests is outstanding.', 5, 'assets/images/testimonials/avatar3.jpg', 3],
        ['Kazi Farhan', 'Student Traveler', 'Got my UK university appointment and visitor visa filing advice from the Mahin Travel team in South Salna. Their guidance on bank statement formatting was invaluable.', 5, 'assets/images/testimonials/avatar4.jpg', 4]
    ];

    foreach ($testimonials as $tst) {
        $chk = $pdo->prepare("SELECT id FROM testimonials WHERE client_name = ?");
        $chk->execute([$tst[0]]);
        if (!$chk->fetch()) {
            $ins = $pdo->prepare("INSERT INTO testimonials (client_name, client_role_or_location, comment, rating, avatar, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
            $ins->execute([$tst[0], $tst[1], $tst[2], $tst[3], $tst[4], $tst[5]]);
        }
    }
}
