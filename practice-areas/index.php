<?php
/**
 * practice-areas/index.php — Practice Areas listing with category filter.
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/pa-helpers.php';

// Fetch areas with details so we can read each one's category.
$areas = [];
try {
    $rows = db()->query(
        'SELECT title, slug, icon, image, short_desc, details FROM practice_areas
         WHERE active = 1 ORDER BY order_num, id'
    )->fetchAll();
    foreach ($rows as $r) {
        $r['category'] = '';
        if (!empty($r['details'])) {
            $dec = json_decode($r['details'], true);
            $r['category'] = $dec['category'] ?? '';
        }
        $areas[] = $r;
    }
} catch (Throwable $e) {
    // Fallback to basic list (no categories) if DB is unavailable.
    foreach (getPracticeAreas() as $r) {
        $r['category'] = '';
        $areas[] = $r;
    }
}

$page = [
    'title'       => 'Practice Areas',
    'description' => 'Mason Law, P.C. handles all types of California personal injury cases — '
                   . 'car and truck accidents, slip and fall, wrongful death, brain injuries, and more. Free case evaluation.',
    'path'        => '/practice-areas/',
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/practice-area.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Practice Areas', 'path' => '/practice-areas/'],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<!-- Hero -->
<section class="pa-hero pa-hero--index" aria-label="Practice areas">
  <div class="container pa-hero__inner">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a> <span aria-hidden="true">/</span></li>
        <li><span aria-current="page">Practice Areas</span></li>
      </ol>
    </nav>
    <p class="eyebrow" data-hero-item>How We Can Help</p>
    <h1 class="pa-hero__title" data-hero-item>California Personal Injury Practice Areas</h1>
    <p class="pa-hero__subtext" data-hero-item>We handle all types of personal injury cases throughout California. No upfront fees &mdash; we only get paid when you win.</p>
  </div>
</section>

<!-- Listing + filter -->
<section class="section">
  <div class="container container--wide">
    <div class="pa-filter" data-filter role="tablist" aria-label="Filter practice areas">
      <?php foreach (pa_categories() as $key => $label): ?>
        <button class="pa-filter__btn<?= $key === 'all' ? ' is-active' : '' ?>" type="button"
                role="tab" aria-selected="<?= $key === 'all' ? 'true' : 'false' ?>"
                data-filter-btn="<?= e($key) ?>"><?= e($label) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="pa-grid" data-filter-grid>
      <?php foreach ($areas as $area): ?>
        <a class="pa-card" href="/practice-areas/<?= e($area['slug']) ?>/" data-category="<?= e($area['category']) ?>">
          <?php if (!empty($area['image'])): ?>
            <span class="pa-card__media"><img src="<?= e(pa_card_image($area)) ?>" alt="" loading="lazy"></span>
          <?php endif; ?>
          <span class="pa-card__icon"><?= practice_icon($area['icon'] ?? '') ?></span>
          <h2 class="pa-card__title"><?= e($area['title']) ?></h2>
          <p class="pa-card__desc"><?= e($area['short_desc']) ?></p>
          <span class="pa-card__more">Learn More
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </span>
        </a>
      <?php endforeach; ?>
      <p class="pa-grid__empty" data-filter-empty hidden>No practice areas in this category.</p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/sections/cta.php'; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
