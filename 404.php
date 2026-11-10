<?php
require_once __DIR__ . '/includes/layout.php';
if (!headers_sent()) http_response_code(404);
page_head(['title' => 'העמוד לא נמצא – ' . SITE_NAME, 'path' => '/404', 'robots' => 'noindex, follow', 'body_class' => 'thanks-page']);
?>
<main class="thanks">
  <div class="thanks-card">
    <img class="thanks-logo" src="/assets/logo.svg" alt="<?= e(SITE_NAME) ?>" width="120" height="120">
    <h1>העמוד הזה לא קיים</h1>
    <p class="sub">אולי הקישור השתנה. אפשר לחזור לדף הבית או להתקשר אלינו ישירות.</p>
    <div class="emergency-note"><a class="back-home" href="/">לדף הבית</a> · <a href="tel:<?= PHONE_TEL ?>" dir="ltr"><?= PHONE_DISPLAY ?></a></div>
  </div>
</main>
<script src="/lead-alerts.js?v=1" data-brand="אינסטלטור ברגע" defer></script>
</body>
</html>
