<?php
/**
 * header.php — opens <html>, renders <head> SEO block + site header/nav.
 * Set $page (array) BEFORE including. See functions.php seo_defaults().
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/repo.php';
require_once __DIR__ . '/schema.php';
require_once __DIR__ . '/asset-loader.php';

$page = isset($page) && is_array($page) ? $page : [];
$seo  = seo_defaults($page);

// Start the session before any output so CSRF tokens persist (forms render mid-body).
start_session();

// Maintenance mode: show a holding page to everyone except logged-in admins.
if (cfg('maintenance_mode') === '1') {
    require_once __DIR__ . '/auth.php';
    if (!is_logged_in()) {
        http_response_code(503);
        header('Retry-After: 3600');
        $fn = e(cfg('firm_name', SITE_NAME));
        $ph = e(cfg('site_phone', SITE_PHONE));
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
           . '<title>We&rsquo;ll be right back — ' . $fn . '</title>'
           . '<style>body{margin:0;font-family:system-ui,sans-serif;background:#0A0F1E;color:#E2E8F0;display:grid;place-items:center;min-height:100vh;text-align:center;padding:2rem}'
           . 'h1{font-family:Georgia,serif;color:#fff} a{color:#E8C97E}</style></head><body><div>'
           . '<h1>We&rsquo;ll be right back</h1><p>Our site is briefly down for maintenance. For urgent matters, call <a href="tel:'
           . e(cfg('site_phone_raw', SITE_PHONE_RAW)) . '">' . $ph . '</a>.</p></div></body></html>';
        exit;
    }
}

// Practice areas power the mega-dropdown (falls back if DB is unavailable).
$navAreas = getPracticeAreas();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FOUC guard: set theme before first paint -->
  <script>
    (function () {
      try {
        var t = localStorage.getItem('gsil-theme');
        if (!t) {
          t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        document.documentElement.setAttribute('data-theme', t);
      } catch (e) {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    })();
  </script>

  <title><?= e($seo['title_full']) ?></title>
  <meta name="description" content="<?= e($seo['description']) ?>">
  <meta name="robots" content="<?= e($seo['robots']) ?>">
  <link rel="canonical" href="<?= e(canonical($seo)) ?>">

  <!-- Open Graph -->
  <meta property="og:type" content="<?= e($seo['og_type'] ?? 'website') ?>">
  <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
  <meta property="og:title" content="<?= e($seo['title_full']) ?>">
  <meta property="og:description" content="<?= e($seo['description']) ?>">
  <meta property="og:url" content="<?= e(canonical($seo)) ?>">
  <meta property="og:image" content="<?= e(url($seo['og_image']) . '?v=mason2') ?>">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="<?= e(SITE_NAME) ?> — California Personal Injury Attorneys">
  <meta property="og:locale" content="en_US">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($seo['title_full']) ?>">
  <meta name="twitter:description" content="<?= e($seo['description']) ?>">
  <meta name="twitter:image" content="<?= e(url($seo['og_image']) . '?v=mason2') ?>">

  <!-- Favicon / PWA -->
  <link rel="icon" href="/assets/images/favicon.ico" sizes="any">
  <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">
  <link rel="manifest" href="/assets/images/manifest.json">
  <meta name="theme-color" content="#1B2A4A" media="(prefers-color-scheme: light)">
  <meta name="theme-color" content="#0A0F1E" media="(prefers-color-scheme: dark)">

  <!-- Resource hints: warm up font + animation CDNs before they're needed -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://www.googletagmanager.com">
  <link rel="dns-prefetch" href="https://connect.facebook.net">

  <!-- Fonts: Bebas Neue (display) + Inter (everything else). Preload, then load non-blocking. -->
  <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>

  <!-- Critical above-fold CSS (inlined to avoid a flash before the bundle loads) -->
  <style>html{background:#F8F9FC}html[data-theme="dark"]{background:#0A0F1E}body{margin:0;font-family:'Inter',system-ui,-apple-system,sans-serif;background:#F8F9FC;color:#2D3748;-webkit-font-smoothing:antialiased}html[data-theme="dark"] body{background:#0A0F1E;color:#E2E8F0}.site-header{position:sticky;top:0;min-height:76px}img,svg,video{max-width:100%;display:block}a{color:inherit;text-decoration:none}</style>

  <!-- Styles (cache-busted; combined + minified in production) -->
  <?php asset_styles(array_merge(['/assets/css/design-system.css', '/assets/css/main.css', '/assets/css/animations.css', '/assets/css/phase10.css', '/assets/css/media-fx.css', '/assets/css/chatbot.css'], $seo['styles'] ?? [])); ?>

  <!-- Schema.org -->
  <?= schemaSitewide() ?>
  <?php if (!empty($seo['breadcrumbs'])): ?><?= schemaBreadcrumb($seo['breadcrumbs']) ?><?php endif; ?>
  <?php foreach (($seo['schema'] ?? []) as $jsonld): ?><?= $jsonld ?><?php endforeach; ?>

  <?php /* Analytics — consent-gated (CCPA); only loads after the visitor accepts cookies */ ?>
  <?php require_once __DIR__ . '/analytics.php'; analytics_head(); ?>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" data-header>
  <div class="container container--wide site-header__inner">
    <a class="brand" href="/" aria-label="<?= e(SITE_NAME) ?> home">
      <img class="brand__logo" src="<?= asset_url('/assets/images/logo-light.webp') ?>" width="478" height="150"
           alt="<?= e(SITE_NAME) ?>"
           data-logo data-logo-light="<?= asset_url('/assets/images/logo-light.webp') ?>" data-logo-dark="<?= asset_url('/assets/images/logo-dark.webp') ?>">
    </a>

    <nav class="main-nav" id="main-nav" data-nav aria-label="Primary">
      <ul class="main-nav__list" role="list">
        <li><a class="main-nav__link" href="/"<?= nav_active('index') ?>>Home</a></li>

        <li class="main-nav__item has-mega" data-mega>
          <button class="main-nav__link main-nav__trigger" type="button" aria-expanded="false" aria-haspopup="true">
            Practice Areas
            <svg class="main-nav__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="mega-menu" data-mega-panel>
            <div class="mega-menu__inner">
              <p class="mega-menu__eyebrow">Personal Injury Practice Areas</p>
              <div class="mega-menu__grid">
                <?php foreach ($navAreas as $navArea): ?>
                  <a class="mega-card" href="/practice-areas/<?= e($navArea['slug']) ?>/">
                    <span class="mega-card__icon"><?= practice_icon($navArea['icon'] ?? '') ?></span>
                    <span class="mega-card__text">
                      <span class="mega-card__title"><?= e($navArea['title']) ?></span>
                      <span class="mega-card__desc"><?= e($navArea['short_desc']) ?></span>
                    </span>
                  </a>
                <?php endforeach; ?>
              </div>
              <a class="mega-menu__all" href="/practice-areas/">View all practice areas
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </a>
            </div>
          </div>
        </li>

        <li class="main-nav__item"><a class="main-nav__link" href="/about.php"<?= nav_active('/about') ?>>About</a></li>
        <li class="main-nav__item"><a class="main-nav__link" href="/reviews.php"<?= nav_active('/reviews') ?>>Reviews</a></li>
        <li class="main-nav__item"><a class="main-nav__link" href="/blog/"<?= nav_active('/blog') ?>>Blog</a></li>
        <li class="main-nav__item"><a class="main-nav__link" href="/contact.php"<?= nav_active('/contact') ?>>Contact</a></li>
      </ul>

      <!-- Shown inside the mobile overlay only -->
      <div class="main-nav__mobile-actions">
        <a class="nav-phone" href="tel:<?= e(SITE_PHONE_RAW) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <?= e(SITE_PHONE) ?>
        </a>
        <a class="btn btn--primary btn--glow" href="/case-evaluation.php">Free Consultation</a>
      </div>
    </nav>

    <div class="header-actions">
      <a class="nav-phone nav-phone--header" href="tel:<?= e(SITE_PHONE_RAW) ?>" aria-label="Call <?= e(SITE_PHONE) ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span class="nav-phone__num"><?= e(SITE_PHONE) ?></span>
      </a>

      <button class="theme-toggle" data-theme-toggle type="button"
              aria-label="Switch to dark theme" aria-pressed="false">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="4"/>
          <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
        </svg>
      </button>

      <a class="btn btn--primary btn--glow header-cta" href="/case-evaluation.php" data-magnetic="0.25" data-ripple>Free Consultation</a>

      <button class="nav-toggle" data-nav-toggle type="button"
              aria-label="Open menu" aria-expanded="false" aria-controls="main-nav">
        <span class="nav-toggle__box" aria-hidden="true">
          <span class="nav-toggle__line"></span>
          <span class="nav-toggle__line"></span>
          <span class="nav-toggle__line"></span>
        </span>
      </button>
    </div>
  </div>
</header>
<div class="nav-scrim" data-nav-scrim hidden></div>

<main id="main">
