<?php
require __DIR__ . '/../includes/layout.php';
$blurbs = [
    'blocked-drain' => 'מה אפשר לנסות לבד, מה לא לעשות, ומתי צריך אינסטלטור.',
    'water-leak' => 'סימנים לנזילה, בדיקה עם שעון המים ואיתור בלי לשבור.',
    'water-heater' => 'אין מים חמים? בדיקות ראשונות ותקלות נפוצות בדוד.',
    'renovation-plumbing' => 'מה לתכנן לפני שמרצפים, ואיך להימנע מטעויות.',
];
page_head([
    'title' => 'מדריכי אינסטלציה לבית | ' . SITE_NAME,
    'description' => 'מדריכים קצרים וברורים: סתימות, נזילות נסתרות, תקלות בדוד ואינסטלציה בשיפוץ.',
    'path' => '/guides/',
    'schema' => [breadcrumb_schema(['דף הבית' => '/', 'מדריכים' => '/guides/'])],
]);
topbar(); ?>
<main class="article">
<div class="article-wrap">
<nav class="breadcrumb" aria-label="מיקום באתר" style="text-align:right"><a href="/">דף הבית</a> / <span>מדריכים</span></nav>
<h1>מדריכי אינסטלציה לבית</h1>
<p class="lead">מדריכים קצרים שעוזרים להבין מה קורה בצנרת, מה אפשר לעשות לבד, ומתי כדאי לקרוא לאינסטלטור.</p>
<ul class="guides-list">
<?php foreach (GUIDES as $slug => $t): ?>
<li><a href="/guides/<?= $slug ?>/"><img src="/assets/guide-<?= $slug ?>.webp" alt="" width="1200" height="675" loading="lazy" decoding="async"><strong><?= e($t) ?></strong><span><?= e($blurbs[$slug] ?? '') ?></span></a></li>
<?php endforeach; ?>
</ul>
</div>
</main>
<?php
contact_section();
page_end('#contactForm');
