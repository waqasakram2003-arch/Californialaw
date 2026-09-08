<?php
/** TEMP: back-edits — point existing posts at the new fees guide. Self-deleting. */
if (($_GET['key'] ?? '') !== 'feesback-4h9') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Blog 7's brief: "Damages post links here · How Long Does a Case Take links here ·
 * Blog 6 pillar 'Do You Need a Lawyer?' section links here."
 * Idempotent: skipped if the post already points at the fees guide.
 */
$T = '/blog/personal-injury-lawyer-fees-california/';

$EDITS = [
  'damages-in-a-california-injury-claim' => [
    ['<h2>Proving damages well</h2>',
     '<h2>What the fee takes out</h2>' . "\n"
     . '<p>Damages are the gross figure. What actually reaches you is that number minus the attorney\'s fee, case costs, and any medical liens — walked through line by line in our guide to <a href="' . $T . '">what a personal injury lawyer costs in California</a>.</p>' . "\n"
     . '<h2>Proving damages well</h2>'],
  ],
  'how-long-does-a-california-injury-case-take' => [
    ['<h2>Why fast is not always better</h2>',
     '<h2>What it costs to be represented</h2>' . "\n"
     . '<p>Contingency representation means no upfront cost and no hourly bills — the fee comes out of the recovery at the end. Our guide to <a href="' . $T . '">personal injury lawyer fees in California</a> breaks down the percentages, case costs, and liens.</p>' . "\n"
     . '<h2>Why fast is not always better</h2>'],
  ],
  'folsom-car-accident-guide' => [
    ['Two structural facts make the decision easier than people expect.',
     'Two structural facts make the decision easier than people expect — and our guide to <a href="' . $T . '">what a personal injury lawyer costs in California</a> walks through the fee math in detail.'],
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
        if (strpos($row['content'], $T) !== false) { echo "--   $slug: already links to the fees guide\n"; continue; }
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
        echo "OK   $slug -> fees guide\n";
    }
    echo "\nback-links added: $n\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
