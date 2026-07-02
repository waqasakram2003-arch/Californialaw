<?php
/**
 * booking-widget.php — floating "Free Consult" widget shown on every page.
 * Included near the end of footer.php (session already started for CSRF).
 */
?>
<div class="booking" data-booking hidden aria-live="polite">
  <button class="booking__fab" type="button" data-booking-toggle aria-expanded="false" aria-controls="booking-panel">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    <span class="booking__fab-label">Free Consult</span>
  </button>

  <div class="booking__panel" id="booking-panel" data-booking-panel hidden>
    <div class="booking__head">
      <strong>Request a Free Consultation</strong>
      <button class="booking__close" type="button" data-booking-close aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
      </button>
    </div>

    <form class="booking__form" data-booking-form action="/api/form-handler.php" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="booking-widget">
      <div class="hp-field" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <div class="field"><label for="bk-name">Name</label><input type="text" id="bk-name" name="name" required placeholder="Your name"><span class="field__error" aria-live="polite"></span></div>
      <div class="field"><label for="bk-phone">Phone</label><input type="tel" id="bk-phone" name="phone" required placeholder="(000) 000-0000"><span class="field__error" aria-live="polite"></span></div>
      <div class="field"><label for="bk-time">Best time to call</label><select id="bk-time" name="best_time"><option value="">Anytime</option><option>Morning</option><option>Afternoon</option><option>Evening</option></select></div>

      <button type="submit" class="btn btn--primary btn--block">Request Call Back</button>
      <a class="booking__call" href="tel:<?= e(SITE_PHONE_RAW) ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        Or call now: <?= e(SITE_PHONE) ?>
      </a>

      <div class="booking__success" data-booking-success hidden>
        <span class="form-success__check" aria-hidden="true"><svg viewBox="0 0 52 52"><circle class="fs-circle" cx="26" cy="26" r="24" fill="none"/><path class="fs-check" fill="none" d="M14 27l8 8 16-16"/></svg></span>
        <p data-booking-success-msg>Thank you! We&rsquo;ll call you shortly.</p>
      </div>
    </form>
  </div>
</div>
