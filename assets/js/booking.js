/* ===========================================================================
   booking.js — floating "Free Consult" widget (site-wide).
   Springy entrance after 3s, slide-up panel, dismissable, AJAX call-back form.
   =========================================================================== */
(function () {
  'use strict';

  var DISMISS_KEY = 'gsil-booking-dismissed';

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  function dismissedRecently() {
    try {
      var t = parseInt(localStorage.getItem(DISMISS_KEY), 10);
      return t && (Date.now() - t) < 24 * 60 * 60 * 1000; // 24h
    } catch (e) { return false; }
  }

  ready(function () {
    var widget = document.querySelector('[data-booking]');
    if (!widget) return;

    var fab = widget.querySelector('[data-booking-toggle]');
    var panel = widget.querySelector('[data-booking-panel]');
    var closeBtn = widget.querySelector('[data-booking-close]');
    var form = widget.querySelector('[data-booking-form]');

    /* ---- Entrance after 3s ---- */
    function reveal() {
      widget.hidden = false;
      requestAnimationFrame(function () { widget.classList.add('is-in'); });
    }
    if (!dismissedRecently()) {
      setTimeout(reveal, 3000);
    } else {
      // Still allow it, just without the auto-pop emphasis.
      setTimeout(reveal, 3000);
    }

    /* ---- Panel open/close ---- */
    function openPanel() {
      panel.hidden = false;
      requestAnimationFrame(function () { widget.classList.add('is-open'); });
      fab.setAttribute('aria-expanded', 'true');
      var first = panel.querySelector('input, select');
      if (first) first.focus({ preventScroll: true });
    }
    function closePanel() {
      widget.classList.remove('is-open');
      fab.setAttribute('aria-expanded', 'false');
      try { localStorage.setItem(DISMISS_KEY, String(Date.now())); } catch (e) {}
      setTimeout(function () { panel.hidden = true; }, 300);
    }
    fab.addEventListener('click', function () {
      if (widget.classList.contains('is-open')) closePanel(); else openPanel();
    });
    if (closeBtn) closeBtn.addEventListener('click', closePanel);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && widget.classList.contains('is-open')) closePanel();
    });

    /* ---- Phone mask ---- */
    var phone = form && form.querySelector('[name="phone"]');
    if (phone) {
      phone.addEventListener('input', function () {
        var d = phone.value.replace(/\D/g, '').slice(0, 10), out = d;
        if (d.length > 6) out = '(' + d.slice(0, 3) + ') ' + d.slice(3, 6) + '-' + d.slice(6);
        else if (d.length > 3) out = '(' + d.slice(0, 3) + ') ' + d.slice(3);
        else if (d.length > 0) out = '(' + d;
        phone.value = out;
      });
    }

    /* ---- Form submit ---- */
    if (form) {
      function setError(input, msg) {
        var f = input.closest('.field'); if (!f) return;
        f.classList.toggle('field--error', !!msg);
        var note = f.querySelector('.field__error'); if (note) note.textContent = msg || '';
      }
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var hp = form.querySelector('[name="website"]');
        if (hp && hp.value) return;
        var name = form.querySelector('[name="name"]');
        var ok = true;
        if (name.value.trim().length < 2) { setError(name, 'Enter your name.'); ok = false; } else setError(name, '');
        if (phone.value.replace(/\D/g, '').length < 7) { setError(phone, 'Enter a valid phone.'); ok = false; } else setError(phone, '');
        if (!ok) return;

        var btn = form.querySelector('[type="submit"]');
        if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Sending…'; }

        fetch(form.getAttribute('action'), { method: 'POST', headers: { 'Accept': 'application/json' }, body: new FormData(form) })
          .then(function (r) { return r.json(); })
          .then(function (j) {
            if (j && j.ok) {
              var box = form.querySelector('[data-booking-success]');
              var msg = form.querySelector('[data-booking-success-msg]');
              if (msg && j.message) msg.textContent = j.message;
              if (box) { box.hidden = false; requestAnimationFrame(function () { box.classList.add('is-shown'); }); }
            } else {
              if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Submit'; }
              var errs = (j && j.errors) || {};
              Object.keys(errs).forEach(function (k) { var inp = form.querySelector('[name="' + k + '"]'); if (inp) setError(inp, errs[k]); });
            }
          })
          .catch(function () {
            if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Submit'; }
          });
      });
    }
  });
})();
