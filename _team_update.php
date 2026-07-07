<?php
/**
 * _team_update.php — ONE-TIME team change: remove Priya + Marcus, add Shannon
 * Mason as Founder & CEO (placeholder bio + initials avatar; NO unverified
 * awards). Self-deletes on success. DELETE this file after running.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'gsil-team-7b3c9a';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

try {
    $pdo = db();

    // 1) Remove Priya Nair + Marcus Bell
    $deleted = $pdo->exec("DELETE FROM attorneys WHERE slug IN ('priya-nair','marcus-bell')");
    echo "Removed Priya/Marcus: {$deleted} rows\n";

    // 2) Demote the current founder (Elena) to a senior role + reorder
    $pdo->exec("UPDATE attorneys SET title='Senior Trial Partner', order_num=2 WHERE slug='elena-marquez'");
    $pdo->exec("UPDATE attorneys SET order_num=3 WHERE slug='daniel-cho'");

    // 3) Add Shannon Mason as Founder & CEO (placeholder — swap real photo/bio later)
    $details = json_encode([
        'years'        => null,
        'languages'    => ['English'],
        'practices'    => ['Personal Injury Law'],
        'education'    => [],
        'publications' => [],
        'bio_long'     => [
            'Shannon founded the firm to give injured Californians a dedicated advocate who treats every client like a person, not a case number.',
            'A full professional biography, education history, and verified credentials will be published here soon.',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $bio = 'Shannon Mason is the founder and CEO of the firm. A California personal injury attorney, '
         . 'she is dedicated to helping injured Californians and their families pursue full and fair '
         . 'compensation. A complete biography and credentials will be added soon.';

    $pdo->exec("DELETE FROM attorneys WHERE slug='shannon-mason'"); // idempotent
    $ins = $pdo->prepare(
        "INSERT INTO attorneys (name, title, bio, details, image, slug, order_num, active)
         VALUES (:name, :title, :bio, :details, NULL, 'shannon-mason', 1, 1)"
    );
    $ins->execute([
        ':name'    => 'Shannon Mason',
        ':title'   => 'Founder & CEO',
        ':bio'     => $bio,
        ':details' => $details,
    ]);
    echo "Added Shannon Mason (Founder & CEO).\n\nTeam now:\n";

    foreach ($pdo->query("SELECT name, title, slug, order_num FROM attorneys WHERE active=1 ORDER BY order_num, id") as $r) {
        echo "  {$r['order_num']}. {$r['name']} — {$r['title']}  ({$r['slug']})\n";
    }

    @unlink(__FILE__); // self-destruct
    echo "\nDONE. This script has removed itself.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
