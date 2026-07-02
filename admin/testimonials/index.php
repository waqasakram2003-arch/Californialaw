<?php
/** admin/testimonials/ — approve (active) + feature (verified) + delete. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_id'])) {
    admin_require_csrf();
    $pdo->prepare('DELETE FROM testimonials WHERE id = ?')->execute([(int) $_POST['delete_id']]);
    admin_flash('success', 'Testimonial deleted.');
    admin_redirect('/admin/testimonials/');
}
$rows = $pdo->query("SELECT id, client_name, case_type, rating, active, verified FROM testimonials ORDER BY id DESC")->fetchAll();
$pageTitle = 'Testimonials';
$activeNav = 'testimonials';
require __DIR__ . '/../includes/admin-head.php';
$csrf = csrf_token();
?>
<div class="section-title"><h2>Testimonials</h2><a class="btn btn-primary" href="/admin/testimonials/edit.php">+ New Testimonial</a></div>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th>Client</th><th>Case Type</th><th>Rating</th><th>Approved</th><th>Featured</th><th>Actions</th></tr></thead>
    <tbody>
      <?php if (!$rows): ?><tr><td colspan="6" class="adm-table__empty">No testimonials.</td></tr><?php endif; ?>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td class="num"><?= e($r['client_name']) ?></td>
          <td><?= e($r['case_type'] ?? '—') ?></td>
          <td><?= str_repeat('★', (int) $r['rating']) ?></td>
          <td><label class="switch"><input type="checkbox" data-toggle-field="active" data-table="testimonials" data-id="<?= (int) $r['id'] ?>" <?= $r['active'] ? 'checked' : '' ?>><span class="switch__track"></span></label></td>
          <td><label class="switch"><input type="checkbox" data-toggle-field="verified" data-table="testimonials" data-id="<?= (int) $r['id'] ?>" <?= $r['verified'] ? 'checked' : '' ?>><span class="switch__track"></span></label></td>
          <td class="row-actions">
            <a class="btn btn-ghost btn-sm" href="/admin/testimonials/edit.php?id=<?= (int) $r['id'] ?>">Edit</a>
            <form method="post" style="display:inline" data-confirm-submit="Delete this testimonial?"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="delete_id" value="<?= (int) $r['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
