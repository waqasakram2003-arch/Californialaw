/* ===========================================================================
   about.js — timeline line draw + flip-card tap toggle.
   Particles/reveals/form are handled by home.js + animations.js.
   =========================================================================== */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  ready(function () {

    /* ---- Timeline: SVG stroke-dasharray draw when in view ---- */
    document.querySelectorAll('[data-timeline]').forEach(function (timeline) {
      var line = timeline.querySelector('.timeline__line');
      if (!line) return;
      var len;
      try { len = line.getTotalLength(); } catch (e) { len = 1000; }

      if (reduceMotion) { return; } // leave the line fully drawn

      // Prime the draw (no-JS leaves the line visible; this only runs with JS).
      line.style.strokeDasharray = len;
      line.style.strokeDashoffset = len;

      function draw() { line.style.strokeDashoffset = '0'; }

      if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries, obs) {
          entries.forEach(function (en) {
            if (en.isIntersecting) { draw(); obs.disconnect(); }
          });
        }, { threshold: 0.3 });
        io.observe(timeline);
        // Safety net (rAF/IO throttled): draw after a short delay regardless.
        setTimeout(draw, 1500);
      } else {
        draw();
      }
    });

    /* ---- Flip cards: tap / keyboard toggle (hover handled by CSS) ---- */
    document.querySelectorAll('[data-flip]').forEach(function (card) {
      card.addEventListener('click', function () { card.classList.toggle('is-flipped'); });
      card.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          card.classList.toggle('is-flipped');
        }
      });
    });
  });
})();
