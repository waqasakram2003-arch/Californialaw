<?php
/** admin/practice-areas/ — list + reorder + toggle active + edit. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$rows = $pdo->query("SELECT id, title, slug, active FROM practice_areas ORDER BY order_num, id")->fetchAll();
$pageTitle = 'Practice Areas';
$activeNav = 'practice-areas';
require __DIR__ . '/../includes/admin-head.php';
?>
<div class="section-title"><h2>Practice Areas</h2></div>
<p class="hint" style="color:var(--a-muted);margin-bottom:14px">Drag rows to reorder. Toggle to show/hide on the site.</p>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th style="width:30px"></th><th>Title</th><th>Slug</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody data-reorder="practice_areas">
      <?php foreach ($rows as $r): ?>
        <tr data-id="<?= (int) $r['id'] ?>">
          <td><span class="drag-handle">⋮⋮</span></td>
          <td class="num"><?= e($r['title']) ?></td>
          <td><code><?= e($r['slug']) ?></code></td>
          <td><label class="switch"><input type="checkbox" data-toggle-field="active" data-table="practice_areas" data-id="<?= (int) $r['id'] ?>" <?= $r['active'] ? 'checked' : '' ?>><span class="switch__track"></span></label></td>
          <td class="row-actions">
            <a class="btn btn-ghost btn-sm" href="/admin/practice-areas/edit.php?id=<?= (int) $r['id'] ?>">Edit</a>
            <a class="btn btn-ghost btn-sm" href="/practice-areas/<?= e($r['slug']) ?>/" target="_blank" rel="noopener">View</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
