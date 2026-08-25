<?php
/** TEMP: add authoritative citations to the two posts still lacking them. Self-deleting. */
if (($_GET['key'] ?? '') !== 'cite3-5w9') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Verified 2026-08-25 on leginfo.legislature.ca.gov before linking:
 *   VEH 312.5 — defines Class 1/2/3 e-bikes; 750W cap; 20/20/28 mph cutoffs.
 *               Confirms the class table already published in the e-bike post.
 *   Li v. Yellow Cab Co. (1975) 13 Cal.3d 804 — pure comparative negligence (FindLaw).
 * Only anchors are added; no factual statements are altered.
 */
$EDITS = [
  'e-bike-accident-liability-california' => [
    [
      'California Vehicle Code section 312.5 sorts every legal electric bicycle',
      '<a href="https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=VEH&sectionNum=312.5" target="_blank" rel="noopener">California Vehicle Code section 312.5</a> sorts every legal electric bicycle',
    ],
  ],
  'how-much-is-my-california-car-accident-case-worth' => [
    [
      'California follows <strong>pure comparative negligence</strong>.',
      'California follows <strong>pure comparative negligence</strong>, adopted by the California Supreme Court in <a href="https://caselaw.findlaw.com/ca-supreme-court/1830713.html" target="_blank" rel="noopener"><em>Li v. Yellow Cab Co.</em> (1975)</a>.',
    ],
  ],
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
        $c = $row['content'];
        $applied = 0;
        foreach ($pairs as [$find, $replace]) {
            if (strpos($c, $replace) !== false) { echo "--  $slug: citation already present\n"; continue; }
            if (strpos($c, $find) === false)    { echo "!!  $slug: anchor text not found\n"; continue; }
            $c = str_replace($find, $replace, $c, $n);
            $applied += $n;
        }
        if (!$applied) { continue; }
        $upd->execute([':c' => $c, ':d' => date('Y-m-d H:i:s'), ':id' => $row['id']]);
        $total += $applied;
        echo "OK  $slug: +$applied citation(s)\n";
    }
    echo "\ncitations added: $total\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
