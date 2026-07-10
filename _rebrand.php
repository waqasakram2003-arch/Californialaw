<?php
/**
 * _rebrand.php — ONE-TIME: rebrand the site to Mason Law, P.C.
 *   - updates the settings table (firm name, phone, email, address, socials)
 *   - renames the founder placeholder to the real attorney (Shannon Ramos)
 * Self-deletes on success. DELETE this file after running.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'gsil-rebrand-9k2m7x';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

$settings = [
    'firm_name'        => 'Mason Law, P.C.',
    'site_phone'       => '(916) 587-2997',
    'site_phone_raw'   => '+19165872997',
    'site_email'       => 'info@lawyermason.com',
    'site_address'     => '1024 Iron Point Road, Folsom, CA 95630',
    'social_facebook'  => 'https://www.facebook.com/mlomarin/',
    'social_x'         => 'https://twitter.com/office_mason',
    'social_linkedin'  => 'https://www.linkedin.com/in/shannon-ramos-83b220179/',
    'social_avvo'      => 'https://www.avvo.com/attorneys/95630-ca-shannon-ramos-4240698.html',
    'social_yelp'      => 'https://www.yelp.com/biz/mason-law-office-sacramento-10',
    'social_instagram' => '',   // firm has no Instagram — clear it
    'footer_disclaimer'=> '',   // '' => cfg() falls back to the (now Mason Law) default
];

try {
    $pdo = db();

    // 1) Settings upsert (settings.key is unique)
    $up = $pdo->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
    foreach ($settings as $k => $v) { $up->execute([$k, $v]); }
    echo "Settings updated (" . count($settings) . " keys).\n";

    // 2) Rename founder placeholder -> real attorney Shannon Ramos
    $details = json_encode([
        'years'        => null,
        'languages'    => ['English'],
        'practices'    => ['Trial Advocacy', 'Litigation'],
        'education'    => [],
        'publications' => [],
        'links'        => [
            'avvo'     => 'https://www.avvo.com/attorneys/95630-ca-shannon-ramos-4240698.html',
            'linkedin' => 'https://www.linkedin.com/in/shannon-ramos-83b220179/',
        ],
        'bio_long'     => [
            'Shannon Ramos is the founder of Mason Law, P.C., a California trial attorney dedicated to advocating for her clients.',
            'A full professional biography, education history, and verified credentials will be published here soon.',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $bio = 'Shannon Ramos is the founder of Mason Law, P.C. A California trial attorney, she is known as '
         . 'a strong, determined advocate for her clients. A complete biography and credentials will be added soon.';

    $stmt = $pdo->prepare(
        "UPDATE attorneys
            SET name = :name, title = :title, slug = :slug, bio = :bio, details = :details
          WHERE slug = 'shannon-mason'"
    );
    $stmt->execute([
        ':name'    => 'Shannon Ramos',
        ':title'   => 'Founder & Attorney at Law',
        ':slug'    => 'shannon-ramos',
        ':bio'     => $bio,
        ':details' => $details,
    ]);
    echo "Attorney rows updated: " . $stmt->rowCount() . " (shannon-mason -> shannon-ramos)\n\n";

    echo "Team now:\n";
    foreach ($pdo->query("SELECT name, title, slug, order_num FROM attorneys WHERE active=1 ORDER BY order_num, id") as $r) {
        echo "  {$r['order_num']}. {$r['name']} — {$r['title']}  ({$r['slug']})\n";
    }
    echo "\nBrand settings now:\n";
    $keys = "'" . implode("','", array_keys($settings)) . "'";
    foreach ($pdo->query("SELECT `key`,`value` FROM settings WHERE `key` IN ($keys) ORDER BY `key`") as $r) {
        echo "  {$r['key']} = {$r['value']}\n";
    }

    @unlink(__FILE__); // self-destruct
    echo "\nDONE. This script has removed itself.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
