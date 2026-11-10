<?php
require_once __DIR__ . '/layout.php';
function simple_page($title, $path, $h1, $bodyHtml) {
    page_head(['title' => $title . ' – ' . SITE_NAME, 'path' => $path, 'description' => $title . ' של ' . SITE_NAME]);
    topbar(); ?>
<main class="article">
<div class="article-wrap simple-page">
<nav class="breadcrumb" aria-label="מיקום באתר" style="text-align:right"><a href="/">דף הבית</a> / <span><?= e($h1) ?></span></nav>
<h1><?= e($h1) ?></h1>
<?= $bodyHtml ?>
</div>
</main>
<?php
    access_widget();
    site_footer(); ?>
<script src="/assets/site.js?v=<?= @filemtime(__DIR__ . '/../assets/site.js') ?>"></script>
<script src="/lead-alerts.js?v=1" data-brand="אינסטלטור ברגע" defer></script>
</body>
</html>
<?php }
