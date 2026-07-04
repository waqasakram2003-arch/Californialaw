<?php
/**
 * functions.php — shared helpers: security, routing, formatting, SEO.
 * Golden State Injury Lawyers
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/* ===========================================================================
   SECURITY / SANITIZATION
   =========================================================================== */

/** Escape for safe HTML output. Use on EVERYTHING echoed into markup. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Sanitize untrusted input.
 * Scalars are trimmed and whitespace-collapsed; arrays are sanitized recursively.
 * NOTE: this is for storage/processing — always still use e() on OUTPUT.
 */
function sanitize($value)
{
    if (is_array($value)) {
        return array_map('sanitize', $value);
    }
    $value = (string) $value;
    // Strip control chars except tab/newline, then collapse runs of whitespace.
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
    return trim(preg_replace('/[ \t]+/', ' ', $value));
}

/** Validate an email; returns the sanitized address or '' if invalid. */
function valid_email(?string $value): string
{
    $value = filter_var((string) $value, FILTER_SANITIZE_EMAIL);
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '';
}

/** Send an HTTP redirect and stop. $url may be relative or absolute. */
function redirect(string $url, int $status = 302): void
{
    if (!preg_match('#^https?://#i', $url)) {
        $url = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
    }
    header('Location: ' . $url, true, $status);
    exit;
}

/* ---- Sessions ------------------------------------------------------------ */

/**
 * Start the public-facing session with hardened cookie params.
 * Call BEFORE any output (header.php does this). Admin pages use the separate
 * admin_session_start() in auth.php and must not include header.php.
 */
function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    if (headers_sent()) {
        // Too late to start cleanly; avoid emitting a warning.
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
    session_start();
}

/* ---- CSRF ---------------------------------------------------------------- */

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return !empty($_SESSION['csrf_token'])
        && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

/* ===========================================================================
   STRINGS / FORMATTING
   =========================================================================== */

/** URL-safe slug from arbitrary text. */
function generateSlug(string $text): string
{
    $text = trim($text);
    // Transliterate accents to ASCII where the intl/iconv path is available.
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        }
    }
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text !== '' ? $text : 'item';
}

/** Format a date string/timestamp for display. */
function formatDate($date, string $format = 'F j, Y'): string
{
    if ($date === null || $date === '') {
        return '';
    }
    try {
        $dt = $date instanceof DateTimeInterface
            ? $date
            : new DateTime(is_numeric($date) ? '@' . $date : (string) $date);
        return $dt->format($format);
    } catch (Exception $e) {
        return '';
    }
}

/** Truncate text to a length on a word boundary, appending an ellipsis. */
function excerpt(string $text, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    $cut = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($cut, ' ');
    if ($lastSpace !== false) {
        $cut = mb_substr($cut, 0, $lastSpace);
    }
    return $cut . '…';
}

/* ===========================================================================
   PAGINATION
   =========================================================================== */

/**
 * Compute pagination metadata.
 * Returns: page, per_page, total, total_pages, offset, has_prev, has_next.
 */
function paginate(int $totalItems, int $page = 1, int $perPage = 10): array
{
    $perPage    = max(1, $perPage);
    $totalPages = max(1, (int) ceil($totalItems / $perPage));
    $page       = min(max(1, $page), $totalPages);
    $offset     = ($page - 1) * $perPage;

    return [
        'page'        => $page,
        'per_page'    => $perPage,
        'total'       => $totalItems,
        'total_pages' => $totalPages,
        'offset'      => $offset,
        'has_prev'    => $page > 1,
        'has_next'    => $page < $totalPages,
        'prev_page'   => max(1, $page - 1),
        'next_page'   => min($totalPages, $page + 1),
    ];
}

/* ===========================================================================
   SEO HELPERS — every page sets $page before including header.php.
   =========================================================================== */

function seo_defaults(array $page): array
{
    $title = $page['title'] ?? 'Personal Injury Attorney';
    $titleFull = $title . ' | ' . SITE_NAME . ' | California Personal Injury Attorney';
    return array_merge([
        'description' => 'California personal injury attorneys serving injured Californians. '
                       . 'Free, confidential case evaluation. Past results do not guarantee future outcomes.',
        'path'        => $_SERVER['REQUEST_URI'] ?? '/',
        'og_image'    => '/assets/images/og-default.jpg',
        'robots'      => 'index, follow',
        'breadcrumbs' => [],
    ], $page, ['title_full' => $titleFull]);
}

/** Absolute URL from a site-relative path. */
function url(string $path = '/'): string
{
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/** Canonical URL for the current/declared path (query string stripped). */
function canonical(array $page): string
{
    $path = $page['path'] ?? ($_SERVER['REQUEST_URI'] ?? '/');
    $path = strtok($path, '?');
    return url($path);
}

/** Schema.org JSON-LD: LegalService + LocalBusiness. Required every page. */
function schema_legalservice(): string
{
    $data = [
        '@context'    => 'https://schema.org',
        '@type'       => ['LegalService', 'LocalBusiness'],
        'name'        => SITE_NAME,
        'description' => 'California personal injury law firm.',
        'url'         => BASE_URL,
        'telephone'   => SITE_PHONE,
        'email'       => SITE_EMAIL,
        'areaServed'  => ['@type' => 'State', 'name' => 'California'],
        'address'     => [
            '@type'          => 'PostalAddress',
            'addressRegion'  => 'CA',
            'addressCountry' => 'US',
        ],
        'priceRange'  => 'Free Consultation',
    ];
    return '<script type="application/ld+json">'
        . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . '</script>';
}

/** Breadcrumb JSON-LD from [['name'=>, 'path'=>], ...]. */
function schema_breadcrumbs(array $items): string
{
    if (empty($items)) {
        return '';
    }
    $list = [];
    foreach ($items as $i => $item) {
        $list[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $item['name'],
            'item'     => url($item['path'] ?? '/'),
        ];
    }
    $data = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $list,
    ];
    return '<script type="application/ld+json">'
        . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . '</script>';
}

/** Two-letter initials from a name (for CSS-art avatar placeholders). */
function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $a = mb_substr($parts[0] ?? '', 0, 1);
    $b = mb_substr($parts[count($parts) - 1] ?? '', 0, 1);
    return mb_strtoupper($a . $b);
}

/**
 * Read an admin-editable site setting (cached), falling back to $default.
 * Empty stored values are treated as "not set" and return $default.
 */
function cfg(string $key, $default = null)
{
    static $s = null;
    if ($s === null) {
        $s = [];
        require_once __DIR__ . '/db.php';
        try {
            foreach (db()->query('SELECT `key`, `value` FROM settings') as $r) {
                $s[$r['key']] = $r['value'];
            }
        } catch (Throwable $e) {
            $s = [];
        }
    }
    return (isset($s[$key]) && $s[$key] !== '') ? $s[$key] : $default;
}

/** Mark a nav link active when it matches the current script. */
function nav_active(string $path): string
{
    $current = $_SERVER['SCRIPT_NAME'] ?? '';
    return str_contains($current, $path) ? ' aria-current="page"' : '';
}

/**
 * Card thumbnail for a practice area. The detail-page HERO keeps the original
 * cinematic image ($area['image'], e.g. pa-<slug>.webp); the CARD prefers a
 * separate real photo at pa-<slug>-card.webp when that file exists, else falls
 * back to the shared image. Lets card and hero use different images.
 */
function pa_card_image(array $area): string
{
    $img = $area['image'] ?? '';
    if ($img === '') return '';
    $card = preg_replace('/(\.[a-z0-9]+)$/i', '-card$1', $img);
    $root = $_SERVER['DOCUMENT_ROOT'] ?: dirname(__DIR__);
    return ($card && is_file($root . $card)) ? $card : $img;
}
