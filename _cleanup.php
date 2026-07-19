<?php
if (($_GET['key'] ?? '') !== 'clean-2p') { http_response_code(403); exit('no'); }
header('Content-Type: text/plain; charset=utf-8');
require __DIR__.'/includes/config.php'; require __DIR__.'/includes/db.php';
$a=db()->exec("DELETE FROM contacts WHERE name LIKE 'DeployTest%' OR email='deploytest@example.com'");
$b=db()->exec("DELETE FROM case_evaluations WHERE name LIKE 'DeployTest%' OR email='deploytest@example.com'");
echo "deleted contacts=$a case_evaluations=$b\n"; @unlink(__FILE__); echo "done\n";
