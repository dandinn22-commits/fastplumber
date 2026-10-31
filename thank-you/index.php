<?php
require __DIR__ . '/../includes/layout.php';
page_head(['title' => 'תודה שהשארתם פרטים – ' . SITE_NAME, 'path' => '/thank-you/', 'robots' => 'noindex, nofollow', 'body_class' => 'thanks-page']);
?>
<main class="thanks">
  <div class="thanks-card">
    <img class="thanks-logo" src="/assets/logo.svg" alt="<?= e(SITE_NAME) ?>" width="120" height="120">
    <div class="thanks-check" aria-hidden="true"><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="22" fill="#1A8FE3"/><path d="M14 25l7 7 13-15" stroke="#fff" stroke-width="4.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
    <h1>תודה<span class="accent" id="nameSpan"></span>! הפרטים התקבלו</h1>
    <p class="sub">צוות "<?= e(SITE_NAME) ?>" קיבל את הפנייה וכבר בודק אותה – נחזור אליכם תוך <?= e(RESPONSE_TIME) ?>, בשעות הפעילות.</p>
    <div class="photo-box">
      <h2>רוצים מחיר מהיר ומדויק יותר?</h2>
      <p>שלחו לנו עכשיו בווטסאפ תמונה או סרטון קצר של התקלה. ככה נוכל להבין מה צריך כבר בשיחה הראשונה.</p>
      <a id="waPhotos" class="form-wa" href="<?= e(wa_link('היי, השארתי פרטים באתר. מצרף/ת תמונה של התקלה:')) ?>" target="_blank" rel="noopener"><?= wa_icon() ?> שליחת תמונה בווטסאפ</a>
      <p class="photo-tip">טיפ: צלמו באור טוב, מקרוב ומרחוק, וכתבו בכמה מילים מתי התקלה התחילה.</p>
    </div>
    <div class="emergency-note"><strong>תקלה דחופה?</strong> אל תחכו – התקשרו: <a href="tel:<?= PHONE_TEL ?>" dir="ltr"><?= PHONE_DISPLAY ?></a></div>
    <p><a class="back-home" href="/">חזרה לדף הבית</a></p>
  </div>
</main>
<script>
(function(){
  try {
    var name = sessionStorage.getItem('lead_name') || '';
    var sent = sessionStorage.getItem('lead_sent') === '1';
    if (name) document.getElementById('nameSpan').textContent = ' ' + name.split(' ')[0];
    if (sent) {
      sessionStorage.removeItem('lead_sent');
      var phone = sessionStorage.getItem('lead_phone') || '';
      if (window.SITE_CFG && SITE_CFG.adsId && phone) {
        gtag('set', 'user_data', { phone_number: '+972' + phone.replace(/^0/, '') });
      }
      if (window.trackConversion) trackConversion('form');
    }
  } catch(e) {}
})();
</script>
</body>
</html>
