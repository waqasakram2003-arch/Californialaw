<?php
/** admin/attorneys/ — list + reorder + toggle active + delete. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_id'])) {
    admin_require_csrf();
    $pdo->prepare('DELETE FROM attorneys WHERE id = ?')->execute([(int) $_POST['delete_id']]);
    admin_flash('success', 'Attorney deleted.');
    admin_redirect('/admin/attorneys/');
}
$rows = $pdo->query("SELECT id, name, title, slug, active FROM attorneys ORDER BY order_num, id")->fetchAll();
$pageTitle = 'Attorneys';
$activeNav = 'attorneys';
require __DIR__ . '/../includes/admin-head.php';
$csrf = csrf_token();
?>
<div class="section-title"><h2>Attorneys</h2><a class="btn btn-primary" href="/admin/attorneys/edit.php">+ New Attorney</a></div>
<p class="hint" style="color:var(--a-muted);margin-bottom:14px">Drag rows to reorder.</p>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th style="width:30px"></th><th>Name</th><th>Title</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody data-reorder="attorneys">
      <?php foreach ($rows as $r): ?>
        <tr data-id="<?= (int) $r['id'] ?>">
          <td><span class="drag-handle">⋮⋮</span></td>
          <td class="num"><?= e($r['name']) ?></td>
          <td><?= e($r['title']) ?></td>
          <td><label class="switch"><input type="checkbox" data-toggle-field="active" data-table="attorneys" data-id="<?= (int) $r['id'] ?>" <?= $r['active'] ? 'checked' : '' ?>><span class="switch__track"></span></label></td>
          <td class="row-actions">
            <a class="btn btn-ghost btn-sm" href="/admin/attorneys/edit.php?id=<?= (int) $r['id'] ?>">Edit</a>
            <form method="post" style="display:inline" data-confirm-submit="Delete this attorney?"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="delete_id" value="<?= (int) $r['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
