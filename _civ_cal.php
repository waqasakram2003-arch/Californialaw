<?php
/** _civ_cal.php — set civil-site Calendly link + hours. Key-guarded, self-deletes. */
header('Content-Type: text/plain; charset=utf-8');
if (($_GET['key'] ?? '') !== 'civ-cal-7z') { http_response_code(403); exit("no\n"); }
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
$set = db()->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
foreach ([
  'calendly_url' => 'https://calendly.com/waqasakram00111/30min',
  'office_hours' => 'Mon–Fri, 9:00am–5:00pm',
] as $k=>$v){ $set->execute([$k,$v]); echo "set $k = $v\n"; }
@unlink(__FILE__); echo "done\n";
