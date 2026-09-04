<?php
/** TEMP: reset the SSD super-admin password at the owner's request. Self-deleting. */
if (($_GET['key'] ?? '') !== 'pwreset-9m3') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');

$R = '/home/u128533805/domains/masonsocialsec.com';
$env = @require "$R/config/env.php";
if (!is_array($env)) { echo "cannot load env.php\n"; @unlink(__FILE__); exit; }

$NEW = 'MSS-a4aen8yUicDdvz3L';
$USER = 'lawshark9';

try {
    $pdo = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
        $env['DB_USER'], $env['DB_PASS'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $st = $pdo->prepare('SELECT id, username, email, role FROM admin_users WHERE username = ?');
    $st->execute([$USER]);
    $u = $st->fetch();
    if (!$u) { echo "account not found: $USER\n"; @unlink(__FILE__); exit; }

    // cost 12 to match the app's own setup.php
    $hash = password_hash($NEW, PASSWORD_BCRYPT, ['cost' => 12]);
    $pdo->prepare('UPDATE admin_users SET password_hash = :h, failed_attempts = 0, locked_until = NULL, is_active = 1 WHERE id = :id')
        ->execute([':h' => $hash, ':id' => $u['id']]);

    // clear any brute-force lockout rows for this user
    try { $pdo->prepare('DELETE FROM login_attempts WHERE username = ?')->execute([$USER]); } catch (Throwable $e) {}

    // verify the new hash actually validates before reporting success
    $chk = $pdo->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
    $chk->execute([$u['id']]);
    $ok = password_verify($NEW, $chk->fetchColumn());

    echo "account : {$u['username']} <{$u['email']}>  role={$u['role']}\n";
    echo "reset   : " . ($ok ? 'OK — new password verifies' : 'FAILED verification') . "\n";
    echo "lockout : cleared\n";
    echo "DONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
