<?php
/**
 * mobile-cta-bar.php — fixed bottom action bar for phones.
 * Left: click-to-call. Right: gold "Free Case Review" button.
 * Visibility (1s entrance + hide-near-footer) is handled in phase10.js;
 * the bar is display:none above the mobile breakpoint via phase10.css.
 */
?>
<div class="mcta" data-mobile-cta hidden aria-label="Quick contact">
  <a class="mcta__call" href="tel:<?= e(cfg('site_phone_raw', SITE_PHONE_RAW)) ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    <span class="mcta__call-text">
      <span class="mcta__call-label">Call Now</span>
      <span class="mcta__call-num"><?= e(cfg('site_phone', SITE_PHONE)) ?></span>
    </span>
  </a>
  <a class="mcta__review btn btn--primary" href="/case-evaluation.php">Free Case Review</a>
</div>
