<?php
/** admin/results/edit.php — add/edit a case result. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    $type = trim((string) ($_POST['case_type'] ?? ''));
    $amt = trim((string) ($_POST['result_amount'] ?? ''));
    $desc = trim((string) ($_POST['description'] ?? ''));
    $year = ($_POST['result_year'] ?? '') !== '' ? (int) $_POST['result_year'] : null;
    $display = isset($_POST['display']) ? 1 : 0;
    $aid = ($_POST['attorney_id'] ?? '') !== '' ? (int) $_POST['attorney_id'] : null;
    $pid = (int) ($_POST['id'] ?? 0);
    if ($type === '' || $amt === '') { admin_flash('error', 'Case type and amount are required.'); admin_redirect('/admin/results/edit.php' . ($pid ? '?id=' . $pid : '')); }
    if ($pid) {
        $pdo->prepare('UPDATE case_results SET case_type=?, result_amount=?, description=?, result_year=?, display=?, attorney_id=? WHERE id=?')
            ->execute([$type, $amt, $desc, $year, $display, $aid, $pid]);
    } else {
        $ord = (int) $pdo->query('SELECT COALESCE(MAX(order_num),0)+1 FROM case_results')->fetchColumn();
        $pdo->prepare('INSERT INTO case_results (case_type, result_amount, description, result_year, display, order_num, attorney_id) VALUES (?,?,?,?,?,?,?)')
            ->execute([$type, $amt, $desc, $year, $display, $ord, $aid]);
    }
    admin_flash('success', 'Result saved.');
    admin_redirect('/admin/results/');
}

$row = ['case_type' => '', 'result_amount' => '', 'description' => '', 'result_year' => '', 'display' => 1, 'attorney_id' => null];
if ($id) { $st = $pdo->prepare('SELECT * FROM case_results WHERE id=?'); $st->execute([$id]); $row = $st->fetch() ?: $row; }
$attorneys = $pdo->query("SELECT id, name FROM attorneys ORDER BY order_num, id")->fetchAll();
$pageTitle = $id ? 'Edit Result' : 'New Result';
$activeNav = 'results';
require __DIR__ . '/../includes/admin-head.php';
?>
<form method="post" class="adm-card" style="max-width:760px">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $id ?>">
  <div class="adm-row">
    <div class="adm-field"><label for="ct">Case Type</label><input class="adm-input" type="text" id="ct" name="case_type" value="<?= e($row['case_type']) ?>" required placeholder="e.g. Car Accident"></div>
    <div class="adm-field"><label for="am">Amount (display)</label><input class="adm-input" type="text" id="am" name="result_amount" value="<?= e($row['result_amount']) ?>" required placeholder="e.g. $2.1M"></div>
  </div>
  <div class="adm-row">
    <div class="adm-field"><label for="yr">Year</label><input class="adm-input" type="number" id="yr" name="result_year" value="<?= e($row['result_year'] ?? '') ?>" min="1990" max="2100" placeholder="2024"></div>
    <div class="adm-field"><label for="at">Handling Attorney (optional)</label><select class="adm-select" id="at" name="attorney_id"><option value="">— None —</option><?php foreach ($attorneys as $a): ?><option value="<?= (int) $a['id'] ?>" <?= (int) ($row['attorney_id'] ?? 0) === (int) $a['id'] ? 'selected' : '' ?>><?= e($a['name']) ?></option><?php endforeach; ?></select></div>
  </div>
  <div class="adm-field"><label for="de">Description</label><textarea class="adm-textarea" id="de" name="description" rows="3"><?= e($row['description']) ?></textarea></div>
  <div class="adm-field"><label class="switch"><input type="checkbox" name="display" value="1" <?= $row['display'] ? 'checked' : '' ?>><span class="switch__track"></span><span>Display on site</span></label></div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Save Result</button><a class="btn btn-ghost" href="/admin/results/">Cancel</a></div>
</form>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
