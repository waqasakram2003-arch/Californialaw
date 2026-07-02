<?php
/**
 * resources.php — legal resources for California injury victims.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';

/* FAQs from the database. */
$faqs = [];
try {
    $faqs = db()->query("SELECT question, answer FROM faq_items WHERE active = 1 ORDER BY order_num, id LIMIT 6")->fetchAll();
} catch (Throwable $e) { $faqs = []; }

$guides = [
    ['title' => 'After a Car Accident: A California Checklist', 'desc' => 'A printable step-by-step guide for the hours and days after a collision.'],
    ['title' => 'Understanding Your California Injury Claim', 'desc' => 'An overview of the claims process, deadlines, and what to expect.'],
    ['title' => 'Dealing With Insurance Adjusters', 'desc' => 'Practical tips for protecting yourself when the insurer calls.'],
];

$externalLinks = [
    ['name' => 'California DMV', 'desc' => 'Driver records, accident reporting (SR-1), and forms.', 'url' => 'https://www.dmv.ca.gov/'],
    ['name' => 'Cal/OSHA', 'desc' => 'Workplace safety and health for California workers.', 'url' => 'https://www.dir.ca.gov/dosh/'],
    ['name' => 'California Courts Self-Help', 'desc' => 'Official self-help resources from the Judicial Branch.', 'url' => 'https://www.courts.ca.gov/selfhelp.htm'],
    ['name' => 'California Dept. of Insurance', 'desc' => 'File a complaint or learn about your insurance rights.', 'url' => 'https://www.insurance.ca.gov/'],
];

$glossary = [
    ['Bodily Injury', 'Physical harm to a person, as opposed to damage to property.'],
    ['Comparative Negligence', 'A rule that reduces a recovery by the injured person\'s share of fault. California uses a pure comparative negligence rule.'],
    ['Contingency Fee', 'A fee arrangement in which the attorney is paid only if there is a recovery, taken as a percentage of it.'],
    ['Damages', 'The compensation a person may recover for losses caused by another\'s wrongful conduct.'],
    ['Defendant', 'The party against whom a claim or lawsuit is brought.'],
    ['Demand Letter', 'A letter that sets out a claim and requests compensation, often sent before a lawsuit.'],
    ['Deposition', 'Sworn, out-of-court testimony given during the discovery phase of a case.'],
    ['Discovery', 'The formal process of exchanging information and evidence between parties before trial.'],
    ['Duty of Care', 'A legal obligation to act with reasonable care to avoid harming others.'],
    ['Economic Damages', 'Measurable financial losses such as medical bills and lost wages.'],
    ['Liability', 'Legal responsibility for one\'s acts or omissions.'],
    ['Negligence', 'A failure to use reasonable care that results in harm to another person.'],
    ['Non-Economic Damages', 'Losses that are harder to quantify, such as pain and suffering.'],
    ['Plaintiff', 'The party who brings a claim or lawsuit.'],
    ['Premises Liability', 'A property owner\'s responsibility for injuries caused by unsafe conditions.'],
    ['Settlement', 'An agreement that resolves a claim without a trial.'],
    ['Statute of Limitations', 'The deadline for filing a lawsuit. In California it is generally two years for personal injury.'],
    ['Subrogation', 'An insurer\'s right to be reimbursed from a recovery for benefits it paid.'],
    ['Tort', 'A civil wrong, other than a breach of contract, that causes harm.'],
    ['Wrongful Death', 'A claim brought by survivors when a death is caused by another\'s wrongful act.'],
];
// Group glossary by first letter.
$byLetter = [];
foreach ($glossary as $g) { $byLetter[strtoupper($g[0][0])][] = $g; }
ksort($byLetter);

$page = [
    'title'       => 'Legal Resources',
    'description' => 'Free legal resources for injured Californians: guides, official California links, FAQs, '
                   . 'and a glossary of personal injury terms.',
    'path'        => '/resources.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/blog.css', '/assets/css/practice-area.css'],
    'scripts'     => ['/assets/js/practice-area.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Resources', 'path' => '/resources.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Resources">
  <div class="container pa-hero__inner">
    <p class="eyebrow">For Injured Californians</p>
    <h1 class="pa-hero__title">Legal Resources</h1>
    <p class="pa-hero__subtext">Helpful guides, official California links, answers to common questions, and a glossary of legal terms. This information is educational and not legal advice.</p>
  </div>
</section>

<!-- Guides -->
<section class="section">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Free Guides</p><h2 class="has-underline">Downloadable Guides</h2></div>
    <div class="grid grid--3 stagger-children">
      <?php foreach ($guides as $g): ?>
        <a class="card guide-card" href="#" aria-label="Download (placeholder)">
          <span class="guide-card__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg></span>
          <h3><?= e($g['title']) ?></h3>
          <p class="text-muted"><?= e($g['desc']) ?></p>
          <span class="guide-card__tag">PDF &middot; Coming soon</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- External links -->
<section class="section section--muted">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Official California Resources</p><h2 class="has-underline">Helpful Links</h2></div>
    <div class="grid grid--2 stagger-children">
      <?php foreach ($externalLinks as $l): ?>
        <a class="card link-card" href="<?= e($l['url']) ?>" target="_blank" rel="noopener noreferrer">
          <div>
            <h3><?= e($l['name']) ?> <svg class="link-card__ext" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></h3>
            <p class="text-muted"><?= e($l['desc']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="disclaimer-note" style="max-width:64ch;margin:2rem auto 0;text-align:center;">External links are provided for convenience. We are not responsible for the content of third-party websites.</p>
  </div>
</section>

<!-- FAQ -->
<?php if ($faqs): ?>
<section class="section">
  <div class="container container--narrow">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Common Questions</p><h2 class="has-underline">Frequently Asked Questions</h2></div>
    <div class="accordion" data-accordion>
      <?php foreach ($faqs as $i => $f): ?>
        <div class="accordion__item<?= $i === 0 ? ' is-open' : '' ?>">
          <button class="accordion__trigger" type="button" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"><span><?= e($f['question']) ?></span><span class="accordion__icon" aria-hidden="true"></span></button>
          <div class="accordion__panel"><div class="accordion__body"><p><?= e($f['answer']) ?></p></div></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center" style="margin-top:var(--space-6);"><a class="btn btn--ghost" href="/faq.php">View All FAQs</a></div>
  </div>
</section>
<?php endif; ?>

<!-- Glossary -->
<section class="section section--muted" id="glossary">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Plain English</p><h2 class="has-underline">Glossary of Legal Terms</h2></div>

    <nav class="az-index" aria-label="Glossary index">
      <?php foreach (range('A', 'Z') as $L): ?>
        <?php if (isset($byLetter[$L])): ?><a href="#term-<?= $L ?>"><?= $L ?></a><?php else: ?><span><?= $L ?></span><?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <div class="glossary">
      <?php foreach ($byLetter as $L => $terms): ?>
        <div class="glossary__group" id="term-<?= e($L) ?>">
          <h3 class="glossary__letter"><?= e($L) ?></h3>
          <dl>
            <?php foreach ($terms as $t): ?>
              <div class="glossary__item"><dt><?= e($t[0]) ?></dt><dd><?= e($t[1]) ?></dd></div>
            <?php endforeach; ?>
          </dl>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/sections/cta.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
