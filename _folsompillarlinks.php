<?php
/** TEMP: back-edits — point supporting posts UP at the Folsom pillar. Self-deleting. */
if (($_GET['key'] ?? '') !== 'pillar-8k5') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Blog 6's brief: "Blogs 3, 4, 5, A, B each add a link UP to this pillar; What to Do
 * After a Car Accident links here." Each edit links the FIRST unlinked occurrence of a
 * natural phrase and is skipped if the post already points at the pillar (idempotent).
 */
$T = '/blog/folsom-car-accident-guide/';

$EDITS = [
  // Blog 3 — Folsom police accident report
  'how-to-get-folsom-police-accident-report' => [
    ['If you were hurt in a crash anywhere in Folsom or on Highway 50',
     'For the full local picture, see our <a href="' . $T . '">complete Folsom car accident guide</a>. If you were hurt in a crash anywhere in Folsom or on Highway 50'],
  ],
  // Blog 4 — e-bike liability
  'e-bike-accident-liability-california' => [
    ['Here\'s what almost nobody riding Folsom\'s 60-plus miles of trails realizes',
     'Here\'s what almost nobody riding Folsom\'s 60-plus miles of trails realizes (our <a href="' . $T . '">Folsom car accident guide</a> covers the wider local picture)'],
  ],
  // Blog 5 — UM/UIM
  'uninsured-underinsured-motorist-claims-california' => [
    ['Mason Law, P.C. handles uninsured and underinsured motorist claims throughout Folsom',
     'If your crash happened locally, our <a href="' . $T . '">Folsom car accident guide</a> walks through the agencies, hospitals, and deadlines involved. Mason Law, P.C. handles uninsured and underinsured motorist claims throughout Folsom'],
  ],
  // Blog A — Move Over law
  'california-move-over-law-2026' => [
    ['Highway 50', 'Highway 50'],   // resolved below by the generic pass
  ],
  // Blog B — 30/60/15 minimums
  'california-30-60-15-insurance-minimums' => [
    ['Highway 50', 'Highway 50'],
  ],
  // What to Do After a Car Accident
  'what-to-do-after-a-car-accident-in-california' => [
    ['<h2>Where a lawyer fits in</h2>',
     '<h2>If your crash happened in Folsom</h2>' . "\n"
     . '<p>The agencies, hospitals, deadlines, and courthouse that apply to a local crash are covered in our <a href="' . $T . '">complete Folsom car accident guide</a>.</p>' . "\n"
     . '<h2>Where a lawyer fits in</h2>'],
  ],
];

/* Blogs A and B need a real sentence rather than a bare phrase — set explicitly. */
$EDITS['california-move-over-law-2026'] = [
  ['<h2>The Bottom Line</h2>',
   '<h2>If your crash happened in Folsom</h2>' . "\n"
   . '<p>Our <a href="' . $T . '">Folsom car accident guide</a> covers which agency responds on the Highway 50 corridor, where you will be treated, and the deadlines that follow.</p>' . "\n"
   . '<h2>The Bottom Line</h2>'],
];
$EDITS['california-30-60-15-insurance-minimums'] = [
  ['<h2>The Bottom Line</h2>',
   '<h2>If your crash happened in Folsom</h2>' . "\n"
   . '<p>See our <a href="' . $T . '">Folsom car accident guide</a> for the local agencies, hospitals, reporting duties, and deadlines that apply.</p>' . "\n"
   . '<h2>The Bottom Line</h2>'],
];

try {
    $pdo = db();
    $sel = $pdo->prepare('SELECT id, content FROM blog_posts WHERE slug = ?');
    $upd = $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id');
    $n = 0;
    foreach ($EDITS as $slug => $pairs) {
        $sel->execute([$slug]);
        $row = $sel->fetch();
        if (!$row) { echo "SKIP (missing): $slug\n"; continue; }
        if (strpos($row['content'], $T) !== false) { echo "--   $slug: already links to the pillar\n"; continue; }

        $c = $row['content']; $done = false;
        foreach ($pairs as [$find, $replace]) {
            if ($done || $find === $replace) { continue; }
            $pos = strpos($c, $find);
            if ($pos === false) { continue; }
            $before = substr($c, 0, $pos);
            if (substr_count($before, '<a ') > substr_count($before, '</a>')) { continue; } // inside an anchor
            $c = substr_replace($c, $replace, $pos, strlen($find));
            $done = true;
        }
        if (!$done) { echo "!!   $slug: no usable anchor found\n"; continue; }
        $upd->execute([':c' => $c, ':d' => date('Y-m-d H:i:s'), ':id' => $row['id']]);
        $n++;
        echo "OK   $slug -> pillar\n";
    }
    echo "\nback-links added: $n\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
