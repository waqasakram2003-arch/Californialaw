<?php
/**
 * results.php — case results / settlements.
 * COMPLIANCE: prominent "past results do not guarantee" disclaimer at the top
 * AND the bottom of the page (in addition to the site footer).
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';
require_once __DIR__ . '/includes/schema.php';
require_once __DIR__ . '/includes/pa-helpers.php';

$resultsDisclaimer = 'The case results shown are examples of prior results obtained by ' . SITE_NAME . '. '
    . 'Past results do not guarantee, warrant, or predict future case outcomes. Each case is unique and must be '
    . 'judged on its own merits. These results do not constitute a promise or guarantee of a similar result.';

/* Map each case type to a practice-area category for filtering. */
$catMap = [
    'Car Accident' => 'motor-vehicle', 'Truck Accident' => 'motor-vehicle', 'Motorcycle Accident' => 'motor-vehicle',
    'Pedestrian Accident' => 'motor-vehicle', 'Rideshare Accident' => 'motor-vehicle',
    'Slip & Fall' => 'premises', 'Dog Bite' => 'premises', 'Workplace Injury' => 'premises',
    'Wrongful Death' => 'catastrophic', 'Traumatic Brain Injury' => 'catastrophic',
];

$results = [];
try {
    $results = db()->query("SELECT case_type, result_amount, description, result_year FROM case_results WHERE display = 1 ORDER BY order_num, id")->fetchAll();
} catch (Throwable $e) {
    $results = getCaseResults();
}

$steps = [
    ['n' => '01', 'title' => 'Investigate', 'body' => 'We dig into how the injury happened — gathering evidence, records, and input from outside professionals to build a strong case.'],
    ['n' => '02', 'title' => 'Document', 'body' => 'We document the full extent of your injuries and losses, including future medical needs and lost earnings.'],
    ['n' => '03', 'title' => 'Negotiate', 'body' => 'We deal with the insurance companies and pursue fair compensation on your behalf.'],
    ['n' => '04', 'title' => 'Litigate', 'body' => 'When insurers will not treat you fairly, we are prepared to take your case to court.'],
];

$page = [
    'title'       => 'Case Results',
    'description' => 'Examples of prior case results obtained by Golden State Injury Lawyers for injured Californians. '
                   . 'Past results do not guarantee future outcomes.',
    'path'        => '/results.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css', '/assets/css/results.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/attorney.js'],
    'schema'      => array_filter([schemaReview(db()->query("SELECT rating FROM testimonials WHERE active=1 AND verified=1")->fetchAll())]),
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Results', 'path' => '/results.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<!-- 1. HERO -->
<section class="pa-hero pa-hero--index results-hero" aria-label="Case results">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Case Results</p>
    <h1 class="pa-hero__title">Results We&rsquo;ve Achieved for Our California Clients</h1>
    <p class="results-disclaimer results-disclaimer--top"><?= e($resultsDisclaimer) ?></p>

    <div class="results-counters">
      <div class="result-stat">
        <span class="result-stat__num" data-counter data-prefix="$" data-target="50" data-suffix="M+">$0M+</span>
        <span class="result-stat__label">Total Recovered</span>
      </div>
      <div class="result-stat">
        <span class="result-stat__num" data-counter data-target="1000" data-suffix="+">0+</span>
        <span class="result-stat__label">Cases Resolved</span>
      </div>
      <div class="result-stat">
        <span class="result-stat__num" data-counter data-target="500" data-suffix="+">0+</span>
        <span class="result-stat__label">Clients Helped</span>
      </div>
    </div>
  </div>
</section>

<!-- 2 + 3. FILTER + GRID -->
<section class="section">
  <div class="container container--wide">
    <div class="pa-filter" data-filter role="tablist" aria-label="Filter results by practice area">
      <?php foreach (pa_categories() as $key => $label): ?>
        <button class="pa-filter__btn<?= $key === 'all' ? ' is-active' : '' ?>" type="button" role="tab"
                aria-selected="<?= $key === 'all' ? 'true' : 'false' ?>" data-filter-btn="<?= e($key) ?>"><?= e($label) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="results-tiles" data-filter-grid>
      <?php foreach ($results as $r): $cat = $catMap[$r['case_type']] ?? ''; ?>
        <article class="result-tile" data-filter-item data-categories="<?= e($cat) ?>">
          <div class="result-tile__front">
            <span class="result-tile__badge"><?= e($r['case_type']) ?></span>
            <p class="result-tile__amount"><?= e($r['result_amount']) ?></p>
            <?php if (!empty($r['result_year'])): ?><p class="result-tile__year"><?= (int) $r['result_year'] ?></p><?php endif; ?>
            <?php if (!empty($r['description'])): ?><p class="result-tile__teaser"><?= e($r['description']) ?></p><?php endif; ?>
          </div>
          <div class="result-tile__reveal">
            <p class="result-tile__desc"><?= e($r['description']) ?></p>
            <span class="result-tile__note">Past results do not guarantee future outcomes.</span>
          </div>
        </article>
      <?php endforeach; ?>
      <p class="pa-grid__empty" data-filter-empty hidden>No results in this category.</p>
    </div>
  </div>
</section>

<!-- 4. PROCESS -->
<section class="section section--muted">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Our Approach</p><h2 class="has-underline">How We Achieve Results</h2></div>
    <div class="grid grid--4 stagger-children process-grid">
      <?php foreach ($steps as $s): ?>
        <div class="process-step">
          <span class="process-step__num"><?= e($s['n']) ?></span>
          <h3><?= e($s['title']) ?></h3>
          <p class="text-muted"><?= e($s['body']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 5. TESTIMONIALS -->
<?php require __DIR__ . '/includes/sections/testimonials.php'; ?>

<!-- Bottom disclaimer (required) -->
<section class="section results-disclaimer-band">
  <div class="container container--narrow">
    <p class="results-disclaimer"><?= e($resultsDisclaimer) ?></p>
  </div>
</section>

<!-- 6. CTA -->
<?php require __DIR__ . '/includes/sections/cta.php'; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
