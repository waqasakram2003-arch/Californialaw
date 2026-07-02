<?php
/** admin/api/reorder.php — update order_num for a whitelisted table. */
declare(strict_types=1);
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
function out($code, $p) { http_response_code($code); echo json_encode($p); exit; }

if (!current_admin())                        out(403, ['ok' => false, 'message' => 'Not authorized.']);
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') out(405, ['ok' => false]);
if (!csrf_verify($_POST['csrf_token'] ?? null))    out(419, ['ok' => false, 'message' => 'Session expired.']);

$allowed = ['practice_areas', 'attorneys', 'case_results', 'faq_items', 'testimonials'];
$table = $_POST['table'] ?? '';
if (!in_array($table, $allowed, true)) out(400, ['ok' => false, 'message' => 'Invalid table.']);

$ids = array_filter(array_map('intval', explode(',', (string) ($_POST['ids'] ?? ''))));
if (!$ids) out(400, ['ok' => false, 'message' => 'No items.']);

try {
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE `$table` SET order_num = ? WHERE id = ?");
    $pos = 1;
    foreach ($ids as $id) { $stmt->execute([$pos++, $id]); }
    out(200, ['ok' => true]);
} catch (Throwable $e) {
    out(500, ['ok' => false, 'message' => 'Update failed.']);
}
