<?php
/**
 * _civil_update.php — ONE-TIME July 2026 client-feedback DB updates (civil site).
 * Key-guarded, self-deleting. Run once via:  /_civil_update.php?key=civil-9k2m7x
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'civil-9k2m7x';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

$log = [];
try {
    $pdo = db();

    /* 1) Settings: email + office hours (Calendly URL left blank until provided) */
    $set = $pdo->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
    foreach ([
        'site_email'   => 'civil@masonlaw.co',
        'office_hours' => 'Mon–Fri, 9:00am–5:00pm',
    ] as $k => $v) {
        $set->execute([$k, $v]);
        $log[] = "setting $k = $v";
    }

    /* 2) Shannon Ramos: correct Top-100 badge org + swap "tenacity" wording for loyalty */
    $bioLong =
      '<p>Shannon Ramos is a California licensed attorney practicing in Family Law since 2013. '
    . 'Ms. Ramos is not your average attorney &mdash; she goes the extra mile for her clients and thinks '
    . 'outside the box, using every angle available to achieve your goals while weighing the evidence for '
    . 'leverage. Nicknamed &ldquo;El Toro&rdquo; or &ldquo;The Bull,&rdquo; she is loyal, relentless, and calculating.</p>'
    . '<p>Her attention to detail, arguments, trial skills and writing have earned her respect in the field '
    . '&mdash; and the results in her cases speak for themselves.</p>';

    $bio = 'Shannon Ramos is a California-licensed attorney practicing family law since 2013. Loyal, '
         . 'detail-driven, and known in the courtroom as “El Toro,” she brings relentless preparation '
         . 'and trial skill to every case.';
    $bio = json_decode('"' . str_replace('"', '\"', $bio) . '"');

    $details = json_encode([
        'years'        => 13,
        'languages'    => ['English', 'Spanish'],
        'practices'    => ['Family Law', 'Criminal Defense', 'Trial Litigation'],
        'badges'       => ['The National Top 100 Trial Lawyers'],
        'education'    => [],
        'publications' => [],
        'links'        => [
            'avvo'     => 'https://www.avvo.com/attorneys/95630-ca-shannon-ramos-4240698.html',
            'linkedin' => 'https://www.linkedin.com/in/shannon-ramos-83b220179/',
        ],
        'bio_long'     => $bioLong,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("UPDATE attorneys SET bio = :bio, details = :details WHERE slug = 'shannon-ramos'");
    $stmt->execute([':bio' => $bio, ':details' => $details]);
    $log[] = "attorneys shannon-ramos updated: " . $stmt->rowCount() . " row(s)";

    /* 3) Scrub any lingering "tenacity/tenacious" in DB testimonials/pages text */
    $pdo->exec("UPDATE testimonials SET quote = REPLACE(REPLACE(quote,'tenacity','loyalty'),'tenacious','loyal') WHERE quote LIKE '%tenaci%'");

    echo "=== CIVIL UPDATE REPORT ===\n" . implode("\n", $log) . "\n=== DONE ===\n";
    @unlink(__FILE__);
    echo "(script removed itself)\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
