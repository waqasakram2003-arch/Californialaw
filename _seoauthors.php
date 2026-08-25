<?php
/** TEMP: reattribute posts bylined to attorneys with no profile page (404). Self-deleting. */
if (($_GET['key'] ?? '') !== 'authors-6h2') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * 5 posts carry bylines for "Priya Nair" / "Marcus Bell" — attorneys who are no
 * longer on the roster and whose /attorney/<slug>/ pages return 404. That breaks
 * the Person->Article entity chain and publishes an unverifiable author on YMYL
 * legal content. Reassign to a current attorney whose practice matches the topic.
 */
$REASSIGN = [
    // motorcycle + pedestrian/vehicle topics -> Daniel Cho (Senior Trial Attorney, motor vehicle)
    'california-lane-splitting-laws'            => ['Daniel Cho', 'daniel-cho'],
    'pedestrian-right-of-way-laws-california'   => ['Daniel Cho', 'daniel-cho'],
    // core liability / premises / procedure -> Elena Marquez (Founding Partner, trial practice)
    'how-comparative-fault-works-in-california' => ['Elena Marquez', 'elena-marquez'],
    'do-i-need-a-police-report-california'      => ['Elena Marquez', 'elena-marquez'],
    'california-dog-bite-laws-strict-liability' => ['Elena Marquez', 'elena-marquez'],
];

try {
    $pdo = db();

    // Safety: only reassign to attorneys that actually exist and are active.
    $valid = [];
    foreach ($pdo->query("SELECT name, slug FROM attorneys WHERE active = 1")->fetchAll() as $a) {
        $valid[$a['slug']] = $a['name'];
    }
    echo "active attorneys: " . implode(', ', array_keys($valid)) . "\n\n";

    $sel = $pdo->prepare('SELECT id, author_name, author_slug FROM blog_posts WHERE slug = ?');
    $upd = $pdo->prepare('UPDATE blog_posts SET author_name = :n, author_slug = :s WHERE id = :id');
    $n = 0;

    foreach ($REASSIGN as $slug => [$name, $aslug]) {
        if (!isset($valid[$aslug])) { echo "SKIP $slug — target '$aslug' is not an active attorney\n"; continue; }
        $sel->execute([$slug]);
        $row = $sel->fetch();
        if (!$row) { echo "SKIP $slug — post not found\n"; continue; }
        if ($row['author_slug'] === $aslug) { echo "--   $slug already attributed\n"; continue; }
        $upd->execute([':n' => $name, ':s' => $aslug, ':id' => $row['id']]);
        $n++;
        echo "OK   $slug: {$row['author_name']} ({$row['author_slug']}) -> $name ($aslug)\n";
    }
    echo "\nposts reattributed: $n\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
