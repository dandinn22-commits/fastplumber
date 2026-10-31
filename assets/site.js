(function(){
  var CFG = window.SITE_CFG || {};

  // מחזיר מספר ישראלי תקין בפורמט 05XXXXXXXX, או מחרוזת ריקה
  function normalizePhone(raw){
    var d = String(raw || '').replace(/[^\d+]/g, '');
    if (d.indexOf('+972') === 0) d = '0' + d.slice(4);
    else if (d.indexOf('972') === 0 && d.length >= 11) d = '0' + d.slice(3);
    d = d.replace(/\D/g, '');
    if (d.length === 9 && d.charAt(0) !== '0') d = '0' + d;
    return /^0(?:5\d{8}|7\d{8}|[23489]\d{7})$/.test(d) ? d : '';
  }

  // ===== שליחת לידים: נשלח לשרת (lead.php) ומעביר לדף תודה =====
  function handleLeadForm(form){
    var btn = form.querySelector('button[type=submit]');
    var nameEl = form.querySelector('input[name="name"]');
    var phoneEl = form.querySelector('input[name="phone"]');
    var issueEl = form.querySelector('select[name="issue"]');
    var hp = form.querySelector('input[name="website"]');
    var errBox = form.querySelector('.form-error');
    var phoneErr = document.getElementById(phoneEl.id + 'Err');
    var source = form.getAttribute('data-source') || 'טופס';

    function showPhoneErr(on){
      if (phoneErr) phoneErr.hidden = !on;
      phoneEl.setAttribute('aria-invalid', on ? 'true' : 'false');
    }
    phoneEl.addEventListener('input', function(){
      if (phoneErr && !phoneErr.hidden && normalizePhone(phoneEl.value)) showPhoneErr(false);
    });

    form.addEventListener('submit', function(e){
      e.preventDefault();
      if (btn.disabled) return;
      if (errBox) errBox.hidden = true;

      var name = nameEl.value.trim();
      var phone = normalizePhone(phoneEl.value);
      var issue = issueEl ? issueEl.value : '';
      if (!phone){ showPhoneErr(true); phoneEl.focus(); return; }
      showPhoneErr(false);

      // שדה נסתר שרק רובוטים ממלאים
      if (hp && hp.value){ window.location.href = '/thank-you/'; return; }

      btn.disabled = true;
      btn.classList.add('is-sending');
      var finished = false;

      function success(){
        if (finished) return; finished = true;
        try {
          sessionStorage.setItem('lead_name', name);
          sessionStorage.setItem('lead_phone', phone);
          sessionStorage.setItem('lead_sent', '1');
        } catch(err) {}
        window.location.href = '/thank-you/';
      }
      function failure(){
        if (finished) return; finished = true;
        btn.disabled = false;
        btn.classList.remove('is-sending');
        if (!errBox) return;
        var msg = 'היי, אני ' + name + ' (' + phone + '). אשמח לקבל הצעת מחיר לאינסטלציה' + (issue ? ' – ' + issue : '');
        errBox.innerHTML = 'השליחה לא עברה. אפשר <a href="https://wa.me/' + CFG.wa + '?text=' + encodeURIComponent(msg) +
          '" target="_blank" rel="noopener">לשלוח לנו את הפרטים בווטסאפ</a> או להתקשר ל־<a href="tel:' + CFG.phoneTel + '" dir="ltr">' + CFG.phone + '</a>.';
        errBox.hidden = false;
      }
      var timer = setTimeout(failure, 15000);

      var fields = {
        name: name, phone: phone, issue: issue, source: source,
        from_ads: window.fromAds ? '1' : '0', page: location.pathname, website: ''
      };

      // גיבוי לגיליון גוגל (אם הוגדר)
      if (CFG.sheetUrl) {
        try {
          fetch(CFG.sheetUrl, { method: 'POST', mode: 'no-cors', keepalive: true,
            body: new URLSearchParams({ name: name, phone: phone, source: source + (issue ? ' – ' + issue : '') + (window.fromAds ? ' – גוגל אדס' : ''), page: location.pathname }) });
        } catch(err) {}
      }

      try {
        fetch('/lead.php', { method: 'POST', headers: { 'Accept': 'application/json' }, body: new URLSearchParams(fields) })
          .then(function(r){ return r.json(); })
          .then(function(res){
            clearTimeout(timer);
            if (res && res.ok) success(); else failure();
          }, function(){ clearTimeout(timer); failure(); });
      } catch(err) { clearTimeout(timer); failure(); }
    });
  }

  var forms = document.querySelectorAll('form[action="/lead.php"]');
  for (var i = 0; i < forms.length; i++) handleLeadForm(forms[i]);

  var callFloatClose = document.getElementById('callFloatClose');
  if (callFloatClose) callFloatClose.addEventListener('click', function(){
    document.getElementById('callFloat').classList.add('hidden');
  });

  // ===== קרוסלה =====
  function initCarousel(opts){
    var track = document.getElementById(opts.track);
    if (!track) return;
    var total = track.children.length;
    var prevBtn = document.getElementById(opts.prev);
    var nextBtn = document.getElementById(opts.next);
    var dotsWrap = document.getElementById(opts.dots);
    var index = 0;

    function visibleCount(){
      var w = window.innerWidth;
      if (w <= 640) return 1;
      if (w <= 860) return 2;
      return 3;
    }
    function maxIndex(){ return Math.max(0, total - visibleCount()); }
    function buildDots(){
      dotsWrap.innerHTML = '';
      for (var i = 0; i <= maxIndex(); i++){
        var d = document.createElement('button');
        d.type = 'button';
        d.className = 'carousel-dot' + (i === index ? ' active' : '');
        d.setAttribute('aria-label', opts.dotLabel + ' ' + (i + 1));
        d.addEventListener('click', (function(idx){ return function(){ index = idx; update(); }; })(i));
        dotsWrap.appendChild(d);
      }
    }
    function update(){
      var mi = maxIndex();
      if (index > mi) index = mi;
      if (index < 0) index = 0;
      var hideNav = mi === 0;
      prevBtn.classList.toggle('hidden', hideNav);
      nextBtn.classList.toggle('hidden', hideNav);
      dotsWrap.classList.toggle('hidden', hideNav);
      track.style.transform = 'translateX(-' + ((100 / visibleCount()) * index) + '%)';
      var dots = dotsWrap.children;
      for (var i = 0; i < dots.length; i++) dots[i].classList.toggle('active', i === index);
    }
    nextBtn.addEventListener('click', function(){ index = (index + 1) > maxIndex() ? 0 : index + 1; update(); });
    prevBtn.addEventListener('click', function(){ index = (index - 1) < 0 ? maxIndex() : index - 1; update(); });
    var resizeTimer;
    window.addEventListener('resize', function(){
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function(){ buildDots(); update(); }, 150);
    });
    buildDots();
    update();
  }
  initCarousel({track:'carTrack', prev:'carPrev', next:'carNext', dots:'carDots', dotLabel:'מעבר לביקורת'});

  // ===== תפריט נגישות =====
  var accessToggle = document.getElementById('accessToggle');
  var accessPanel = document.getElementById('accessPanel');
  if (accessToggle && accessPanel) {
    var fontStep = 0;
    accessToggle.addEventListener('click', function(){
      var open = accessPanel.classList.toggle('open');
      accessToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    accessPanel.addEventListener('click', function(e){
      var action = e.target.getAttribute('data-action');
      if (!action) return;
      if (action === 'font-up' && fontStep < 3) fontStep++;
      if (action === 'font-down' && fontStep > -2) fontStep--;
      if (action === 'reset') { fontStep = 0; document.body.classList.remove('high-contrast'); }
      if (action === 'contrast') document.body.classList.toggle('high-contrast');
      document.documentElement.style.fontSize = (100 + fontStep * 12) + '%';
    });
  }
})();
