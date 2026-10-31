<?php
/* תבנית לדף עיר. כל תיקיית עיר מכילה index.php קצר שקורא לקובץ הזה. */
require_once __DIR__ . '/layout.php';

function render_city($slug) {
    $cities = require __DIR__ . '/cities.php';
    if (!isset(CITIES[$slug], $cities[$slug])) { http_response_code(404); require __DIR__ . '/../404.php'; return; }
    $c = CITIES[$slug];
    $d = $cities[$slug];
    $faqs = array_merge($d['faq'], [
        ['אתם נותנים שירות חירום ' . $c['in'] . '?', 'כן. בפיצוץ צינור, הצפה או ביוב שעולה – התקשרו ונעשה הכול כדי להגיע מהר. עד שנגיע, סגרו את ברז המים הראשי.'],
        ['יש אחריות על העבודה?', 'כן, יש אחריות מלאה על כל עבודה שאנחנו מבצעים. תנאי האחריות מופיעים בהצעת המחיר.'],
    ]);
    $path = "/$slug/";
    $service = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'אינסטלטור ' . $c['in'],
        'serviceType' => 'אינסטלציה',
        'url' => abs_url($path),
        'provider' => business_schema(),
        'areaServed' => ['@type' => 'City', 'name' => $c['name']],
    ];
    page_head([
        'title' => 'אינסטלטור ' . $c['in'] . ' – סתימות, נזילות ודודים | ' . SITE_NAME,
        'description' => $d['desc'],
        'path' => $path,
        'preload_hero' => true,
        'schema' => [$service, breadcrumb_schema(['דף הבית' => '/', 'אינסטלטור ' . $c['in'] => $path]), faq_schema($faqs)],
    ]);

    hero('אינסטלטור מקצועי', $c['in'],
        'צוות אינסטלציה מנוסה ' . $c['in'] . ': פתיחת סתימות, איתור נזילות, דודים וברזים, עם אחריות מלאה על כל עבודה. משאירים פרטים ומקבלים הצעת מחיר.');
    emergency_section($c['in']);
    ?>
<section class="local" id="local">
<div class="container">
<nav class="breadcrumb" aria-label="מיקום באתר">
<a href="/">דף הבית</a> / <span>אינסטלטור <?= e($c['in']) ?></span>
</nav>
<div class="section-head">
<div class="section-eyebrow">אזור שירות: <?= e($c['name']) ?></div>
<h2>שירותי אינסטלציה <?= e($c['in']) ?></h2>
</div>
<div class="local-text">
<?php foreach ($d['intro'] as $para): ?>
<p><?= e($para) ?></p>
<?php endforeach; ?>
<h3>מה אנחנו פוגשים <?= e($c['in']) ?></h3>
<ul class="local-seen">
<?php foreach ($d['seen'] as $s): ?>
<li><strong><?= e($s[0]) ?></strong><?= e($s[1]) ?></li>
<?php endforeach; ?>
</ul>
<p>כדאי לקרוא גם: <a href="/guides/<?= e($d['guide']) ?>/"><?= e(GUIDES[$d['guide']]) ?></a>.</p>
<p class="local-hoods">אנחנו מגיעים לכל השכונות <?= e($c['in']) ?>, ביניהן: <?= e($d['hoods']) ?>.</p>
</div>
</div>
</section>
<?php
    services_section($c['in']);
    how_section();
    why_section();
    reviews_section();
    areas_section($slug);
    faq_section($faqs);
    cta_strip();
    contact_section();
    page_end();
}
