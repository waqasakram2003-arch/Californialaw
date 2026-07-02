<?php
/**
 * attorney/profile.php — dynamic attorney profile (clean URL /attorney/<slug>/).
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/schema.php';
require_once __DIR__ . '/../includes/attorney-helpers.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['slug'])) : '';
$att  = $slug !== '' ? getAttorneyBySlug($slug) : null;

if (!$att) {
    http_response_code(404);
    $page = ['title' => 'Attorney Not Found', 'robots' => 'noindex, follow', 'path' => '/attorney/'];
    require __DIR__ . '/../includes/header.php';
    ?>
    <section class="section">
      <div class="container text-center">
        <p class="eyebrow">404</p>
        <h1>We couldn&rsquo;t find that attorney</h1>
        <p style="margin-top:2rem;"><a class="btn btn--primary" href="/attorney/">Meet Our Team</a></p>
      </div>
    </section>
    <?php
    require __DIR__ . '/../includes/footer.php';
    return;
}

$d           = is_array($att['details'] ?? null) ? $att['details'] : [];
$testimonials = getTestimonialsForAttorney((int) $att['id']);
$notable      = getNotableCasesForAttorney((int) $att['id']);
$others       = getOtherAttorneys((int) $att['id']);

$page = [
    'title'       => $att['name'] . ', ' . $att['title'],
    'description' => 'Meet ' . $att['name'] . ', ' . $att['title'] . ' at Golden State Injury Lawyers — '
                   . 'a California personal injury attorney. ' . strip_tags($att['bio']),
    'path'        => '/attorney/' . $att['slug'] . '/',
    'styles'      => ['/assets/css/home.css', '/assets/css/practice-area.css', '/assets/css/attorney.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/practice-area.js', '/assets/js/attorney.js'],
    'schema'      => [schemaAttorney($att)],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Our Team', 'path' => '/attorney/'],
        ['name' => $att['name'], 'path' => '/attorney/' . $att['slug'] . '/'],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<!-- 1. HERO -->
<section class="attorney-hero" aria-label="<?= e($att['name']) ?>">
  <div class="container attorney-hero__inner">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <ol>
        <li><a href="/">Home</a> <span aria-hidden="true">/</span></li>
        <li><a href="/attorney/">Our Team</a> <span aria-hidden="true">/</span></li>
        <li><span aria-current="page"><?= e($att['name']) ?></span></li>
      </ol>
    </nav>
    <div class="attorney-hero__head" data-hero-item>
      <div class="attorney-hero__photo" aria-hidden="true">
        <?php if (!empty($att['image'])): ?>
          <img src="<?= e($att['image']) ?>" alt="">
        <?php else: ?>
          <span class="attorney-hero__initials"><?= e(initials($att['name'])) ?></span>
        <?php endif; ?>
      </div>
      <div class="attorney-hero__meta">
        <p class="eyebrow">California Personal Injury Attorney</p>
        <h1><?= e($att['name']) ?></h1>
        <p class="attorney-hero__title"><?= e($att['title']) ?></p>
        <div class="attorney-hero__cta">
          <a class="btn btn--primary" href="#contact-attorney" data-ripple>Contact <?= e(explode(' ', $att['name'])[0]) ?></a>
          <a class="btn btn--on-primary" href="tel:<?= e(SITE_PHONE_RAW) ?>">Call <?= e(SITE_PHONE) ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 2. INFO BAR -->
<section class="info-bar">
  <div class="container container--wide">
    <dl class="info-bar__grid">
      <div class="info-bar__item"><dt>Bar Number</dt><dd><?= e($att['bar_number'] ?: '—') ?></dd></div>
      <div class="info-bar__item"><dt>Experience</dt><dd><?= e(($d['years'] ?? '—')) ?> Years</dd></div>
      <div class="info-bar__item"><dt>Languages</dt><dd><?= e(implode(', ', $d['languages'] ?? ['English'])) ?></dd></div>
      <div class="info-bar__item"><dt>Areas of Practice</dt><dd><?= e(implode(', ', $d['practices'] ?? [])) ?: '—' ?></dd></div>
    </dl>
  </div>
</section>

<!-- 3. BIOGRAPHY -->
<section class="section">
  <div class="container container--narrow animate-on-scroll">
    <div class="section-head"><p class="eyebrow">Biography</p><h2 class="has-underline">About <?= e($att['name']) ?></h2></div>
    <div class="attorney-bio">
      <?= $d['bio_long'] ?? ('<p>' . e($att['bio']) . '</p>') ?>
    </div>
  </div>
</section>

<!-- 4. EDUCATION & CREDENTIALS -->
<?php if (!empty($d['education'])): ?>
<section class="section section--muted">
  <div class="container container--narrow">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Credentials</p><h2 class="has-underline">Education &amp; Admissions</h2></div>
    <ol class="cred-timeline">
      <?php foreach ($d['education'] as $ed): ?>
        <li class="cred-timeline__item animate-on-scroll">
          <span class="cred-timeline__year"><?= e($ed['year'] ?? '') ?></span>
          <div>
            <h3 class="cred-timeline__school"><?= e($ed['school'] ?? '') ?></h3>
            <p class="cred-timeline__detail"><?= e($ed['detail'] ?? '') ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>

<!-- 5. NOTABLE / REPRESENTATIVE MATTERS -->
<?php if ($notable): ?>
<section class="section">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Experience</p><h2 class="has-underline">Representative Matters</h2></div>
    <div class="grid grid--3 stagger-children">
      <?php foreach ($notable as $c): ?>
        <div class="card matter-card">
          <p class="matter-card__type"><?= e($c['case_type']) ?></p>
          <p class="matter-card__desc text-muted"><?= e($c['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="disclaimer-note" style="max-width:70ch;margin:2rem auto 0;text-align:center;">Past results do not guarantee future outcomes. Each case is unique and results depend on its specific facts.</p>
  </div>
</section>
<?php endif; ?>

<!-- 6. PUBLICATIONS & MEDIA -->
<?php if (!empty($d['publications'])): ?>
<section class="section section--muted">
  <div class="container container--narrow">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Insights</p><h2 class="has-underline">Publications &amp; Media</h2></div>
    <div class="accordion" data-accordion>
      <?php foreach ($d['publications'] as $idx => $pub): ?>
        <div class="accordion__item<?= $idx === 0 ? ' is-open' : '' ?>">
          <button class="accordion__trigger" type="button" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>">
            <span><?= e($pub['title'] ?? '') ?></span><span class="accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="accordion__panel"><div class="accordion__body"><p><?= e($pub['desc'] ?? '') ?></p></div></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 7. TESTIMONIALS -->
<?php if ($testimonials): ?>
<section class="section section--dark">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Client Experiences</p>
      <h2 class="has-underline">What Clients Say</h2>
      <p class="disclaimer-note" style="margin-inline:auto;display:inline-block;background:rgba(255,255,255,.08);color:rgba(255,255,255,.85);">Individual results may vary.</p>
    </div>
    <div class="grid grid--<?= count($testimonials) >= 3 ? '3' : '2' ?> stagger-children">
      <?php foreach ($testimonials as $t): ?>
        <figure class="att-testimonial glass">
          <div class="att-testimonial__stars" aria-label="<?= (int)$t['rating'] ?> of 5 stars">
            <?php for ($s=0;$s<5;$s++): ?><svg class="<?= $s < (int)$t['rating'] ? 'is-on' : '' ?>" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><?php endfor; ?>
          </div>
          <blockquote><?= e($t['testimonial']) ?></blockquote>
          <figcaption><span class="att-testimonial__name"><?= e($t['client_name']) ?></span><span class="att-testimonial__case"><?= e($t['case_type']) ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 8. CONTACT THIS ATTORNEY -->
<section class="section" id="contact-attorney">
  <div class="container container--narrow">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Get in Touch</p><h2 class="has-underline">Contact <?= e($att['name']) ?></h2></div>
    <form class="att-form card" data-ajax-form action="/api/form-handler.php" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="attorney-<?= e($att['slug']) ?>">
      <input type="hidden" name="case_type" value="<?= e($att['name']) ?> (attorney inquiry)">
      <div class="hp-field" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="att-form__row">
        <div class="field"><label for="af-name">Full Name</label><input type="text" id="af-name" name="name" required autocomplete="name" placeholder="Your name"><span class="field__error" aria-live="polite"></span></div>
        <div class="field"><label for="af-phone">Phone</label><input type="tel" id="af-phone" name="phone" required autocomplete="tel" placeholder="(000) 000-0000"><span class="field__error" aria-live="polite"></span></div>
      </div>
      <div class="field"><label for="af-email">Email</label><input type="email" id="af-email" name="email" autocomplete="email" placeholder="you@example.com"><span class="field__error" aria-live="polite"></span></div>
      <div class="field"><label for="af-msg">How can we help?</label><textarea id="af-msg" name="message" rows="4" placeholder="Briefly describe your situation"></textarea></div>
      <button type="submit" class="btn btn--primary btn--block" data-ripple>Send Message</button>
      <p class="cta-form__fineprint" style="color:var(--color-text-muted);">Submitting this form does not create an attorney-client relationship.</p>
      <div class="form-success" data-form-success hidden>
        <span class="form-success__check" aria-hidden="true"><svg viewBox="0 0 52 52"><circle class="fs-circle" cx="26" cy="26" r="24" fill="none"/><path class="fs-check" fill="none" d="M14 27l8 8 16-16"/></svg></span>
        <p class="form-success__msg" data-form-success-msg>Thank you. Our team will contact you shortly.</p>
      </div>
    </form>
  </div>
</section>

<!-- 9. OTHER TEAM MEMBERS -->
<?php if ($others): ?>
<section class="section section--muted">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll"><p class="eyebrow">Our Team</p><h2 class="has-underline">Meet the Rest of the Team</h2></div>
    <div class="team-scroll">
      <?php foreach ($others as $o): ?>
        <a class="team-scroll__card" href="/attorney/<?= e($o['slug']) ?>/">
          <span class="team-scroll__photo" aria-hidden="true">
            <?php if (!empty($o['image'])): ?><img src="<?= e($o['image']) ?>" alt=""><?php else: ?><span class="team-scroll__initials"><?= e(initials($o['name'])) ?></span><?php endif; ?>
          </span>
          <span class="team-scroll__name"><?= e($o['name']) ?></span>
          <span class="team-scroll__title"><?= e($o['title']) ?></span>
          <span class="team-scroll__bar"><?= e($o['bar_number']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
