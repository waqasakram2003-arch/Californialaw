/* ===========================================================================
   blog.js — live search dropdown + share (copy link).
   =========================================================================== */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {

    /* ---- Live search ---- */
    var search = document.querySelector('[data-blog-search]');
    if (search) {
      var input = search.querySelector('[data-blog-search-input]');
      var results = search.querySelector('[data-blog-search-results]');
      var timer = null, lastQ = '';

      function hide() { if (results) { results.hidden = true; results.innerHTML = ''; } }
      function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }

      function render(items, q) {
        if (!items.length) { results.innerHTML = '<div class="empty">No articles found for &ldquo;' + esc(q) + '&rdquo;.</div>'; }
        else {
          results.innerHTML = items.map(function (it) {
            return '<a href="' + esc(it.url) + '"><strong>' + esc(it.title) + '</strong>' +
              (it.cat ? '<small>' + esc(it.cat) + '</small>' : '') + '</a>';
          }).join('');
        }
        results.hidden = false;
      }

      function run(q) {
        fetch('/api/blog-search.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (j) { if (input.value.trim() === q) render(j.results || [], q); })
          .catch(function () {});
      }

      input.addEventListener('input', function () {
        var q = input.value.trim();
        if (q === lastQ) return; lastQ = q;
        clearTimeout(timer);
        if (q.length < 2) { hide(); return; }
        timer = setTimeout(function () { run(q); }, 220);
      });
      input.addEventListener('focus', function () { if (input.value.trim().length >= 2 && results.innerHTML) results.hidden = false; });
      document.addEventListener('click', function (e) { if (!search.contains(e.target)) hide(); });
    }

    /* ---- Share: copy link ---- */
    document.querySelectorAll('[data-share]').forEach(function (share) {
      var btn = share.querySelector('[data-share-copy]');
      if (!btn) return;
      var url = share.getAttribute('data-share-url') || window.location.href;
      var label = btn.querySelector('[data-share-label]');
      var original = label ? label.textContent : '';

      function done() {
        btn.classList.add('is-copied');
        if (label) label.textContent = 'Link copied!';
        setTimeout(function () { btn.classList.remove('is-copied'); if (label) label.textContent = original; }, 2000);
      }
      btn.addEventListener('click', function () {
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(done).catch(fallback);
        } else { fallback(); }
      });
      function fallback() {
        var t = document.createElement('input');
        t.value = url; document.body.appendChild(t); t.select();
        try { document.execCommand('copy'); done(); } catch (e) {}
        document.body.removeChild(t);
      }
    });
  });
})();

/* R8.7 — Reading progress bar: fills as the user scrolls through the article. */
(function () {
  var fill = document.querySelector('[data-reading-progress]');
  var target = document.querySelector('[data-reading-target]');
  if (!fill || !target) return;
  var ticking = false;
  function update() {
    ticking = false;
    var rect = target.getBoundingClientRect();
    var total = target.offsetHeight - window.innerHeight;
    var scrolled = -rect.top;
    var pct = total > 0 ? Math.min(1, Math.max(0, scrolled / total)) : 0;
    fill.style.width = (pct * 100).toFixed(2) + '%';
  }
  function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(update); } }
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
  update();
})();
