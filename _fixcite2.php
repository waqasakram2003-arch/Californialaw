<?php
/** TEMP: repoint two third-party portal links to official agency pages. Self-deleting. */
if (($_GET['key'] ?? '') !== 'cite2-7r4') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Verified 2026-08-25:
 *  - https://crashes.chp.ca.gov/ => ECONNREFUSED from every agent tested. Hard failure,
 *    not bot-blocking. Replaced with the CHP's own stable landing page, which returns 200:
 *    https://www.chp.ca.gov/traffic/request-a-crash-report/
 *  - https://ecrash.lexisnexis.com/ => 403 to all agents; also could not confirm it is the
 *    current BuyCrash host. Replaced with the City of Folsom's official collision-report
 *    page, which is the authoritative instruction source (and better for E-E-A-T than
 *    linking a third-party vendor portal).
 * Anchor text is left untouched — only the href targets change.
 */
$SWAP = [
  'https://crashes.chp.ca.gov/'   => 'https://www.chp.ca.gov/traffic/request-a-crash-report/',
  'https://ecrash.lexisnexis.com/' => 'https://www.folsom.ca.us/government/police/online-services/request-a-copy-of-a-police-report/request-a-copy-of-collision-report',
];

try {
    $pdo = db();
    $upd = $pdo->prepare('UPDATE blog_posts SET content = :c, date_modified = :d WHERE id = :id');
    $total = 0;
    foreach ($SWAP as $old => $new) {
        $stmt = $pdo->prepare("SELECT id, slug, content FROM blog_posts WHERE content LIKE ?");
        $stmt->execute(['%' . $old . '%']);
        foreach ($stmt->fetchAll() as $r) {
            $c = str_replace($old, $new, $r['content'], $n);
            if (!$n) { continue; }
            $upd->execute([':c' => $c, ':d' => date('Y-m-d H:i:s'), ':id' => $r['id']]);
            $total += $n;
            echo "OK  {$r['slug']}: $n link(s)\n     $old\n  -> $new\n";
        }
    }
    echo "\nlinks repointed: $total\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
