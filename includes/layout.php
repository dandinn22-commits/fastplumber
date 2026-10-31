<?php
/* =====================================================================
   תבניות משותפות לכל הדפים – אינסטלטור ברגע
   ===================================================================== */
require_once __DIR__ . '/config.php';

/* ---------- עזרים ---------- */

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function site_url() {
    if (SITE_URL !== '') return rtrim(SITE_URL, '/');
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $host = preg_replace('/[^A-Za-z0-9.\-:]/', '', $host);
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
        || (($_SERVER['SERVER_PORT'] ?? '') == 443);
    $local = preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $host);
    return ($https || !$local ? 'https' : 'http') . '://' . $host;
}

function abs_url($path) { return site_url() . $path; }

function wa_link($text = 'היי, אשמח לקבל הצעת מחיר לאינסטלציה') {
    return 'https://wa.me/' . WA_NUMBER . '?text=' . rawurlencode($text);
}

function years_text() { return YEARS_EXPERIENCE > 0 ? YEARS_EXPERIENCE . ' שנות ניסיון' : 'צוות מקצועי ומנוסה'; }

function cities_list_text($sep = ', ', $last = ' ו') {
    $names = array_column(CITIES, 'name');
    $tail = array_pop($names);
    return implode($sep, $names) . $last . $tail;
}

function json_ld($data) {
    echo '<script type="application/ld+json">' . "\n"
        . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        . "\n</script>\n";
}

/* ---------- אייקונים ---------- */

function icon($name) {
    $c = '#1A8FE3';
    $f = 'fill="' . $c . '" fill-opacity="0.15"';
    $s = 'stroke="' . $c . '" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
    $icons = [
        // כיור וסתימה
        'drain'  => "<path d=\"M3 10h18v2a6 6 0 0 1-6 6H9a6 6 0 0 1-6-6v-2Z\" $s $f/><path d=\"M12 18v3M9 21h6M8 10V7a3 3 0 0 1 6 0\" $s/><circle cx=\"12\" cy=\"13.5\" r=\"1.2\" fill=\"$c\"/>",
        // טיפת מים – נזילה
        'leak'   => "<path d=\"M12 3s6 6.6 6 11a6 6 0 0 1-12 0c0-4.4 6-11 6-11Z\" $s $f/><path d=\"M9.5 14.5a2.5 2.5 0 0 0 2.5 2.5\" $s/>",
        // ברז
        'faucet' => "<path d=\"M4 9h9a4 4 0 0 1 4 4v1h-4v-1H4z\" $s $f/><path d=\"M8 9V6M5.5 6h5\" $s/><path d=\"M15 17.5c0 1-.7 1.8-1.5 1.8S12 18.5 12 17.5c0-.8 1.5-2.5 1.5-2.5s1.5 1.7 1.5 2.5Z\" $s/>",
        // דוד
        'heater' => "<rect x=\"6\" y=\"3\" width=\"12\" height=\"16\" rx=\"5\" $s $f/><path d=\"M9 19v2M15 19v2\" $s/><circle cx=\"12\" cy=\"9\" r=\"2.2\" $s/><path d=\"M12 9l1-1\" $s/><path d=\"M9.5 14h5\" $s/>",
        // אסלה
        'toilet' => "<path d=\"M5 4h6v6H5z\" $s $f/><path d=\"M4 10h14a2 2 0 0 1 2 2 6 6 0 0 1-6 6h-1l1 3H8l1-3.5A6 6 0 0 1 4 12z\" $s $f/>",
        // צינור
        'pipe'   => "<path d=\"M3 7h9a4 4 0 0 1 4 4v10\" $s/><path d=\"M3 11h8a1 1 0 0 1 1 1v9\" $s/><rect x=\"2\" y=\"5.5\" width=\"3\" height=\"7\" rx=\"1\" $s $f/><rect x=\"10.5\" y=\"18\" width=\"7\" height=\"3\" rx=\"1\" $s $f/>",
        // ניסיון – מפתח שוודי
        'wrench' => "<path d=\"M14.5 3.5a4.5 4.5 0 0 0-4.3 5.8L3.6 15.9a1.9 1.9 0 0 0 2.7 2.7l6.6-6.6a4.5 4.5 0 0 0 5.8-4.3l-2.7 2.7-2.4-.6-.6-2.4 2.7-2.7a4.5 4.5 0 0 0-1.2-.2Z\" $s $f/>",
        // אחריות – מגן
        'shield' => "<path d=\"M12 3 L20 6.5 V12 C20 17 16.5 20.3 12 21.5 C7.5 20.3 4 17 4 12 V6.5 L12 3Z\" $s $f/><path d=\"M9 12 L11 14 L15.5 9.5\" $s/>",
        // ניקיון – ניצוץ
        'clean'  => "<path d=\"M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8z\" $s $f/><path d=\"M18 15l.8 2.2L21 18l-2.2.8L18 21l-.8-2.2L15 18l2.2-.8z\" $s/>",
        // זמינות – שעון
        'clock'  => "<circle cx=\"12\" cy=\"12\" r=\"8.5\" $s $f/><path d=\"M12 7.5 V12 L15 14\" $s/>",
    ];
    return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
}

function wa_icon($size = 22) {
    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.36 5.07L2 22l5.07-1.33A9.94 9.94 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2Z" fill="#FFFFFF"/><path d="M17.47 14.38c-.28-.14-1.67-.82-1.93-.92-.26-.1-.45-.14-.64.14-.19.28-.73.92-.9 1.11-.16.19-.33.21-.61.07-.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.66-1.56-1.94-.16-.28-.02-.43.12-.57.13-.13.28-.33.42-.5.14-.16.19-.28.28-.47.09-.19.05-.35-.02-.5-.07-.14-.64-1.55-.88-2.13-.23-.55-.47-.48-.64-.49-.16-.01-.35-.01-.54-.01-.19 0-.5.07-.76.35-.26.28-1 1-1 2.42 0 1.43 1.03 2.81 1.17 3 .14.19 2.03 3.1 4.92 4.35.69.3 1.22.48 1.64.61.69.22 1.32.19 1.81.11.55-.08 1.67-.68 1.9-1.34.24-.65.24-1.21.16-1.34-.07-.12-.26-.19-.54-.33Z" fill="#25D366"/></svg>';
}

function phone_icon($size = 15, $color = '#14181A') {
    return '<svg viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8Z" fill="' . $color . '"/></svg>';
}

/* ---------- ראש הדף ---------- */

/**
 * $p: title, description, path, robots, schema (מערך של אובייקטי JSON-LD), body_class, preload_hero, og_type
 */
function page_head(array $p) {
    $title = $p['title'];
    $desc  = $p['description'] ?? '';
    $path  = $p['path'] ?? '/';
    $robots = $p['robots'] ?? 'index, follow';
    $canonical = abs_url($path);
    ?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
<meta charset="UTF-8">
<?php if (GOOGLE_ADS_ID || GA4_ID): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GOOGLE_ADS_ID ?: GA4_ID) ?>"></script>
<?php endif; ?>
<script>window.SITE_CFG=<?= json_encode([
    'adsId' => GOOGLE_ADS_ID, 'ga4Id' => GA4_ID, 'labels' => ADS_LABELS,
    'sheetUrl' => SHEET_BACKUP_URL, 'wa' => WA_NUMBER, 'phone' => PHONE_DISPLAY, 'phoneTel' => PHONE_TEL,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;</script>
<script src="/assets/tracking.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?></title>
<?php if ($desc): ?><meta name="description" content="<?= e($desc) ?>">
<?php endif; ?>
<?php if ($robots === 'index, follow'): ?><link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
<meta name="robots" content="<?= e($robots) ?>">
<?php if (GOOGLE_SITE_VERIFICATION): ?><meta name="google-site-verification" content="<?= e(GOOGLE_SITE_VERIFICATION) ?>">
<?php endif; ?>
<meta name="theme-color" content="#1A8FE3">
<meta property="og:type" content="<?= e($p['og_type'] ?? 'website') ?>">
<meta property="og:locale" content="he_IL">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<?php if ($desc): ?><meta property="og:description" content="<?= e($desc) ?>">
<?php endif; ?>
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e(abs_url('/assets/og-image.jpg')) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32.png">
<link rel="icon" type="image/png" sizes="48x48" href="/assets/favicon-48.png">
<link rel="apple-touch-icon" href="/assets/apple-touch-icon.png">
<?php if (!empty($p['preload_hero'])): ?><link rel="preload" as="image" href="/assets/hero-tall.webp" type="image/webp" media="(max-width: 860px)" fetchpriority="high">
<link rel="preload" as="image" href="/assets/hero-wide.webp" type="image/webp" media="(min-width: 861px)" fetchpriority="high">
<?php endif; ?>
<?php foreach ($p['schema'] ?? [] as $s) json_ld($s); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500;700;800;900&family=Assistant:wght@400;500;600;700&family=Suez+One&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/site.css?v=<?= @filemtime(__DIR__ . '/../assets/site.css') ?>">
</head>
<body<?= !empty($p['body_class']) ? ' class="' . e($p['body_class']) . '"' : '' ?>>
<?php
    top_call_bar();
}

function top_call_bar() { ?>
<a href="tel:<?= PHONE_TEL ?>" class="top-call-bar" aria-label="התקשרו אלינו <?= PHONE_DISPLAY ?>"><span class="top-call-icon" aria-hidden="true"><?= phone_icon() ?></span><span class="top-call-text">התקשרו עכשיו</span><span class="top-call-number" dir="ltr"><?= PHONE_DISPLAY ?></span></a>
<?php }

/* ---------- סכמות ---------- */

function business_schema() {
    $areas = [];
    foreach (CITIES as $slug => $c) $areas[] = ['@type' => 'City', 'name' => $c['name'], 'url' => abs_url("/$slug/")];
    $hours = [];
    foreach (HOURS as $h) $hours[] = ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $h['schema'], 'opens' => $h['open'], 'closes' => $h['close']];
    $offers = [];
    foreach (services() as $s) $offers[] = ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $s['title'], 'description' => $s['text']]];
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Plumber',
        '@id' => abs_url('/#business'),
        'name' => SITE_NAME,
        'url' => abs_url('/'),
        'logo' => abs_url('/assets/logo-512.png'),
        'image' => [abs_url('/assets/og-image.jpg')],
        'telephone' => PHONE_INTL,
        'email' => CONTACT_EMAIL,
        'description' => 'אינסטלטור באזור המרכז – פתיחת סתימות, איתור ותיקון נזילות, דודים וברזים. אחריות מלאה וחוזרים אליכם תוך ' . RESPONSE_TIME . '.',
        'priceRange' => '₪₪',
        'address' => ['@type' => 'PostalAddress', 'addressRegion' => 'מחוז המרכז', 'addressCountry' => 'IL'],
        'areaServed' => $areas,
        'openingHoursSpecification' => $hours,
        'contactPoint' => ['@type' => 'ContactPoint', 'telephone' => PHONE_INTL, 'contactType' => 'customer service', 'availableLanguage' => 'Hebrew'],
        'hasOfferCatalog' => ['@type' => 'OfferCatalog', 'name' => 'שירותי אינסטלציה', 'itemListElement' => $offers],
    ];
}

function faq_schema(array $faqs) {
    $items = [];
    foreach ($faqs as $f) $items[] = ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f[1])]];
    return ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items];
}

function breadcrumb_schema(array $crumbs) {
    $items = [];
    $i = 1;
    foreach ($crumbs as $name => $path) $items[] = ['@type' => 'ListItem', 'position' => $i++, 'name' => $name, 'item' => abs_url($path)];
    return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
}

/* ---------- כפתורי מברשת ---------- */

function brush_bg($id, $kind) {
    $g = [
        'fill'     => ['#5CB6F2', '#1A8FE3', '#0E5C9E', '#083D6B', '#DDF1FF', 0.5, 0.35],
        'call'     => ['#2B2F31', '#14181A', '#000000', '#000000', '#1A8FE3', 0.55, 0.18],
        'whatsapp' => ['#3EE07F', '#25D366', '#128C4A', '#0B5E30', '#DFFFEA', 0.5, 0.35],
    ][$kind];
    $seed = ['fill' => 7, 'call' => 21, 'whatsapp' => 33][$kind];
    return <<<SVG
<svg class="brush-bg" viewBox="0 0 270 88" preserveAspectRatio="none" aria-hidden="true">
  <defs>
    <filter id="rough$id" x="-30%" y="-60%" width="160%" height="220%">
      <feTurbulence type="fractalNoise" baseFrequency="0.02 0.09" numOctaves="2" seed="$seed" result="noise"/>
      <feDisplacementMap in="SourceGraphic" in2="noise" scale="10" xChannelSelector="R" yChannelSelector="G"/>
    </filter>
    <linearGradient id="grad$id" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$g[0]}"/><stop offset="55%" stop-color="{$g[1]}"/><stop offset="100%" stop-color="{$g[2]}"/>
    </linearGradient>
  </defs>
  <rect x="19" y="34" width="232" height="24" rx="10" fill="{$g[3]}" opacity="{$g[5]}" filter="url(#rough$id)"/>
  <rect x="15" y="18" width="240" height="52" rx="14" fill="url(#grad$id)" filter="url(#rough$id)"/>
  <rect x="31" y="26" width="180" height="9" rx="4" fill="{$g[4]}" opacity="{$g[6]}" filter="url(#rough$id)"/>
</svg>
SVG;
}

function hero_buttons($formAnchor = '#quoteForm') { ?>
    <div class="hero-cta-row">
      <a href="<?= $formAnchor ?>" class="btn-brush fill">
        <?= brush_bg('Hero1', 'fill') ?>
        <span class="brush-label form-label">
          <svg class="form-3d-icon" viewBox="0 0 48 48" aria-hidden="true"><rect x="9" y="8" width="30" height="36" rx="4.5" fill="#14181A"/><rect x="16" y="3.5" width="16" height="8" rx="2.4" fill="#FFFFFF"/><rect x="14.5" y="16" width="19" height="2.8" rx="1.4" fill="#FFFFFF" opacity="0.9"/><rect x="14.5" y="22.5" width="19" height="2.8" rx="1.4" fill="#FFFFFF" opacity="0.65"/><rect x="14.5" y="29" width="12.5" height="2.8" rx="1.4" fill="#FFFFFF" opacity="0.65"/><path d="M15.5 37.5l3.8 3.8 8.5-9.2" stroke="#5CB6F2" stroke-width="2.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
          מלאו להצעת מחיר מהירה
        </span>
      </a>
      <a href="tel:<?= PHONE_TEL ?>" class="btn-brush call">
        <?= brush_bg('Hero2', 'call') ?>
        <span class="brush-label call-label">
          <svg class="phone-3d-icon" viewBox="0 0 48 48" aria-hidden="true"><path d="M15 6.5c-3.1 0-5.6 2.6-5.1 5.6 1.3 8.6 5.6 16.8 12.2 23.4 6.6 6.6 14.8 10.9 23.4 12.2 3 .5 5.6-2 5.6-5.1v-4.9c0-2.1-1.4-3.9-3.4-4.4l-7-1.7c-1.7-.4-3.4.2-4.4 1.6l-1.9 2.5a26.6 26.6 0 0 1-12.9-12.9l2.5-1.9c1.4-1 2-2.7 1.6-4.4l-1.7-7c-.5-2-2.3-3.4-4.4-3.4H15Z" fill="#FFFFFF"/></svg>
          התקשרו עכשיו
        </span>
      </a>
      <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="btn-brush whatsapp">
        <?= brush_bg('Hero3', 'whatsapp') ?>
        <span class="brush-label whatsapp-label">
          <?= str_replace('width="24" height="24"', '', str_replace('<svg ', '<svg class="whatsapp-3d-icon" ', wa_icon(24))) ?>
          שלחו הודעה בוואטסאפ
        </span>
      </a>
    </div>
<?php }

/* ---------- גיבור (חלק עליון) ---------- */

function hero($line1, $line2, $sub) { ?>
<section class="hero">
  <picture>
    <source media="(min-width: 861px)" srcset="/assets/hero-wide.webp" type="image/webp">
    <img class="hero-bg" src="/assets/hero-tall.webp" alt="" width="900" height="1600" fetchpriority="high" decoding="async">
  </picture>
  <div class="hero-veil"></div>
  <div class="call-float" id="callFloat">
    <button class="call-float-close" id="callFloatClose" aria-label="סגירת הבאנר">✕</button>
    <div class="call-content">
      <div class="call-icon"><?= phone_icon(20, '#5CB6F2') ?></div>
      <a href="tel:<?= PHONE_TEL ?>" class="call-number" dir="ltr"><?= PHONE_DISPLAY ?></a>
    </div>
  </div>
  <div class="hero-inner">
    <div class="logo-wrap">
      <img src="/assets/logo.svg" alt="<?= e(SITE_NAME) ?> – <?= e(SITE_TAGLINE) ?>" width="207" height="207">
    </div>
    <div class="avail-badge"><span class="avail-dot" aria-hidden="true"></span><?= e(AVAILABILITY_BADGE) ?></div>
    <div class="eyebrow"><?= e(years_text()) ?> · חוזרים אליכם תוך <?= e(RESPONSE_TIME) ?></div>
    <h1>
<?= e($line1) ?><br>
<span class="stroke"><?= e($line2) ?>
<svg viewBox="0 0 300 22" preserveAspectRatio="none" aria-hidden="true"><path d="M4 14 Q60 4 120 12 T240 10 T296 15" stroke="#5CB6F2" stroke-width="7" fill="none" stroke-linecap="round"/></svg>
</span>
</h1>
    <p class="hero-sub"><span class="hero-sub-text"><?= e($sub) ?></span></p>
<?php hero_buttons(); ?>
<?php lead_form('quoteForm', 'q', 'טופס עליון', true); ?>
    <div class="scroll-cue" aria-hidden="true"></div>
  </div>
</section>
<?php }

/* ---------- טפסים ---------- */

function lead_form($formId, $prefix, $source, $card = false) {
    $nameId = $prefix . 'Name';
    $phoneId = $prefix . 'Phone';
    $issueId = $prefix . 'Issue';
    ?>
<form class="<?= $card ? 'form-card' : 'contact-form' ?>" id="<?= $formId ?>" action="/lead.php" method="post" data-source="<?= e($source) ?>" novalidate>
  <div class="form-fields">
<?php if ($card): ?>
    <h2 class="form-title">קבלו הצעת מחיר <span>מהירה</span> <span class="bold-word">בחינם</span></h2>
    <p class="form-note">ממלאים פרטים קצרים — חוזרים אליכם תוך <?= e(RESPONSE_TIME) ?></p>
    <ul class="form-trust"><li><?= e(YEARS_EXPERIENCE > 0 ? years_text() : 'צוות מקצועי') ?></li><li>אחריות מלאה</li><li>בלי התחייבות</li></ul>
<?php endif; ?>
    <div class="field">
      <label for="<?= $nameId ?>">שם מלא</label>
      <input type="text" id="<?= $nameId ?>" name="name" placeholder="איך קוראים לך?" autocomplete="name" maxlength="60" required>
    </div>
    <div class="field">
      <label for="<?= $phoneId ?>">טלפון</label>
      <input type="tel" enterkeyhint="send" id="<?= $phoneId ?>" name="phone" placeholder="050-0000000" autocomplete="tel" inputmode="tel" maxlength="20" aria-describedby="<?= $phoneId ?>Err" required>
      <p class="field-error" id="<?= $phoneId ?>Err" hidden>נא להזין מספר טלפון ישראלי תקין, למשל 050-1234567</p>
    </div>
    <div class="field">
      <label for="<?= $issueId ?>">מה התקלה? <span class="optional">(לא חובה)</span></label>
      <select id="<?= $issueId ?>" name="issue">
        <option value="">בחרו מהרשימה</option>
        <option>סתימה</option>
        <option>נזילה / רטיבות</option>
        <option>ברז או סוללה</option>
        <option>דוד מים</option>
        <option>אסלה / ניאגרה</option>
        <option>שיפוץ או החלפת צנרת</option>
        <option>אחר</option>
      </select>
    </div>
    <div class="hp-field" aria-hidden="true"><label for="<?= $prefix ?>Website">אתר</label><input type="text" id="<?= $prefix ?>Website" name="website" tabindex="-1" autocomplete="off"></div>
    <button type="submit" class="btn-3d"><span class="drip"></span><span class="drip"></span>שליחה – חוזרים תוך <?= e(RESPONSE_TIME) ?></button>
    <p class="form-error" role="alert" hidden></p>
    <p class="form-consent">בשליחת הפרטים אתם מסכימים ל<a href="/privacy/">מדיניות הפרטיות</a> ולכך שניצור איתכם קשר.</p>
    <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="form-wa"><?= wa_icon() ?> או שלחו לנו הודעה בוואטסאפ</a>
  </div>
</form>
<?php }

/* ---------- שירותים ---------- */

function services() {
    return [
        ['icon' => 'drain',  'title' => 'פתיחת סתימות',          'text' => 'כיורים, מקלחות, אסלות וקווי ביוב – פותחים את הסתימה ובודקים למה היא נוצרה.', 'guide' => 'blocked-drain'],
        ['icon' => 'leak',   'title' => 'איתור ותיקון נזילות',    'text' => 'מאתרים את מקור הנזילה ופותחים רק את מה שצריך, כדי לחסוך שבירה מיותרת.', 'guide' => 'water-leak'],
        ['icon' => 'faucet', 'title' => 'ברזים וסוללות',          'text' => 'תיקון ברז מטפטף, החלפת סוללה במטבח או במקלחת, והתקנת ברזים חדשים.', 'guide' => ''],
        ['icon' => 'heater', 'title' => 'דודי שמש ודודי חשמל',     'text' => 'תיקון והחלפה של דודים, ברזי דוד וצנרת הדוד, כולל בדיקת התקלה לפני הכול.', 'guide' => 'water-heater'],
        ['icon' => 'toilet', 'title' => 'אסלות וניאגרות',          'text' => 'ניאגרה שלא מפסיקה לזרום, אסלה שזזה או דולפת, והתקנת אסלה חדשה.', 'guide' => ''],
        ['icon' => 'pipe',   'title' => 'צנרת ושיפוצים',           'text' => 'החלפת צנרת ישנה, הכנה למטבח או לאמבטיה חדשים, וחיבור כלים סניטריים.', 'guide' => 'renovation-plumbing'],
    ];
}

function services_section($cityIn = '') { ?>
<section class="services" id="services">
<div class="container">
<div class="section-head">
<div class="section-eyebrow">השירותים שלנו</div>
<h2>במה אנחנו מטפלים<?= $cityIn ? ' ' . e($cityIn) : '' ?></h2>
<p>תקלות אינסטלציה בבית ובעסק – מברז מטפטף ועד החלפת צנרת</p>
</div>
<div class="why-grid services-grid">
<?php foreach (services() as $s): ?>
<div class="why-card">
<div class="why-icon"><?= icon($s['icon']) ?></div>
<h3><?= e($s['title']) ?></h3>
<p><?= e($s['text']) ?><?php if ($s['guide']): ?> <a href="/guides/<?= $s['guide'] ?>/">למדריך</a><?php endif; ?></p>
</div>
<?php endforeach; ?>
</div>
</div>
</section>
<?php }

function how_section() { ?>
<section class="how" id="how">
<div class="container">
<div class="section-head">
<div class="section-eyebrow">איך זה עובד</div>
<h2>שלושה צעדים ובעיה נפתרת</h2>
</div>
<ol class="steps-list">
<li><h3>משאירים פרטים</h3><p>בטופס, בטלפון או בווטסאפ. אפשר לצרף תמונה או סרטון קצר של התקלה.</p></li>
<li><h3>מקבלים הצעת מחיר</h3><p>חוזרים אליכם תוך <?= e(RESPONSE_TIME) ?>. בתקלות מורכבות נתאם ביקור כדי לבדוק במקום.</p></li>
<li><h3>מתקנים ומשאירים נקי</h3><p>מגיעים בזמן שסוכם, מתקנים, בודקים שהכול עובד ומנקים אחרינו.</p></li>
</ol>
</div>
</section>
<?php }

function why_section() { ?>
<section class="why" id="why">
  <div class="container">
    <div class="section-head">
      <div class="section-eyebrow">למה לבחור בנו</div>
      <h2>אינסטלטור שאפשר לסמוך עליו</h2>
      <p>מגיעים בזמן, אומרים מראש מה צריך לעשות ומה המחיר – בלי הפתעות</p>
    </div>
    <div class="why-grid">
      <div class="why-card"><div class="why-icon"><?= icon('wrench') ?></div><h3>עבודה מקצועית</h3><p><?= YEARS_EXPERIENCE > 0 ? e(years_text()) . ' בתיקוני אינסטלציה בבתים ובעסקים' : 'צוות מנוסה בתיקוני אינסטלציה בבתים ובעסקים' ?></p></div>
      <div class="why-card"><div class="why-icon"><?= icon('shield') ?></div><h3>אחריות מלאה</h3><p>על כל עבודה שאנחנו מבצעים, מהיום הראשון</p></div>
      <div class="why-card"><div class="why-icon"><?= icon('clean') ?></div><h3>עבודה נקייה</h3><p>מגינים על הרצפה והארונות, ומשאירים את המקום מסודר</p></div>
      <div class="why-card"><div class="why-icon"><?= icon('clock') ?></div><h3>מענה מהיר</h3><p>חוזרים אליכם תוך <?= e(RESPONSE_TIME) ?> ומתאמים הגעה בזמן שנוח לכם</p></div>
    </div>
  </div>
</section>
<?php }

function reviews_section() {
    if (!REVIEWS) return; ?>
<section class="testimonials" id="reviews">
  <div class="container">
    <div class="section-head">
      <div class="section-eyebrow">מה הלקוחות אומרים</div>
      <h2>ביקורות מלקוחות</h2>
    </div>
    <div class="carousel">
      <button class="carousel-arrow" id="carPrev" aria-label="ביקורת קודמת">›</button>
      <div class="carousel-viewport">
        <div class="carousel-track" id="carTrack">
<?php foreach (REVIEWS as $r): $stars = max(1, min(5, (int)($r['stars'] ?? 5))); ?>
          <div class="review-card" dir="rtl">
            <div class="review-stars" aria-label="<?= $stars ?> מתוך 5"><?= str_repeat('★', $stars) . str_repeat('☆', 5 - $stars) ?></div>
            <p class="review-text"><?= e($r['text']) ?></p>
            <div class="review-author"><div class="review-avatar"><?= e(mb_substr($r['name'], 0, 1)) ?></div><?= e($r['name']) ?></div>
          </div>
<?php endforeach; ?>
        </div>
      </div>
      <button class="carousel-arrow" id="carNext" aria-label="ביקורת הבאה">‹</button>
    </div>
    <div class="carousel-dots" id="carDots"></div>
  </div>
</section>
<?php }

function areas_section($currentSlug = '') { ?>
<section class="areas" id="areas">
  <div class="container">
    <div class="section-head">
      <div class="section-eyebrow">אזורי שירות</div>
      <h2>אינסטלטור בכל אזור המרכז</h2>
      <p>אנחנו מגיעים לבתים, לדירות ולעסקים בערים הבאות</p>
    </div>
    <ul class="areas-list">
<?php foreach (CITIES as $slug => $c): if ($slug === $currentSlug) continue; ?>
      <li><a href="/<?= $slug ?>/">אינסטלטור <?= e($c['in']) ?></a></li>
<?php endforeach; ?>
    </ul>
    <p class="areas-note">לא מצאתם את העיר שלכם? <a href="tel:<?= PHONE_TEL ?>">התקשרו</a> ונבדוק אם אנחנו מגיעים גם אליכם.</p>
  </div>
</section>
<?php }

function faq_items(array $faqs) {
    foreach ($faqs as $f): ?>
<details class="faq-item">
<summary><?= e($f[0]) ?></summary>
<p><?= $f[1] ?></p>
</details>
<?php endforeach;
}

function faq_section(array $faqs) { ?>
<section class="faq" id="faq">
<div class="container">
<div class="section-head">
<div class="section-eyebrow">יש לכם שאלה?</div>
<h2>שאלות נפוצות</h2>
<p>תשובות קצרות לשאלות שאנחנו שומעים הכי הרבה</p>
</div>
<div class="faq-list">
<?php faq_items($faqs); ?>
</div>
<p class="faq-more">רוצים לדעת עוד? <a href="/guides/">כל המדריכים שלנו</a></p>
</div>
</section>
<?php }

function cta_strip() { ?>
<section class="cta-strip">
  <div class="container">
    <h2>יש תקלה? בואו נפתור אותה</h2>
    <p>השאירו פרטים עכשיו ונחזור אליכם עם הצעת מחיר מותאמת</p>
    <a href="#quoteForm" class="btn-3d inline"><span class="drip"></span><span class="drip"></span>קבלו הצעת מחיר בחינם</a>
  </div>
</section>
<?php }

function hours_card() { ?>
      <div class="hours-card">
        <div class="hours-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="#14181A" stroke-width="1.6"/><path d="M12 7.5 V12 L15 14" stroke="#14181A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h3>שעות פעילות</h3>
        <ul class="hours-list">
<?php foreach (HOURS as $h): ?>
          <li><span><?= e($h['days']) ?></span><span dir="ltr"><?= $h['open'] ?> – <?= $h['close'] ?></span></li>
<?php endforeach; ?>
        </ul>
        <a href="tel:<?= PHONE_TEL ?>" class="hours-phone" dir="ltr"><?= PHONE_DISPLAY ?></a>
      </div>
<?php }

function contact_section() { ?>
<section class="contact-section" id="contact">
  <div class="container">
    <div class="section-head">
      <div class="section-eyebrow">בואו נתחיל</div>
      <h2>השאירו פרטים ונחזור אליכם</h2>
      <p>ספרו לנו בקצרה מה התקלה, ונחזור אליכם עם הצעת מחיר</p>
    </div>
    <div class="contact-grid">
<?php lead_form('contactForm', 'c', 'טופס תחתון'); ?>
<?php hours_card(); ?>
    </div>
  </div>
</section>
<?php }

function sticky_actions($anchor = '#quoteForm') { ?>
<div class="sticky-actions">
  <a href="<?= $anchor ?>" class="sticky-btn quote-sticky-btn" aria-label="קבלת הצעת מחיר בחינם"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="5" y="3.5" width="14" height="17" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M8.5 9h7M8.5 12.5h7M8.5 16h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><span class="sticky-label">הצעת מחיר</span></a>
  <a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener" class="sticky-btn whatsapp-btn" aria-label="שליחת הודעה בוואטסאפ" title="דברו איתנו בוואטסאפ"><?= wa_icon() ?><span class="sticky-label">וואטסאפ</span></a>
  <a href="tel:<?= PHONE_TEL ?>" class="sticky-btn call-sticky-btn" aria-label="חייגו אלינו עכשיו" title="התקשרו עכשיו"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6.5 3.5c-1.5 0-2.5 1.2-2.3 2.7.6 4.6 2.9 8.9 6.3 12.3 3.4 3.4 7.7 5.7 12.3 6.3 1.5.2 2.7-.8 2.7-2.3v-2.6c0-1-.7-1.9-1.7-2.1l-3.6-.9c-.8-.2-1.7.1-2.2.8l-.9 1.2a15 15 0 0 1-6.9-6.9l1.2-.9c.7-.5 1-1.4.8-2.2l-.9-3.6c-.2-1-1.1-1.7-2.1-1.7H6.5Z" fill="currentColor"/></svg><span class="sticky-label">חייגו עכשיו</span></a>
</div>
<?php }

function access_widget() { ?>
<button class="access-toggle" id="accessToggle" aria-label="פתיחת תפריט נגישות" aria-expanded="false" aria-controls="accessPanel" title="אפשרויות נגישות">
  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10.5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="7.3" r="1.4" fill="currentColor"/><path d="M6.5 9.8c3.6 1.1 7.4 1.1 11 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M12 9.8V14l-2.3 5.4M12 14l2.3 5.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>
<div class="access-panel" id="accessPanel">
  <h4>אפשרויות נגישות</h4>
  <button type="button" data-action="font-up">הגדלת טקסט A+</button>
  <button type="button" data-action="font-down">הקטנת טקסט A-</button>
  <button type="button" data-action="contrast">ניגודיות גבוהה</button>
  <button type="button" data-action="reset">איפוס תצוגה</button>
</div>
<?php }

function site_footer() { ?>
<footer>
<div class="footer-logo"><img src="/assets/icon.svg" alt="<?= e(SITE_NAME) ?>" width="42" height="42" loading="lazy"></div>
<nav class="footer-cities" aria-label="אזורי שירות">
<?php foreach (CITIES as $slug => $c): ?>
<a href="/<?= $slug ?>/">אינסטלטור <?= e($c['in']) ?></a>
<?php endforeach; ?>
</nav>
<p><?= e(SITE_NAME) ?> · אינסטלציה לבית ולעסק באזור המרכז © <?= date('Y') ?></p>
<p class="footer-links"><a href="tel:<?= PHONE_TEL ?>" dir="ltr"><?= PHONE_DISPLAY ?></a> · <a href="/accessibility/">הצהרת נגישות</a> · <a href="/privacy/">מדיניות פרטיות</a> · <a href="/guides/">מדריכים</a><?php foreach (GUIDES as $slug => $t): ?> · <a href="/guides/<?= $slug ?>/"><?= e($t) ?></a><?php endforeach; ?></p>
</footer>
<?php }

/* ---------- סוף הדף ---------- */

function page_end($anchor = '#quoteForm') {
    sticky_actions($anchor);
    access_widget();
    site_footer(); ?>
<script src="/assets/site.js?v=<?= @filemtime(__DIR__ . '/../assets/site.js') ?>"></script>
</body>
</html>
<?php }

/* ---------- סרגל עליון לדפי תוכן ---------- */

function topbar() { ?>
<header class="topbar">
<div class="topbar-inner">
<a class="topbar-brand" href="/"><img src="/assets/icon.svg" alt="" width="44" height="44"><?= e(SITE_NAME) ?></a>
<a class="topbar-call" href="tel:<?= PHONE_TEL ?>">התקשרו: <span dir="ltr"><?= PHONE_DISPLAY ?></span></a>
</div>
</header>
<?php }

/* ---------- שירות חירום ---------- */

function emergency_section($cityIn = '') { ?>
<section class="emergency" id="emergency">
  <div class="container">
    <div>
      <div class="emergency-tag">שירות חירום</div>
      <h2>פיצוץ צינור או הצפה<?= $cityIn ? ' ' . e($cityIn) : '' ?>? התקשרו עכשיו</h2>
      <p>בתקלה דחופה לא ממלאים טופס – מתקשרים. נעשה הכול כדי להגיע אליכם כמה שיותר מהר. עד שנגיע: סגרו את ברז המים הראשי, ואם המים מגיעים לשקעים – נתקו את החשמל בלוח.</p>
      <ul>
        <li>פיצוץ צינור ונזילה חזקה</li>
        <li>הצפה בדירה או בחצר</li>
        <li>ביוב שעולה מהאסלה או מהמקלחת</li>
        <li>אין מים בכל הבית</li>
      </ul>
    </div>
    <div class="emergency-box">
      <p class="em-label">קו חירום</p>
      <a class="emergency-call" href="tel:<?= PHONE_TEL ?>" dir="ltr"><?= PHONE_DISPLAY ?></a>
      <a class="em-wa" href="<?= e(wa_link('היי, יש לי תקלה דחופה באינסטלציה')) ?>" target="_blank" rel="noopener"><?= wa_icon(20) ?> או שלחו סרטון של התקלה בווטסאפ</a>
      <p class="em-note">מחוץ לשעות הפעילות – השאירו הודעה ונחזור אליכם בהקדם.</p>
    </div>
  </div>
</section>
<?php }

/* ---------- תמונות אווירה ---------- */

function photos_section() { ?>
<section class="photos" id="photos">
  <div class="container">
    <div class="section-head">
      <div class="section-eyebrow">מה אנחנו עושים</div>
      <h2>אינסטלציה בכל חלקי הבית</h2>
      <p>מהמטבח וחדר הרחצה ועד הדוד על הגג</p>
    </div>
    <div class="photo-strip">
      <figure><img src="/assets/photo-drain.webp" alt="אינסטלטור פותח סתימה בכיור בחדר רחצה" width="1200" height="900" loading="lazy" decoding="async"><figcaption>פתיחת סתימות</figcaption></figure>
      <figure><img src="/assets/photo-leak.webp" alt="בדיקת רטיבות בקיר עם מד לחות" width="1200" height="900" loading="lazy" decoding="async"><figcaption>איתור נזילות</figcaption></figure>
      <figure><img src="/assets/photo-heater.webp" alt="בדיקת דוד שמש על גג בניין" width="1200" height="900" loading="lazy" decoding="async"><figcaption>דודי שמש וחשמל</figcaption></figure>
      <figure><img src="/assets/photo-shower.webp" alt="התקנת סוללה במקלחת" width="1200" height="900" loading="lazy" decoding="async"><figcaption>ברזים וסוללות</figcaption></figure>
      <figure><img src="/assets/photo-toilet.webp" alt="התקנת אסלה תלויה בחדר רחצה משופץ" width="1200" height="900" loading="lazy" decoding="async"><figcaption>אסלות ושיפוצים</figcaption></figure>
      <figure><img src="/assets/photo-kitchen.webp" alt="תיקון צנרת מתחת לכיור במטבח" width="1200" height="900" loading="lazy" decoding="async"><figcaption>צנרת במטבח</figcaption></figure>
    </div>
  </div>
</section>
<?php }

/* ---------- שאלות נפוצות כלליות ---------- */

function home_faqs() {
    return [
        ['תוך כמה זמן חוזרים אליי?', 'תוך ' . RESPONSE_TIME . ' מהפנייה, בשעות הפעילות. בתקלה דחופה עדיף להתקשר ישירות.'],
        ['אתם נותנים שירות חירום?', 'כן. בפיצוץ צינור, הצפה או ביוב שעולה – התקשרו ונעשה הכול כדי להגיע מהר. עד שנגיע, סגרו את ברז המים הראשי.'],
        ['כמה עולה ביקור של אינסטלטור?', 'המחיר תלוי בסוג התקלה. בשיחה נשאל כמה שאלות, ואם אפשר נבקש תמונה, כדי לתת לכם מחיר לפני שמגיעים.'],
        ['אתם מאתרים נזילות בלי לשבור?', 'אנחנו משתמשים בכלים לאיתור רטיבות ונזילות, כדי לפתוח רק במקום הנכון ולחסוך שבירה מיותרת.'],
        ['אתם מטפלים בדודי שמש?', 'כן – דודי שמש ודודי חשמל: גוף חימום, תרמוסטט, ברזי דוד, נזילות והחלפת דוד.'],
        ['יש אחריות על העבודה?', 'כן, יש אחריות מלאה על כל עבודה שאנחנו מבצעים. תנאי האחריות מופיעים בהצעת המחיר.'],
        ['לאילו ערים אתם מגיעים?', 'ל' . cities_list_text() . '. גרים בעיר אחרת? התקשרו ונבדוק.'],
    ];
}
