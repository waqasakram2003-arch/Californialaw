<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-robots-6t1n';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$f = '/home/u128533805/domains/masonsocialsecurity.com/includes/partials/head.php';
if (!is_file($f)) { exit("ABORT: head.php not found\n"); }
$c = file_get_contents($f);

if (strpos($c, 'max-image-preview') !== false) {
    echo "already has max-image-preview robots meta — no change\n";
} else {
    $c2 = preg_replace(
        '/(<meta name="robots" content="noindex, follow">\s*)(<\?php endif; \?>)/',
        '$1<?php else: ?>' . "\n"
        . '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n"
        . '$2',
        $c, 1, $n
    );
    if ($n > 0 && $c2 !== null) { file_put_contents($f, $c2); echo "added indexable robots meta (max-image-preview:large) — $n replacement\n"; }
    else { echo "pattern not matched — no change (head.php structure differs)\n"; }
}

// flush cache
$root = '/home/u128533805/domains/masonsocialsecurity.com';
$k = 0; foreach (glob("$root/storage/cache/*") as $g) { if (is_file($g)) { @unlink($g); $k++; } }
echo "flushed cache: $k\n";

@unlink(__FILE__);
echo "DONE.\n";
