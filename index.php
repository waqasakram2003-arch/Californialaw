<?php
/**
 * index.php — Homepage.
 * Pulls section partials from includes/sections/. Content is DB-driven via
 * includes/repo.php (with graceful fallbacks). Page-specific assets are
 * declared in $page['styles'] / $page['scripts'].
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';

$page = [
    'title'       => 'California Personal Injury Lawyers',
    'description' => 'When you\'re injured in California, we fight back. No upfront fees — we only get '
                   . 'paid when you win. Free, confidential case evaluation. Past results do not guarantee future outcomes.',
    'path'        => '/',
    'styles'      => ['/assets/css/home.css'],
    'scripts'     => ['/assets/js/home.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
    ],
];

require __DIR__ . '/includes/header.php';

$sections = ['hero', 'trust-bar', 'practice-areas', 'why-choose', 'results',
             'testimonials', 'attorneys', 'cta', 'blog', 'service-area'];
foreach ($sections as $s) {
    require __DIR__ . '/includes/sections/' . $s . '.php';
}

require __DIR__ . '/includes/footer.php';
