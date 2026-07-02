/* ===========================================================================
   case-evaluation.js — 3-step intake: step nav, progress, validation,
   char counter, phone mask, conditional reveal, AJAX submit + success.
   =========================================================================== */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {
    var form = document.querySelector('[data-multistep]');
    if (!form) return;

    var steps = Array.prototype.slice.call(form.querySelectorAll('.step'));
    var total = steps.length;
    var current = 1;
    var fill = form.querySelector('[data-progress-fill]');
    var indicators = Array.prototype.slice.call(form.querySelectorAll('[data-progress-step]'));
    var success = form.querySelector('[data-form-success]');

    /* ---- date max = today ---- */
    var dateInput = form.querySelector('input[type="date"]');
    if (dateInput) {
      var t = new Date();
      var iso = t.getFullYear() + '-' + String(t.getMonth() + 1).padStart(2, '0') + '-' + String(t.getDate()).padStart(2, '0');
      dateInput.setAttribute('max', iso);
    }

    /* ---- char counter ---- */
    form.querySelectorAll('[data-counter]').forEach(function (ta) {
      var out = form.querySelector('[data-counter-out]');
      function upd() { if (out) out.textContent = String(ta.value.length); }
      ta.addEventListener('input', upd); upd();
    });

    /* ---- phone mask ---- */
    form.querySelectorAll('[data-phone-mask]').forEach(function (inp) {
      inp.addEventListener('input', function () {
        var d = inp.value.replace(/\D/g, '').slice(0, 10);
        var out = d;
        if (d.length > 6) out = '(' + d.slice(0, 3) + ') ' + d.slice(3, 6) + '-' + d.slice(6);
        else if (d.length > 3) out = '(' + d.slice(0, 3) + ') ' + d.slice(3);
        else if (d.length > 0) out = '(' + d;
        inp.value = out;
      });
    });

    /* ---- conditional reveal (medical treatment yes/no) ---- */
    form.querySelectorAll('[data-toggle-reveal]').forEach(function (group) {
      var targetName = group.getAttribute('data-toggle-reveal');
      var target = form.querySelector('[data-reveal-target="' + targetName + '"]');
      if (!target) return;
      group.querySelectorAll('input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
          var show = radio.value === 'yes' && radio.checked;
          target.hidden = !show;
        });
      });
    });

    /* ---- validation ---- */
    function setError(field, msg) {
      if (!field) return;
      var wrap = field.closest('.field') || field;
      wrap.classList.toggle('field--error', !!msg);
      var note = wrap.querySelector('.field__error');
      if (note) note.textContent = msg || '';
    }
    function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    function validateStep(stepEl) {
      var ok = true;

      // Required radio group (incident type)
      var reqGroup = stepEl.querySelector('[data-validate-required]');
      if (reqGroup) {
        var checked = reqGroup.querySelector('input:checked');
        setError(reqGroup, checked ? '' : 'Please select an option.');
        if (!checked) ok = false;
      }

      // Required text/email/tel inputs
      stepEl.querySelectorAll('input[required], textarea[required]').forEach(function (f) {
        var v = (f.value || '').trim();
        if (!v) { setError(f, 'This field is required.'); ok = false; }
        else if (f.type === 'email' && !isEmail(v)) { setError(f, 'Enter a valid email address.'); ok = false; }
        else if (f.type === 'tel' && v.replace(/\D/g, '').length < 7) { setError(f, 'Enter a valid phone number.'); ok = false; }
        else setError(f, '');
      });

      // Consent checkbox
      var consent = stepEl.querySelector('input[name="consent"]');
      if (consent) {
        var note = stepEl.querySelector('[data-consent-error]');
        if (!consent.checked) { if (note) note.textContent = 'Please check this box to continue.'; ok = false; }
        else if (note) note.textContent = '';
      }
      return ok;
    }

    /* ---- step navigation ---- */
    function goTo(n) {
      if (n < 1 || n > total) return;
      steps.forEach(function (s) { s.classList.remove('is-active', 'is-prev'); });
      var target = steps[n - 1];
      target.classList.add('is-active');
      current = n;
      // progress
      if (fill) fill.style.width = ((n - 1) / (total - 1) * 100) + '%';
      indicators.forEach(function (ind, i) {
        ind.classList.toggle('is-active', i === n - 1);
        ind.classList.toggle('is-done', i < n - 1);
      });
      // focus first field for a11y
      var first = target.querySelector('input, select, textarea, button');
      if (first) first.focus({ preventScroll: true });
      form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    form.querySelectorAll('[data-next]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var stepEl = btn.closest('.step');
        if (validateStep(stepEl)) goTo(current + 1);
      });
    });
    form.querySelectorAll('[data-back]').forEach(function (btn) {
      btn.addEventListener('click', function () { goTo(current - 1); });
    });

    /* ---- clear errors as user edits ---- */
    form.querySelectorAll('input, textarea, select').forEach(function (f) {
      f.addEventListener('input', function () {
        var wrap = f.closest('.field');
        if (wrap && wrap.classList.contains('field--error')) setError(f, '');
      });
    });

    /* ---- submit ---- */
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var hp = form.querySelector('[name="website"]');
      if (hp && hp.value) return;
      if (!validateStep(steps[total - 1])) return;

      var btn = form.querySelector('[type="submit"]');
      if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Sending…'; }

      fetch(form.getAttribute('action'), {
        method: 'POST', headers: { 'Accept': 'application/json' }, body: new FormData(form)
      })
        .then(function (r) { return r.json().then(function (j) { return { status: r.status, body: j }; }); })
        .then(function (res) {
          if (res.body && res.body.ok) {
            form.classList.add('is-submitted');
            var msg = form.querySelector('[data-form-success-msg]');
            if (msg && res.body.message) msg.textContent = res.body.message;
            if (success) { success.hidden = false; requestAnimationFrame(function () { success.classList.add('is-shown'); }); }
            window.scrollTo({ top: form.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });
          } else {
            if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Submit'; }
            var errs = (res.body && res.body.errors) || {};
            var jumped = false;
            Object.keys(errs).forEach(function (k) {
              var field = form.querySelector('[name="' + k + '"]') || form.querySelector('[name="' + k + '[]"]');
              if (field) {
                setError(field, errs[k]);
                // jump back to the step containing the first error
                if (!jumped) {
                  var st = field.closest('.step');
                  if (st) { var idx = steps.indexOf(st); if (idx > -1) goTo(idx + 1); }
                  jumped = true;
                }
              }
            });
          }
        })
        .catch(function () {
          if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Submit'; }
          alert('Network error. Please call us directly at the number above.');
        });
    });

    goTo(1);
  });
})();
