<?php
/** TEMP: store the Google Ads tag ID in settings. Self-deleting. */
if (($_GET['key'] ?? '') !== 'adsid-3k7') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

$K = 'ads_id';
$V = 'AW-18449142843';

try {
    $pdo = db();
    $sel = $pdo->prepare('SELECT `value` FROM settings WHERE `key` = ?');
    $sel->execute([$K]);
    $cur = $sel->fetchColumn();

    if ($cur === false) {
        $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (?, ?)')->execute([$K, $V]);
        echo "INSERTED $K = $V\n";
    } elseif ($cur !== $V) {
        $pdo->prepare('UPDATE settings SET `value` = ? WHERE `key` = ?')->execute([$V, $K]);
        echo "UPDATED $K: '$cur' -> '$V'\n";
    } else {
        echo "-- $K already set to $V\n";
    }

    // report the whole tracker picture so the consent behaviour is unambiguous
    foreach (['ga_id', 'ads_id', 'pixel_id'] as $k) {
        $sel->execute([$k]);
        $v = $sel->fetchColumn();
        printf("  %-9s %s\n", $k, ($v === false || $v === '') ? '(not set)' : $v);
    }
    echo "DONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
