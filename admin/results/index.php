<?php
/** admin/results/ — list + reorder + toggle display + delete. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_id'])) {
    admin_require_csrf();
    $pdo->prepare('DELETE FROM case_results WHERE id = ?')->execute([(int) $_POST['delete_id']]);
    admin_flash('success', 'Result deleted.');
    admin_redirect('/admin/results/');
}
$rows = $pdo->query("SELECT id, case_type, result_amount, result_year, display FROM case_results ORDER BY order_num, id")->fetchAll();
$pageTitle = 'Case Results';
$activeNav = 'results';
require __DIR__ . '/../includes/admin-head.php';
$csrf = csrf_token();
?>
<div class="section-title"><h2>Case Results</h2><a class="btn btn-primary" href="/admin/results/edit.php">+ New Result</a></div>
<p class="hint" style="color:var(--a-muted);margin-bottom:14px">Drag rows to reorder. Toggle to show/hide on the site.</p>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th style="width:30px"></th><th>Case Type</th><th>Amount</th><th>Year</th><th>Display</th><th>Actions</th></tr></thead>
    <tbody data-reorder="case_results">
      <?php if (!$rows): ?><tr><td colspan="6" class="adm-table__empty">No results.</td></tr><?php endif; ?>
      <?php foreach ($rows as $r): ?>
        <tr data-id="<?= (int) $r['id'] ?>">
          <td><span class="drag-handle">⋮⋮</span></td>
          <td class="num"><?= e($r['case_type']) ?></td>
          <td style="color:var(--a-amber);font-weight:800"><?= e($r['result_amount']) ?></td>
          <td><?= e($r['result_year'] ?: '—') ?></td>
          <td><label class="switch"><input type="checkbox" data-toggle-field="display" data-table="case_results" data-id="<?= (int) $r['id'] ?>" <?= $r['display'] ? 'checked' : '' ?>><span class="switch__track"></span></label></td>
          <td class="row-actions">
            <a class="btn btn-ghost btn-sm" href="/admin/results/edit.php?id=<?= (int) $r['id'] ?>">Edit</a>
            <form method="post" style="display:inline" data-confirm-submit="Delete this result?"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="delete_id" value="<?= (int) $r['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
