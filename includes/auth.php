<?php
/**
 * auth.php — Admin authentication & session helpers.
 * Include at the top of any /admin/ page that requires login:
 *   require_once __DIR__ . '/../includes/auth.php';
 *   require_admin();
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

/* ---- Hardened session bootstrap ----------------------------------------- */
function admin_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name(ADMIN_SESSION_NAME);
    session_start();
}

/** True if an admin is currently authenticated. */
function is_logged_in(): bool
{
    admin_session_start();
    return !empty($_SESSION['admin_id']);
}

/** Return the current admin row, or null. */
function current_admin(): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    $stmt = db()->prepare(
        'SELECT id, username, email, last_login, active FROM admin_users WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();
    return ($admin && (int) $admin['active'] === 1) ? $admin : null;
}

/** Gate a page: redirect to login if not authenticated (or deactivated). */
function require_admin(string $loginPath = '/admin/login.php'): void
{
    if (!current_admin()) {
        // Preserve intended destination.
        admin_session_start();
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/admin/';
        redirect($loginPath);
    }
}

/**
 * Attempt login with username/email + password. Returns true on success.
 * Uses password_verify against the stored hash; no info leak on failure.
 */
function attempt_login(string $identifier, string $password): bool
{
    admin_session_start();
    $stmt = db()->prepare(
        'SELECT id, password_hash, active FROM admin_users
         WHERE (username = :u OR email = :e) LIMIT 1'
    );
    $stmt->execute([':u' => $identifier, ':e' => $identifier]);
    $admin = $stmt->fetch();

    if (!$admin || (int) $admin['active'] !== 1 || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    // Transparent rehash if the algorithm/cost changed.
    if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
        $new = password_hash($password, PASSWORD_DEFAULT);
        $upd = db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?');
        $upd->execute([$new, $admin['id']]);
    }

    // Prevent session fixation.
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];

    db()->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = ?')
        ->execute([$admin['id']]);

    return true;
}

/** Destroy the admin session. */
function logout(): void
{
    admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
