<?php
/** TEMP cross-site SEO deployer for masonsocialsec.com. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'ssdseo-9k2') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
$R = '/home/u128533805/domains/masonsocialsec.com';
$log = [];

/* ---------- 1. schema_article -> ISO-8601 dates ---------- */
$f = "$R/includes/schema.php";
$src = @file_get_contents($f);
if ($src === false) { $log[] = "ABORT: cannot read schema.php"; }
elseif (strpos($src, '$iso = static function') !== false) { $log[] = "schema dates: already ISO (skip)"; }
else {
    $anchor = <<<'EOT'
function schema_article(array $opts): array
{
    return [
EOT;
    $inject = <<<'EOT'
function schema_article(array $opts): array
{
    $iso = static function ($v): ?string {
        if (empty($v)) return null;
        $ts = is_numeric($v) ? (int) $v : strtotime((string) $v);
        return $ts ? date('c', $ts) : null;
    };
    $published = $iso($opts['published'] ?? null);
    $modified  = $iso($opts['modified'] ?? null) ?? $published;

    return [
EOT;
    $oldDates = <<<'EOT'
        'datePublished' => $opts['published'] ?? null,
        'dateModified'  => $opts['modified'] ?? ($opts['published'] ?? null),
EOT;
    $newDates = <<<'EOT'
        'datePublished' => $published,
        'dateModified'  => $modified,
EOT;
    $ok1 = strpos($src, $anchor) !== false;
    $ok2 = strpos($src, $oldDates) !== false;
    if ($ok1 && $ok2) {
        $src = str_replace($anchor, $inject, $src);
        $src = str_replace($oldDates, $newDates, $src);
        file_put_contents($f, $src);
        $log[] = "schema dates: PATCHED to ISO-8601";
    } else {
        $log[] = "schema dates: anchors not found (anchor=$ok1 dates=$ok2) — SKIP";
    }
}

/* ---------- 2. og:locale in head.php ---------- */
$hf = "$R/includes/partials/head.php";
$h = @file_get_contents($hf);
if ($h === false) { $log[] = "ABORT: cannot read head.php"; }
elseif (strpos($h, 'og:locale') !== false) { $log[] = "og:locale: already present (skip)"; }
else {
    $a = '<meta property="og:site_name" content="Mason Law, P.C.">';
    if (strpos($h, $a) !== false) {
        $h = str_replace($a, $a . "\n" . '<meta property="og:locale" content="en_US">', $h);
        file_put_contents($hf, $h);
        $log[] = "og:locale: ADDED";
    } else { $log[] = "og:locale: anchor not found — SKIP"; }
}

/* ---------- 3. unique meta descriptions via seo_pages ---------- */
$desc = [
    '/social-security-disability' => "SSDI benefits explained — who qualifies, how to apply, and how we fight denials. Speak with a Social Security Disability attorney free. No fee unless you win.",
    '/ssi' => "SSI benefits for people with limited income and a disability. Learn eligibility, monthly payment amounts, and how we handle SSI denials and appeals. Free review.",
    '/appeals' => "Denied Social Security disability? Learn every appeal level — reconsideration, ALJ hearing, Appeals Council and federal court. Our attorneys handle it all. Free review.",
    '/federal-hearings' => "Facing a disability hearing before an ALJ? Learn what to expect and how our attorneys prepare your testimony, evidence, and expert witnesses. Free case review.",
    '/denied-claims' => "A denied disability claim isn't the end — most are denied at first. Learn your appeal options and how our attorneys turn denials into approvals. Free review.",
    '/veterans-disability' => "Veterans can receive both VA disability and Social Security (SSDI/SSI). Learn how the programs differ and how we help veterans claim every benefit. Free review.",
    '/conditions' => "Which medical conditions qualify for Social Security disability? Browse SSA Blue Book listings and learn how our attorneys prove your condition. Free case review.",
    '/contact' => "Contact Mason Law for a free, no-obligation Social Security Disability case review. Call (916) 587-2997 or request a callback — no fee unless you win.",
    '/free-case-evaluation' => "Start your free Social Security Disability case review. Answer a few quick questions and our team will tell you where your claim stands. No fee unless you win.",
];
$env = @require "$R/config/env.php";
if (!is_array($env)) { $log[] = "DB: cannot load env.php — SKIP descriptions"; }
else {
    try {
        $dsn = "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4";
        $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $cols = $pdo->query("SHOW COLUMNS FROM seo_pages")->fetchAll(PDO::FETCH_COLUMN);
        $hasIdx = in_array('is_indexable', $cols, true);
        $ins = 0; $upd = 0;
        foreach ($desc as $route => $d) {
            $ex = $pdo->prepare("SELECT id FROM seo_pages WHERE route = ?");
            $ex->execute([$route]);
            $id = $ex->fetchColumn();
            if ($id) {
                $pdo->prepare("UPDATE seo_pages SET seo_description = ? WHERE id = ?")->execute([$d, $id]);
                $upd++;
            } else {
                if ($hasIdx) {
                    $pdo->prepare("INSERT INTO seo_pages (route, seo_description, is_indexable) VALUES (?,?,1)")->execute([$route, $d]);
                } else {
                    $pdo->prepare("INSERT INTO seo_pages (route, seo_description) VALUES (?,?)")->execute([$route, $d]);
                }
                $ins++;
            }
        }
        $log[] = "descriptions: cols=[" . implode(',', $cols) . "] inserted=$ins updated=$upd";
    } catch (Throwable $e) {
        $log[] = "DB ERROR: " . $e->getMessage();
    }
}

/* ---------- 4. flush cache ---------- */
$n = 0;
foreach (glob("$R/storage/cache/*") as $c) { if (is_file($c) && @unlink($c)) $n++; }
$log[] = "cache flushed: $n files";

echo implode("\n", $log) . "\nDONE.\n";
@unlink(__FILE__);
