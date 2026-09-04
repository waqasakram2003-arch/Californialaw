<?php
/** TEMP read-only: list admin accounts on the SSD site. No changes. Self-deleting. */
if (($_GET['key'] ?? '') !== 'admininfo-4b7') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');

$R = '/home/u128533805/domains/masonsocialsec.com';
$env = @require "$R/config/env.php";
if (!is_array($env)) { echo "cannot load env.php\n"; @unlink(__FILE__); exit; }

try {
    $pdo = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
        $env['DB_USER'], $env['DB_PASS'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    // Deliberately NOT selecting password_hash — nothing to gain, and it should not travel.
    $rows = $pdo->query(
        'SELECT id, username, email, role, display_name, is_active, failed_attempts, locked_until, last_login_at, created_at
         FROM admin_users ORDER BY id'
    )->fetchAll();

    echo "admin_users on masonsocialsec.com: " . count($rows) . "\n\n";
    foreach ($rows as $r) {
        foreach ($r as $k => $v) { printf("  %-16s %s\n", $k, $v === null ? 'NULL' : $v); }
        echo "  " . str_repeat('-', 46) . "\n";
    }

    // Is the seeded default hash still in place? (tells us if the password was ever changed)
    $seedHash = '$2y$12$BcwCilQFd2M1g2p/VwRPJuSm1TB8UCqfluh7qVElkP6uUTI6w2DUa';
    $st = $pdo->prepare('SELECT username FROM admin_users WHERE password_hash = ?');
    $st->execute([$seedHash]);
    $stillSeed = $st->fetchAll(PDO::FETCH_COLUMN);
    echo "\naccounts still using the seeded demo hash: "
       . ($stillSeed ? implode(', ', $stillSeed) : 'none (password was changed)') . "\n";

    echo "setup.php present on server: " . (is_file("$R/public_html/setup.php") ? 'YES' : 'no') . "\n";
    echo "DONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
