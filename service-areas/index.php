<?php
/**
 * service-areas/index.php — hub linking to all city landing pages. (SEO item 11)
 * Clean URL: /service-areas/
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/service-areas.php';

$areas = service_areas();

$page = [
    'title'       => 'Service Areas — California Personal Injury',
    'description' => 'Mason Law, P.C. represents injured people across the Sacramento region, including Folsom, Sacramento, El Dorado Hills, and Roseville. Free consultation.',
    'path'        => '/service-areas/',
    'styles'      => ['/assets/css/home.css'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Service Areas', 'path' => '/service-areas/'],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Service areas">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Where We Practice</p>
    <h1 class="pa-hero__title">Our California Service Areas</h1>
    <p class="pa-hero__subtext">Based in Folsom and serving injured people across the greater Sacramento region. Free consultation &middot; no fee unless we win.</p>
  </div>
</section>

<section class="section">
  <div class="container container--wide">
    <div class="grid grid--3">
      <?php foreach ($areas as $slug => $a): ?>
        <a href="/personal-injury-lawyer-<?= e($slug) ?>-ca/" style="display:flex;flex-direction:column;gap:8px;padding:var(--space-6);background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);transition:transform var(--transition-base),box-shadow var(--transition-base)">
          <span style="color:var(--color-accent)"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <span style="font-family:var(--font-heading);font-weight:var(--weight-semibold);font-size:var(--text-lg);color:var(--color-heading)">Personal Injury Lawyer in <?= e($a['name']) ?></span>
          <span style="color:var(--color-text-muted);font-size:var(--text-sm)"><?= e($a['county']) ?> &rarr;</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
