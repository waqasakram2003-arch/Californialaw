<?php /* Section 3 — Trust bar */ ?>
<section class="trust-bar section--primary" aria-label="Professional affiliations">
  <div class="container container--wide">
    <p class="trust-bar__label animate-on-scroll">Members &amp; Affiliations</p>
    <ul class="trust-bar__row stagger-children" role="list">
      <?php
        $affiliations = [
            'State Bar of California',
            'American Bar Association',
            'Consumer Attorneys of California',
            'American Association for Justice',
            'Los Angeles County Bar',
        ];
        foreach ($affiliations as $aff):
      ?>
      <li class="trust-badge">
        <span class="trust-badge__seal" aria-hidden="true">
          <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6">
            <circle cx="24" cy="24" r="18"/>
            <circle cx="24" cy="24" r="13" stroke-dasharray="2 3"/>
            <path d="M24 16v16M18 22h12M19 28h10" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="trust-badge__name"><?= e($aff) ?></span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
