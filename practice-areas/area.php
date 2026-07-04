<?php
/**
 * practice-areas/area.php — dynamic Practice Area detail template.
 * Slug comes from the URL via router.php / .htaccess (clean URL
 * /practice-areas/<slug>/) or directly as ?slug=<slug>.
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/schema.php';
require_once __DIR__ . '/../includes/pa-helpers.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['slug'])) : '';
$area = $slug !== '' ? getPracticeAreaBySlug($slug) : null;

/* ---- 404 ---- */
if (!$area) {
    http_response_code(404);
    $page = ['title' => 'Practice Area Not Found', 'robots' => 'noindex, follow', 'path' => '/practice-areas/'];
    require __DIR__ . '/../includes/header.php';
    ?>
    <section class="section">
      <div class="container text-center">
        <p class="eyebrow">404</p>
        <h1>We couldn&rsquo;t find that practice area</h1>
        <p class="lead">The page may have moved. Browse all of our California practice areas instead.</p>
        <p style="margin-top:2rem;"><a class="btn btn--primary" href="/practice-areas/">View All Practice Areas</a></p>
      </div>
    </section>
    <?php
    require __DIR__ . '/../includes/footer.php';
    return;
}

$d        = is_array($area['details'] ?? null) ? $area['details'] : [];
$title    = $area['title'];
$titleS   = pa_singular($title); // singular form for headings
$base     = '/practice-areas/' . $area['slug'] . '/';
$results  = getCaseResultsForArea($d['result_match'] ?? $title);
$related  = getRelatedAreas($d['related'] ?? []);

$page = [
    'title'       => $area['meta_title'] ?: ($title . ' Attorney California'),
    'description' => $area['meta_desc'] ?: $area['short_desc'],
    'path'        => $base,
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/practice-area.js'],
    'schema'      => [schemaPracticeArea($area)],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Practice Areas', 'path' => '/practice-areas/'],
        ['name' => $title, 'path' => $base],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<!-- ============ 1. PAGE HERO ============ -->
<section class="pa-hero" aria-label="<?= e($title) ?>">
  <div class="pa-hero__bg" aria-hidden="true">
    <?php if (!empty($area['image'])): ?>
      <span class="pa-hero__photo" style="background-image:url('<?= e($area['image']) ?>')"></span>
    <?php endif; ?>
    <span class="pa-hero__glyph"><?= practice_icon($area['icon'] ?? '') ?></span>
  </div>
  <div class="container pa-hero__inner">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <ol>
        <?php foreach ($page['breadcrumbs'] as $i => $bc): ?>
          <li>
            <?php if ($i < count($page['breadcrumbs']) - 1): ?>
              <a href="<?= e($bc['path']) ?>"><?= e($bc['name']) ?></a>
              <span aria-hidden="true">/</span>
            <?php else: ?>
              <span aria-current="page"><?= e($bc['name']) ?></span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ol>
    </nav>

    <p class="eyebrow" data-hero-item>California Personal Injury Attorneys</p>
    <h1 class="pa-hero__title" data-hero-item><?= e($titleS) ?> Attorney in California</h1>
    <p class="pa-hero__subtext" data-hero-item><?= e($d['hero_subtext'] ?? $area['short_desc']) ?></p>
    <div class="pa-hero__cta" data-hero-item>
      <a class="btn btn--primary btn--lg" href="/case-evaluation.php" data-magnetic="0.3" data-ripple>Free Case Evaluation</a>
      <a class="btn btn--on-primary btn--lg" href="tel:<?= e(SITE_PHONE_RAW) ?>">Call <?= e(SITE_PHONE) ?></a>
    </div>
    <ul class="pa-hero__badges" data-hero-item role="list">
      <li>No Win, No Fee</li><li>Free Consultations</li><li>Available 24/7</li><li>Serving All of California</li>
    </ul>
  </div>
</section>

<!-- ============ 2. WHAT IS ============ -->
<section class="section">
  <div class="container container--narrow prose animate-on-scroll">
    <h2 class="has-underline">What Is a <?= e($titleS) ?> Case?</h2>
    <?= $area['full_content'] ?: '<p>' . e($area['short_desc']) . '</p>' ?>
  </div>
</section>

<!-- ============ 3. COMMON CAUSES ============ -->
<?php if (!empty($d['causes'])): ?>
<section class="section section--muted">
  <div class="container">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Common Causes</p>
      <h2 class="has-underline">What Causes These Cases</h2>
    </div>
    <div class="causes-grid stagger-children">
      <?php foreach ($d['causes'] as $i => $cause): ?>
        <div class="cause-card">
          <span class="cause-card__icon"><?= pa_cause_icon($i) ?></span>
          <span class="cause-card__label"><?= e($cause) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ 4. WHAT TO DO ============ -->
<section class="section">
  <div class="container">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">After an Injury</p>
      <h2 class="has-underline">What to Do After a <?= e($titleS) ?></h2>
    </div>
    <ol class="steps">
      <?php foreach (pa_steps($titleS) as $step): ?>
        <li class="step-card animate-on-scroll">
          <span class="step-card__num"><?= e($step['n']) ?></span>
          <div>
            <h3 class="step-card__title"><?= e($step['title']) ?></h3>
            <p><?= e($step['body']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- ============ 5. CALIFORNIA LAWS (accordion) ============ -->
<section class="section section--muted">
  <div class="container container--narrow">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Know Your Rights</p>
      <h2 class="has-underline">California Laws You Should Know</h2>
    </div>
    <div class="accordion" data-accordion>
      <?php foreach (pa_laws() as $idx => $law): ?>
        <div class="accordion__item<?= $idx === 0 ? ' is-open' : '' ?>">
          <button class="accordion__trigger" type="button" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>">
            <span><?= e($law['q']) ?></span>
            <span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel">
            <div class="accordion__body"><p><?= $law['a'] ?></p></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 6. DAMAGES ============ -->
<section class="section">
  <div class="container">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Compensation</p>
      <h2 class="has-underline">Damages You May Recover</h2>
    </div>
    <div class="damages-grid">
      <?php foreach (pa_damages() as $heading => $items): ?>
        <div class="damages-col animate-on-scroll" data-anim="<?= str_contains($heading, 'Non') ? 'right' : 'left' ?>">
          <h3><?= e($heading) ?></h3>
          <ul role="list">
            <?php foreach ($items as $item): ?>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                <?= e($item) ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="disclaimer-note" style="max-width:70ch;margin:2.5rem auto 0;">Compensation amounts vary based on the specific facts of your case.</p>
  </div>
</section>

<!-- ============ 7. CASE RESULTS ============ -->
<section class="section section--dark">
  <div class="container">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Case Results</p>
      <h2 class="has-underline"><?= e($titleS) ?> Case Results</h2>
      <p class="results__disclaimer">Past results do not guarantee future outcomes. Each case is unique.</p>
    </div>
    <?php if ($results): ?>
      <div class="results-stats stagger-children">
        <?php foreach ($results as $r): ?>
          <div class="result-stat">
            <span class="result-stat__num"><?= e($r['result_amount']) ?></span>
            <span class="result-stat__label"><?= e($r['case_type']) ?></span>
            <?php if (!empty($r['description'])): ?><p class="result-stat__desc"><?= e($r['description']) ?></p><?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center" style="color:rgba(255,255,255,.8);">
        See a selection of our firm&rsquo;s case results on our
        <a class="text-gold" href="/results.php">results page</a>.
      </p>
    <?php endif; ?>
  </div>
</section>

<!-- ============ 8. ATTORNEYS ============ -->
<?php require __DIR__ . '/../includes/sections/attorneys.php'; ?>

<!-- ============ 9. RELATED PRACTICE AREAS ============ -->
<?php if ($related): ?>
<section class="section">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Explore More</p>
      <h2 class="has-underline">Related Practice Areas</h2>
    </div>
    <div class="grid grid--3 stagger-children">
      <?php foreach ($related as $rel): ?>
        <a class="pa-card" href="/practice-areas/<?= e($rel['slug']) ?>/">
          <?php if (!empty($rel['image'])): ?>
            <span class="pa-card__media"><img src="<?= e(pa_card_image($rel)) ?>" alt="" loading="lazy"></span>
          <?php endif; ?>
          <span class="pa-card__icon"><?= practice_icon($rel['icon'] ?? '') ?></span>
          <h3 class="pa-card__title"><?= e($rel['title']) ?></h3>
          <p class="pa-card__desc"><?= e($rel['short_desc']) ?></p>
          <span class="pa-card__more">Learn More
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ 10. CONSULTATION CTA ============ -->
<?php require __DIR__ . '/../includes/sections/cta.php'; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
