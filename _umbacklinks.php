<?php
/** TEMP: back-edits — point existing posts at the new UM/UIM guide. Self-deleting. */
if (($_GET['key'] ?? '') !== 'umback-7c4') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Blog 5's publishing block specified these back-edits within 48 hrs:
 *   (1) 30/60/15 post  -> link here from the "still isn't enough" discussion
 *   (2) Adjusters post -> link here
 *   (3) What to Do After a Car Accident -> link here
 *   (4) Blog 4 (e-bike) insurance-gap link re-points here
 * Each edit links the FIRST unlinked occurrence of a natural phrase, and is skipped
 * if the post already links to the UM/UIM guide (idempotent).
 */
$TARGET = '/blog/uninsured-underinsured-motorist-claims-california/';

$EDITS = [
  'california-30-60-15-insurance-minimums' => [
    ['uninsured/underinsured motorist coverage',
     '<a href="' . $TARGET . '">uninsured/underinsured motorist coverage</a>'],
    ['uninsured or underinsured motorist coverage',
     '<a href="' . $TARGET . '">uninsured or underinsured motorist coverage</a>'],
    ['underinsured motorist',
     '<a href="' . $TARGET . '">underinsured motorist</a>'],
  ],
  'dealing-with-insurance-adjusters-california' => [
    ['Watch the deadline.',
     'If the at-fault driver was uninsured, your own carrier becomes the payer — see <a href="' . $TARGET . '">uninsured and underinsured motorist claims</a>. Watch the deadline.'],
  ],
  'what-to-do-after-a-car-accident-in-california' => [
    ['<strong>Notify your own insurer</strong> as your policy requires.',
     '<strong>Notify your own insurer</strong> as your policy requires — and if the other driver turns out to be uninsured, see <a href="' . $TARGET . '">how UM/UIM claims work</a>, which carry their own much shorter deadlines.'],
  ],
  'e-bike-accident-liability-california' => [
    ['uninsured/underinsured motorist (UM/UIM) coverage</strong>',
     'uninsured/underinsured motorist (UM/UIM) coverage</strong></a>'],  // placeholder, handled below
  ],
];

/* The e-bike edit needs a real anchor rather than a tag patch — handle it explicitly. */
$EDITS['e-bike-accident-liability-california'] = [
  ['The quiet hero is your own <strong>uninsured/underinsured motorist (UM/UIM) coverage</strong>',
   'The quiet hero is your own <strong><a href="' . $TARGET . '">uninsured/underinsured motorist (UM/UIM) coverage</a></strong>'],
];

try {
    $pdo = db();
    $sel = $pdo->prepare('SELECT id, content FROM blog_posts WHERE slug = ?');
    $upd = $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id');
    $total = 0;

    foreach ($EDITS as $slug => $pairs) {
        $sel->execute([$slug]);
        $row = $sel->fetch();
        if (!$row) { echo "SKIP (missing): $slug\n"; continue; }
        if (strpos($row['content'], $TARGET) !== false) { echo "--   $slug: already links to the UM guide\n"; continue; }

        $c = $row['content'];
        $done = false;
        foreach ($pairs as [$find, $replace]) {
            if ($done) { break; }
            $pos = strpos($c, $find);
            if ($pos === false) { continue; }
            // Do not link inside an existing anchor.
            $before = substr($c, 0, $pos);
            if (substr_count($before, '<a ') > substr_count($before, '</a>')) { continue; }
            $c = substr_replace($c, $replace, $pos, strlen($find));
            $done = true;
        }
        if (!$done) { echo "!!   $slug: no usable anchor phrase found\n"; continue; }
        $upd->execute([':c' => $c, ':d' => date('Y-m-d H:i:s'), ':id' => $row['id']]);
        $total++;
        echo "OK   $slug: linked -> UM/UIM guide\n";
    }
    echo "\nback-links added: $total\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
