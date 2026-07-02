<?php /* Section 9 — Free consultation CTA */
$ctaAreas = getPracticeAreas();
?>
<section class="section cta-band" aria-label="Free case evaluation">
  <div class="cta-band__bg" aria-hidden="true">
    <canvas class="cta-band__particles" data-particles></canvas>
  </div>
  <div class="container cta-band__inner">
    <div class="cta-band__copy animate-on-scroll">
      <h2>Your Case Evaluation Is Free. Your Future Matters.</h2>
      <p>California law may limit the time you have to file a claim. Act now &mdash; there is no cost and no obligation to speak with our team.</p>
      <p class="cta-band__call">
        OR CALL US:
        <a href="tel:<?= e(SITE_PHONE_RAW) ?>"><?= e(SITE_PHONE) ?></a>
      </p>
    </div>

    <form class="cta-form glass animate-on-scroll" data-anim="right" data-ajax-form action="/api/form-handler.php" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="homepage-cta">
      <!-- Honeypot -->
      <div class="hp-field" aria-hidden="true">
        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
      </div>

      <div class="cta-form__head">
        <h3 class="cta-form__title">Request a Free Evaluation</h3>
        <p class="cta-form__sub">Confidential &middot; No obligation &middot; We reply within 24 hours</p>
      </div>

      <div class="field">
        <label for="cta-name">Full Name</label>
        <input type="text" id="cta-name" name="name" required autocomplete="name" placeholder="Your name">
        <span class="field__error" aria-live="polite"></span>
      </div>
      <div class="field">
        <label for="cta-phone">Phone</label>
        <input type="tel" id="cta-phone" name="phone" required autocomplete="tel" placeholder="(000) 000-0000">
        <span class="field__error" aria-live="polite"></span>
      </div>
      <div class="field">
        <label for="cta-case">Case Type</label>
        <select id="cta-case" name="case_type">
          <option value="">Select a case type</option>
          <?php foreach ($ctaAreas as $ctaArea): ?>
            <option value="<?= e($ctaArea['title']) ?>"><?= e($ctaArea['title']) ?></option>
          <?php endforeach; ?>
          <option value="Other">Other</option>
        </select>
      </div>
      <button type="submit" class="btn btn--primary btn--block" data-ripple>Request Free Evaluation</button>

      <p class="cta-form__fineprint">By submitting, you agree to be contacted about your inquiry. Submitting this form does not create an attorney-client relationship.</p>

      <!-- Success state -->
      <div class="form-success" data-form-success hidden>
        <span class="form-success__check" aria-hidden="true">
          <svg viewBox="0 0 52 52"><circle class="fs-circle" cx="26" cy="26" r="24" fill="none"/><path class="fs-check" fill="none" d="M14 27l8 8 16-16"/></svg>
        </span>
        <p class="form-success__msg" data-form-success-msg>Thank you. Our team will contact you shortly.</p>
      </div>
    </form>
  </div>
</section>
