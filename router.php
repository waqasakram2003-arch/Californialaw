<?php
/**
 * router.php — front controller for PHP's built-in server (local dev only).
 * Enables clean practice-area URLs (/practice-areas/<slug>/) without Apache.
 * Hostinger/Apache uses practice-areas/.htaccess for the same rewrite.
 *
 * Usage (launch.json): php -S localhost:8137 -t public_html public_html/router.php
 */

$uri     = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$docroot = __DIR__;

// Serve any existing real file (assets, .php pages, api, images) directly.
if ($uri !== '/' && $uri !== false) {
    $real = realpath($docroot . $uri);
    if ($real !== false && is_file($real) && strpos($real, $docroot) === 0) {
        return false; // let the built-in server handle it
    }
}

// Clean practice-area detail URL → area.php?slug=<slug>
if (preg_match('#^/practice-areas/([a-z0-9][a-z0-9-]*)/?$#', (string) $uri, $m)) {
    $slug = $m[1];
    if ($slug !== 'index' && $slug !== 'area') {
        $_GET['slug'] = $_REQUEST['slug'] = $slug;
        require $docroot . '/practice-areas/area.php';
        return true;
    }
}

// Clean attorney profile URL → attorney/profile.php?slug=<slug>
if (preg_match('#^/attorney/([a-z0-9][a-z0-9-]*)/?$#', (string) $uri, $m)) {
    $slug = $m[1];
    if ($slug !== 'index' && $slug !== 'profile') {
        $_GET['slug'] = $_REQUEST['slug'] = $slug;
        require $docroot . '/attorney/profile.php';
        return true;
    }
}

// Blog category → blog/category.php?slug=<slug>
if (preg_match('#^/blog/category/([a-z0-9][a-z0-9-]*)/?$#', (string) $uri, $m)) {
    $_GET['slug'] = $_REQUEST['slug'] = $m[1];
    require $docroot . '/blog/category.php';
    return true;
}

// Blog single post → blog/post.php?slug=<slug>
if (preg_match('#^/blog/([a-z0-9][a-z0-9-]*)/?$#', (string) $uri, $m)) {
    $slug = $m[1];
    if (!in_array($slug, ['index', 'post', 'category'], true)) {
        $_GET['slug'] = $_REQUEST['slug'] = $slug;
        require $docroot . '/blog/post.php';
        return true;
    }
}

// Dynamic XML sitemap (Apache uses a RewriteRule; this covers the local server).
if ($uri === '/sitemap.xml') {
    require $docroot . '/sitemap.php';
    return true;
}

// Directory-index requests (/, /practice-areas/, /blog/, ...) → let the server
// serve the folder's index.php.
$resolved = realpath($docroot . $uri);
if ($resolved !== false && is_dir($resolved) && is_file($resolved . '/index.php')) {
    return false;
}

// Genuinely unknown path: serve the branded 404 (Apache does this via
// ErrorDocument 404 /404.php; this mirrors it on the local PHP server).
http_response_code(404);
require $docroot . '/404.php';
return true;
