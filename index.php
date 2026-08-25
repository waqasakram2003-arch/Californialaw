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
    'description' => 'Injured in California? Our personal injury attorneys fight insurers for '
                   . 'you. No upfront fees. Free, confidential case evaluation.',
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
