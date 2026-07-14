</main><!-- /#main -->

<footer class="site-footer">
  <!-- Footer CTA band -->
  <div class="footer-cta">
    <div class="container container--wide footer-cta__inner">
      <div class="footer-cta__copy">
        <p class="footer-cta__eyebrow">Free, Confidential Consultation</p>
        <h2 class="footer-cta__title">Injured? Let&rsquo;s talk about your case.</h2>
        <p class="footer-cta__text">Free, confidential consultations. We&rsquo;ll listen to your situation and explain your options.</p>
      </div>
      <div class="footer-cta__actions">
        <a class="btn btn--primary btn--lg" href="/case-evaluation.php" data-ripple>Free Case Evaluation</a>
        <a class="btn btn--ghost-light btn--lg" href="tel:<?= e(cfg('site_phone_raw', SITE_PHONE_RAW)) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <?= e(cfg('site_phone', SITE_PHONE)) ?>
        </a>
      </div>
    </div>
  </div>

  <div class="container container--wide">
    <div class="footer-grid">

      <!-- Col 1: brand + social -->
      <div class="footer-col footer-col--brand">
        <!-- Footer is always dark, so always use the light-text (dark) logo — no theme swap. -->
        <img class="footer-logo" src="<?= asset_url('/assets/images/logo-dark.webp') ?>" width="478" height="150"
             alt="<?= e(cfg('firm_name', SITE_NAME)) ?>">
        <p class="footer-tagline">Fighting for California&rsquo;s Injured.<br>No Fee Unless We Win.</p>
        <?php
          // Only render social links that are actually configured (skip blanks / '#').
          $__socials = [
            'facebook' => ['label' => 'Facebook', 'svg' => '<path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/>', 'fill' => true],
            'x'        => ['label' => 'X (Twitter)', 'svg' => '<path d="M18.9 2H22l-7.3 8.3L23 22h-6.8l-5.3-6.9L4.8 22H2l7.8-8.9L1.5 2h6.9l4.8 6.3L18.9 2zm-1.2 18h1.9L7.1 3.9H5.1L17.7 20z"/>', 'fill' => true],
            'linkedin' => ['label' => 'LinkedIn', 'svg' => '<path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6V21h-4v-5.3c0-1.3 0-2.9-1.8-2.9s-2 1.4-2 2.8V21H9z"/>', 'fill' => true],
            'avvo'     => ['label' => 'Avvo', 'svg' => '<text x="12" y="17" text-anchor="middle" font-family="Georgia,serif" font-weight="700" font-size="15" fill="currentColor">A</text><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.6"/>', 'fill' => false],
            'yelp'     => ['label' => 'Yelp', 'svg' => '<path d="M11 3.2c0-.7-.6-1.2-1.3-1C8 2.7 6.4 3.4 5 4.4c-.6.4-.6 1.2-.1 1.7l4.4 4.3c.8.8 2.1.2 2.1-.9L11 3.2zM4.1 11.3c-.7-.2-1.4.3-1.4 1 0 1.7.4 3.4 1.1 4.9.3.6 1.1.7 1.6.2l2.9-2.9c.8-.8.3-2.1-.8-2.3l-3.4-.9zM10 14.7c-.8-.4-1.7.1-1.9 1L7.2 19c-.2.7.4 1.4 1.1 1.3 1.4-.3 2.7-.9 3.8-1.7.6-.4.5-1.3-.1-1.7L10 14.7zm5.6-1.1c-1-.4-2 .5-1.7 1.5l1 3.4c.2.7 1 .9 1.5.4 1.1-1 2-2.3 2.6-3.7.3-.6-.2-1.4-.9-1.4l-2.5-.2zm.3-2.9c1 .2 2-.7 1.7-1.7-.5-1.6-1.3-3-2.4-4.2-.5-.5-1.3-.3-1.6.3l-2 5.9c-.3 1 .7 2 1.7 1.6l2.6-1.9z"/>', 'fill' => true],
          ];
          $__any = false;
          foreach ($__socials as $__k => $__s) { if (($__v = cfg('social_' . $__k, '')) && $__v !== '#') { $__any = true; break; } }
        ?>
        <?php if ($__any): ?>
        <ul class="footer-social" role="list" aria-label="Social media">
          <?php foreach ($__socials as $__k => $__s): $__url = cfg('social_' . $__k, ''); if (!$__url || $__url === '#') continue; ?>
          <li><a href="<?= e($__url) ?>" aria-label="<?= e($__s['label']) ?>" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" <?= $__s['fill'] ? 'fill="currentColor"' : 'fill="none"' ?> aria-hidden="true"><?= $__s['svg'] ?></svg></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>

      <!-- Col 2: quick links -->
      <div class="footer-col">
        <h3>Quick Links</h3>
        <ul role="list">
          <li><a href="/about.php">About Us</a></li>
          <li><a href="/reviews.php">Client Reviews</a></li>
          <li><a href="/blog/">Blog</a></li>
          <li><a href="/faq.php">FAQ</a></li>
          <li><a href="/case-evaluation.php">Free Case Evaluation</a></li>
          <li><a href="/contact.php">Contact</a></li>
        </ul>
      </div>

      <!-- Col 3: practice areas -->
      <div class="footer-col">
        <h3>Practice Areas</h3>
        <ul role="list">
          <li><a href="/practice-areas/car-accidents/">Car Accidents</a></li>
          <li><a href="/practice-areas/truck-accidents/">Truck Accidents</a></li>
          <li><a href="/practice-areas/motorcycle-accidents/">Motorcycle Accidents</a></li>
          <li><a href="/practice-areas/slip-and-fall/">Slip &amp; Fall</a></li>
          <li><a href="/practice-areas/wrongful-death/">Wrongful Death</a></li>
          <li><a href="/practice-areas/">All Practice Areas</a></li>
        </ul>
      </div>

      <!-- Col 4: contact + map -->
      <div class="footer-col">
        <h3>Contact</h3>
        <ul class="footer-contact" role="list">
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            <a href="tel:<?= e(cfg('site_phone_raw', SITE_PHONE_RAW)) ?>"><?= e(cfg('site_phone', SITE_PHONE)) ?></a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
            <a href="mailto:<?= e(cfg('site_email', SITE_EMAIL)) ?>"><?= e(cfg('site_email', SITE_EMAIL)) ?></a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span><?= e(cfg('site_address', SITE_ADDRESS)) ?></span>
          </li>
        </ul>
        <a class="footer-directions" href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode(cfg('site_address', SITE_ADDRESS)) ?>" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
          Get Directions
        </a>
        <p class="footer-hours"><?= e(cfg('office_hours', 'Mon–Fri, 9:00am–5:00pm')) ?></p>
        <?php if ($calUrl = cfg('calendly_url', '')): ?>
        <a class="btn btn--primary btn--sm" style="margin-top:.75rem" href="<?= e($calUrl) ?>" target="_blank" rel="noopener" data-calendly>Schedule a Consultation</a>
        <?php endif; ?>
      </div>
    </div>

    <!-- CALIFORNIA BAR COMPLIANCE: required notices -->
    <div class="footer-disclaimer">
      <p class="advertising">&copy; <span data-year>2026</span> <?= e(cfg('firm_name', SITE_NAME)) ?>. All Rights Reserved. | Attorney Advertising.</p>
      <p><?= e(cfg('footer_disclaimer', 'This website is for informational purposes only and does not constitute legal advice. Viewing this website does not create an attorney-client relationship. Past results do not guarantee future outcomes. ' . SITE_NAME . ' is licensed to practice law in California only.')) ?></p>
    </div>

    <div class="footer-bottom">
      <span>Serving Sacramento, Placer, El Dorado &amp; Marin counties.</span>
      <span class="footer-bottom__links">
        <a href="/privacy-policy.php">Privacy Policy</a> &middot;
        <a href="/terms.php">Terms</a> &middot;
        <a href="/sitemap.xml">Sitemap</a> &middot;
        <a href="/disclaimer.php">Disclaimer</a>
      </span>
    </div>
  </div>
</footer>

<!-- Mobile sticky CTA bar (phone + free case review) — shown on small screens -->
<?php require __DIR__ . '/mobile-cta-bar.php'; ?>

<!-- Site JS — animations.js lazy-loads GSAP + ScrollTrigger from CDN itself,
     with an IntersectionObserver fallback if the CDN is unreachable. -->
<?php require __DIR__ . '/booking-widget.php'; ?>
<?php require __DIR__ . '/chatbot.php'; ?>

<!-- CCPA cookie consent banner (only renders when trackers are configured + undecided) -->
<?php analytics_banner(); ?>

<!-- Calendly popup — activates once calendly_url is set in Settings; links with [data-calendly] open the popup -->
<?php require __DIR__ . '/calendly.php'; ?>

<?php asset_scripts(array_merge(['/assets/js/theme.js', '/assets/js/main.js', '/assets/js/animations.js', '/assets/js/forms.js', '/assets/js/booking.js', '/assets/js/phase10.js', '/assets/js/chatbot.js'], $page['scripts'] ?? [])); ?>
</body>
</html>
