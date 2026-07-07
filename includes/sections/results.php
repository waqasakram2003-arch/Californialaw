<?php /* Section 6 — Case results / settlements */
$results = getCaseResults();
$stats = [
    ['prefix' => '$', 'target' => 50,  'suffix' => 'M+', 'label' => 'Recovered for Clients'],
    ['prefix' => '',  'target' => 800, 'suffix' => '+',  'label' => 'Cases Handled'],
    ['prefix' => '',  'target' => 25,  'suffix' => '+',  'label' => 'Years of Experience'],
    ['prefix' => '',  'target' => 500, 'suffix' => '+',  'label' => '5-Star Reviews'],
];
?>
<section class="section section--dark results" id="results" aria-label="Case results">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Case Results</p>
      <h2 class="has-underline">Results That Speak for Themselves</h2>
      <p class="results__disclaimer">Past results do not guarantee future outcomes. Each case is unique.</p>
    </div>

    <!-- Drag / touch carousel -->
    <div class="results-carousel" data-carousel>
      <div class="results-carousel__track" data-carousel-track>
        <?php foreach ($results as $r): ?>
          <article class="result-card">
            <p class="result-card__type"><?= e($r['case_type']) ?></p>
            <p class="result-card__amount"><?= e($r['result_amount']) ?></p>
            <p class="result-card__desc"><?= e($r['description']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="results-carousel__controls">
        <button type="button" class="carousel-btn" data-carousel-prev aria-label="Previous results">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button type="button" class="carousel-btn" data-carousel-next aria-label="Next results">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>

    <!-- Animated stat boxes -->
    <div class="results-stats stagger-children">
      <?php foreach ($stats as $s): ?>
        <div class="result-stat">
          <span class="result-stat__num" data-counter data-prefix="<?= e($s['prefix']) ?>" data-target="<?= (int)$s['target'] ?>" data-suffix="<?= e($s['suffix']) ?>"><?= e($s['prefix']) ?>0<?= e($s['suffix']) ?></span>
          <span class="result-stat__label"><?= e($s['label']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
