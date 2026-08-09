<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$KEY = 'ssd-probe-5m2p';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden\n"); }

$base = '/home/u128533805/domains';
echo "== domain dirs under $base ==\n";
foreach (glob("$base/*", GLOB_ONLYDIR) as $d) { echo "  " . basename($d) . "\n"; }

function inspect(string $label, string $dir) {
    echo "\n== $label: $dir ==\n";
    echo "  exists: " . (is_dir($dir) ? 'yes' : 'NO') . "\n";
    if (!is_dir($dir)) return;
    $ph = "$dir/public_html";
    echo "  public_html exists: " . (is_dir($ph) ? 'yes' : 'NO') . "\n";
    if (is_dir($ph)) {
        echo "  public_html/index.php: " . (is_file("$ph/index.php") ? 'yes' : 'NO') . "\n";
        echo "  public_html/.htaccess: " . (is_file("$ph/.htaccess") ? 'yes' : 'NO') . "\n";
        $e = array_slice(array_values(array_diff(scandir($ph), ['.', '..'])), 0, 15);
        echo "  public_html entries: " . implode(', ', $e) . "\n";
    }
    // app libs
    foreach (['config/env.php', 'includes/bootstrap.php', 'templates', 'storage'] as $lib) {
        echo "  $lib: " . (file_exists("$dir/$lib") ? 'yes' : 'no') . "\n";
    }
}
inspect('deeppink (temp)', "$base/deeppink-partridge-979149.hostingersite.com");
inspect('masonsocialsecurity.com', "$base/masonsocialsecurity.com");

@unlink(__FILE__);
echo "\nDONE.\n";
