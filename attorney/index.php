<?php
/**
 * attorney/index.php — Meet the team (listing + practice-area filter).
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/attorney-helpers.php';
require_once __DIR__ . '/../includes/pa-helpers.php';

$attorneys = getAttorneysWithDetails();

// Map practice-area title -> category, to filter attorneys by category.
$catMap = [];
try {
    foreach (db()->query('SELECT title, details FROM practice_areas')->fetchAll() as $r) {
        $cat = '';
        if (!empty($r['details'])) {
            $dec = json_decode($r['details'], true);
            $cat = $dec['category'] ?? '';
        }
        if ($cat !== '') {
            $catMap[$r['title']] = $cat;
        }
    }
} catch (Throwable $e) { /* fallback: no categories */ }

/** Derive an attorney's distinct categories from their practice labels. */
function attorney_categories(array $details, array $catMap): array
{
    $cats = [];
    foreach ($details['practices'] ?? [] as $label) {
        if (isset($catMap[$label])) {
            $cats[$catMap[$label]] = true;
        }
    }
    return array_keys($cats);
}

$page = [
    'title'       => 'Our Attorneys',
    'description' => 'Meet the attorneys of Mason Law, P.C. — an experienced California personal '
                   . 'injury team serving injured clients throughout the state.',
    'path'        => '/attorney/',
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css', '/assets/css/attorney.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/attorney.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Our Team', 'path' => '/attorney/'],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Our attorneys">
  <div class="container pa-hero__inner">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <ol><li><a href="/">Home</a> <span aria-hidden="true">/</span></li><li><span aria-current="page">Our Team</span></li></ol>
    </nav>
    <p class="eyebrow" data-hero-item>Our Team</p>
    <h1 class="pa-hero__title" data-hero-item>Meet Your Legal Team</h1>
    <p class="pa-hero__subtext" data-hero-item>Experienced California personal injury attorneys dedicated to your recovery. Get to know the people who will fight for you.</p>
  </div>
</section>

<section class="section">
  <div class="container container--wide">
    <div class="pa-filter" data-filter role="tablist" aria-label="Filter attorneys by practice area">
      <?php foreach (pa_categories() as $key => $label): ?>
        <button class="pa-filter__btn<?= $key === 'all' ? ' is-active' : '' ?>" type="button"
                role="tab" aria-selected="<?= $key === 'all' ? 'true' : 'false' ?>"
                data-filter-btn="<?= e($key) ?>"><?= e($label) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="attorney-grid-list" data-filter-grid>
      <?php foreach ($attorneys as $a):
        $det  = is_array($a['details'] ?? null) ? $a['details'] : [];
        $cats = attorney_categories($det, $catMap);
      ?>
        <div class="att-list-card" data-filter-item data-categories="<?= e(implode(' ', $cats)) ?>">
          <div class="att-list-card__photo" aria-hidden="true">
            <?php if (!empty($a['image'])): ?><img src="<?= e($a['image']) ?>" alt=""><?php else: ?><span class="att-list-card__initials"><?= e(initials($a['name'])) ?></span><?php endif; ?>
          </div>
          <h2 class="att-list-card__name"><?= e($a['name']) ?></h2>
          <p class="att-list-card__title"><?= e($a['title']) ?></p>
          <p class="att-list-card__bio"><?= e($a['bio']) ?></p>
          <span class="att-list-card__bar"><?= e($a['bar_number']) ?></span>
          <a class="btn btn--ghost btn--sm" href="/attorney/<?= e($a['slug']) ?>/">View Profile</a>
        </div>
      <?php endforeach; ?>
      <p class="pa-grid__empty" data-filter-empty hidden>No attorneys match this practice area.</p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/sections/cta.php'; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
