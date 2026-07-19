<?php
if (($_GET['key'] ?? '') !== 'env-4z') { http_response_code(403); exit('no'); }
header('Content-Type: text/plain; charset=utf-8');
require __DIR__.'/includes/config.php'; require __DIR__.'/includes/db.php';
echo "APP_ENV=".APP_ENV."\nHOST=".($_SERVER['HTTP_HOST']??'')."\nBASE_URL=".BASE_URL."\nDB_NAME=".DB_NAME."\nDB_USER=".DB_USER."\nGSIL_DB_PASS_defined=".(defined('GSIL_DB_PASS')?'yes':'no')."\n";
try { $n=db()->query('SELECT COUNT(*) FROM practice_areas')->fetchColumn(); echo "DB OK — practice_areas=$n\n";
  $l=db()->query('SELECT COUNT(*) FROM case_evaluations')->fetchColumn(); echo "case_evaluations rows=$l\n"; }
catch (Throwable $e){ echo "DB FAIL: ".$e->getMessage()."\n"; }
@unlink(__FILE__);
