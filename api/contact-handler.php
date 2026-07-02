<?php
/**
 * api/contact-handler.php — contact page form. Stores a lead in `contacts`.
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
$consent  = !empty($input['consent']) ? 1 : 0;

$errors = [];
if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter your name.';
}
if ($email === '') {
    $errors['email'] = 'Please enter a valid email address.';
}
$phoneDigits = preg_replace('/\D/', '', $phone);
if ($phone !== '' && strlen($phoneDigits) < 7) {
    $errors['phone'] = 'Please enter a valid phone number.';
}
if (!$consent) {
    $errors['consent'] = 'Please confirm you agree to be contacted.';
}
if ($errors) {
    json_response(422, ['ok' => false, 'message' => 'Please check the highlighted fields.', 'errors' => $errors]);
}

try {
    $stmt = db()->prepare(
        'INSERT INTO contacts (name, email, phone, case_type, message, consent, ip_address, status)
         VALUES (:name, :email, :phone, :case_type, :message, :consent, INET6_ATON(:ip), "new")'
    );
    $stmt->execute([
        ':name'      => $name,
        ':email'     => $email,
        ':phone'     => $phone,
        ':case_type' => $caseType !== '' ? $caseType : null,
        ':message'   => $message,
        ':consent'   => $consent,
        ':ip'        => client_ip(),
    ]);
} catch (Throwable $e) {
    json_response(500, ['ok' => false, 'message' => APP_ENV !== 'production'
        ? 'Server error: ' . $e->getMessage()
        : 'Something went wrong. Please call us directly.']);
}

send_mail(SITE_EMAIL, 'New contact form message',
    "Name: $name\nEmail: $email\nPhone: $phone\nCase type: $caseType\n\n$message\n", $email);

json_response(200, [
    'ok'      => true,
    'message' => 'Thank you for reaching out. A member of our team will be in touch shortly.',
]);
