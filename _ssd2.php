<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd2-4q8w';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$base = '/home/u128533805/domains';
echo "== domain dirs ==\n";
foreach (glob("$base/*", GLOB_ONLYDIR) as $d) { echo "  " . basename($d) . "\n"; }

foreach (['masonsocialsecurity.com', 'masonsocialsec.com'] as $dom) {
    $dir = "$base/$dom";
    echo "\n== $dom ==\n  dir: " . (is_dir($dir) ? 'yes' : 'NO')
       . " | public_html/index.php: " . (is_file("$dir/public_html/index.php") ? 'yes' : 'no')
       . " | config/env.php: " . (is_file("$dir/config/env.php") ? 'yes' : 'no') . "\n";
}

// where does the app currently live + its SITE_URL
foreach (['masonsocialsecurity.com', 'masonsocialsec.com'] as $dom) {
    $ef = "$base/$dom/config/env.php";
    if (is_file($ef)) { $e = require $ef; echo "  [$dom] SITE_URL = " . ($e['SITE_URL'] ?? '?') . " DB=" . ($e['DB_NAME'] ?? '?') . "\n"; }
}

// loopback render check (is the app serving under its current host?)
function lb($host, $path='/') {
    $ch = curl_init();
    curl_setopt_array($ch, [CURLOPT_URL=>'http://127.0.0.1'.$path, CURLOPT_RETURNTRANSFER=>1, CURLOPT_FOLLOWLOCATION=>1, CURLOPT_MAXREDIRS=>2, CURLOPT_TIMEOUT=>15,
        CURLOPT_HTTPHEADER=>["Host: $host", 'X-Forwarded-Proto: https']]);
    $b=(string)curl_exec($ch); $c=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    return [$c, preg_match('/<title>(.*?)<\/title>/is',$b,$m)?trim($m[1]):'', strlen($b)];
}
echo "\n== loopback render ==\n";
foreach (['masonsocialsecurity.com','masonsocialsec.com'] as $h) {
    [$c,$t,$len]=lb($h); echo "  Host $h -> HTTP $c  bytes=$len  title=" . substr($t,0,45) . "\n";
}
@unlink(__FILE__);
echo "\nDONE.\n";
