<?php header('Content-Type: text/plain');
if(($_GET['key']??'')!=='v9'){http_response_code(403);exit;}
$ch=curl_init();curl_setopt_array($ch,[CURLOPT_URL=>'http://127.0.0.1/',CURLOPT_RETURNTRANSFER=>1,CURLOPT_FOLLOWLOCATION=>1,CURLOPT_HTTPHEADER=>['Host: masonsocialsecurity.com','X-Forwarded-Proto: https']]);
$h=curl_exec($ch);curl_close($ch);
preg_match('/<meta name="robots"[^>]*>/i',$h,$m);
echo "homepage robots meta: ".($m[0]??'(none)')."\n";
@unlink(__FILE__);echo "DONE.\n";
