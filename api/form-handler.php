<?php
/**
 * api/form-handler.php — general contact / quick-consultation submissions
 * (homepage hero/CTA, booking widget). Stores a lead in `contacts`.
 * Security: CSRF + honeypot + IP rate limit + sanitization + prepared statement.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/form-security.php';

$input = form_gate('contact', 5);

$name     = sanitize($input['name'] ?? '');
$email    = valid_email($input['email'] ?? '');
$phone    = sanitize($input['phone'] ?? '');
$caseType = sanitize($input['case_type'] ?? '');
$message  = sanitize($input['message'] ?? '');
$source   = sanitize($input['source'] ?? 'homepage');

$errors = [];
if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter your name.';
}
$phoneDigits = preg_replace('/\D/', '', $phone);
if ($email === '' && strlen($phoneDigits) < 7) {
    $errors['phone'] = 'Please enter a valid phone number or email.';
}
if ($phone !== '' && strlen($phoneDigits) < 7) {
    $errors['phone'] = 'Please enter a valid phone number.';
}
if ($errors) {
    json_response(422, ['ok' => false, 'message' => 'Please check the highlighted fields.', 'errors' => $errors]);
}

try {
    $stmt = db()->prepare(
        'INSERT INTO contacts (name, email, phone, case_type, message, ip_address, status)
         VALUES (:name, :email, :phone, :case_type, :message, INET6_ATON(:ip), "new")'
    );
    $stmt->execute([
        ':name'      => $name,
        ':email'     => $email,
        ':phone'     => $phone,
        ':case_type' => $caseType !== '' ? $caseType : null,
        ':message'   => ($message !== '' ? $message . ' ' : '') . '[source: ' . $source . ']',
        ':ip'        => client_ip(),
    ]);
} catch (Throwable $e) {
    json_response(500, ['ok' => false, 'message' => APP_ENV !== 'production'
        ? 'Server error: ' . $e->getMessage()
        : 'Something went wrong. Please call us directly.']);
}

// Notify the firm (non-fatal).
send_mail(SITE_EMAIL, 'New website inquiry (' . $source . ')',
    "Name: $name\nPhone: $phone\nEmail: $email\nCase type: $caseType\nMessage: $message\n", $email ?: null);

json_response(200, [
    'ok'      => true,
    'message' => 'Thank you. Your request has been received — a member of our team will contact you shortly.',
]);
