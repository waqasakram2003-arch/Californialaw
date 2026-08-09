<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-seo-2v8b';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }
function lb(string $path): array {
    $ch = curl_init();
    curl_setopt_array($ch, [CURLOPT_URL => 'http://127.0.0.1' . $path, CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => 3, CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => ['Host: masonsocialsecurity.com', 'X-Forwarded-Proto: https']]);
    $b = (string) curl_exec($ch); $c = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch); return [$c, $b];
}
echo "== SUB-SITEMAPS ==\n";
foreach (['/sitemap-core.xml', '/sitemap-conditions.xml', '/sitemap-resources.xml', '/sitemap-blog.xml'] as $s) {
    [$c, $b] = lb($s);
    preg_match_all('/<loc>/', $b, $m);
    printf("  %-26s HTTP %-3d  urls=%d  newdomain=%d old=%d\n", $s, $c, count($m[0]),
        substr_count($b, 'masonsocialsecurity.com'), substr_count($b, 'deeppink') + preg_match_all('/liberty/i', $b));
}
echo "\n== HOMEPAGE SEO META ==\n";
[$hc, $h] = lb('/');
foreach ([
  'robots meta' => '/<meta[^>]*name=["\']robots["\'][^>]*content=["\']([^"\']*)/i',
  'og:url'      => '/<meta[^>]*property=["\']og:url["\'][^>]*content=["\']([^"\']*)/i',
  'og:image'    => '/<meta[^>]*property=["\']og:image["\'][^>]*content=["\']([^"\']*)/i',
  'og:title'    => '/<meta[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']*)/i',
  'description' => '/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']{0,70})/i',
] as $label => $re) {
    echo "  $label: " . (preg_match($re, $h, $m) ? $m[1] : '(none)') . "\n";
}
echo "  JSON-LD schema blocks: " . substr_count($h, 'application/ld+json') . "\n";
echo "  schema @type hits: " . implode(', ', array_slice(array_unique(preg_match_all('/"@type"\s*:\s*"([^"]+)"/', $h, $mm) ? $mm[1] : []), 0, 12)) . "\n";
@unlink(__FILE__);
echo "\nDONE.\n";
