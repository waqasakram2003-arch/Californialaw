<?php
/** TEMP: build internal-link architecture across legacy blog posts. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'seolinks-5t3') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Per-article contextual links. Each entry: phrase => target URL.
 * Phrases are matched case-sensitively on the FIRST unlinked occurrence only,
 * so anchors stay natural and we never double-link or nest <a> tags.
 */
$MAP = [
  'what-to-do-after-a-car-accident-in-california' => [
    'A car accident'                     => '/practice-areas/car-accidents/',
    'pure comparative negligence rule'   => '/blog/how-comparative-fault-works-in-california/',
    'recorded statements'                => '/blog/dealing-with-insurance-adjusters-california/',
  ],
  'california-statute-of-limitations-injury-claims' => [
    'statute of limitations'             => '/practice-areas/car-accidents/',
    'California Government Claims Act'   => '/blog/how-long-does-a-california-injury-case-take/',
  ],
  'how-comparative-fault-works-in-california' => [
    'comparative fault'                  => '/practice-areas/car-accidents/',
    'damages'                            => '/blog/damages-in-a-california-injury-claim/',
  ],
  'california-lane-splitting-laws' => [
    'lane splitting'                     => '/practice-areas/motorcycle-accidents/',
    'Insurance adjusters'                => '/blog/dealing-with-insurance-adjusters-california/',
  ],
  'dealing-with-insurance-adjusters-california' => [
    'insurance adjuster'                 => '/practice-areas/car-accidents/',
    'settlement'                         => '/blog/how-much-is-my-california-car-accident-case-worth/',
  ],
  'damages-in-a-california-injury-claim' => [
    'personal injury'                    => '/practice-areas/',
    'pain and suffering'                 => '/blog/how-much-is-my-california-car-accident-case-worth/',
  ],
  'do-i-need-a-police-report-california' => [
    'police report'                      => '/blog/how-to-get-folsom-police-accident-report/',
    'car accident'                       => '/practice-areas/car-accidents/',
  ],
  'california-dog-bite-laws-strict-liability' => [
    'dog bite'                           => '/practice-areas/dog-bites/',
    'damages'                            => '/blog/damages-in-a-california-injury-claim/',
  ],
  'how-long-does-a-california-injury-case-take' => [
    'personal injury'                    => '/practice-areas/',
    'statute of limitations'             => '/blog/california-statute-of-limitations-injury-claims/',
  ],
  'uber-lyft-accidents-california-insurance' => [
    'rideshare'                          => '/practice-areas/rideshare-accidents/',
    'insurance'                          => '/blog/california-30-60-15-insurance-minimums/',
  ],
  'pedestrian-right-of-way-laws-california' => [
    'pedestrian'                         => '/practice-areas/pedestrian-accidents/',
    'comparative'                        => '/blog/how-comparative-fault-works-in-california/',
  ],
  'understanding-traumatic-brain-injuries' => [
    'brain injur'                        => '/practice-areas/brain-injuries/',
    'damages'                            => '/blog/damages-in-a-california-injury-claim/',
  ],
];

/**
 * Link the first occurrence of $phrase that is NOT already inside an <a>…</a>,
 * and not inside a heading. Returns null if no safe match.
 */
function link_first(string $html, string $phrase, string $url): ?string
{
    // Split on anchors and headings so we only edit "free" text segments.
    $parts = preg_split('#(<a\b[^>]*>.*?</a>|<h[1-6]\b[^>]*>.*?</h[1-6]>)#is', $html, -1,
                        PREG_SPLIT_DELIM_CAPTURE);
    foreach ($parts as $i => $seg) {
        if ($i % 2 === 1) { continue; }              // protected segment
        $pos = strpos($seg, $phrase);
        if ($pos === false) { continue; }
        // Don't break an HTML tag: ensure the match isn't inside <...>
        $before = substr($seg, 0, $pos);
        if (substr_count($before, '<') !== substr_count($before, '>')) { continue; }
        $parts[$i] = substr($seg, 0, $pos)
                   . '<a href="' . $url . '">' . $phrase . '</a>'
                   . substr($seg, $pos + strlen($phrase));
        return implode('', $parts);
    }
    return null;
}

try {
    $pdo = db();
    $sel = $pdo->prepare('SELECT id, content FROM blog_posts WHERE slug = ?');
    $upd = $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id');
    $totalLinks = 0; $touched = 0;

    foreach ($MAP as $slug => $links) {
        $sel->execute([$slug]);
        $row = $sel->fetch();
        if (!$row) { echo "SKIP (not found): $slug\n"; continue; }
        $html = $row['content'];
        $added = [];
        foreach ($links as $phrase => $url) {
            if (strpos($html, 'href="' . $url . '"') !== false) { continue; } // already linked
            $new = link_first($html, $phrase, $url);
            if ($new !== null) { $html = $new; $added[] = "$phrase→$url"; }
        }
        if ($added) {
            $upd->execute([':c' => $html, ':d' => date('Y-m-d H:i:s'), ':id' => $row['id']]);
            $totalLinks += count($added); $touched++;
            echo "OK  $slug (+" . count($added) . "): " . implode(' | ', $added) . "\n";
        } else {
            echo "--  $slug: no new links\n";
        }
    }
    echo "\nposts updated: $touched   links added: $totalLinks\nDONE.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
@unlink(__FILE__);
