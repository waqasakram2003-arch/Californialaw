<?php
/** TEMP: expand the case-worth post with an example + checklist (triggers TOC). Self-deleting. */
if (($_GET['key'] ?? '') !== 'worthexp-6q1') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

$slug = 'how-much-is-my-california-car-accident-case-worth';
$block = <<<'HTML'
<h2>A Simple Example of How the Pieces Fit Together</h2>
<p>Here's a purely illustrative example — not a prediction of any result — to show how the factors interact. Imagine a driver is rear-ended on Highway 50 and suffers a herniated disc that needs months of treatment:</p>
<ul>
  <li><strong>Medical bills to date:</strong> $28,000 (ER, imaging, injections, physical therapy)</li>
  <li><strong>Estimated future care:</strong> $12,000</li>
  <li><strong>Lost wages:</strong> $9,000 for time missed at work</li>
  <li><strong>Economic damages subtotal:</strong> about $49,000</li>
  <li><strong>Non-economic (pain and suffering):</strong> negotiated separately, weighed against how severe and lasting the injury is</li>
</ul>
<p>If liability is clear and the at-fault driver carries enough coverage, the claim is built up from that roughly $49,000 foundation, plus non-economic damages for the disruption to the person's life. Now change a single variable. If the injured driver is found 15% at fault, pure comparative negligence trims the recovery by that share. If the at-fault driver carries only California's $30,000 minimum, that policy becomes a hard ceiling — and the injured driver may have to turn to their own underinsured motorist coverage to be made whole. Same injury, very different outcomes. That's exactly why a single "average" figure can't tell you what your claim is worth, and why every case has to be valued on its own facts.</p>

<h2>What to Gather to Protect Your Claim's Value</h2>
<p>The strongest claims are almost always the best-documented ones. As soon as you're able, hold on to:</p>
<ul>
  <li>The police or collision report and the officer's business card</li>
  <li>Photos of the vehicles, the scene, road conditions, and any visible injuries</li>
  <li>Every medical record, bill, and referral — plus a log of the appointments you attended</li>
  <li>Pay stubs or a letter from your employer showing the work and income you lost</li>
  <li>Names and contact details for any witnesses</li>
  <li>A short daily note of your pain levels and the everyday activities you couldn't do</li>
</ul>
<p>None of this has to be perfect. But the more of it you keep, the harder it is for an insurer to argue your injuries weren't real or weren't serious — and the more your claim reflects what you actually lost.</p>
HTML;

try {
    $pdo = db();
    $row = $pdo->prepare('SELECT id, content FROM blog_posts WHERE slug = ?');
    $row->execute([$slug]);
    $r = $row->fetch();
    if (!$r) { echo "post not found\n"; @unlink(__FILE__); exit; }
    if (strpos($r['content'], 'A Simple Example of How the Pieces') !== false) {
        echo "already expanded (skip)\n";
    } else {
        $needle = '<h2>The Bottom Line</h2>';
        if (strpos($r['content'], $needle) === false) { echo "anchor not found\n"; @unlink(__FILE__); exit; }
        $new = str_replace($needle, $block . "\n\n" . $needle, $r['content']);
        $stmt = $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id');
        $stmt->execute([':c' => $new, ':d' => date('Y-m-d H:i:s'), ':id' => $r['id']]);
        $words = str_word_count(strip_tags($new));
        echo "EXPANDED id={$r['id']} new_word_count=$words\n";
    }
    echo "DONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
