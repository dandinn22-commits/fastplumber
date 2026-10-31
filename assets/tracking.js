/* ===== מעקב המרות – אינסטלטור ברגע =====
   המזהים והתוויות מוגדרים בקובץ includes/config.php ומגיעים לכאן דרך window.SITE_CFG */
(function(){
  var CFG = window.SITE_CFG || {};
  var ADS_ID = CFG.adsId || '';
  var GA4_ID = CFG.ga4Id || '';
  var LABELS = CFG.labels || {};
  var CLICK_LOG = CFG.sheetUrl || '';

  window.dataLayer = window.dataLayer || [];
  window.gtag = window.gtag || function(){ dataLayer.push(arguments); };
  if (ADS_ID || GA4_ID) {
    gtag('js', new Date());
    if (ADS_ID) gtag('config', ADS_ID, { allow_enhanced_conversions: true });
    if (GA4_ID) gtag('config', GA4_ID);
  }

  // זיהוי מבקר שהגיע ממודעה בגוגל אדס (נשמר לכל הביקור)
  try {
    if (/[?&](gclid|gbraid|wbraid)=|[?&]utm_medium=cpc/.test(location.search)) sessionStorage.setItem('from_ads', '1');
    window.fromAds = sessionStorage.getItem('from_ads') === '1';
  } catch(e) { window.fromAds = false; }

  var GA4_EVENTS = { form: 'generate_lead', call: 'click_call', whatsapp: 'click_whatsapp' };

  window.trackConversion = function(kind){
    try {
      if (ADS_ID && LABELS[kind]) gtag('event', 'conversion', { send_to: ADS_ID + '/' + LABELS[kind] });
      if (GA4_ID) gtag('event', GA4_EVENTS[kind] || kind, { page_path: location.pathname });
    } catch(e) {}
  };

  function logClick(kind){
    if (!CLICK_LOG) return;
    try {
      var data = new URLSearchParams({ type: 'click', kind: kind, page: location.pathname + (window.fromAds ? ' – גוגל אדס' : '') });
      if (navigator.sendBeacon) navigator.sendBeacon(CLICK_LOG, data);
      else fetch(CLICK_LOG, { method: 'POST', mode: 'no-cors', keepalive: true, body: data });
    } catch(e) {}
  }

  // לחיצות על טלפון ווטסאפ בכל מקום באתר
  document.addEventListener('click', function(e){
    var a = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!a) return;
    var href = a.getAttribute('href') || '';
    var kind = '';
    if (href.indexOf('tel:') === 0) kind = 'call';
    else if (href.indexOf('wa.me') > -1 || href.indexOf('api.whatsapp.com') > -1) kind = 'whatsapp';
    if (!kind) return;
    if (location.pathname.indexOf('thank-you') === -1) trackConversion(kind);
    logClick(kind);
  }, true);
})();
