/* ===========================================================================
   faq.js — category tabs (sliding indicator) + live search filter.
   Accordion behavior is handled by practice-area.js.
   =========================================================================== */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {
    var list = document.querySelector('[data-faq-list]');
    if (!list) return;

    var items = Array.prototype.slice.call(list.querySelectorAll('.faq-item'));
    var empty = list.querySelector('[data-faq-empty]');
    var tabsWrap = document.querySelector('[data-faq-tabs]');
    var tabs = tabsWrap ? Array.prototype.slice.call(tabsWrap.querySelectorAll('[data-faq-tab]')) : [];
    var indicator = tabsWrap ? tabsWrap.querySelector('[data-faq-indicator]') : null;
    var searchInput = document.querySelector('[data-faq-search]');
    var clearBtn = document.querySelector('[data-faq-clear]');
    var activeCat = tabs.length ? tabs[0].getAttribute('data-faq-tab') : null;

    function moveIndicator(btn) {
      if (!indicator || !btn) return;
      indicator.style.left = btn.offsetLeft + 'px';
      indicator.style.width = btn.offsetWidth + 'px';
    }

    function showByCategory(cat) {
      items.forEach(function (it) { it.hidden = it.getAttribute('data-category') !== cat; });
      if (empty) empty.hidden = true;
    }

    function showBySearch(q) {
      var shown = 0;
      items.forEach(function (it) {
        var match = it.getAttribute('data-text').indexOf(q) !== -1;
        it.hidden = !match;
        if (match) shown++;
      });
      if (empty) empty.hidden = shown !== 0;
    }

    /* Tabs */
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) { t.classList.remove('is-active'); t.setAttribute('aria-selected', 'false'); });
        tab.classList.add('is-active'); tab.setAttribute('aria-selected', 'true');
        activeCat = tab.getAttribute('data-faq-tab');
        moveIndicator(tab);
        if (searchInput) searchInput.value = '';
        showByCategory(activeCat);
      });
    });

    /* Search */
    function applySearch() {
      var q = (searchInput.value || '').trim().toLowerCase();
      if (q.length === 0) { showByCategory(activeCat); return; }
      showBySearch(q);
    }
    if (searchInput) {
      var t = null;
      searchInput.addEventListener('input', function () { clearTimeout(t); t = setTimeout(applySearch, 120); });
    }
    if (clearBtn) clearBtn.addEventListener('click', function () { if (searchInput) searchInput.value = ''; showByCategory(activeCat); if (searchInput) searchInput.focus(); });

    /* Init */
    if (tabs.length) {
      // position indicator after layout settles
      requestAnimationFrame(function () { moveIndicator(tabs[0]); });
      window.addEventListener('resize', function () {
        var active = tabsWrap.querySelector('.faq-tab.is-active');
        moveIndicator(active);
      }, { passive: true });
      showByCategory(activeCat);
    }
  });
})();
