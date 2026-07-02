<?php
/**
 * contact.php — two-column contact page.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';

$caseTypes = getPracticeAreas();

$page = [
    'title'       => 'Contact Us',
    'description' => 'Contact Golden State Injury Lawyers for a free, confidential consultation about your '
                   . 'California personal injury claim. Available 24/7.',
    'path'        => '/contact.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/forms.css'],
    'scripts'     => ['/assets/js/home.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Contact', 'path' => '/contact.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Contact us">
  <div class="container pa-hero__inner">
    <p class="eyebrow">We&rsquo;re Here to Help</p>
    <h1 class="pa-hero__title">Contact Us</h1>
    <p class="pa-hero__subtext">Reach out any time for a free, confidential consultation. There is no cost and no obligation.</p>
  </div>
</section>

<section class="section">
  <div class="container container--wide">
    <div class="contact-layout">

      <!-- LEFT: info -->
      <div class="contact-info">
        <div class="section-head"><p class="eyebrow">Get in Touch</p><h2 class="has-underline">Speak With Our Team</h2></div>

        <ul class="contact-info__list">
          <li>
            <span class="contact-info__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
            <span><span class="contact-info__label">Phone</span><span class="contact-info__value"><a href="tel:<?= e(SITE_PHONE_RAW) ?>"><?= e(SITE_PHONE) ?></a></span></span>
          </li>
          <li>
            <span class="contact-info__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg></span>
            <span><span class="contact-info__label">Email</span><span class="contact-info__value"><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></span></span>
          </li>
          <li>
            <span class="contact-info__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
            <span><span class="contact-info__label">Office</span><span class="contact-info__value"><?= e(SITE_ADDRESS) ?></span></span>
          </li>
        </ul>

        <h3 style="font-size:var(--text-lg);margin-bottom:var(--space-3);">Office Hours</h3>
        <ul class="contact-hours">
          <li><span>Monday &ndash; Friday</span><span>Open 24 Hours</span></li>
          <li><span>Saturday &ndash; Sunday</span><span>By Appointment</span></li>
          <li><span>Phone Support</span><span>Available 24/7</span></li>
        </ul>

        <div class="contact-social" aria-label="Social media">
          <a href="#" aria-label="Facebook" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/></svg></a>
          <a href="#" aria-label="X" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-7.3 8.3L23 22h-6.8l-5.3-6.9L4.8 22H2l7.8-8.9L1.5 2h6.9l4.8 6.3L18.9 2z"/></svg></a>
          <a href="#" aria-label="LinkedIn" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6V21h-4v-5.3c0-1.3 0-2.9-1.8-2.9s-2 1.4-2 2.8V21H9z"/></svg></a>
        </div>

        <div class="contact-map">
          <iframe title="Map of our service area" src="https://www.google.com/maps?q=Los%20Angeles%2C%20CA&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>

      <!-- RIGHT: form -->
      <form class="contact-form card" data-ajax-form action="/api/contact-handler.php" method="post" novalidate>
        <h2 style="margin-bottom:var(--space-5);">Send Us a Message</h2>
        <?= csrf_field() ?>
        <input type="hidden" name="source" value="contact-page">
        <div class="hp-field" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

        <div class="contact-form__row">
          <div class="field"><label for="c-name">Full Name <span class="req">*</span></label><input type="text" id="c-name" name="name" required autocomplete="name" placeholder="Your name"><span class="field__error" aria-live="polite"></span></div>
          <div class="field"><label for="c-phone">Phone</label><input type="tel" id="c-phone" name="phone" autocomplete="tel" placeholder="(000) 000-0000"><span class="field__error" aria-live="polite"></span></div>
        </div>
        <div class="contact-form__row">
          <div class="field"><label for="c-email">Email <span class="req">*</span></label><input type="email" id="c-email" name="email" required autocomplete="email" placeholder="you@example.com"><span class="field__error" aria-live="polite"></span></div>
          <div class="field">
            <label for="c-case">Case Type</label>
            <select id="c-case" name="case_type"><option value="">Select a case type</option><?php foreach ($caseTypes as $ct): ?><option value="<?= e($ct['title']) ?>"><?= e($ct['title']) ?></option><?php endforeach; ?><option value="Other">Other</option></select>
          </div>
        </div>
        <div class="field"><label for="c-msg">Message <span class="req">*</span></label><textarea id="c-msg" name="message" rows="5" required placeholder="How can we help you?"></textarea><span class="field__error" aria-live="polite"></span></div>

        <label class="consent-check"><input type="checkbox" name="consent" value="1" required><span>I agree to be contacted about my inquiry. I understand this does not create an attorney-client relationship. <span class="req">*</span></span></label>
        <span class="field__error" aria-live="polite"></span>

        <button type="submit" class="btn btn--primary btn--block" style="margin-top:var(--space-5);" data-ripple>Send Message</button>
        <p class="cta-form__fineprint" style="color:var(--color-text-muted);">Your information is confidential. We typically respond within one business day.</p>

        <div class="form-success" data-form-success hidden>
          <span class="form-success__check" aria-hidden="true"><svg viewBox="0 0 52 52"><circle class="fs-circle" cx="26" cy="26" r="24" fill="none"/><path class="fs-check" fill="none" d="M14 27l8 8 16-16"/></svg></span>
          <p class="form-success__msg" data-form-success-msg>Thank you. We&rsquo;ll be in touch shortly.</p>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
