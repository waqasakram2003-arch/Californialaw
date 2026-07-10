<?php
/**
 * _shannon.php — ONE-TIME: add Shannon Ramos's real photo, bio, credential.
 * Self-deletes on success. DELETE after running.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'gsil-shannon-3p8w1q';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

$bioLong =
  '<p>Shannon Ramos is a California licensed attorney practicing in Family Law since 2013. '
. 'Ms. Ramos is not your average attorney &mdash; she goes the extra mile for her clients and thinks '
. 'outside the box, using every angle available to achieve your goals while weighing the evidence for '
. 'leverage. Nicknamed &ldquo;El Toro&rdquo; or &ldquo;The Bull,&rdquo; she is tenacious and calculating.</p>'
. '<p>Her attention to detail, arguments, trial skills and writing have earned her respect in the field '
. '&mdash; and the results in her cases speak for themselves.</p>';

$bio = 'Shannon Ramos is a California-licensed attorney practicing family law since 2013. Tenacious, '
     . 'detail-driven, and known in the courtroom as “El Toro,” she brings relentless preparation '
     . 'and trial skill to every case.';
$bio = json_decode('"' . str_replace('"', '\"', $bio) . '"'); // decode the “ escapes

$details = json_encode([
    'years'        => 13,                       // practicing since 2013
    'languages'    => ['English'],
    'practices'    => ['Family Law', 'Trial Litigation'],
    'badges'       => ['Top 100 Trial Lawyers'],
    'education'    => [],
    'publications' => [],
    'links'        => [
        'avvo'     => 'https://www.avvo.com/attorneys/95630-ca-shannon-ramos-4240698.html',
        'linkedin' => 'https://www.linkedin.com/in/shannon-ramos-83b220179/',
    ],
    'bio_long'     => $bioLong,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

try {
    $pdo = db();
    $stmt = $pdo->prepare(
        "UPDATE attorneys
            SET image = :img, bio = :bio, details = :details
          WHERE slug = 'shannon-ramos'"
    );
    $stmt->execute([
        ':img'     => '/assets/images/generated/shannon-ramos.webp',
        ':bio'     => $bio,
        ':details' => $details,
    ]);
    echo "Updated shannon-ramos: " . $stmt->rowCount() . " row(s)\n\n";

    foreach ($pdo->query("SELECT name, title, image, LEFT(bio,60) AS bio FROM attorneys WHERE slug='shannon-ramos'") as $r) {
        echo "  name:  {$r['name']}\n  title: {$r['title']}\n  image: {$r['image']}\n  bio:   {$r['bio']}...\n";
    }

    @unlink(__FILE__);
    echo "\nDONE. This script has removed itself.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
