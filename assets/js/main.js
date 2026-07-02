/* ===========================================================================
   main.js — Core UI behaviors: mobile nav, header state, smooth in-page nav.
   =========================================================================== */
(function () {
  'use strict';

  // Signal to CSS that JS is active (reveal fallbacks gate on html.js).
  document.documentElement.classList.add('js');

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else { fn(); }
  }

  ready(function () {
    /* ---- Mobile nav toggle + scrim ---- */
    var navToggle = document.querySelector('[data-nav-toggle]');
    var nav = document.querySelector('[data-nav]');
    var scrim = document.querySelector('[data-nav-scrim]');

    function closeNav() {
      if (!nav) return;
      nav.classList.remove('is-open');
      if (navToggle) navToggle.setAttribute('aria-expanded', 'false');
      if (scrim) { scrim.classList.remove('is-active'); scrim.hidden = true; }
      document.body.style.overflow = '';
    }
    function openNav() {
      nav.classList.add('is-open');
      if (navToggle) navToggle.setAttribute('aria-expanded', 'true');
      if (scrim) { scrim.hidden = false; requestAnimationFrame(function () { scrim.classList.add('is-active'); }); }
      document.body.style.overflow = 'hidden';
    }
    if (navToggle && nav) {
      navToggle.addEventListener('click', function () {
        if (nav.classList.contains('is-open')) closeNav(); else openNav();
      });
      // Close on real link click (not the mega trigger button).
      nav.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', closeNav);
      });
    }
    if (scrim) scrim.addEventListener('click', closeNav);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeNav();
        document.querySelectorAll('.has-mega.is-open').forEach(function (m) { m.classList.remove('is-open'); });
      }
    });

    /* ---- Mega dropdown: click/keyboard toggle (hover handled by CSS) ---- */
    document.querySelectorAll('[data-mega] .main-nav__trigger').forEach(function (trigger) {
      var item = trigger.closest('[data-mega]');
      trigger.addEventListener('click', function () {
        var open = item.classList.toggle('is-open');
        trigger.setAttribute('aria-expanded', String(open));
      });
    });
    // Close any open mega menu when clicking outside it.
    document.addEventListener('click', function (e) {
      if (!e.target.closest('[data-mega]')) {
        document.querySelectorAll('[data-mega].is-open').forEach(function (m) {
          m.classList.remove('is-open');
          var t = m.querySelector('.main-nav__trigger');
          if (t) t.setAttribute('aria-expanded', 'false');
        });
      }
    });

    /* ---- Header elevation on scroll ---- */
    var header = document.querySelector('.site-header');
    if (header) {
      var onScroll = function () {
        header.classList.toggle('is-scrolled', window.scrollY > 12);
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }

    /* ---- Current year in footer ---- */
    document.querySelectorAll('[data-year]').forEach(function (el) {
      el.textContent = String(new Date().getFullYear());
    });
  });
})();
