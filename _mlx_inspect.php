<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
if (($_GET['key'] ?? '') !== 'mlx-inspect-7d3f9a') { http_response_code(403); exit("forbidden\n"); }
require __DIR__ . '/includes/db.php';
$pdo = db();
echo "COLUMNS:\n"; foreach ($pdo->query("SHOW COLUMNS FROM blog_posts") as $c) echo "  ".$c['Field']."\n";
echo "\nATTORNEYS(active):\n"; foreach ($pdo->query("SELECT slug,name,title FROM attorneys WHERE active=1 ORDER BY order_num") as $a) echo "  ".$a['slug']." | ".$a['name']." | ".$a['title']."\n";
echo "\nCATEGORIES:\n"; foreach ($pdo->query("SELECT slug,name FROM blog_categories ORDER BY id") as $c) echo "  ".$c['slug']." = ".$c['name']."\n";
echo "\nPOSTS (date | status | author | slug):\n"; foreach ($pdo->query("SELECT slug,status,published_at,author_slug FROM blog_posts ORDER BY published_at") as $p) echo "  ".substr((string)$p['published_at'],0,10)." | ".$p['status']." | ".$p['author_slug']." | ".$p['slug']."\n";
foreach ($pdo->query("SELECT COUNT(*) n,MAX(published_at) mx FROM blog_posts") as $r) echo "\nTOTAL=".$r['n']." MAX=".$r['mx']."\n";
echo "\nSETTINGS: "; foreach ($pdo->query("SELECT `key`,`value` FROM settings WHERE `key` IN ('ga_id','ads_id','pixel_id')") as $s) echo $s['key']."=".($s['value']?:'(empty)')."  ";
echo "\n";
