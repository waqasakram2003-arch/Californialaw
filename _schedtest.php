<?php
/** TEMP: prove future-dated posts stay hidden until their date. Self-deleting. */
if (($_GET['key'] ?? '') !== 'sched-6t3') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

$slug = '__sched-probe-delete-me';
$mode = $_GET['mode'] ?? '';

try {
    $pdo = db();
    if ($mode === 'create') {
        $future = date('Y-m-d H:i:s', strtotime('+2 days'));
        $pdo->prepare('DELETE FROM blog_posts WHERE slug = ?')->execute([$slug]);
        $pdo->prepare(
            'INSERT INTO blog_posts (title, slug, excerpt, content, status, published_at, meta_title, meta_desc)
             VALUES (?,?,?,?,?,?,?,?)'
        )->execute(['Scheduling probe', $slug, 'probe', '<p>probe</p>', 'published', $future, 'probe', 'probe']);
        echo "created with published_at = $future (status=published)\n";
    } elseif ($mode === 'drop') {
        $pdo->prepare('DELETE FROM blog_posts WHERE slug = ?')->execute([$slug]);
        echo "probe deleted\n";
        @unlink(__FILE__);
    }

    // report how each query path sees it
    $vis = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug=? AND status='published' AND published_at <= NOW()");
    $vis->execute([$slug]);
    $any = $pdo->prepare('SELECT COUNT(*) FROM blog_posts WHERE slug=?');
    $any->execute([$slug]);
    echo "  rows in table            : " . $any->fetchColumn() . "\n";
    echo "  visible to live queries  : " . $vis->fetchColumn() . "  (0 = correctly hidden)\n";
    echo "DONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
