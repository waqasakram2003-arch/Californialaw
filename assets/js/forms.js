/* ===========================================================================
   forms.js — Progressive enhancement for contact / case-evaluation forms.
   Client validation is convenience only; api/form-handler.php re-validates
   and is the source of truth.
   =========================================================================== */
(function () {
  'use strict';

  function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

  function setError(field, msg) {
    var wrap = field.closest('.field');
    if (!wrap) return;
    wrap.classList.toggle('field--error', !!msg);
    var note = wrap.querySelector('.field__error');
    if (note) note.textContent = msg || '';
    field.setAttribute('aria-invalid', msg ? 'true' : 'false');
  }

  function validateField(field) {
    var val = (field.value || '').trim();
    if (field.required && !val) { setError(field, 'This field is required.'); return false; }
    if (field.type === 'email' && val && !isEmail(val)) { setError(field, 'Enter a valid email address.'); return false; }
    if (field.type === 'tel' && val && val.replace(/\D/g, '').length < 7) { setError(field, 'Enter a valid phone number.'); return false; }
    setError(field, '');
    return true;
  }

  function initForm(form) {
    var fields = form.querySelectorAll('input, textarea, select');

    fields.forEach(function (f) {
      f.addEventListener('blur', function () { validateField(f); });
      f.addEventListener('input', function () {
        if (f.getAttribute('aria-invalid') === 'true') validateField(f);
      });
    });

    form.addEventListener('submit', function (e) {
      var ok = true;
      fields.forEach(function (f) { if (!validateField(f)) ok = false; });
      // Honeypot: if filled, silently drop.
      var hp = form.querySelector('[name="website"]');
      if (hp && hp.value) { e.preventDefault(); return; }
      if (!ok) {
        e.preventDefault();
        var firstErr = form.querySelector('.field--error input, .field--error textarea, .field--error select');
        if (firstErr) firstErr.focus();
        return;
      }
      var btn = form.querySelector('[type="submit"]');
      if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Sending…'; }
    });
  }

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {
    document.querySelectorAll('form[data-validate]').forEach(initForm);
  });
})();
