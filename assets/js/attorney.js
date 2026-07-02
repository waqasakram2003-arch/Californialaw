/* ===========================================================================
   attorney.js — attorney listing filter (by practice-area category).
   Profile-page accordion uses practice-area.js; the contact form uses home.js.
   =========================================================================== */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {
    var filter = document.querySelector('[data-filter]');
    var grid = document.querySelector('[data-filter-grid]');
    if (!filter || !grid) return;

    var buttons = Array.prototype.slice.call(filter.querySelectorAll('[data-filter-btn]'));
    var items = Array.prototype.slice.call(grid.querySelectorAll('[data-filter-item]'));
    var empty = grid.querySelector('[data-filter-empty]');

    function apply(cat) {
      var shown = 0;
      items.forEach(function (item) {
        var cats = (item.getAttribute('data-categories') || '').split(/\s+/);
        var match = cat === 'all' || cats.indexOf(cat) !== -1;
        item.classList.toggle('is-hidden', !match);
        if (match) {
          shown++;
          item.classList.remove('is-filtering');
          void item.offsetWidth; // restart the fade animation
          item.classList.add('is-filtering');
        }
      });
      if (empty) empty.hidden = shown !== 0;
    }

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        buttons.forEach(function (b) { b.classList.remove('is-active'); b.setAttribute('aria-selected', 'false'); });
        btn.classList.add('is-active');
        btn.setAttribute('aria-selected', 'true');
        apply(btn.getAttribute('data-filter-btn'));
      });
    });
  });
})();
