<?php
require_once __DIR__ . '/includes/layout.php';

$faqs = home_faqs();
page_head([
    'title' => 'אינסטלטור במרכז – פתיחת סתימות ואיתור נזילות | ' . SITE_NAME,
    'description' => 'אינסטלטור בתל אביב, רמת גן, גבעתיים, פתח תקווה, ראשון לציון ועוד ערים במרכז: סתימות, נזילות, דודים וברזים. שירות חירום, אחריות מלאה ומענה תוך ' . RESPONSE_TIME . '.',
    'path' => '/',
    'preload_hero' => true,
    'schema' => [business_schema(), faq_schema($faqs)],
]);

hero('אינסטלטור מקצועי במרכז', 'מגיעים מהר ופותרים',
    'סתימות, נזילות, דודים וברזים – צוות אינסטלציה מנוסה בכל אזור המרכז, עם אחריות מלאה על כל עבודה. משאירים פרטים ומקבלים הצעת מחיר.');

emergency_section();
services_section();
photos_section();
how_section();
why_section();
reviews_section();
areas_section();
faq_section($faqs);
cta_strip();
contact_section();
page_end();
