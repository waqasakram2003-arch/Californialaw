<?php
/** TEMP: fix the truncated "brain injur" anchor text. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'anchor-2w7') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

try {
    $pdo = db();
    $sel = $pdo->prepare('SELECT id, content FROM blog_posts WHERE slug = ?');
    $sel->execute(['understanding-traumatic-brain-injuries']);
    $row = $sel->fetch();
    if (!$row) { echo "not found\n"; @unlink(__FILE__); exit; }

    $html = $row['content'];
    // "<a ...>brain injur</a>ies"  ->  "<a ...>brain injuries</a>"
    $fixed = preg_replace(
        '#<a href="(/practice-areas/brain-injuries/)">brain injur</a>(ies|y)#i',
        '<a href="$1">brain injur$2</a>',
        $html, 1, $n
    );
    if ($n) {
        $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id')
            ->execute([':c' => $fixed, ':d' => date('Y-m-d H:i:s'), ':id' => $row['id']]);
        echo "FIXED anchor ($n replacement)\n";
    } else {
        // Fallback: show what the anchor actually looks like so we can see it.
        preg_match('#<a href="/practice-areas/brain-injuries/">.{0,30}#i', $html, $m);
        echo "no regex match. context: " . ($m[0] ?? 'none') . "\n";
    }
    echo "DONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
