<?php
/** TEMP: the one description the 158-char guard rejected. Self-deleting. */
if (($_GET['key'] ?? '') !== 'meta2-8x3') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

$slug = 'california-statute-of-limitations-injury-claims';
$desc = 'Most California injury claims expire in two years, but government claims can expire in six months. The deadlines, exceptions, and what to watch.';

try {
    if (mb_strlen($desc) > 158) { throw new RuntimeException('still too long: ' . mb_strlen($desc)); }
    $pdo = db();
    $sel = $pdo->prepare('SELECT id, meta_desc FROM blog_posts WHERE slug = ?');
    $sel->execute([$slug]);
    $row = $sel->fetch();
    if (!$row) { echo "post not found\n"; @unlink(__FILE__); exit; }
    $pdo->prepare('UPDATE blog_posts SET meta_desc = :d WHERE id = :id')
        ->execute([':d' => $desc, ':id' => $row['id']]);
    printf("OK  %s: %d -> %d chars\nDONE.\n", $slug, mb_strlen((string) $row['meta_desc']), mb_strlen($desc));
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
