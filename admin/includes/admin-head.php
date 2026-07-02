<?php
/** admin-head.php — admin chrome (sidebar + topbar). Expects $pageTitle, $activeNav. */
$pageTitle = $pageTitle ?? 'Admin';
$activeNav = $activeNav ?? '';
$nav = [
    ['dashboard',      'Dashboard',      '/admin/',                'M3 13h8V3H3zM13 21h8V11h-8zM13 3v6h8V3zM3 21h8v-6H3z'],
    ['blog',           'Blog',           '/admin/blog/',           'M4 4h16v16H4zM8 8h8M8 12h8M8 16h5'],
    ['practice-areas', 'Practice Areas', '/admin/practice-areas/', 'M12 2 4 7v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V7z'],
    ['attorneys',      'Attorneys',      '/admin/attorneys/',      'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
    ['testimonials',   'Testimonials',   '/admin/testimonials/',   'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z'],
    ['results',        'Results',        '/admin/results/',        'M3 3v18h18M7 14l4-4 3 3 5-6'],
    ['faqs',           'FAQs',           '/admin/faqs/',           'M12 17h.01M12 13a3 3 0 1 0-3-3M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18z'],
    ['leads',          'Form Submissions','/admin/leads/',         'M22 6 12 13 2 6M2 6h20v12H2z'],
    ['media',          'Media',          '/admin/media/',          'M3 3h18v18H3zM3 15l5-5 4 4 3-3 6 6'],
    ['settings',       'Settings',       '/admin/settings/',       'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-2.82 1.17V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15H4.5a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 6 9.4l-.06-.06A2 2 0 1 1 8.77 6.5l.06.06A1.65 1.65 0 0 0 11 6.6V4.5a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 16.6 6.5l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 11H21a2 2 0 0 1 0 4z'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="csrf" content="<?= e(csrf_token()) ?>">
  <title><?= e($pageTitle) ?> &middot; Admin &middot; <?= e(cfg('firm_name', SITE_NAME)) ?></title>
  <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="/admin/assets/admin.css">
  <?php foreach (($pageStyles ?? []) as $s): ?><link rel="stylesheet" href="<?= e($s) ?>"><?php endforeach; ?>
</head>
<body class="admin">
<div class="admin-shell">
  <aside class="admin-sidebar" data-sidebar>
    <a class="admin-logo" href="/admin/">
      <span class="admin-logo__mark">GS</span>
      <span class="admin-logo__text">Admin Panel</span>
    </a>
    <nav class="admin-nav" aria-label="Admin">
      <?php foreach ($nav as $n): ?>
        <a class="admin-nav__link<?= $activeNav === $n[0] ? ' is-active' : '' ?>" href="<?= e($n[2]) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="<?= $n[3] ?>"/></svg>
          <span><?= e($n[1]) ?></span>
        </a>
      <?php endforeach; ?>
      <a class="admin-nav__link admin-nav__logout" href="/admin/logout.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        <span>Logout</span>
      </a>
    </nav>
  </aside>

  <div class="admin-main">
    <header class="admin-topbar">
      <button class="admin-burger" type="button" data-sidebar-toggle aria-label="Toggle menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="admin-topbar__title"><?= e($pageTitle) ?></h1>
      <div class="admin-topbar__right">
        <a class="admin-topbar__view" href="/" target="_blank" rel="noopener">View Site &#8599;</a>
        <span class="admin-topbar__user"><?= e($ADMIN['username'] ?? 'admin') ?></span>
      </div>
    </header>

    <main class="admin-content">
      <?php foreach (admin_take_flash() as $fl): ?>
        <div class="flash flash--<?= e($fl['type']) ?>"><?= e($fl['msg']) ?></div>
      <?php endforeach; ?>
