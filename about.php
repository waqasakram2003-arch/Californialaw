<?php
/**
 * about.php — About Mason Law, P.C.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';

$milestones = [
    ['year' => '2013', 'title' => 'The Firm Is Founded', 'body' => 'Shannon Ramos founds Mason Law, P.C. to give California families and individuals a strong, personal advocate in the courtroom.'],
    ['year' => 'Since 2013', 'title' => 'Earning Trust in the Courtroom', 'body' => 'The firm builds its reputation on a powerful courtroom presence, honest counsel, and hands-on client service.'],
    ['year' => 'Today', 'title' => 'Serving Four Counties', 'body' => 'Mason Law, P.C. represents clients across Sacramento, Placer, El Dorado, and Marin counties &mdash; with Shannon Ramos recognized among The National Top 100 Trial Lawyers.'],
];

$values = [
    ['name' => 'Compassion', 'icon' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7z"/>', 'desc' => 'We treat every client with dignity and care, and we take the time to listen. You are a person to us, never a case number.'],
    ['name' => 'Integrity', 'icon' => '<path d="M12 2 4 7v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V7z"/><path d="M9 12l2 2 4-4"/>', 'desc' => 'We give honest, straightforward guidance about your options &mdash; even when it is not what you hoped to hear.'],
    ['name' => 'Loyalty', 'icon' => '<path d="M12 2 4 7v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V7z"/><path d="M12 8v4M12 15h.01"/>', 'desc' => 'We stand by our clients from the first call through the final hearing &mdash; responsive, dependable, and fully in your corner.'],
    ['name' => 'Client Service', 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>', 'desc' => 'We answer your questions in plain language, keep you informed at every step, and hablamos espa&ntilde;ol. Help should be easy to reach.'],
];

$community = [
    ['icon' => '<path d="M3 12h4l3 8 4-16 3 8h4"/>', 'title' => 'Road Safety Education', 'body' => 'Supporting local programs that promote safer streets for drivers, riders, and pedestrians across California.'],
    ['icon' => '<path d="M22 10 12 5 2 10l10 5 10-5zM6 12v5c0 1 2.5 3 6 3s6-2 6-3v-5"/>', 'title' => 'Student Scholarships', 'body' => 'Investing in California students through annual scholarship opportunities in our communities.'],
    ['icon' => '<path d="M20 6 9 17l-5-5"/>', 'title' => 'Pro Bono Service', 'body' => 'Volunteering time and resources to community legal clinics that help neighbors in need.'],
];

$page = [
    'title'       => 'About Us',
    'description' => 'Learn about Mason Law, P.C. — a California trial firm founded in 2013, serving clients '
                   . 'across Sacramento, Placer, El Dorado, and Marin counties.',
    'path'        => '/about.php',
    'styles'      => ['/assets/css/home.css', '/assets/css/about.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/about.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'About', 'path' => '/about.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<!-- 1. HERO -->
<section class="about-hero" data-hero aria-label="About us">
  <div class="about-hero__bg" aria-hidden="true">
    <canvas class="about-hero__particles" data-particles></canvas>
  </div>
  <div class="container about-hero__inner">
    <p class="eyebrow" data-hero-item>Our Story</p>
    <h1 data-hero-item>About Mason Law, P.C.</h1>
    <p class="lead" data-hero-item style="color:rgba(255,255,255,.82);max-width:62ch;margin-inline:auto;">
      We are a California personal injury firm built on one belief: injured Californians deserve a dedicated advocate who treats them like a person.
    </p>
  </div>
</section>

<!-- 2. FIRM STORY / TIMELINE -->
<section class="section">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Our Journey</p>
      <h2 class="has-underline">The Story of Our Firm</h2>
    </div>

    <div class="timeline" data-timeline>
      <svg class="timeline__track" viewBox="0 0 1000 4" preserveAspectRatio="none" aria-hidden="true">
        <line class="timeline__line" x1="0" y1="2" x2="1000" y2="2" />
      </svg>
      <ol class="timeline__items">
        <?php foreach ($milestones as $i => $m): ?>
          <li class="timeline__item animate-on-scroll" style="transition-delay: <?= $i * 0.1 ?>s">
            <span class="timeline__dot" aria-hidden="true"></span>
            <span class="timeline__year"><?= e($m['year']) ?></span>
            <h3 class="timeline__title"><?= e($m['title']) ?></h3>
            <p class="timeline__body"><?= e($m['body']) ?></p>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>

<!-- 3. MISSION -->
<section class="section section--dark mission">
  <div class="container container--narrow text-center animate-on-scroll">
    <p class="eyebrow">Our Mission</p>
    <blockquote class="mission__quote">
      &ldquo;We stand firm in the belief that our work ethic, combined with our commitment to provide the highest level of customer service, will always result in an unsurpassed benefit to our clients.&rdquo;
    </blockquote>
  </div>
</section>

<!-- 4. CORE VALUES (flip cards) -->
<section class="section">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">What Drives Us</p>
      <h2 class="has-underline">Our Core Values</h2>
    </div>
    <div class="values-grid stagger-children">
      <?php foreach ($values as $v): ?>
        <div class="flip-card" tabindex="0" data-flip>
          <div class="flip-card__inner">
            <div class="flip-card__face flip-card__front">
              <span class="flip-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $v['icon'] ?></svg>
              </span>
              <h3><?= e($v['name']) ?></h3>
              <span class="flip-card__hint" aria-hidden="true" title="Flip to learn more">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
              </span>
            </div>
            <div class="flip-card__face flip-card__back">
              <h3><?= e($v['name']) ?></h3>
              <p><?= $v['desc'] ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 5. OFFICE (CSS-art panels) -->
<section class="section section--muted">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Where We Work</p>
      <h2 class="has-underline">Inside the Firm</h2>
    </div>
    <div class="office-grid stagger-children">
      <div class="office-panel office-panel--1"><span>Our Offices</span></div>
      <div class="office-panel office-panel--2"><span>Meeting Our Clients</span></div>
      <div class="office-panel office-panel--3"><span>Preparing for Trial</span></div>
    </div>
  </div>
</section>

<!-- 6. COMMUNITY -->
<section class="section">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Giving Back</p>
      <h2 class="has-underline">Community Involvement</h2>
    </div>
    <div class="grid grid--3 stagger-children">
      <?php foreach ($community as $c): ?>
        <div class="card community-card">
          <span class="community-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $c['icon'] ?></svg>
          </span>
          <h3><?= e($c['title']) ?></h3>
          <p class="text-muted"><?= e($c['body']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 8. CTA -->
<?php require __DIR__ . '/includes/sections/cta.php'; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
