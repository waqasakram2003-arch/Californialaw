<?php
/** _probe.php — one-time recon for cross-site deploy. Key-guarded, self-deletes. */
header('Content-Type: text/plain; charset=utf-8');
if (($_GET['key'] ?? '') !== 'probe-x9q2') { http_response_code(403); exit("no\n"); }

$out = [];
$out[] = 'uid=' . getmyuid() . ' user=' . (function_exists('posix_getpwuid') ? (posix_getpwuid(getmyuid())['name'] ?? '?') : '?');
$out[] = 'HOME=' . getenv('HOME');
$out[] = '__DIR__=' . __DIR__;
$out[] = 'open_basedir=' . (ini_get('open_basedir') ?: '(none)');

$cands = [
    '/home/u128533805',
    '/home/u128533805/public_html',
    '/home/u128533805/includes/bootstrap.php',
    '/home/u128533805/public_html/index.php',
    '/home/u128533805/config/env.php',
];
foreach ($cands as $c) {
    $out[] = sprintf('%-55s exists=%d readable=%d', $c, (int) @file_exists($c), (int) @is_readable($c));
}

// Try a write to the deeppink docroot.
$testFile = '/home/u128533805/public_html/__xtest.txt';
$w = @file_put_contents($testFile, 'ok');
$out[] = 'WRITE /home/u128533805/public_html/__xtest.txt => ' . ($w !== false ? 'OK (' . $w . ' bytes)' : 'FAILED');
if ($w !== false) { @unlink($testFile); $out[] = 'cleaned up test file'; }

echo implode("\n", $out) . "\n";
@unlink(__FILE__);
