<?php
/**
 * faq.php — Frequently Asked Questions (category tabs + accordion + live search).
 * Pulls from faq_items grouped by category. Outputs FAQPage JSON-LD.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';
require_once __DIR__ . '/includes/schema.php';

/* Group FAQs by category (preserve seeded order). */
$grouped = [];
$allFaqs = [];
try {
    foreach (db()->query("SELECT question, answer, category FROM faq_items WHERE active = 1 ORDER BY order_num, id") as $row) {
        $grouped[$row['category']][] = $row;
        $allFaqs[] = $row;
    }
} catch (Throwable $e) { $grouped = []; }
$catList = array_keys($grouped);

$page = [
    'title'       => 'Frequently Asked Questions',
    'description' => 'Answers to common questions about California personal injury claims, the legal process, '
                   . 'fees, and deadlines. Informational only — not legal advice.',
    'path'        => '/faq.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css', '/assets/css/faq.css'],
    'scripts'     => ['/assets/js/practice-area.js', '/assets/js/faq.js'],
    'schema'      => $allFaqs ? [schemaFAQ($allFaqs)] : [],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'FAQ', 'path' => '/faq.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Frequently asked questions">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Answers</p>
    <h1 class="pa-hero__title">Frequently Asked Questions</h1>
    <p class="pa-hero__subtext">Common questions about California injury claims. This information is educational and is not legal advice.</p>
    <div class="faq-search" role="search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="search" placeholder="Search questions…" autocomplete="off" aria-label="Search FAQs" data-faq-search>
    </div>
  </div>
</section>

<section class="section">
  <div class="container container--narrow">

    <?php if ($catList): ?>
      <div class="faq-tabs" data-faq-tabs role="tablist" aria-label="FAQ categories">
        <?php foreach ($catList as $i => $cat): ?>
          <button class="faq-tab<?= $i === 0 ? ' is-active' : '' ?>" type="button" role="tab"
                  aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" data-faq-tab="<?= e($cat) ?>"><?= e($cat) ?></button>
        <?php endforeach; ?>
        <span class="faq-tabs__indicator" data-faq-indicator aria-hidden="true"></span>
      </div>
    <?php endif; ?>

    <div class="accordion faq-accordion" data-accordion data-faq-list>
      <?php $first = true; foreach ($grouped as $cat => $items): ?>
        <?php foreach ($items as $it):
          $text = strtolower($it['question'] . ' ' . strip_tags($it['answer']));
        ?>
          <div class="accordion__item faq-item" data-category="<?= e($cat) ?>" data-text="<?= e($text) ?>"<?= $cat === ($catList[0] ?? '') ? '' : ' hidden' ?>>
            <button class="accordion__trigger" type="button" aria-expanded="false"><span><?= e($it['question']) ?></span><span class="accordion__icon" aria-hidden="true"></span></button>
            <div class="accordion__panel"><div class="accordion__body"><p><?= $it['answer'] ?></p></div></div>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
      <p class="faq-empty" data-faq-empty hidden>No questions match your search. <button type="button" class="link-btn" data-faq-clear>Clear search</button></p>
    </div>

    <div class="faq-cta">
      <p>Still have questions? We&rsquo;re happy to help.</p>
      <div class="faq-cta__actions">
        <a class="btn btn--primary" href="/case-evaluation.php" data-ripple>Free Case Evaluation</a>
        <a class="btn btn--ghost" href="/contact.php">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
