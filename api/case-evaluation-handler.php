<?php
/**
 * api/case-evaluation-handler.php — multi-step case evaluation intake.
 * Stores the submission in `case_evaluations`, emails the firm + the client.
 * Security: CSRF + honeypot + IP rate limit + sanitization + prepared statement.
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/form-security.php';

$input = form_gate('case-evaluation', 5);

/* --- helpers --- */
$boolish = static function ($v): int {
    $v = is_string($v) ? strtolower(trim($v)) : $v;
    return in_array($v, ['1', 'yes', 'true', 'on', true, 1], true) ? 1 : 0;
};

/* --- Step 1 --- */
$incidentType = sanitize($input['incident_type'] ?? '');
$incidentDate = trim((string) ($input['incident_date'] ?? ''));
$location     = sanitize($input['location'] ?? '');
$description  = mb_substr(sanitize($input['description'] ?? ''), 0, 600);

// Validate date (Y-m-d) or null.
$dateValid = null;
if ($incidentDate !== '') {
    $dt = DateTime::createFromFormat('Y-m-d', $incidentDate);
    if ($dt && $dt->format('Y-m-d') === $incidentDate && $dt <= new DateTime('today +1 day')) {
        $dateValid = $incidentDate;
    }
}

/* --- Step 2 --- */
$injuriesArr  = isset($input['injuries']) && is_array($input['injuries'])
    ? array_map('sanitize', $input['injuries']) : [];
$injuries     = implode(', ', array_filter($injuriesArr));
$medical      = $boolish($input['medical_treatment'] ?? 0);
$treatType    = sanitize($input['treatment_type'] ?? '');
$physicians   = sanitize($input['physicians'] ?? '');
$stillTreating = $boolish($input['still_treating'] ?? 0);
$policeReport = $boolish($input['police_report'] ?? 0);
$insuranceClaim = $boolish($input['insurance_claim'] ?? 0);

/* --- Step 3 --- */
$name      = sanitize($input['name'] ?? '');
$phone     = sanitize($input['phone'] ?? '');
$email     = valid_email($input['email'] ?? '');
$prefContact = sanitize($input['preferred_contact'] ?? '');
$bestTime  = sanitize($input['best_time'] ?? '');
$hearAbout = sanitize($input['hear_about'] ?? '');
$consent   = !empty($input['consent']) ? 1 : 0;

/* --- Validation --- */
$errors = [];
if ($incidentType === '') {
    $errors['incident_type'] = 'Please select the type of incident.';
}
if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter your full name.';
}
$phoneDigits = preg_replace('/\D/', '', $phone);
if (strlen($phoneDigits) < 7) {
    $errors['phone'] = 'Please enter a valid phone number.';
}
if ($email === '') {
    $errors['email'] = 'Please enter a valid email address.';
}
if (!$consent) {
    $errors['consent'] = 'Please acknowledge the statement to continue.';
}
if ($errors) {
    json_response(422, ['ok' => false, 'message' => 'Please review the highlighted fields.', 'errors' => $errors]);
}

$details = [
    'location'         => $location,
    'treatment_type'   => $treatType,
    'physicians'       => $physicians,
    'still_treating'   => (bool) $stillTreating,
    'insurance_claim'  => (bool) $insuranceClaim,
    'preferred_contact'=> $prefContact,
    'best_time'        => $bestTime,
    'hear_about'       => $hearAbout,
];

try {
    $stmt = db()->prepare(
        'INSERT INTO case_evaluations
            (name, email, phone, incident_date, incident_type, description, injuries,
             medical_treatment, police_report, details, consent, ip_address, status)
         VALUES
            (:name, :email, :phone, :idate, :itype, :descr, :inj,
             :med, :police, :details, :consent, INET6_ATON(:ip), "new")'
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':phone'   => $phone,
        ':idate'   => $dateValid,
        ':itype'   => $incidentType,
        ':descr'   => $description,
        ':inj'     => $injuries,
        ':med'     => $medical,
        ':police'  => $policeReport,
        ':details' => json_encode($details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ':consent' => $consent,
        ':ip'      => client_ip(),
    ]);
} catch (Throwable $e) {
    json_response(500, ['ok' => false, 'message' => APP_ENV !== 'production'
        ? 'Server error: ' . $e->getMessage()
        : 'Something went wrong. Please call us directly.']);
}

/* --- Emails (non-fatal) --- */
$firmBody = "New case evaluation request\n\n"
    . "Name: $name\nPhone: $phone\nEmail: $email\n"
    . "Incident: $incidentType" . ($location ? " in $location" : '') . ($dateValid ? " on $dateValid" : '') . "\n"
    . "Injuries: $injuries\nMedical treatment: " . ($medical ? 'Yes' : 'No')
    . "\nPolice report: " . ($policeReport ? 'Yes' : 'No')
    . "\nInsurance claim: " . ($insuranceClaim ? 'Yes' : 'No')
    . "\nPreferred contact: $prefContact ($bestTime)\nHeard about us: $hearAbout\n\n"
    . "Description:\n$description\n";
send_mail(SITE_EMAIL, 'New Case Evaluation Request', $firmBody, $email);

$clientBody = "Hi $name,\n\nThank you for requesting a free case evaluation from " . SITE_NAME . ". "
    . "We have received your information and a member of our team will contact you shortly"
    . ($bestTime ? " (you indicated: $bestTime)" : '') . ".\n\n"
    . "This message confirms receipt of your request. It is not legal advice and does not create an "
    . "attorney-client relationship. If your matter is urgent, please call us at " . SITE_PHONE . ".\n\n"
    . "— " . SITE_NAME . "\nPast results do not guarantee future outcomes.\n";
send_mail($email, 'We received your case evaluation request', $clientBody);

json_response(200, [
    'ok'      => true,
    'message' => 'Thank you, ' . $name . '. Your request has been received.',
]);
