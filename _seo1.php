<?php
/**
 * _seo1.php — ONE-TIME: add SEO columns to blog_posts.
 *   date_modified DATE, og_image VARCHAR, og_image_alt VARCHAR, faqs TEXT (JSON).
 * Idempotent (checks information_schema). Self-deletes. DELETE after running.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'gsil-seo1-6h4k9d';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

$cols = [
    'date_modified' => "ADD COLUMN `date_modified` DATE NULL AFTER `published_at`",
    'og_image'      => "ADD COLUMN `og_image` VARCHAR(255) NULL",
    'og_image_alt'  => "ADD COLUMN `og_image_alt` VARCHAR(255) NULL",
    'faqs'          => "ADD COLUMN `faqs` TEXT NULL",
];

try {
    $pdo = db();
    $have = [];
    $q = $pdo->prepare("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blog_posts'");
    $q->execute();
    foreach ($q->fetchAll(PDO::FETCH_COLUMN) as $c) { $have[$c] = true; }

    foreach ($cols as $name => $ddl) {
        if (isset($have[$name])) { echo "exists: {$name}\n"; continue; }
        $pdo->exec("ALTER TABLE blog_posts {$ddl}");
        echo "added:  {$name}\n";
    }

    // Demo: seed FAQs + a last-updated date on the newest post so FAQPage /
    // dateModified rich results are testable now. (Admins can edit/remove in CMS.)
    $demoFaqs = "How long do I have to file a personal injury claim in California? :: In most cases you have two years from the date of the injury to file a lawsuit (California Code of Civil Procedure section 335.1). Shorter deadlines can apply — for example, claims against a government agency generally require a written claim within six months. This is general information, not legal advice.\n"
        . "How much does it cost to hire a personal injury attorney? :: Mason Law, P.C. handles injury cases on a contingency fee, which generally means you pay no attorney fee unless there is a recovery in your case. The initial consultation is free and confidential.\n"
        . "What should I do right after a car accident? :: Get medical attention, document the scene and your injuries, keep all records, and avoid discussing fault or posting about the crash online. Speak with an attorney before giving a recorded statement to an insurance company.";
    $newest = (int) $pdo->query("SELECT id FROM blog_posts WHERE status='published' AND published_at <= NOW() ORDER BY published_at DESC LIMIT 1")->fetchColumn();
    if ($newest) {
        $u = $pdo->prepare("UPDATE blog_posts SET faqs = :f, date_modified = :d WHERE id = :id AND (faqs IS NULL OR faqs = '')");
        $u->execute([':f' => $demoFaqs, ':d' => date('Y-m-d'), ':id' => $newest]);
        echo "\nSeeded demo FAQs + date_modified on post id {$newest} ({$u->rowCount()} row).\n";
    }

    echo "\nblog_posts columns now:\n";
    $q2 = $pdo->query("SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blog_posts' ORDER BY ORDINAL_POSITION");
    foreach ($q2 as $r) { echo "  {$r['COLUMN_NAME']} ({$r['DATA_TYPE']})\n"; }

    @unlink(__FILE__);
    echo "\nDONE. This script has removed itself.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
