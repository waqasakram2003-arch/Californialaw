<?php
if (($_GET['key'] ?? '') !== 'cal-9x') { http_response_code(403); exit('no'); }
header('Content-Type: text/plain; charset=utf-8');
require __DIR__.'/includes/config.php'; require __DIR__.'/includes/db.php';
db()->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)')->execute(['calendly_url','https://calendly.com/masonlawpc/30min']);
echo "civil calendly_url = https://calendly.com/masonlawpc/30min\n"; @unlink(__FILE__); echo "done\n";
