<?php
/**
 * service-areas/location.php — city landing page.
 * Clean URL: /personal-injury-lawyer-<slug>-ca/  → ?city=<slug>
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/schema.php';
require_once __DIR__ . '/../includes/service-areas.php';

$citySlug = isset($_GET['city']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['city'])) : '';
$areas    = service_areas();
$area     = $areas[$citySlug] ?? null;

if (!$area) {
    http_response_code(404);
    $page = ['title' => 'Area Not Found', 'robots' => 'noindex, follow', 'path' => '/service-areas/'];
    require __DIR__ . '/../includes/header.php';
    echo '<section class="section"><div class="container text-center"><p class="eyebrow">404</p><h1>Service area not found</h1><p style="margin-top:2rem"><a class="btn btn--primary" href="/service-areas/">View all service areas</a></p></div></section>';
    require __DIR__ . '/../includes/footer.php';
    return;
}

$city   = $area['name'];
$path   = '/personal-injury-lawyer-' . $citySlug . '-ca/';
$faqs   = service_area_faqs($city, $area['faqs_local'] ?? []);
$pas    = getPracticeAreas();

// LegalService node scoped to this city + BreadcrumbList + FAQPage, in one @graph.
$graph = [
    [
        '@type'       => 'LegalService',
        '@id'         => url($path) . '#service',
        'name'        => cfg('firm_name', SITE_NAME) . ' — Personal Injury Lawyer in ' . $city . ', CA',
        'url'         => url($path),
        'telephone'   => cfg('site_phone', SITE_PHONE),
        'priceRange'  => 'Free Consultation',
        'areaServed'  => ['@type' => 'City', 'name' => $city],
        'parentOrganization' => ['@id' => BASE_URL . '/#firm'],
        'address'     => ['@type' => 'PostalAddress', 'streetAddress' => cfg('site_address', SITE_ADDRESS),
                          'addressLocality' => 'Folsom', 'addressRegion' => 'CA', 'postalCode' => '95630', 'addressCountry' => 'US'],
        'geo'         => ['@type' => 'GeoCoordinates', 'latitude' => $area['lat'], 'longitude' => $area['lng']],
    ],
    [
        '@type' => 'BreadcrumbList', '@id' => url($path) . '#breadcrumb',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Service Areas', 'item' => url('/service-areas/')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $city, 'item' => url($path)],
        ],
    ],
    [
        '@type' => 'FAQPage', '@id' => url($path) . '#faq',
        'mainEntity' => array_map(static fn ($f) => [
            '@type' => 'Question', 'name' => $f['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['answer']],
        ], $faqs),
    ],
];

$page = [
    'title'       => 'Personal Injury Lawyer in ' . $city . ', CA',
    'description' => 'Injured in ' . $city . ', California? Mason Law, P.C. offers a free consultation and works on a no-fee-unless-we-win basis. Call (916) 587-2997.',
    'path'        => $path,
    'styles'      => ['/assets/css/home.css', '/assets/css/blog.css'],
    'schema'      => [schema_script(['@context' => 'https://schema.org', '@graph' => $graph])],
];

require __DIR__ . '/../includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="<?= e($city) ?> personal injury lawyer">
  <div class="container pa-hero__inner">
    <nav class="breadcrumbs breadcrumbs--light" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a> <span aria-hidden="true">/</span></li>
        <li><a href="/service-areas/">Service Areas</a> <span aria-hidden="true">/</span></li>
        <li><span aria-current="page"><?= e($city) ?></span></li>
      </ol>
    </nav>
    <p class="eyebrow"><?= e($area['county']) ?></p>
    <h1 class="pa-hero__title">Personal Injury Lawyer in <?= e($city) ?>, CA</h1>
    <p class="pa-hero__subtext">Free consultation &middot; No fee unless we win &middot; Serving all of <?= e($area['county']) ?></p>
    <div class="pa-hero__cta">
      <a class="btn btn--primary btn--glow" href="/case-evaluation.php" data-ripple>Free Case Evaluation</a>
      <a class="btn btn--on-primary" href="tel:<?= e(SITE_PHONE_RAW) ?>">Call <?= e(SITE_PHONE) ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container container--narrow">
    <div class="post-content">
      <?php foreach ($area['intro'] as $p): ?><p><?= e($p) ?></p><?php endforeach; ?>

      <?php if (!empty($area['hotspots'])): ?>
        <h2 class="has-underline">Common accident locations in <?= e($city) ?></h2>
        <?php if (!empty($area['roads_intro'])): ?><p><?= e($area['roads_intro']) ?></p><?php endif; ?>
        <ul>
          <?php foreach ($area['hotspots'] as $spot): ?><li><?= e($spot) ?></li><?php endforeach; ?>
        </ul>
        <?php if (!empty($area['local'])): ?><p><?= e($area['local']) ?></p><?php endif; ?>
      <?php endif; ?>

      <h2 class="has-underline">Neighborhoods we serve in <?= e($city) ?></h2>
      <p>We help injured people throughout <?= e($city) ?>, including <?= e($area['neighborhoods']) ?>.</p>

      <h2 class="has-underline">How we help injured people in <?= e($city) ?></h2>
      <p>Whatever the cause of your injury, our team can help you understand your options and pursue fair compensation. We handle:</p>
      <ul>
        <?php foreach (array_slice($pas, 0, 8) as $pa): ?>
          <li><a href="/practice-areas/<?= e($pa['slug']) ?>/"><?= e($pa['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <p><a href="/practice-areas/">View all practice areas &rarr;</a></p>

      <h2 class="has-underline">Why injured <?= e($city) ?> residents choose Mason Law</h2>
      <p>Founder Shannon Ramos is a California trial attorney known for preparation and persistence. We keep our caseload manageable so every client gets real attention, we communicate in plain English, and we advance the costs of building your case. You pay nothing up front, and no attorney fee unless we recover for you.</p>

      <div class="post-disclaimer">
        <strong>Disclaimer:</strong> This page is attorney advertising and is for general information only. It is not legal advice, and it does not create an attorney-client relationship. Past results do not guarantee future outcomes.
      </div>
    </div>

    <?php if ($faqs): ?>
      <section class="post-faq" aria-labelledby="sa-faq-h">
        <h2 id="sa-faq-h" class="has-underline">Frequently Asked Questions</h2>
        <?php foreach ($faqs as $f): ?>
          <details class="post-faq__item">
            <summary><?= e($f['question']) ?><svg class="post-faq__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg></summary>
            <div class="post-faq__a"><?= e($f['answer']) ?></div>
          </details>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <div class="post-cta">
      <h3>Injured in <?= e($city) ?>? Let&rsquo;s talk.</h3>
      <p>Free, confidential consultation. You pay nothing unless we recover for you.</p>
      <div class="post-cta__actions">
        <a class="btn btn--primary" href="/case-evaluation.php" data-ripple>Free Case Evaluation</a>
        <a class="btn btn--ghost" href="/contact.php">Contact Our Attorneys</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
