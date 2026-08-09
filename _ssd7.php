<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd7-6q2w';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }
function lb($path) {
    $ch = curl_init();
    curl_setopt_array($ch, [CURLOPT_URL=>'http://127.0.0.1'.$path, CURLOPT_RETURNTRANSFER=>1, CURLOPT_FOLLOWLOCATION=>1, CURLOPT_MAXREDIRS=>2, CURLOPT_TIMEOUT=>15,
        CURLOPT_HTTPHEADER=>['Host: masonsocialsec.com', 'X-Forwarded-Proto: https']]);
    $b=(string)curl_exec($ch); $c=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch); return [$c,$b];
}
echo "== FINAL AUDIT (masonsocialsec.com) ==\n";
foreach (['/','/about','/contact','/social-security-disability','/ssi','/appeals','/blog','/free-case-evaluation'] as $p) {
    [$c,$h]=lb($p);
    $err=preg_match_all('/(Fatal error|Warning:|Undefined)/i',$h);
    printf("  %-26s HTTP %d err=%d\n",$p,$c,$err);
}
[$c,$h]=lb('/');
echo "\nHomepage checks:\n";
echo "  canonical: " . (preg_match('/rel=["\']canonical["\'] href=["\']([^"\']+)/i',$h,$m)?$m[1]:'?') . "\n";
echo "  new-domain links: " . substr_count($h,'masonsocialsec.com') . "  OLD (masonsocialsecurity/deeppink): " . (substr_count($h,'masonsocialsecurity.com')+substr_count($h,'deeppink')) . "\n";
echo "  robots meta: " . (preg_match('/<meta name="robots"[^>]*content="([^"]*)/i',$h,$m)?$m[1]:'(none)') . "\n";
echo "  og:url: " . (preg_match('/property=["\']og:url["\'][^>]*content=["\']([^"\']+)/i',$h,$m)?$m[1]:'?') . "\n";
[$sc,$sm]=lb('/sitemap.xml');
echo "  sitemap: HTTP $sc  new-domain=" . substr_count($sm,'masonsocialsec.com') . " old=" . (substr_count($sm,'masonsocialsecurity.com')+substr_count($sm,'deeppink')) . "\n";
[$rc,$rb]=lb('/robots.txt');
echo "  robots.txt Sitemap line: " . (preg_match('/Sitemap:\s*(\S+)/i',$rb,$m)?$m[1]:'?') . "\n";
[$cc,$ch2]=lb('/contact');
echo "  contact email: " . (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i',$ch2,$m)?$m[0]:'?') . "\n";
@unlink(__FILE__);
echo "\nDONE.\n";
