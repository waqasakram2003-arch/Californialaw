<?php
/** admin/faqs/ — list + reorder + toggle + delete. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_id'])) {
    admin_require_csrf();
    $pdo->prepare('DELETE FROM faq_items WHERE id = ?')->execute([(int) $_POST['delete_id']]);
    admin_flash('success', 'FAQ deleted.');
    admin_redirect('/admin/faqs/');
}

$rows = $pdo->query("SELECT id, question, category, active FROM faq_items ORDER BY category, order_num, id")->fetchAll();
$pageTitle = 'FAQs';
$activeNav = 'faqs';
require __DIR__ . '/../includes/admin-head.php';
$csrf = csrf_token();
?>
<div class="section-title"><h2>Frequently Asked Questions</h2><a class="btn btn-primary" href="/admin/faqs/edit.php">+ New FAQ</a></div>
<p class="hint" style="color:var(--a-muted);margin-bottom:14px">Drag rows to reorder. Toggle to show/hide on the site.</p>

<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th style="width:30px"></th><th>Question</th><th>Category</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody data-reorder="faq_items">
      <?php if (!$rows): ?><tr><td colspan="5" class="adm-table__empty">No FAQs yet.</td></tr><?php endif; ?>
      <?php foreach ($rows as $r): ?>
        <tr data-id="<?= (int) $r['id'] ?>">
          <td><span class="drag-handle">⋮⋮</span></td>
          <td class="num"><?= e($r['question']) ?></td>
          <td><?= e($r['category']) ?></td>
          <td>
            <label class="switch"><input type="checkbox" data-toggle-field="active" data-table="faq_items" data-id="<?= (int) $r['id'] ?>" <?= $r['active'] ? 'checked' : '' ?>><span class="switch__track"></span></label>
          </td>
          <td class="row-actions">
            <a class="btn btn-ghost btn-sm" href="/admin/faqs/edit.php?id=<?= (int) $r['id'] ?>">Edit</a>
            <form method="post" style="display:inline" data-confirm-submit="Delete this FAQ?">
              <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="delete_id" value="<?= (int) $r['id'] ?>">
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
