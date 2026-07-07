<?php
/**
 * _results_update.php — ONE-TIME: reset case_results amounts to realistic
 * average CA personal-injury figures (believable descending spread).
 * Self-deletes on success. DELETE this file after running.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'gsil-results-4d8f2b';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

// id => new realistic amount (matched to each case's type/severity)
$amounts = [
    19 => '$1.85M', // Truck Accident (commercial, I-5)
    20 => '$1.4M',  // Wrongful Death (fatal multi-vehicle)
    21 => '$1.1M',  // Car Accident (spinal, rear-end)
    22 => '$925K',  // Traumatic Brain Injury (pedestrian head injury)
    23 => '$780K',  // Pedestrian Accident (crosswalk)
    24 => '$640K',  // Car Accident (distracted driving, LA)
    25 => '$535K',  // Motorcycle Accident
    26 => '$475K',  // Workplace Injury (third-party)
    27 => '$410K',  // Rideshare Accident (Uber passenger)
    28 => '$340K',  // Slip & Fall (premises)
    29 => '$265K',  // Slip & Fall (staircase)
    30 => '$185K',  // Dog Bite (child)
];

try {
    $pdo = db();
    $stmt = $pdo->prepare('UPDATE case_results SET amount = :amt WHERE id = :id');
    $n = 0;
    foreach ($amounts as $id => $amt) {
        $stmt->execute([':amt' => $amt, ':id' => $id]);
        $n += $stmt->rowCount();
    }
    echo "Updated {$n} case_results rows.\n\nNow:\n";
    foreach ($pdo->query("SELECT id, title, amount FROM case_results ORDER BY id") as $r) {
        echo "  {$r['id']}. {$r['title']} — {$r['amount']}\n";
    }
    @unlink(__FILE__); // self-destruct
    echo "\nDONE. This script has removed itself.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
