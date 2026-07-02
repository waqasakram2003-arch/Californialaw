<?php
/**
 * admin-helpers.php — flash messages, settings, uploads, CSV, CSRF gate.
 */
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';

/* ---- Flash ---- */
function admin_flash(string $type, string $msg): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
    $_SESSION['admin_flash'][] = ['type' => $type, 'msg' => $msg];
}
function admin_take_flash(): array
{
    $f = $_SESSION['admin_flash'] ?? [];
    unset($_SESSION['admin_flash']);
    return $f;
}

/* ---- Redirect ---- */
function admin_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/* ---- CSRF gate for admin POST ---- */
function admin_require_csrf(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !csrf_verify($_POST['csrf_token'] ?? null)) {
        admin_flash('error', 'Security check failed. Please try again.');
        admin_redirect($_SERVER['HTTP_REFERER'] ?? '/admin/');
    }
}

/* ---- Settings ---- */
function admin_all_settings(): array
{
    $out = [];
    foreach (db()->query('SELECT `key`, `value` FROM settings') as $r) { $out[$r['key']] = $r['value']; }
    return $out;
}
function admin_set_setting(string $key, string $value): void
{
    db()->prepare('INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)')
        ->execute([$key, $value]);
}

/* ---- Media upload (jpg/png/webp, <= 5MB) ---- */
function admin_upload_image(string $field): array
{
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'error' => 'No file selected.'];
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Upload failed (code ' . (int) $f['error'] . ').'];
    }
    if ($f['size'] > 5 * 1024 * 1024) {
        return ['ok' => false, 'error' => 'File is larger than 5MB.'];
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($f['tmp_name']);
    if (!isset($allowed[$mime])) {
        return ['ok' => false, 'error' => 'Only JPG, PNG, and WebP images are allowed.'];
    }
    $ext = $allowed[$mime];

    $sub  = date('Y') . '/' . date('m');
    $dir  = dirname(__DIR__, 2) . '/assets/uploads/' . $sub;   // public_html/assets/uploads/Y/m
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        return ['ok' => false, 'error' => 'Could not create the upload directory.'];
    }
    $base = generateSlug(pathinfo($f['name'], PATHINFO_FILENAME)) ?: 'image';
    $name = $base . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = $dir . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        return ['ok' => false, 'error' => 'Could not save the uploaded file.'];
    }
    $url = '/assets/uploads/' . $sub . '/' . $name;
    try {
        db()->prepare('INSERT INTO media (url, filename, original_name, mime, size_bytes) VALUES (?,?,?,?,?)')
            ->execute([$url, $name, $f['name'], $mime, (int) $f['size']]);
    } catch (Throwable $e) { /* file saved even if media row fails */ }
    return ['ok' => true, 'url' => $url, 'name' => $name];
}

/* ---- CSV download ---- */
function admin_send_csv(string $filename, array $header, array $rows): void
{
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
    fputcsv($out, $header);
    foreach ($rows as $r) { fputcsv($out, $r); }
    fclose($out);
    exit;
}

/* ---- Small helpers ---- */
function admin_status_badge(string $status): string
{
    $map = ['new' => 'is-new', 'published' => 'is-published', 'draft' => 'is-draft',
            'contacted' => 'is-contacted', 'closed' => 'is-closed', 'reviewing' => 'is-reviewing'];
    $cls = $map[$status] ?? '';
    return '<span class="badge ' . $cls . '">' . e(ucfirst($status)) . '</span>';
}
