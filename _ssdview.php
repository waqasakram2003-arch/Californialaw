<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-view-3j9d';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$root = '/home/u128533805/domains/masonsocialsecurity.com';
$ht = "$root/public_html/.htaccess";
echo "== .htaccess https-force rule ==\n";
if (is_file($ht)) {
    foreach (preg_grep('/HTTPS|X-Forwarded|RewriteRule .*https|301|443/i', file($ht, FILE_IGNORE_NEW_LINES)) as $l) { echo "  $l\n"; }
}

$path = $_GET['p'] ?? '/';
echo "\n== loopback render of $path (Host=masonsocialsecurity.com, X-Forwarded-Proto=https) ==\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://127.0.0.1' . $path,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 3,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_HTTPHEADER => ['Host: masonsocialsecurity.com', 'X-Forwarded-Proto: https', 'X-Forwarded-For: 127.0.0.1'],
]);
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);
echo "HTTP $code  err=" . ($err ?: 'none') . "  bytes=" . strlen((string) $html) . "\n";

if ($html) {
    if (preg_match('/<title>(.*?)<\/title>/is', $html, $m)) echo "title: " . trim($m[1]) . "\n";
    // report key facts + issues
    echo "Mason Law refs: " . substr_count($html, 'Mason Law') . "\n";
    echo "Liberty leftovers: " . preg_match_all('/liberty/i', $html) . "\n";
    echo "masonsocialsecurity.com in canonical/links: " . substr_count($html, 'masonsocialsecurity.com') . "\n";
    echo "deeppink leftovers (bad): " . substr_count($html, 'deeppink') . "\n";
    echo "email masonsocialsecurity@gmail: " . substr_count($html, 'masonsocialsecurity@gmail.com') . "\n";
    echo "phone (916) 587-2997: " . substr_count($html, '587-2997') . "\n";
    echo "PHP error/notice markers: " . preg_match_all('/(Fatal error|Warning:|Notice:|Deprecated:|Undefined)/i', $html) . "\n";
    if (preg_match('/rel=["\']canonical["\'] href=["\']([^"\']+)/i', $html, $m)) echo "canonical: {$m[1]}\n";
    // dump a slice for eyeball
    echo "\n--- HTML head slice ---\n" . substr(strip_tags(preg_replace('/<(script|style)[^>]*>.*?<\/\1>/is', '', $html)), 0, 900) . "\n";
}
@unlink(__FILE__);
echo "\nDONE.\n";
