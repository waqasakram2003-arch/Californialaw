<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd5-3n8x';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }
$base = '/home/u128533805/domains';
echo "== domain dirs ==\n";
foreach (glob("$base/*", GLOB_ONLYDIR) as $d) { echo "  " . basename($d) . "\n"; }
foreach (['masonsocialsecurity.com', 'masonsocialsec.com'] as $dom) {
    $dir = "$base/$dom";
    echo "\n== $dom ==\n  dir=" . (is_dir($dir) ? 'yes' : 'NO')
       . " index.php=" . (is_file("$dir/public_html/index.php") ? 'yes' : 'no')
       . " env.php=" . (is_file("$dir/config/env.php") ? 'yes' : 'no') . "\n";
    if (is_file("$dir/config/env.php")) { $e = require "$dir/config/env.php"; echo "  SITE_URL=" . ($e['SITE_URL'] ?? '?') . " DB=" . ($e['DB_NAME'] ?? '?') . "\n"; }
}
function lb($host) {
    $ch = curl_init();
    curl_setopt_array($ch, [CURLOPT_URL=>'http://127.0.0.1/', CURLOPT_RETURNTRANSFER=>1, CURLOPT_FOLLOWLOCATION=>0, CURLOPT_TIMEOUT=>12,
        CURLOPT_HTTPHEADER=>["Host: $host", 'X-Forwarded-Proto: https']]);
    $b=(string)curl_exec($ch); $c=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    return [$c, preg_match('/<title>(.*?)<\/title>/is',$b,$m)?trim($m[1]):substr(strip_tags($b),0,40)];
}
echo "\n== loopback ==\n";
foreach (['masonsocialsec.com','masonsocialsecurity.com'] as $h) { [$c,$t]=lb($h); echo "  Host $h -> $c  $t\n"; }
@unlink(__FILE__);
echo "\nDONE.\n";
