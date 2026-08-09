<?php
/**
 * _ssdemail.php — ONE-TIME cross-site deployer (runs on the civil site, writes
 * to the SSD/Mason-Social-Security site on the shared account filesystem).
 * Sets the contact/intake email to masonsocialsecurity@gmail.com. Self-deletes.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-email-7x3k9q';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

$SSD = '/home/u128533805/domains/deeppink-partridge-979149.hostingersite.com';
echo "SSD root: $SSD  exists=" . (is_dir($SSD) ? 'yes' : 'NO') . "\n";
if (!is_dir($SSD)) { exit("ABORT: SSD root not found.\n"); }
$envFile = "$SSD/config/env.php";
if (!is_file($envFile)) { exit("ABORT: env.php not found at $envFile\n"); }
$env = require $envFile;

$newEmail = 'masonsocialsecurity@gmail.com';

/* 1) DB setting email_intake */
try {
    $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=" . ($env['DB_CHARSET'] ?? 'utf8mb4');
    $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $chk = $pdo->prepare('SELECT COUNT(*) FROM settings WHERE setting_key = ?');
    $chk->execute(['email_intake']);
    if ((int) $chk->fetchColumn() > 0) {
        $pdo->prepare('UPDATE settings SET setting_value = ? WHERE setting_key = ?')->execute([$newEmail, 'email_intake']);
        echo "email_intake UPDATED\n";
    } else {
        $pdo->prepare('INSERT INTO settings (setting_key, setting_value, autoload) VALUES (?,?,1)')->execute(['email_intake', $newEmail]);
        echo "email_intake INSERTED\n";
    }
    $v = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = ?'); $v->execute(['email_intake']);
    echo "  now: " . $v->fetchColumn() . "\n";
    // also freshen SMTP_FROM-ish contact keys if present (email_from), harmless if absent
    foreach (['email_from', 'contact_email'] as $k) {
        $c = $pdo->prepare('SELECT COUNT(*) FROM settings WHERE setting_key = ?'); $c->execute([$k]);
        if ((int) $c->fetchColumn() > 0) { $pdo->prepare('UPDATE settings SET setting_value=? WHERE setting_key=?')->execute([$newEmail, $k]); echo "  also set $k\n"; }
    }
} catch (Throwable $e) { echo "DB ERROR: " . $e->getMessage() . "\n"; }

/* 2) code fallback in contact.php */
$cf = "$SSD/templates/contact.php";
if (is_file($cf)) {
    $c = file_get_contents($cf);
    $c2 = str_replace('ssdi@masonlaw.co', $newEmail, $c);
    if ($c2 !== $c) { file_put_contents($cf, $c2); echo "contact.php fallback updated\n"; }
    else { echo "contact.php fallback: pattern not found (ok, DB setting drives it)\n"; }
}

/* 3) flush page cache */
$n = 0;
foreach (glob("$SSD/storage/cache/*") as $f) { if (is_file($f)) { @unlink($f); $n++; } }
echo "flushed cache files: $n\n";

@unlink(__FILE__);
echo "DONE. removed self.\n";
