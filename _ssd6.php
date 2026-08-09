<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd6-9v4k';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }
$envFile = '/home/u128533805/domains/masonsocialsec.com/config/env.php';
if (!is_file($envFile)) { exit("ABORT: env.php not found\n"); }
$c = file_get_contents($envFile);
preg_match("/'SITE_URL'\\s*=>\\s*'([^']*)'/", $c, $m);
echo "current SITE_URL: " . ($m[1] ?? '?') . "\n";
$c2 = preg_replace("/('SITE_URL'\\s*=>\\s*)'[^']*'/", "$1'https://masonsocialsec.com'", $c, 1);
if ($c2 !== null && $c2 !== $c) { file_put_contents($envFile, $c2); echo "SITE_URL -> https://masonsocialsec.com\n"; }
else { echo "no change\n"; }
$env = require $envFile; echo "verify: " . ($env['SITE_URL'] ?? '?') . "\n";
$root = '/home/u128533805/domains/masonsocialsec.com';
$n = 0; foreach (glob("$root/storage/cache/*") as $f) { if (is_file($f)) { @unlink($f); $n++; } }
echo "flushed cache: $n\n";
@unlink(__FILE__);
echo "DONE.\n";
