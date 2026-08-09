<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssdfix-8h3q';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$f = '/home/u128533805/domains/masonsocialsec.com/includes/partials/footer.php';
if (!is_file($f)) { exit("ABORT: footer.php not found\n"); }
$c = file_get_contents($f);

$newP = <<<'HTML'
<p><strong class="text-cream-100/75">NOT A GOVERNMENT AGENCY.</strong> Mason Law, P.C. is a private law firm. We are not affiliated with, endorsed by, or acting on behalf of the Social Security Administration (SSA) or any other government agency. Official information and services are available free of charge at <a class="underline hover:text-gold-400" href="https://www.ssa.gov/" target="_blank" rel="noopener">ssa.gov</a>.</p>

HTML;

$anchor = '<p><strong class="text-cream-100/75">ATTORNEY ADVERTISING.</strong>';

if (strpos($c, 'NOT A GOVERNMENT AGENCY') !== false) {
    echo "already present — no change\n";
} elseif (strpos($c, $anchor) !== false) {
    $c2 = str_replace($anchor, $newP . $anchor, $c);
    file_put_contents($f, $c2);
    echo "inserted 'NOT A GOVERNMENT AGENCY' disclaimer before ATTORNEY ADVERTISING\n";
} else {
    echo "ANCHOR NOT FOUND — footer structure differs; no change\n";
}

$root = '/home/u128533805/domains/masonsocialsec.com';
$n = 0; foreach (glob("$root/storage/cache/*") as $g) { if (is_file($g)) { @unlink($g); $n++; } }
echo "flushed cache: $n\n";

// verify via loopback
$ch = curl_init();
curl_setopt_array($ch, [CURLOPT_URL=>'http://127.0.0.1/', CURLOPT_RETURNTRANSFER=>1, CURLOPT_FOLLOWLOCATION=>1, CURLOPT_TIMEOUT=>15,
    CURLOPT_HTTPHEADER=>['Host: masonsocialsec.com', 'X-Forwarded-Proto: https']]);
$h = (string) curl_exec($ch); curl_close($ch);
echo "homepage now shows disclaimer: " . (strpos($h, 'NOT A GOVERNMENT AGENCY') !== false ? 'YES' : 'no') . "\n";

@unlink(__FILE__);
echo "DONE.\n";
