<?php
/** TEMP: trim over-long meta titles/descriptions to SERP-safe lengths. Self-deleting. */
if (($_GET['key'] ?? '') !== 'meta-2p7') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Google truncates titles around 60-65 chars and descriptions around 155-160.
 * These were 167-179 chars and were being cut mid-sentence in the SERP, wasting
 * the CTA. Rewritten (not mechanically truncated) to stay under 158 while keeping
 * the primary keyword early and a reason to click. No factual claims changed.
 */
$TITLES = [
    // 72 chars before the brand suffix -> trimmed
    'california-statute-of-limitations-injury-claims' => 'California Statute of Limitations for Injury Claims',
];

$DESCS = [
'e-bike-accident-liability-california' =>
  'Who pays after a California e-bike crash? Classes 1-3, trail rules, the 2025-26 equipment laws, and the insurance gap most riders never see coming.',
'how-much-is-my-california-car-accident-case-worth' =>
  'What actually determines a California car accident settlement: medical bills, lost wages, pain and suffering, fault, and insurance limits.',
'california-30-60-15-insurance-minimums' =>
  "California's auto insurance minimums rose to 30/60/15 in 2025. What the numbers mean, who they protect, and why they still fall short.",
'what-to-do-after-a-car-accident-in-california' =>
  'What to do after a California car accident: the steps that protect your claim, the 24-hour police and 10-day DMV deadlines, and mistakes to avoid.',
'california-statute-of-limitations-injury-claims' =>
  'Most California injury claims expire in two years - but government claims can expire in six months. The deadlines, exceptions, and what happens if you miss one.',
'how-comparative-fault-works-in-california' =>
  'California is a pure comparative fault state: being partly to blame reduces your recovery but never bars it. How the percentages are set.',
'california-lane-splitting-laws' =>
  'California lane splitting law explained: what Vehicle Code 21658.1 actually says, why there is no statutory speed limit, and how fault is decided.',
'dealing-with-insurance-adjusters-california' =>
  'What California law requires of insurance adjusters, why recorded statements are risky, and how to handle the first offer without hurting your claim.',
'damages-in-a-california-injury-claim' =>
  'Economic and non-economic damages in a California injury claim, how future losses are valued, and what Proposition 51 means for multiple defendants.',
'california-dog-bite-laws-strict-liability' =>
  'California dog bite law: why Civil Code 3342 makes owners strictly liable, who is protected, the defenses that still apply, and the filing deadline.',
'how-long-does-a-california-injury-case-take' =>
  'How long a California injury case takes: the stages from treatment to settlement, what causes delays, and why rushing a claim usually costs money.',
'pedestrian-right-of-way-laws-california' =>
  'California pedestrian right-of-way law: what Vehicle Code 21950 requires of drivers, the duties pedestrians owe, and how crosswalk fault is decided.',
];

try {
    $pdo = db();
    $selT = $pdo->prepare('SELECT id, meta_title FROM blog_posts WHERE slug = ?');
    $updT = $pdo->prepare('UPDATE blog_posts SET meta_title = :t WHERE id = :id');
    $t = 0;
    foreach ($TITLES as $slug => $title) {
        $selT->execute([$slug]);
        $row = $selT->fetch();
        if (!$row) { echo "SKIP title: $slug\n"; continue; }
        if ($row['meta_title'] === $title) { echo "--   title already set: $slug\n"; continue; }
        $updT->execute([':t' => $title, ':id' => $row['id']]);
        $t++;
        printf("OK   title %-48s %d -> %d chars\n", $slug, mb_strlen((string) $row['meta_title']), mb_strlen($title));
    }

    $selD = $pdo->prepare('SELECT id, meta_desc FROM blog_posts WHERE slug = ?');
    $updD = $pdo->prepare('UPDATE blog_posts SET meta_desc = :d WHERE id = :id');
    $d = 0;
    foreach ($DESCS as $slug => $desc) {
        if (mb_strlen($desc) > 158) { printf("!!   desc too long (%d), skipping: %s\n", mb_strlen($desc), $slug); continue; }
        $selD->execute([$slug]);
        $row = $selD->fetch();
        if (!$row) { echo "SKIP desc: $slug\n"; continue; }
        $updD->execute([':d' => $desc, ':id' => $row['id']]);
        $d++;
        printf("OK   desc  %-48s %d -> %d chars\n", $slug, mb_strlen((string) $row['meta_desc']), mb_strlen($desc));
    }
    echo "\ntitles updated: $t   descriptions updated: $d\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
