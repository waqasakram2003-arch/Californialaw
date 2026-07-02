<?php
/**
 * sitemap.php — dynamic XML sitemap (served at /sitemap.xml via rewrite).
 */
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

/** Optionally ping search engines (call after publishing). Not auto-invoked. */
function pingSearchEngines(): void
{
    $sm = urlencode(rtrim(BASE_URL, '/') . '/sitemap.xml');
    @file_get_contents('https://www.google.com/ping?sitemap=' . $sm);
    @file_get_contents('https://www.bing.com/ping?sitemap=' . $sm);
}

$urls = [];
$add = function (string $path, string $freq, string $prio, ?string $lastmod = null) use (&$urls) {
    $urls[] = ['loc' => url($path), 'freq' => $freq, 'prio' => $prio, 'lastmod' => $lastmod];
};

/* Static pages */
$add('/', 'weekly', '1.0');
$add('/about.php', 'monthly', '0.7');
$add('/practice-areas/', 'monthly', '0.9');
$add('/attorney/', 'monthly', '0.7');
$add('/results.php', 'monthly', '0.8');
$add('/blog/', 'daily', '0.8');
$add('/faq.php', 'monthly', '0.6');
$add('/resources.php', 'monthly', '0.5');
$add('/contact.php', 'yearly', '0.6');
$add('/case-evaluation.php', 'yearly', '0.8');
$add('/privacy-policy.php', 'yearly', '0.3');
$add('/terms.php', 'yearly', '0.3');
$add('/disclaimer.php', 'yearly', '0.3');

try {
    $pdo = db();
    foreach ($pdo->query("SELECT slug, updated_at FROM practice_areas WHERE active=1 ORDER BY order_num") as $r) {
        $add('/practice-areas/' . $r['slug'] . '/', 'monthly', '0.8', $r['updated_at']);
    }
    foreach ($pdo->query("SELECT slug, updated_at FROM attorneys WHERE active=1 ORDER BY order_num") as $r) {
        $add('/attorney/' . $r['slug'] . '/', 'monthly', '0.6', $r['updated_at']);
    }
    foreach ($pdo->query("SELECT slug FROM blog_categories ORDER BY name") as $r) {
        $add('/blog/category/' . $r['slug'] . '/', 'weekly', '0.5');
    }
    foreach ($pdo->query("SELECT slug, COALESCE(updated_at, published_at) AS lm FROM blog_posts WHERE status='published' AND published_at <= NOW() ORDER BY published_at DESC") as $r) {
        $add('/blog/' . $r['slug'] . '/', 'monthly', '0.7', $r['lm']);
    }
} catch (Throwable $e) { /* output what we have */ }

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n    <loc>" . e($u['loc']) . "</loc>\n";
    if (!empty($u['lastmod'])) { echo "    <lastmod>" . e(date('Y-m-d', strtotime((string) $u['lastmod']))) . "</lastmod>\n"; }
    echo "    <changefreq>" . e($u['freq']) . "</changefreq>\n";
    echo "    <priority>" . e($u['prio']) . "</priority>\n  </url>\n";
}
echo "</urlset>\n";
