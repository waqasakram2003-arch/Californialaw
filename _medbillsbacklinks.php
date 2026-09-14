<?php
/** TEMP: back-edits — point existing posts at the new medical-bills guide. Self-deleting. */
if (($_GET['key'] ?? '') !== 'medback-9v2') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Blog 8's brief: "Blog 7 links here (lien line in the worked example) · Blog 6 pillar
 * 'who pays the bills right now' H3 links here · Damages post · TBI post."
 * Blog 7 was published while this post was still a 404, so its lien sentence was
 * deliberately written without the link — this closes that loop.
 */
$T = '/blog/who-pays-medical-bills-after-car-accident-california/';

$EDITS = [
  // Blog 7 — the lien line in the worked example (the link deferred at publish time)
  'personal-injury-lawyer-fees-california' => [
    ['Lien negotiation is one of the least visible things a firm does and one of the most valuable.',
     'Lien negotiation is one of the least visible things a firm does and one of the most valuable — our guide to <a href="' . $T . '">who pays your medical bills after a crash</a> covers how those liens arise and what caps them.'],
  ],
  // Blog 6 pillar — the "who pays the bills right now" H3
  'folsom-car-accident-guide' => [
    ['health insurers typically assert a lien for reimbursement out of your eventual settlement',
     '<a href="' . $T . '">health insurers typically assert a lien</a> for reimbursement out of your eventual settlement'],
  ],
  // Damages post
  'damages-in-a-california-injury-claim' => [
    ['<h2>What the fee takes out</h2>',
     '<h2>Who pays the bills while the claim is pending</h2>' . "\n"
     . '<p>Medical bills arrive long before a settlement does. Our guide to <a href="' . $T . '">who pays your medical bills after a California car accident</a> explains what carries them in the meantime and what gets repaid at the end.</p>' . "\n"
     . '<h2>What the fee takes out</h2>'],
  ],
  // TBI post
  'understanding-traumatic-brain-injuries' => [
    ['<h2>What helps</h2>',
     '<h2>Paying for the care</h2>' . "\n"
     . '<p>Specialist evaluation and neuropsychological testing are not cheap, and the bills arrive while the claim is still open. Our guide to <a href="' . $T . '">who pays your medical bills after a crash</a> covers Med Pay, health coverage, and lien-based treatment.</p>' . "\n"
     . '<h2>What helps</h2>'],
  ],
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
        if (strpos($row['content'], $T) !== false) { echo "--   $slug: already links to the medical-bills guide\n"; continue; }
        $c = $row['content']; $done = false;
        foreach ($pairs as [$find, $replace]) {
            if ($done) { break; }
            $pos = strpos($c, $find);
            if ($pos === false) { continue; }
            $before = substr($c, 0, $pos);
            if (substr_count($before, '<a ') > substr_count($before, '</a>')) { continue; }
            $c = substr_replace($c, $replace, $pos, strlen($find));
            $done = true;
        }
        if (!$done) { echo "!!   $slug: no usable anchor found\n"; continue; }
        $upd->execute([':c' => $c, ':d' => date('Y-m-d H:i:s'), ':id' => $row['id']]);
        $n++;
        echo "OK   $slug -> medical-bills guide\n";
    }
    echo "\nback-links added: $n\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
