/* ===========================================================================
   practice-area.js — accordion (CA laws) + category filter (index page).
   Reveals/counters/forms are handled by animations.js + home.js.
   =========================================================================== */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {

    /* ---- Accordion ---- */
    document.querySelectorAll('[data-accordion]').forEach(function (acc) {
      var items = Array.prototype.slice.call(acc.querySelectorAll('.accordion__item'));

      function setPanel(item, open) {
        var panel = item.querySelector('.accordion__panel');
        var trigger = item.querySelector('.accordion__trigger');
        if (open) {
          item.classList.add('is-open');
          trigger.setAttribute('aria-expanded', 'true');
          panel.style.maxHeight = panel.scrollHeight + 'px';
        } else {
          item.classList.remove('is-open');
          trigger.setAttribute('aria-expanded', 'false');
          panel.style.maxHeight = '0px';
        }
      }

      items.forEach(function (item) {
        var trigger = item.querySelector('.accordion__trigger');
        // Initialize: open items get their height, others collapse.
        setPanel(item, item.classList.contains('is-open'));
        trigger.addEventListener('click', function () {
          var willOpen = !item.classList.contains('is-open');
          // Single-open accordion: close siblings.
          items.forEach(function (other) { if (other !== item) setPanel(other, false); });
          setPanel(item, willOpen);
        });
      });

      // Recompute open panel heights on resize (content reflow).
      window.addEventListener('resize', function () {
        items.forEach(function (item) {
          if (item.classList.contains('is-open')) {
            var panel = item.querySelector('.accordion__panel');
            panel.style.maxHeight = panel.scrollHeight + 'px';
          }
        });
      }, { passive: true });
    });

    /* ---- Category filter (index page) ---- */
    var filter = document.querySelector('[data-filter]');
    var grid = document.querySelector('[data-filter-grid]');
    if (filter && grid) {
      var buttons = Array.prototype.slice.call(filter.querySelectorAll('[data-filter-btn]'));
      var cards = Array.prototype.slice.call(grid.querySelectorAll('.pa-card'));
      var empty = grid.querySelector('[data-filter-empty]');

      function apply(cat) {
        var shown = 0;
        cards.forEach(function (card) {
          var match = cat === 'all' || card.getAttribute('data-category') === cat;
          card.classList.toggle('is-hidden', !match);
          if (match) {
            shown++;
            // retrigger the fade animation
            card.classList.remove('is-filtering');
            // force reflow so the animation restarts
            void card.offsetWidth;
            card.classList.add('is-filtering');
          }
        });
        if (empty) empty.hidden = shown !== 0;
      }

      buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          buttons.forEach(function (b) {
            b.classList.remove('is-active');
            b.setAttribute('aria-selected', 'false');
          });
          btn.classList.add('is-active');
          btn.setAttribute('aria-selected', 'true');
          apply(btn.getAttribute('data-filter-btn'));
        });
      });
    }
  });
})();
