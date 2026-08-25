<?php
/** TEMP: replace the Justia Li v. Yellow Cab citation (403s to all agents) with FindLaw. Self-deleting. */
if (($_GET['key'] ?? '') !== 'fixcite-3d6') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/* Verified: caselaw.findlaw.com/ca-supreme-court/1830713.html IS Li v. Yellow Cab Co. of
   California, Cal. Supreme Court, decided 31 Mar 1975, adopting pure comparative negligence.
   The Justia URL returns 403 to every user agent tested, so it is a broken outbound link. */
$OLD = 'https://law.justia.com/cases/california/supreme-court/3d/13/804.html';
$NEW = 'https://caselaw.findlaw.com/ca-supreme-court/1830713.html';

try {
    $pdo = db();
    $rows = $pdo->query("SELECT id, slug, content FROM blog_posts WHERE content LIKE '%law.justia.com%'")->fetchAll();
    if (!$rows) { echo "no posts reference the Justia URL\n"; }
    $upd = $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id');
    $n = 0;
    foreach ($rows as $r) {
        $new = str_replace($OLD, $NEW, $r['content'], $count);
        if (!$count) { echo "--  {$r['slug']}: no exact match\n"; continue; }
        $upd->execute([':c' => $new, ':d' => date('Y-m-d H:i:s'), ':id' => $r['id']]);
        $n++;
        echo "OK  {$r['slug']}: replaced $count citation link(s)\n";
    }
    echo "\nposts updated: $n\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
