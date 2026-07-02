<?php
/**
 * db.php — PDO MySQL connection (singleton).
 * Always use prepared statements via the returned PDO instance.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Returns a shared PDO connection. Throws on failure (caught here in prod).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_NAME,
        DB_CHARSET
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
        PDO::ATTR_STRINGIFY_FETCHES  => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if (APP_ENV === 'production') {
            http_response_code(503);
            // Never leak DB details to the client in production.
            exit('Service temporarily unavailable. Please try again shortly.');
        }
        // Dev: surface the real error.
        throw $e;
    }

    return $pdo;
}
