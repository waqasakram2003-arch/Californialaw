/* ===========================================================================
   phase10.js — mobile sticky CTA bar + CCPA cookie consent
   Vanilla JS, no dependencies. Safe to defer.
   =========================================================================== */
(function () {
  'use strict';

  /* -----------------------------------------------------------------------
     Mobile sticky CTA bar: appear after 1s, hide while the footer is visible.
     ----------------------------------------------------------------------- */
  function initMobileCta() {
    var bar = document.querySelector('[data-mobile-cta]');
    if (!bar) return;

    bar.hidden = false;            // CSS keeps it off-screen until .is-visible
    var nearFooter = false;
    var entered = false;

    // Entrance after a 1s delay.
    window.setTimeout(function () {
      entered = true;
      if (!nearFooter) bar.classList.add('is-visible');
    }, 1000);

    // Hide when the footer scrolls into view so it never covers contact info.
    var footer = document.querySelector('.site-footer');
    if (footer && 'IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        nearFooter = entries[0].isIntersecting;
        if (!entered) return;
        bar.classList.toggle('is-visible', !nearFooter);
        bar.classList.toggle('is-hidden', nearFooter);
      }, { rootMargin: '0px 0px -10px 0px' });
      io.observe(footer);
    }
  }

  /* -----------------------------------------------------------------------
     Floating "Free Consult" widget: hide it while the footer is in view so it
     never covers the footer CTA. (On mobile it's hidden entirely via CSS.)
     ----------------------------------------------------------------------- */
  function initBookingFooterHide() {
    var widget = document.querySelector('[data-booking]');
    var footer = document.querySelector('.site-footer');
    if (!widget || !footer || !('IntersectionObserver' in window)) return;
    var io = new IntersectionObserver(function (entries) {
      widget.classList.toggle('is-near-footer', entries[0].isIntersecting);
    }, { rootMargin: '0px 0px -10px 0px' });
    io.observe(footer);
  }

  /* -----------------------------------------------------------------------
     Cookie consent (CCPA). Banner only exists in the DOM when undecided and
     trackers are configured. Accept/Decline writes a 1-year cookie; Accept
     reloads so the server can inject the (now-consented) analytics scripts.
     ----------------------------------------------------------------------- */
  function setConsent(value) {
    var oneYear = 60 * 60 * 24 * 365;
    var secure = location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = 'gsil_consent=' + value + '; Max-Age=' + oneYear +
      '; Path=/; SameSite=Lax' + secure;
  }

  function initCookieBanner() {
    var banner = document.querySelector('[data-cookie-banner]');
    if (!banner) return;

    banner.hidden = false;
    // Next frame so the opacity transition runs.
    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(function () { banner.classList.add('is-visible'); });
    });

    function close() {
      banner.classList.remove('is-visible');
      window.setTimeout(function () { banner.hidden = true; }, 320);
    }

    var accept = banner.querySelector('[data-cookie-accept]');
    var decline = banner.querySelector('[data-cookie-decline]');
    if (accept) accept.addEventListener('click', function () {
      setConsent('accept');
      close();
      // Reload so analytics_head() runs server-side with consent.
      window.setTimeout(function () { location.reload(); }, 200);
    });
    if (decline) decline.addEventListener('click', function () {
      setConsent('decline');
      close();
    });
  }

  function init() {
    initMobileCta();
    initBookingFooterHide();
    initCookieBanner();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
