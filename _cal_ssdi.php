<?php
if (($_GET['key'] ?? '') !== 'cal-9x') { http_response_code(403); exit('no'); }
header('Content-Type: text/plain; charset=utf-8');
$A='/home/u128533805/domains/deeppink-partridge-979149.hostingersite.com';
require_once $A.'/includes/bootstrap.php';
db_query('INSERT INTO settings (setting_key,setting_value,autoload) VALUES (?,?,1) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)',['calendly_url','https://calendly.com/masonlawpc/30min']);
if(function_exists('cache_flush')) cache_flush();
echo "ssdi calendly_url = https://calendly.com/masonlawpc/30min\n"; @unlink(__FILE__); echo "done\n";
