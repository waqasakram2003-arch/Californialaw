<?php
/**
 * TEMP: prove the GA4 + Google Ads dual-config path renders correctly, using an
 * obviously-fake measurement ID, then REMOVE it in the same run. Self-deleting.
 *
 * Why a live test: the shared-loader code was written when only the Ads tag existed,
 * so the GA4 branch has never actually executed. Testing it now means the user's real
 * ID works first time instead of being debugged in production.
 *
 * ?key=...&mode=set   -> set the fake ID
 * ?key=...&mode=unset -> remove it (leaves ga_id unset, exactly as before)
 */
if (($_GET['key'] ?? '') !== 'ga4test-5p8') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

$FAKE = 'G-TEST0000000';   // not a real property; used only to render the branch
$mode = $_GET['mode'] ?? '';

try {
    $pdo = db();
    if ($mode === 'set') {
        $sel = $pdo->prepare('SELECT `value` FROM settings WHERE `key` = ?');
        $sel->execute(['ga_id']);
        if ($sel->fetchColumn() === false) {
            $pdo->prepare('INSERT INTO settings (`key`, `value`) VALUES (?,?)')->execute(['ga_id', $FAKE]);
        } else {
            $pdo->prepare('UPDATE settings SET `value` = ? WHERE `key` = ?')->execute([$FAKE, 'ga_id']);
        }
        echo "SET ga_id = $FAKE\n";
    } elseif ($mode === 'unset') {
        $pdo->prepare('DELETE FROM settings WHERE `key` = ?')->execute(['ga_id']);
        echo "UNSET ga_id (removed)\n";
        @unlink(__FILE__);   // only self-delete on the cleanup run
    } else {
        echo "no mode given\n";
    }
    $r = $pdo->query("SELECT `key`,`value` FROM settings WHERE `key` IN ('ga_id','ads_id')")->fetchAll();
    foreach ($r as $row) { printf("  %-8s %s\n", $row['key'], $row['value']); }
    echo "DONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
