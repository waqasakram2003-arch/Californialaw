<?php
/**
 * config.php — Golden State Injury Lawyers
 * Central configuration. KEEP CREDENTIALS OUT OF VERSION CONTROL.
 *
 * SECURITY: On Hostinger, prefer moving this file one level ABOVE /public_html/
 * and updating the require path in db.php. If it must live inside public_html,
 * the /includes/.htaccess in this folder denies all direct web access.
 *
 * PHP 8+ / MySQL 8+
 */

declare(strict_types=1);

// ---------------------------------------------------------------------------
// Environment
// ---------------------------------------------------------------------------
// Environment is auto-detected by hostname: the Hostinger domain runs as
// 'production', anything else (localhost / XAMPP) stays 'development'.
$__gsil_host = $_SERVER['HTTP_HOST'] ?? '';
$__gsil_prod = (stripos($__gsil_host, 'hostingersite.com') !== false)
            || (stripos($__gsil_host, 'snow-elephant') !== false);
define('APP_ENV', $__gsil_prod ? 'production' : 'development'); // 'development' | 'production'

// ---------------------------------------------------------------------------
// Secrets — config.secret.php defines GSIL_DB_PASS and is NEVER committed
// (repo is public). Prefer a copy ABOVE the web root: Hostinger's Git deploy
// does a clean checkout of public_html and wipes anything untracked, so a
// secret inside public_html would be deleted every deploy. A file one level
// above public_html survives. Fall back to includes/ for local dev.
// ---------------------------------------------------------------------------
// Walk up from includes/ looking for config.secret.php — finds it whether it
// lives in includes/ (local dev) or in any ancestor above public_html (server).
$__gsil_dir = __DIR__;
for ($__i = 0; $__i < 6; $__i++) {
    if (is_file($__gsil_dir . '/config.secret.php')) { require $__gsil_dir . '/config.secret.php'; break; }
    $__gsil_parent = dirname($__gsil_dir);
    if ($__gsil_parent === $__gsil_dir) break; // filesystem root reached
    $__gsil_dir = $__gsil_parent;
}

// ---------------------------------------------------------------------------
// Database (MySQL 8+). Name/user are non-sensitive; password comes from the
// server-only secret file above (GSIL_DB_PASS).
// ---------------------------------------------------------------------------
if (APP_ENV === 'production') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u128533805_law');
    define('DB_USER', 'u128533805_law');   // Hostinger DB user (= DB name)
    define('DB_PASS', defined('GSIL_DB_PASS') ? GSIL_DB_PASS : '');
} else {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'golden_state_injury');
    define('DB_USER', 'root');
    define('DB_PASS', defined('GSIL_DB_PASS') ? GSIL_DB_PASS : '');
}
define('DB_CHARSET', 'utf8mb4');

// ---------------------------------------------------------------------------
// Site constants
// ---------------------------------------------------------------------------
define('SITE_NAME', 'Golden State Injury Lawyers');
define('SITE_TAGLINE', 'California Personal Injury Attorney');
// Sample numbers use the 555-01xx range reserved for fictional use. Replace on launch.
define('SITE_PHONE', '(213) 555-0188');           // TODO: real number
define('SITE_PHONE_RAW', '+12135550188');         // tel: link
define('SITE_EMAIL', 'intake@goldenstateinjury.example'); // TODO: real email
define('SITE_ADDRESS', '633 W 5th St, Los Angeles, CA 90071'); // TODO: real address

// Base URL — no trailing slash. Used for canonical + OG tags.
// Auto-detect for local dev; hard-code on production for safety.
if (APP_ENV === 'production') {
    define('BASE_URL', 'https://snow-elephant-667552.hostingersite.com');
} else {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('BASE_URL', $scheme . '://' . $host);
}

// ---------------------------------------------------------------------------
// Admin (Phase: admin panel will expand this)
// ---------------------------------------------------------------------------
define('ADMIN_SESSION_NAME', 'gsil_admin');

// ---------------------------------------------------------------------------
// Error reporting based on environment
// ---------------------------------------------------------------------------
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Output buffering with gzip when the server isn't already compressing.
// (mod_deflate won't double-compress content already served as gzip.)
if (!ob_get_level()) {
    if (extension_loaded('zlib')
        && !ini_get('zlib.output_compression')
        && stripos($_SERVER['HTTP_ACCEPT_ENCODING'] ?? '', 'gzip') !== false) {
        @ob_start('ob_gzhandler');
    } else {
        ob_start();
    }
}

// Security headers (sent on every page that includes config early)
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-XSS-Protection: 1; mode=block');
}
