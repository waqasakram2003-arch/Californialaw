<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-check-9w2r';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

function lb(string $path): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'http://127.0.0.1' . $path,
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => 3, CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => ['Host: masonsocialsecurity.com', 'X-Forwarded-Proto: https'],
    ]);
    $body = (string) curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, $body];
}

$paths = ['/', '/about', '/contact', '/social-security-disability', '/ssi', '/appeals', '/federal-hearings', '/blog', '/faq', '/resources', '/free-case-evaluation', '/search?q=disability'];
echo "== PAGE SWEEP ==\n";
$homeHtml = '';
foreach ($paths as $p) {
    [$code, $html] = lb($p);
    if ($p === '/') $homeHtml = $html;
    $err = preg_match_all('/(Fatal error|Warning:|Notice:|Undefined array|Deprecated:)/i', $html);
    $t = preg_match('/<title>(.*?)<\/title>/is', $html, $m) ? trim($m[1]) : '';
    printf("  %-28s HTTP %-3d err=%d  %s\n", $p, $code, $err, substr($t, 0, 45));
}

echo "\n== robots.txt ==\n";
[$rc, $robots] = lb('/robots.txt');
echo "HTTP $rc\n" . substr($robots, 0, 800) . "\n";

echo "\n== sitemap.xml ==\n";
[$sc, $sm] = lb('/sitemap.xml');
$locs = [];
preg_match_all('/<loc>(.*?)<\/loc>/i', $sm, $locs);
echo "HTTP $sc  <loc> count=" . count($locs[1]) . "\n";
echo "  domain check — masonsocialsecurity.com: " . substr_count($sm, 'masonsocialsecurity.com')
   . " | old (deeppink/liberty/lawyermason): " . (substr_count($sm, 'deeppink') + preg_match_all('/liberty|lawyermason/i', $sm)) . "\n";
echo "  first 6 locs:\n";
foreach (array_slice($locs[1], 0, 6) as $l) echo "    $l\n";
// is it a sitemap index?
echo "  sitemap index? " . (stripos($sm, '<sitemapindex') !== false ? 'yes' : 'no') . "\n";

echo "\n== OLD-DOMAIN / BAD REFS in homepage ==\n";
foreach (['deeppink', 'hostingersite.com', 'libertyssdlaw', 'lawyermason', 'localhost'] as $bad) {
    $n = substr_count($homeHtml, $bad);
    if ($n) echo "  $bad: $n\n";
}
echo "  (none listed above = clean)\n";

echo "\n== INTERNAL LINK CHECK (unique hrefs from homepage) ==\n";
preg_match_all('/href=["\'](https:\/\/masonsocialsecurity\.com([^"\'#?]*)|\/[a-z0-9\-\/]*)["\']/i', $homeHtml, $hm);
$links = [];
foreach ($hm[0] as $i => $_) {
    $u = $hm[2][$i] !== '' ? $hm[2][$i] : $hm[1][$i];
    if ($u === '' ) $u = '/';
    if (strpos($u, 'http') === 0) $u = parse_url($u, PHP_URL_PATH) ?: '/';
    $links[$u] = true;
}
$links = array_slice(array_keys($links), 0, 30);
$broken = [];
foreach ($links as $u) { [$c] = lb($u); if ($c >= 400) $broken[] = "$u ($c)"; }
echo "  checked " . count($links) . " unique internal links\n";
echo "  broken (>=400): " . ($broken ? implode(', ', $broken) : 'NONE ✓') . "\n";

echo "\n== ASSETS (logo, og image, css) present? ==\n";
$root = '/home/u128533805/domains/masonsocialsecurity.com/public_html';
foreach (['assets/img/logo-mark.webp', 'favicon.ico', 'site.webmanifest'] as $a) {
    echo "  $a: " . (is_file("$root/$a") ? 'yes' : 'MISSING') . "\n";
}
// css referenced in home
preg_match_all('/href=["\']([^"\']+\.css[^"\']*)["\']/i', $homeHtml, $css);
echo "  css files referenced: " . implode(', ', array_slice(array_unique($css[1]), 0, 5)) . "\n";

@unlink(__FILE__);
echo "\nDONE.\n";
