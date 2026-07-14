<?php
/** _probe.php — list account dirs to find deeppink app root. Key-guarded, self-deletes. */
header('Content-Type: text/plain; charset=utf-8');
if (($_GET['key'] ?? '') !== 'probe-x9q2') { http_response_code(403); exit("no\n"); }
$out = [];
function lsdir($p, &$out) {
    $out[] = "DIR $p:";
    if (!is_dir($p)) { $out[] = '  (not a dir)'; return; }
    foreach (@scandir($p) ?: [] as $e) { if ($e === '.' || $e === '..') continue; $out[] = '  ' . $e . (is_dir("$p/$e") ? '/' : ''); }
}
lsdir('/home/u128533805', $out);
lsdir('/home/u128533805/domains', $out);
$dp = '/home/u128533805/domains/deeppink-partridge-979149.hostingersite.com';
lsdir($dp, $out);
foreach ([
    "$dp/public_html/index.php",
    "$dp/includes/bootstrap.php",
    "$dp/public_html/includes/bootstrap.php",
    "$dp/config/env.php",
    "$dp/public_html/config/config.php",
] as $c) {
    $out[] = sprintf('%-70s exists=%d writable-dir=%d', $c, (int) @file_exists($c), (int) @is_writable(dirname($c)));
}
echo implode("\n", $out) . "\n";
@unlink(__FILE__);
