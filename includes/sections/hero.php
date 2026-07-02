<?php /* Section 2 — Hero */ ?>
<section class="hero" data-hero aria-label="Introduction">
  <!-- Animated gradient mesh + Ken Burns layer -->
  <div class="hero__bg" aria-hidden="true">
    <div class="hero__mesh"></div>
    <div class="hero__kenburns"></div>
    <canvas class="hero__particles" data-particles aria-hidden="true"></canvas>
    <!-- Abstract scales line-art, right side -->
    <svg class="hero__scales" viewBox="0 0 200 240" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
      <circle cx="100" cy="34" r="5"/>
      <line x1="100" y1="39" x2="100" y2="196"/>
      <line x1="40" y1="60" x2="160" y2="60"/>
      <line x1="70" y1="196" x2="130" y2="196"/>
      <path d="M40 60 L22 110 a18 18 0 0 0 36 0 Z"/>
      <path d="M160 60 L142 110 a18 18 0 0 0 36 0 Z"/>
      <line x1="40" y1="60" x2="40" y2="70"/>
      <line x1="160" y1="60" x2="160" y2="70"/>
    </svg>
  </div>

  <div class="container container--wide hero__inner">
    <div class="hero__content">
      <p class="hero__eyebrow" data-hero-item>California Personal Injury Attorneys</p>

      <h1 class="hero__title">
        <span class="hero__line">
          <span class="hero__word" data-hero-item>When</span>
          <span class="hero__word" data-hero-item>You&rsquo;re</span>
          <span class="hero__word" data-hero-item>Injured,</span>
        </span>
        <span class="hero__line hero__line--accent">
          <span class="hero__word" data-hero-item>We</span>
          <span class="hero__word" data-hero-item>Fight</span>
          <span class="hero__word" data-hero-item>Back.</span>
        </span>
      </h1>

      <p class="hero__subtext" data-hero-item>
        Serving all of California. No upfront fees. We only get paid when you win.
      </p>

      <div class="hero__cta" data-hero-item>
        <a class="btn btn--primary btn--lg glow-pulse" href="/case-evaluation.php" data-magnetic="0.3" data-ripple>
          Get Free Case Evaluation
        </a>
        <a class="btn btn--on-primary btn--lg" href="tel:<?= e(SITE_PHONE_RAW) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          Call Now: <?= e(SITE_PHONE) ?>
        </a>
      </div>

      <ul class="hero__badges" data-hero-item role="list">
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> No Win, No Fee</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Free Consultations</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> Available 24/7</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 5h12M9 3v2M6 17l4-9 4 9M5 13h8"/><path d="M14 14c2 3 4 4 6 4M21 9c-1 4-3 6-6 7"/></svg> Hablamos Español</li>
      </ul>
    </div>

    <!-- Right: floating glass results card -->
    <aside class="hero__card glass float" data-hero-item aria-label="Results we have achieved">
      <p class="hero__card-title">Results We&rsquo;ve Achieved</p>
      <ul class="hero__stats" role="list">
        <li>
          <span class="hero__stat-num" data-counter data-prefix="$" data-target="50" data-suffix="M+">$0M+</span>
          <span class="hero__stat-label">Recovered for Clients</span>
        </li>
        <li>
          <span class="hero__stat-num" data-counter data-target="1000" data-suffix="+">0+</span>
          <span class="hero__stat-label">Cases Handled</span>
        </li>
        <li>
          <span class="hero__stat-num" data-counter data-target="25" data-suffix="+">0+</span>
          <span class="hero__stat-label">Years of Experience</span>
        </li>
      </ul>
      <p class="hero__card-note">Past results do not guarantee future outcomes.</p>
    </aside>
  </div>

  <a class="hero__scroll" href="#practice-areas" aria-label="Scroll to content">
    <span></span>
  </a>
</section>
