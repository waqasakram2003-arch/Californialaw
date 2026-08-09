<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd3-7p2m';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$dir = '/home/u128533805/domains/masonsocialsec.com/public_html';
echo "target docroot: $dir\n";
echo "existed before: " . (is_dir($dir) ? 'yes' : 'no') . "\n";
@mkdir($dir, 0755, true);
echo "mkdir ok: " . (is_dir($dir) ? 'yes' : 'FAILED') . "\n";
$tok = 'vhosttest-' . time();
@file_put_contents("$dir/_t.txt", $tok);
@file_put_contents("$dir/index.php", "<?php echo 'MASONSOCIALSEC-INDEX-OK';");

function lb($host, $path) {
    $ch = curl_init();
    curl_setopt_array($ch, [CURLOPT_URL=>'http://127.0.0.1'.$path, CURLOPT_RETURNTRANSFER=>1, CURLOPT_FOLLOWLOCATION=>0, CURLOPT_TIMEOUT=>12,
        CURLOPT_HTTPHEADER=>["Host: $host", 'X-Forwarded-Proto: https']]);
    $b=(string)curl_exec($ch); $c=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    return [$c, substr($b,0,120)];
}
echo "\n== does the masonsocialsec.com vhost serve this docroot? ==\n";
[$c1,$b1] = lb('masonsocialsec.com', '/_t.txt');
echo "  GET /_t.txt -> HTTP $c1  body=" . trim($b1) . "\n";
[$c2,$b2] = lb('masonsocialsec.com', '/');
echo "  GET /       -> HTTP $c2  body=" . trim(str_replace("\n"," ",$b2)) . "\n";

echo "\n== verdict: " . ($c1 == 200 && strpos($b1, $tok) !== false ? "VHOST SERVES THIS DIR — panel-free path VIABLE" : "vhost does NOT map here — panel needed") . "\n";

// cleanup test files (leave dir; harmless)
@unlink("$dir/_t.txt"); @unlink("$dir/index.php");
@unlink(__FILE__);
echo "\nDONE.\n";
