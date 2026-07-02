/* ===========================================================================
   admin.js — sidebar, AJAX reorder/toggle, char counters, slug, copy, expand.
   =========================================================================== */
(function () {
  'use strict';
  var CSRF = (document.querySelector('meta[name="csrf"]') || {}).content || '';

  function ready(fn) { if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn); else fn(); }
  function post(url, data) {
    var body = new FormData();
    body.append('csrf_token', CSRF);
    Object.keys(data).forEach(function (k) { body.append(k, data[k]); });
    return fetch(url, { method: 'POST', headers: { 'Accept': 'application/json' }, body: body }).then(function (r) { return r.json(); });
  }

  ready(function () {
    /* Sidebar (mobile) */
    var sb = document.querySelector('[data-sidebar]');
    var tg = document.querySelector('[data-sidebar-toggle]');
    if (sb && tg) tg.addEventListener('click', function () { sb.classList.toggle('is-open'); });

    /* Confirm before destructive submit/links */
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
      el.addEventListener('click', function (e) { if (!window.confirm(el.getAttribute('data-confirm'))) e.preventDefault(); });
    });
    document.querySelectorAll('form[data-confirm-submit]').forEach(function (f) {
      f.addEventListener('submit', function (e) { if (!window.confirm(f.getAttribute('data-confirm-submit'))) e.preventDefault(); });
    });

    /* Char counters */
    document.querySelectorAll('[data-charcount]').forEach(function (inp) {
      var out = document.querySelector(inp.getAttribute('data-charcount'));
      function upd() { if (out) out.textContent = inp.value.length + (inp.maxLength > 0 ? '/' + inp.maxLength : ''); }
      inp.addEventListener('input', upd); upd();
    });

    /* Auto-slug */
    document.querySelectorAll('[data-slug-source]').forEach(function (src) {
      var target = document.querySelector(src.getAttribute('data-slug-source'));
      if (!target) return;
      var touched = target.value.trim() !== '';
      target.addEventListener('input', function () { touched = true; });
      src.addEventListener('input', function () {
        if (touched) return;
        target.value = src.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
      });
    });

    /* AJAX toggle switches */
    document.querySelectorAll('[data-toggle-field]').forEach(function (input) {
      input.addEventListener('change', function () {
        post('/admin/api/toggle.php', {
          table: input.getAttribute('data-table'),
          id: input.getAttribute('data-id'),
          field: input.getAttribute('data-toggle-field'),
          value: input.checked ? 1 : 0
        }).then(function (j) {
          if (!j || !j.ok) { input.checked = !input.checked; alert((j && j.message) || 'Update failed.'); }
        }).catch(function () { input.checked = !input.checked; });
      });
    });

    /* Copy to clipboard */
    document.querySelectorAll('[data-copy]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var v = btn.getAttribute('data-copy');
        (navigator.clipboard ? navigator.clipboard.writeText(v) : Promise.reject()).then(function () {
          var t = btn.textContent; btn.textContent = 'Copied!'; setTimeout(function () { btn.textContent = t; }, 1500);
        }).catch(function () {
          var i = document.createElement('input'); i.value = v; document.body.appendChild(i); i.select();
          try { document.execCommand('copy'); } catch (e) {} document.body.removeChild(i);
        });
      });
    });

    /* Lead row expand */
    document.querySelectorAll('[data-lead-row]').forEach(function (row) {
      row.addEventListener('click', function (e) {
        if (e.target.closest('a, button, .switch, select, form')) return;
        var det = row.nextElementSibling;
        if (det && det.classList.contains('lead-detail')) det.hidden = !det.hidden;
      });
    });

    /* Drag-to-reorder (tables/lists with [data-reorder]) */
    document.querySelectorAll('[data-reorder]').forEach(function (container) {
      var table = container.getAttribute('data-reorder');
      var dragged = null;
      container.querySelectorAll('[data-id]').forEach(function (item) {
        item.setAttribute('draggable', 'true');
        item.addEventListener('dragstart', function () { dragged = item; item.classList.add('dragging'); });
        item.addEventListener('dragend', function () { item.classList.remove('dragging'); save(); });
        item.addEventListener('dragover', function (e) { e.preventDefault(); item.classList.add('drag-over'); });
        item.addEventListener('dragleave', function () { item.classList.remove('drag-over'); });
        item.addEventListener('drop', function (e) {
          e.preventDefault(); item.classList.remove('drag-over');
          if (dragged && dragged !== item) {
            var rect = item.getBoundingClientRect();
            var after = (e.clientY - rect.top) > rect.height / 2;
            item.parentNode.insertBefore(dragged, after ? item.nextSibling : item);
          }
        });
      });
      function save() {
        var ids = Array.prototype.map.call(container.querySelectorAll('[data-id]'), function (el) { return el.getAttribute('data-id'); });
        post('/admin/api/reorder.php', { table: table, ids: ids.join(',') }).then(function (j) {
          if (!j || !j.ok) alert((j && j.message) || 'Reorder failed.');
        }).catch(function () {});
      }
    });
  });
})();
