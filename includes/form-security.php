<?php
/**
 * form-security.php — shared protections for all public form handlers:
 * IP rate limiting (MySQL-backed), email sending, and JSON response helpers.
 * CSRF + honeypot + sanitization live in functions.php and are used directly
 * by each handler.
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

/** Client IP, best-effort. */
function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Record an attempt and report whether this IP is over the limit for $key.
 * Allows up to $max attempts per $windowSeconds; the ($max+1)th is blocked.
 * Fails open (returns false) if the DB is unavailable, so a DB hiccup never
 * locks out legitimate users.
 */
function rate_limited(string $key, int $max = 5, int $windowSeconds = 3600): bool
{
    try {
        $pdo = db();

        // Opportunistic cleanup of old rows (keeps the table small).
        $pdo->exec('DELETE FROM form_attempts WHERE created_at < (NOW() - INTERVAL 1 DAY)');

        $ins = $pdo->prepare(
            'INSERT INTO form_attempts (ip_address, form_key) VALUES (INET6_ATON(:ip), :key)'
        );
        $ins->execute([':ip' => client_ip(), ':key' => $key]);

        // INTERVAL can't take a bound placeholder portably; inline the cast int.
        $cnt = $pdo->prepare(
            'SELECT COUNT(*) FROM form_attempts
             WHERE ip_address = INET6_ATON(:ip) AND form_key = :key
               AND created_at > (NOW() - INTERVAL ' . (int) $windowSeconds . ' SECOND)'
        );
        $cnt->execute([':ip' => client_ip(), ':key' => $key]);
        return ((int) $cnt->fetchColumn()) > $max;
    } catch (Throwable $e) {
        return false; // fail open
    }
}

/**
 * Attempt to send an email. Non-fatal: returns false on failure and never
 * throws, so a mail issue never breaks a form submission (the DB row is the
 * source of truth). Uses PHP mail(); swap for PHPMailer/SMTP in production.
 */
function send_mail(string $to, string $subject, string $body, ?string $replyTo = null): bool
{
    $to = valid_email($to);
    if ($to === '') {
        return false;
    }
    $from = 'no-reply@' . preg_replace('#^https?://(www\.)?#', '', rtrim(BASE_URL, '/'));
    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'From: ' . SITE_NAME . ' <' . $from . '>';
    if ($replyTo && valid_email($replyTo) !== '') {
        $headers[] = 'Reply-To: ' . $replyTo;
    }
    try {
        return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
    } catch (Throwable $e) {
        return false;
    }
}

/** Send a JSON response and stop. */
function json_response(int $code, array $payload): void
{
    if (!headers_sent()) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
    }
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Common front-gate for POST handlers: enforces POST, parses JSON/form input,
 * verifies CSRF, silently absorbs honeypot hits, and applies rate limiting.
 * Returns the sanitized-by-caller raw input array, or responds + exits on
 * failure. $honeypotOk=true means "looked like a bot" -> pretend success.
 */
function form_gate(string $rateKey, int $max = 5): array
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        json_response(405, ['ok' => false, 'message' => 'Method not allowed.']);
    }

    $input = $_POST;
    if (empty($input)) {
        $json = json_decode((string) file_get_contents('php://input'), true);
        if (is_array($json)) {
            $input = $json;
        }
    }

    if (!csrf_verify($input['csrf_token'] ?? null)) {
        json_response(419, ['ok' => false, 'message' => 'Your session expired. Please refresh the page and try again.']);
    }

    // Honeypot — pretend success so bots don't learn anything.
    if (!empty($input['website'])) {
        json_response(200, ['ok' => true, 'message' => 'Thank you. We will be in touch shortly.']);
    }

    if (rate_limited($rateKey, $max)) {
        json_response(429, ['ok' => false, 'message' => 'Too many submissions. Please try again later or call us directly.']);
    }

    return $input;
}
