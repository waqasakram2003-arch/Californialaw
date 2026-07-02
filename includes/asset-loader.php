<?php
/**
 * asset-loader.php — cache-busted asset URLs + optional production bundling.
 *
 * Development (APP_ENV !== 'production'): each file is emitted individually with
 * a ?v=<filemtime> query for cache busting.
 *
 * Production: local CSS files are combined + minified into one cached file under
 * /assets/dist/, and local JS files are concatenated into one cached file. The
 * cache key is the max modification time of the inputs, so a deploy busts it.
 * External (http/https) URLs always pass through untouched.
 */
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function asset_public_root(): string { return dirname(__DIR__); } // public_html

/** Append ?v=filemtime() to a site-relative asset path for cache busting. */
function asset_url(string $rel): string
{
    if (preg_match('#^https?://#i', $rel)) { return $rel; }
    $path = asset_public_root() . $rel;
    $v = is_file($path) ? filemtime($path) : null;
    return $rel . ($v ? '?v=' . $v : '');
}

/** Split a file list into [locals, externals]. */
function asset_split(array $files): array
{
    $local = $ext = [];
    foreach ($files as $f) {
        if (preg_match('#^https?://#i', $f)) { $ext[] = $f; } else { $local[] = $f; }
    }
    return [$local, $ext];
}

/** Minify CSS conservatively (strip comments + collapse whitespace). */
function asset_min_css(string $css): string
{
    $css = preg_replace('#/\*.*?\*/#s', '', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = preg_replace('/\s*([{}:;,>])\s*/', '$1', $css);
    $css = str_replace(';}', '}', $css);
    return trim($css);
}

/**
 * Build (or reuse) a combined bundle under /assets/dist/ and return its URL,
 * or null on failure. $minify only applies to CSS.
 */
function asset_bundle(array $locals, string $ext, bool $minify): ?string
{
    if (!$locals) { return null; }
    $root = asset_public_root();
    $mtime = 0;
    foreach ($locals as $f) { $p = $root . $f; if (is_file($p)) { $mtime = max($mtime, filemtime($p)); } }
    $key = substr(md5(implode('|', $locals) . $mtime), 0, 12);
    $distDir = $root . '/assets/dist';
    $relOut  = '/assets/dist/bundle-' . $key . '.' . $ext;
    $outPath = $root . $relOut;

    if (!is_file($outPath)) {
        if (!is_dir($distDir) && !mkdir($distDir, 0775, true) && !is_dir($distDir)) { return null; }
        $buf = '';
        foreach ($locals as $f) {
            $p = $root . $f;
            if (!is_file($p)) { continue; }
            $c = (string) file_get_contents($p);
            $buf .= ($ext === 'css' && $minify) ? asset_min_css($c) : $c;
            $buf .= "\n";
        }
        if (@file_put_contents($outPath, $buf) === false) { return null; }
    }
    return $relOut;
}

/** Emit <link rel=stylesheet> tags for a list of CSS files. */
function asset_styles(array $files): void
{
    [$local, $ext] = asset_split($files);
    if (APP_ENV === 'production' && $local) {
        $url = asset_bundle($local, 'css', true);
        if ($url) { echo '<link rel="stylesheet" href="' . e(asset_url($url)) . '">' . "\n"; }
        else { foreach ($local as $f) { echo '<link rel="stylesheet" href="' . e(asset_url($f)) . '">' . "\n"; } }
    } else {
        foreach ($local as $f) { echo '<link rel="stylesheet" href="' . e(asset_url($f)) . '">' . "\n"; }
    }
    foreach ($ext as $f) { echo '<link rel="stylesheet" href="' . e($f) . '">' . "\n"; }
}

/** Emit deferred <script> tags for a list of JS files. */
function asset_scripts(array $files): void
{
    [$local, $ext] = asset_split($files);
    if (APP_ENV === 'production' && $local) {
        $url = asset_bundle($local, 'js', false);
        if ($url) { echo '<script src="' . e(asset_url($url)) . '" defer></script>' . "\n"; }
        else { foreach ($local as $f) { echo '<script src="' . e(asset_url($f)) . '" defer></script>' . "\n"; } }
    } else {
        foreach ($local as $f) { echo '<script src="' . e(asset_url($f)) . '" defer></script>' . "\n"; }
    }
    foreach ($ext as $f) { echo '<script src="' . e($f) . '" defer></script>' . "\n"; }
}
