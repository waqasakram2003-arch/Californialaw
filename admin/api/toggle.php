<?php
/** admin/api/toggle.php — flip a whitelisted boolean column on a row. */
declare(strict_types=1);
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
function out($code, $p) { http_response_code($code); echo json_encode($p); exit; }

if (!current_admin())                        out(403, ['ok' => false, 'message' => 'Not authorized.']);
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') out(405, ['ok' => false]);
if (!csrf_verify($_POST['csrf_token'] ?? null))    out(419, ['ok' => false, 'message' => 'Session expired.']);

// table => allowed boolean fields
$allowed = [
    'practice_areas' => ['active'],
    'attorneys'      => ['active'],
    'faq_items'      => ['active'],
    'testimonials'   => ['active', 'verified'],
    'case_results'   => ['display'],
];
$table = $_POST['table'] ?? '';
$field = $_POST['field'] ?? '';
$id    = (int) ($_POST['id'] ?? 0);
$value = (isset($_POST['value']) && $_POST['value'] === '1') ? 1 : 0;

if (!isset($allowed[$table]) || !in_array($field, $allowed[$table], true) || $id < 1) {
    out(400, ['ok' => false, 'message' => 'Invalid request.']);
}

try {
    db()->prepare("UPDATE `$table` SET `$field` = ? WHERE id = ?")->execute([$value, $id]);
    out(200, ['ok' => true]);
} catch (Throwable $e) {
    out(500, ['ok' => false, 'message' => 'Update failed.']);
}
