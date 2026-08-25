<?php
/** TEMP: expand thin blog-category descriptions (meta + on-page). Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'seocats-8n4') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

// 150-160 chars, unique, keyword-relevant, human-first. Also renders on-page as the hero subtext.
$D = [
  'auto-accidents'   => 'Guidance for California car, truck, motorcycle, and rideshare accident claims — from what to do at the scene to how fault and settlement value are decided.',
  'insurance-claims' => 'How to handle California insurance claims after an injury: adjuster tactics, policy limits, uninsured motorist coverage, and what to do before you accept an offer.',
  'legal-tips'       => 'Practical guidance on the California personal injury process — filing deadlines, police reports, case timelines, and when it makes sense to talk to an attorney.',
  'your-rights'      => 'Know your rights as an injured Californian: the damages you can recover, how comparative fault works, dog bite liability, and protections after a serious injury.',
];

try {
    $pdo = db();
    $sel = $pdo->prepare('SELECT id, name, description FROM blog_categories WHERE slug = ?');
    $upd = $pdo->prepare('UPDATE blog_categories SET description = :d WHERE id = :id');
    $n = 0;
    foreach ($D as $slug => $desc) {
        $sel->execute([$slug]);
        $row = $sel->fetch();
        if (!$row) { echo "SKIP (missing): $slug\n"; continue; }
        $old = (string) $row['description'];
        if ($old === $desc) { echo "--  $slug: unchanged\n"; continue; }
        $upd->execute([':d' => $desc, ':id' => $row['id']]);
        $n++;
        echo "OK  $slug: " . strlen($old) . " -> " . strlen($desc) . " chars\n";
    }
    echo "\ncategories updated: $n\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
