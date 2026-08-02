<?php
/**
 * results.php — Verdicts & Settlements. (SEO item 12)
 * Lists representative case results with the required CA-Bar disclaimer at the
 * top AND bottom. Nav "Results" links here.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';

$results = getCaseResults();

$page = [
    'title'       => 'Verdicts & Settlements',
    'description' => 'Representative verdicts and settlements from Mason Law, P.C. Past results do not guarantee future outcomes. Free consultation — (916) 587-2997.',
    'path'        => '/results.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/blog.css'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Verdicts & Settlements', 'path' => '/results.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Verdicts and settlements">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Case Results</p>
    <h1 class="pa-hero__title">Verdicts &amp; Settlements</h1>
    <p class="pa-hero__subtext">A sample of results we have obtained for injured clients. <strong>Past results do not guarantee future outcomes.</strong> Every case is different.</p>
  </div>
</section>

<section class="section">
  <div class="container container--wide">
    <?php if ($results): ?>
      <div class="grid grid--3">
        <?php foreach ($results as $r): ?>
          <article class="result-card" style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:var(--space-6);box-shadow:var(--shadow-sm)">
            <p class="result-card__amount" style="font-family:var(--font-display);color:var(--color-accent);font-size:var(--text-4xl);line-height:1"><?= e($r['result_amount']) ?></p>
            <p style="font-weight:var(--weight-semibold);color:var(--color-heading);margin-top:var(--space-2)"><?= e($r['case_type']) ?></p>
            <?php if (!empty($r['description'])): ?><p style="color:var(--color-text-muted);font-size:var(--text-sm);margin-top:var(--space-2)"><?= e($r['description']) ?></p><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center" style="color:var(--color-text-muted)">Results will be published here soon. Call <?= e(SITE_PHONE) ?> to discuss your case.</p>
    <?php endif; ?>

    <!-- Required disclaimer (bottom) -->
    <div class="post-disclaimer" style="max-width:none;margin-top:var(--space-8)">
      <strong>Disclaimer:</strong> The verdicts and settlements shown are examples of results obtained in specific cases and are not a prediction or guarantee of the outcome of any other case. <strong>Past results do not guarantee future outcomes.</strong> The amounts reflect the gross recovery before fees and costs. This page is attorney advertising and does not constitute legal advice.
    </div>

    <div class="post-cta" style="margin-top:var(--space-8)">
      <h3>Injured and unsure what your case is worth?</h3>
      <p>We offer a free, confidential consultation. You pay nothing unless we recover for you.</p>
      <div class="post-cta__actions">
        <a class="btn btn--primary" href="/case-evaluation.php" data-ripple>Free Case Evaluation</a>
        <a class="btn btn--ghost" href="tel:<?= e(SITE_PHONE_RAW) ?>">Call <?= e(SITE_PHONE) ?></a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
