/* ===========================================================================
   theme.js — Golden State Injury Lawyers
   Dual-theme controller: localStorage + prefers-color-scheme, no-flash boot,
   smooth toggle, logo swap, and a 'themeChanged' custom event.

   NOTE: the inline boot snippet in header.php sets data-theme BEFORE first
   paint to prevent FOUC. This module wires up toggling and persistence.
   =========================================================================== */
(function (global) {
  'use strict';

  var STORAGE_KEY = 'gsil-theme';
  var root = document.documentElement;

  function stored() {
    try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; }
  }
  function systemPrefersDark() {
    return global.matchMedia && global.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  function current() {
    return root.getAttribute('data-theme') || stored() || (systemPrefersDark() ? 'dark' : 'light');
  }

  /** Swap any logo image tagged [data-logo] between light/dark sources. */
  function updateLogos(theme) {
    document.querySelectorAll('[data-logo]').forEach(function (img) {
      var light = img.getAttribute('data-logo-light');
      var dark  = img.getAttribute('data-logo-dark');
      var next  = theme === 'dark' ? dark : light;
      if (next && img.getAttribute('src') !== next) img.setAttribute('src', next);
    });
  }

  function syncToggles(theme) {
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', String(theme === 'dark'));
      btn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
    });
  }

  function apply(theme, persist) {
    root.setAttribute('data-theme', theme);

    // Briefly suppress transitions so the switch doesn't smear.
    root.classList.add('no-transitions');
    global.requestAnimationFrame(function () {
      global.requestAnimationFrame(function () { root.classList.remove('no-transitions'); });
    });

    updateLogos(theme);
    syncToggles(theme);

    if (persist) {
      try { localStorage.setItem(STORAGE_KEY, theme); } catch (e) {}
    }

    // Let other scripts react (e.g. particle canvas recolor).
    global.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));
  }

  function toggle() {
    apply(current() === 'dark' ? 'light' : 'dark', true);
  }

  function init() {
    apply(current(), false);
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.addEventListener('click', toggle);
    });
    // Follow OS changes only when the user has NOT made an explicit choice.
    if (global.matchMedia) {
      global.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
        if (!stored()) apply(e.matches ? 'dark' : 'light', false);
      });
    }
  }

  // Public API.
  global.GSILTheme = { apply: apply, toggle: toggle, current: current };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else { init(); }
})(window);
