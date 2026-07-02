<?php /* Section 11 — Service area map (CSS/SVG California) */
$cities = [
    ['name' => 'Sacramento',    'x' => 132, 'y' => 166],
    ['name' => 'Oakland',       'x' => 96,  'y' => 184],
    ['name' => 'San Francisco', 'x' => 86,  'y' => 196],
    ['name' => 'San Jose',      'x' => 104, 'y' => 210],
    ['name' => 'Fresno',        'x' => 152, 'y' => 235],
    ['name' => 'Los Angeles',   'x' => 140, 'y' => 305],
    ['name' => 'San Diego',     'x' => 158, 'y' => 342],
];
?>
<section class="section service-area" aria-label="Service area">
  <div class="container container--wide service-area__inner">
    <div class="service-area__text animate-on-scroll">
      <p class="eyebrow">Statewide Representation</p>
      <h2 class="has-underline">Serving All of California</h2>
      <p class="lead">From the Bay Area to San Diego, our attorneys represent injured clients across the state.</p>
      <p class="service-area__note">We come to you. Serving all 58 California counties.</p>
      <a class="btn btn--primary btn--glow" href="/case-evaluation.php" data-ripple>Start Your Free Evaluation</a>
    </div>

    <div class="service-area__map animate-on-scroll" data-anim="right">
      <svg viewBox="0 0 280 380" role="img" aria-label="Map of California with major cities">
        <path class="ca-outline"
          d="M62 18 L150 14 L150 40 L172 150 L210 250 L236 300 L158 356 L118 322 L104 286 L70 250 L74 206 L52 150 L66 96 Z"
          fill="rgba(var(--color-accent-rgb),0.06)" stroke="var(--color-accent)" stroke-width="2" stroke-linejoin="round"/>
        <?php foreach ($cities as $i => $c): ?>
          <g class="ca-city" style="--d: <?= $i * 0.35 ?>s">
            <circle class="ca-city__pulse" cx="<?= (int)$c['x'] ?>" cy="<?= (int)$c['y'] ?>" r="5"/>
            <circle class="ca-city__dot" cx="<?= (int)$c['x'] ?>" cy="<?= (int)$c['y'] ?>" r="3.2"/>
            <text class="ca-city__label" x="<?= (int)$c['x'] + 8 ?>" y="<?= (int)$c['y'] + 3 ?>"><?= e($c['name']) ?></text>
          </g>
        <?php endforeach; ?>
      </svg>
    </div>
  </div>
</section>
