<?php /* Section 8 — Attorney profiles preview */
$attorneys = getAttorneys(4);
?>
<section class="section section--muted attorneys" aria-label="Our attorneys">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Our Team</p>
      <h2 class="has-underline">Meet Your Legal Team</h2>
      <p class="lead">Experienced California personal injury attorneys dedicated to your recovery.</p>
    </div>

    <div class="attorney-grid stagger-children">
      <?php foreach ($attorneys as $a): ?>
        <article class="attorney-card">
          <div class="attorney-card__photo" aria-hidden="true">
            <?php if (!empty($a['image'])): ?>
              <img src="<?= e($a['image']) ?>" alt="" loading="lazy">
            <?php else: ?>
              <span class="attorney-card__initials"><?= e(initials($a['name'])) ?></span>
            <?php endif; ?>
          </div>
          <h3 class="attorney-card__name"><?= e($a['name']) ?></h3>
          <p class="attorney-card__title"><?= e($a['title']) ?></p>
          <p class="attorney-card__specialty"><?= e($a['bio']) ?></p>
          <p class="attorney-card__bar"><?= e($a['bar_number']) ?></p>
          <a class="btn btn--ghost btn--sm" href="/attorney/<?= e($a['slug']) ?>/">View Profile</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
