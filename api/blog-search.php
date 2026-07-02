<?php
/**
 * api/blog-search.php — live blog search (JSON, read-only GET).
 */
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/blog-helpers.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$q = trim((string) ($_GET['q'] ?? ''));
$results = searchBlogPosts($q, 6);

$out = array_map(static function ($r) {
    return [
        'title'   => $r['title'],
        'url'     => blog_post_url($r['slug']),
        'cat'     => $r['cat_name'] ?? '',
        'excerpt' => mb_substr(strip_tags((string) ($r['excerpt'] ?? '')), 0, 100),
    ];
}, $results);

echo json_encode(['ok' => true, 'query' => $q, 'results' => $out], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
