<?php
/**
 * reviews.php — Client reviews.
 * Replaces the old case-results page: feeds ONLY genuine 5-star client reviews
 * from Avvo (Shannon Ramos, 5.0/5.0, 30 reviews). Reviews are quoted from the
 * firm's public Avvo profile; a live Google/Avvo widget can be embedded later.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';
require_once __DIR__ . '/includes/schema.php';

$avvoUrl   = 'https://www.avvo.com/attorneys/95630-ca-shannon-ramos-4240698.html';
$yelpUrl   = 'https://www.yelp.com/biz/mason-law-office-sacramento-10';
$googleUrl = cfg('google_reviews_url', '');

/* Genuine 5-star client reviews from the firm's Avvo profile. */
$reviews = [
    ['name' => 'Juan',  'title' => 'Won a False Restraining Order Case at Trial', 'body' => 'Shannon immediately recognized the weaknesses in the claims against me and prepared a strong defense. She was fearless in court and challenged the evidence effectively.'],
    ['name' => 'Caleb', 'title' => 'Uncovered Hidden Assets &amp; Protected My Interests', 'body' => 'Her attention to detail uncovered assets that should have been disclosed from the beginning. Her ability to think outside the box was impressive.'],
    ['name' => 'Robin', 'title' => 'Fair Spousal Support Outcome', 'body' => 'Her preparation and advocacy gave me confidence throughout the process. I couldn&rsquo;t have asked for better representation.'],
    ['name' => 'Luana', 'title' => 'Clear Legal Advice on a Prenuptial Agreement', 'body' => 'She explained the agreement line by line, identified potential issues, and answered every question I had.'],
    ['name' => 'Kaye',  'title' => 'Smart, Strategic Support During Mediation', 'body' => 'She recognized issues I would have overlooked and negotiated terms that benefited me in the long run.'],
];

$reviewDisclaimer = 'These are individual client reviews from the firm&rsquo;s Avvo profile. '
    . 'Every case is different; past results do not guarantee, warrant, or predict future outcomes.';

$page = [
    'title'       => 'Client Reviews',
    'description' => 'Read genuine 5-star client reviews for Mason Law, P.C. and attorney Shannon Ramos, '
                   . 'rated 5.0 on Avvo. Serving Sacramento, Placer, El Dorado, and Marin counties.',
    'path'        => '/reviews.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css'],
    'scripts'     => ['/assets/js/home.js'],
    'schema'      => array_filter([schemaReview(array_fill(0, 30, ['rating' => 5]))]),
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Reviews', 'path' => '/reviews.php'],
    ],
];

require __DIR__ . '/includes/header.php';

$stars = static function (int $n = 5): string {
    $out = '';
    for ($i = 0; $i < $n; $i++) {
        $out .= '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" width="18" height="18" style="color:var(--color-accent)"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 15.9 4.7 18.6l1-5.8L1.5 8.7l5.9-.9z"/></svg>';
    }
    return $out;
};
?>

<!-- 1. HERO -->
<section class="pa-hero pa-hero--index" aria-label="Client reviews">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Client Reviews</p>
    <h1 class="pa-hero__title">What Our Clients Say</h1>
    <p class="pa-hero__subtext">Rated <strong>5.0 out of 5.0</strong> across 30 client reviews on Avvo. Here is a selection in our clients&rsquo; own words.</p>
    <div style="display:flex;align-items:center;justify-content:center;gap:.5rem;margin-top:1rem;">
      <?= $stars(5) ?>
      <span style="font-weight:700;letter-spacing:.02em;">5.0 / 5.0 &middot; 30 reviews</span>
    </div>
  </div>
</section>

<!-- 2. REVIEWS GRID -->
<section class="section">
  <div class="container container--wide">
    <div class="grid grid--3 stagger-children">
      <?php foreach ($reviews as $r): ?>
        <figure class="card" style="display:flex;flex-direction:column;gap:.75rem;padding:1.75rem;">
          <div style="display:flex;gap:.15rem;"><?= $stars(5) ?></div>
          <figcaption style="font-family:var(--font-heading);font-weight:700;font-size:var(--text-lg);color:var(--color-heading,inherit);"><?= $r['title'] ?></figcaption>
          <blockquote style="margin:0;color:var(--color-text);line-height:1.6;">&ldquo;<?= $r['body'] ?>&rdquo;</blockquote>
          <span class="text-muted" style="margin-top:auto;font-weight:600;">&mdash; <?= e($r['name']) ?>, via Avvo</span>
        </figure>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="margin-top:2.5rem;display:flex;flex-wrap:wrap;gap:1rem;justify-content:center;">
      <a class="btn btn--primary" href="<?= e($avvoUrl) ?>" target="_blank" rel="noopener">Read all 30 reviews on Avvo &rarr;</a>
      <?php if ($googleUrl): ?><a class="btn btn--ghost" href="<?= e($googleUrl) ?>" target="_blank" rel="noopener">Reviews on Google</a><?php endif; ?>
      <a class="btn btn--ghost" href="<?= e($yelpUrl) ?>" target="_blank" rel="noopener">Reviews on Yelp</a>
    </div>

    <p class="disclaimer-note" style="max-width:64ch;margin:2rem auto 0;text-align:center;"><?= $reviewDisclaimer ?></p>
  </div>
</section>

<!-- 3. CTA -->
<?php require __DIR__ . '/includes/sections/cta.php'; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
