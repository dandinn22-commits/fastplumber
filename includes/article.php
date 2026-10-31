<?php
/* תבנית למדריך. $a: slug, title, h1, desc, lead, toc[id=>title], body (HTML), faq[[q,a]] */
require_once __DIR__ . '/layout.php';

function render_article(array $a) {
    $path = '/guides/' . $a['slug'] . '/';
    $schemaArticle = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $a['h1'],
        'description' => $a['desc'],
        'image' => abs_url('/assets/guide-' . $a['slug'] . '.webp'),
        'author' => ['@type' => 'Organization', 'name' => SITE_NAME, 'url' => abs_url('/')],
        'publisher' => ['@type' => 'Organization', 'name' => SITE_NAME, 'logo' => ['@type' => 'ImageObject', 'url' => abs_url('/assets/logo-512.png')]],
        'datePublished' => '2026-11-01',
        'dateModified' => '2026-11-01',
        'mainEntityOfPage' => abs_url($path),
        'inLanguage' => 'he',
    ];
    $schema = [$schemaArticle, breadcrumb_schema(['דף הבית' => '/', 'מדריכים' => '/guides/', $a['title'] => $path])];
    if (!empty($a['faq'])) $schema[] = faq_schema($a['faq']);
    page_head(['title' => $a['h1'] . ' | ' . SITE_NAME, 'description' => $a['desc'], 'path' => $path, 'og_type' => 'article', 'schema' => $schema]);
    topbar(); ?>
<main class="article">
<div class="article-wrap">
<nav class="breadcrumb" aria-label="מיקום באתר" style="text-align:right"><a href="/">דף הבית</a> / <a href="/guides/">מדריכים</a> / <span><?= e($a['title']) ?></span></nav>
<h1><?= e($a['h1']) ?></h1>
<p class="article-meta">מאת צוות <?= e(SITE_NAME) ?></p>
<img class="article-hero-img" src="/assets/guide-<?= e($a['slug']) ?>.webp" alt="" width="1200" height="675" decoding="async">
<p class="lead"><?= e($a['lead']) ?></p>
<nav class="article-toc" aria-label="תוכן המדריך"><p>במדריך:</p><ol>
<?php foreach ($a['toc'] as $id => $t): ?><li><a href="#<?= e($id) ?>"><?= e($t) ?></a></li><?php endforeach; ?>
<?php if (!empty($a['faq'])): ?><li><a href="#faq">שאלות נפוצות</a></li><?php endif; ?>
</ol></nav>
<?= $a['body'] ?>
<?php if (!empty($a['faq'])): ?>
<h2 id="faq">שאלות נפוצות</h2>
<?php faq_items($a['faq']); ?>
<?php endif; ?>
<nav class="article-related" aria-label="מדריכים נוספים"><p>מדריכים נוספים:</p><ul>
<?php foreach (GUIDES as $slug => $t): if ($slug === $a['slug']) continue; ?><li><a href="/guides/<?= $slug ?>/"><?= e($t) ?></a></li><?php endforeach; ?>
</ul></nav>
<div class="article-cta">
<h2>צריכים אינסטלטור?</h2>
<p>משאירים פרטים ואנחנו חוזרים אליכם תוך <?= e(RESPONSE_TIME) ?>. בתקלה דחופה – התקשרו.</p>
<div class="row">
<a class="a-call" href="tel:<?= PHONE_TEL ?>">התקשרו: <span dir="ltr"><?= PHONE_DISPLAY ?></span></a>
<a class="a-wa" href="<?= e(wa_link()) ?>" target="_blank" rel="noopener">כתבו לנו בווטסאפ</a>
</div>
</div>
</div>
</main>
<?php
    contact_section();
    page_end('#contactForm');
}
