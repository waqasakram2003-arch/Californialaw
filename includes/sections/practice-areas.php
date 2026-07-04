<?php /* Section 4 — Practice areas grid */
$areas = getPracticeAreas();
?>
<section class="section" id="practice-areas" aria-label="Practice areas">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Our Practice Areas</p>
      <h2 class="has-underline">How We Can Help You</h2>
      <p class="lead">We handle all types of personal injury cases throughout California.</p>
    </div>

    <div class="pa-grid">
      <?php foreach ($areas as $i => $area): ?>
        <a class="pa-card animate-on-scroll" data-anim="<?= $i % 2 ? 'right' : 'left' ?>"
           href="/practice-areas/<?= e($area['slug']) ?>/">
          <?php if (!empty($area['image'])): ?>
            <span class="pa-card__media"><img src="<?= e(pa_card_image($area)) ?>" alt="" loading="lazy"></span>
          <?php endif; ?>
          <span class="pa-card__icon"><?= practice_icon($area['icon'] ?? '') ?></span>
          <h3 class="pa-card__title"><?= e($area['title']) ?></h3>
          <p class="pa-card__desc"><?= e($area['short_desc']) ?></p>
          <span class="pa-card__more">Learn More
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
