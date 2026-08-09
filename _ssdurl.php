<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-url-8k4w';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$envFile = '/home/u128533805/domains/masonsocialsecurity.com/config/env.php';
if (!is_file($envFile)) { exit("ABORT: env.php not found at $envFile\n"); }
$c = file_get_contents($envFile);

if (preg_match("/'SITE_URL'\\s*=>\\s*'([^']*)'/", $c, $m)) { echo "current SITE_URL: {$m[1]}\n"; }
else { echo "SITE_URL line not found (pattern)\n"; }

$c2 = preg_replace("/('SITE_URL'\\s*=>\\s*)'[^']*'/", "$1'https://masonsocialsecurity.com'", $c, 1);
if ($c2 !== null && $c2 !== $c) {
    file_put_contents($envFile, $c2);
    echo "SITE_URL updated -> https://masonsocialsecurity.com\n";
} else {
    echo "no change made\n";
}

$env = require $envFile;
echo "verify SITE_URL now: " . ($env['SITE_URL'] ?? '(missing)') . "\n";

// flush page cache so canonical/links refresh
$root = '/home/u128533805/domains/masonsocialsecurity.com';
$n = 0;
foreach (glob("$root/storage/cache/*") as $f) { if (is_file($f)) { @unlink($f); $n++; } }
echo "flushed cache: $n\n";

@unlink(__FILE__);
echo "DONE.\n";
