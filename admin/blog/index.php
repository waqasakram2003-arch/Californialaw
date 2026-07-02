<?php
/** admin/blog/ — posts table with status toggle + delete. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    if (isset($_POST['delete_id'])) {
        $pdo->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([(int) $_POST['delete_id']]);
        admin_flash('success', 'Post deleted.');
    } elseif (isset($_POST['toggle_status'])) {
        $pid = (int) $_POST['toggle_status'];
        $cur = $pdo->prepare('SELECT status, published_at FROM blog_posts WHERE id=?'); $cur->execute([$pid]);
        $p = $cur->fetch();
        if ($p) {
            $new = $p['status'] === 'published' ? 'draft' : 'published';
            $setDate = ($new === 'published' && empty($p['published_at'])) ? date('Y-m-d H:i:s') : $p['published_at'];
            $pdo->prepare('UPDATE blog_posts SET status=?, published_at=? WHERE id=?')->execute([$new, $setDate, $pid]);
            admin_flash('success', 'Post ' . ($new === 'published' ? 'published' : 'moved to draft') . '.');
        }
    }
    admin_redirect('/admin/blog/');
}

$rows = $pdo->query("SELECT p.id, p.title, p.slug, p.status, p.published_at, p.views, c.name AS cat
                     FROM blog_posts p LEFT JOIN blog_categories c ON c.id=p.category_id
                     ORDER BY p.published_at DESC, p.id DESC")->fetchAll();
$pageTitle = 'Blog';
$activeNav = 'blog';
require __DIR__ . '/../includes/admin-head.php';
$csrf = csrf_token();
$now = date('Y-m-d H:i:s');
?>
<div class="section-title"><h2>Blog Posts</h2><a class="btn btn-primary" href="/admin/blog/edit.php">+ New Post</a></div>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Views</th><th>Actions</th></tr></thead>
    <tbody>
      <?php if (!$rows): ?><tr><td colspan="6" class="adm-table__empty">No posts yet.</td></tr><?php endif; ?>
      <?php foreach ($rows as $r):
        $isScheduled = $r['status'] === 'published' && $r['published_at'] && $r['published_at'] > $now;
        $statusLabel = $isScheduled ? 'reviewing' : $r['status']; // reuse amber badge for "scheduled"
      ?>
        <tr>
          <td class="num"><?= e($r['title']) ?></td>
          <td><?= e($r['cat'] ?? '—') ?></td>
          <td><?= $isScheduled ? '<span class="badge is-reviewing">Scheduled</span>' : admin_status_badge($r['status']) ?></td>
          <td><?= e($r['published_at'] ? formatDate($r['published_at'], 'M j, Y') : '—') ?></td>
          <td><?= (int) $r['views'] ?></td>
          <td class="row-actions">
            <a class="btn btn-ghost btn-sm" href="/admin/blog/edit.php?id=<?= (int) $r['id'] ?>">Edit</a>
            <form method="post" style="display:inline"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="toggle_status" value="<?= (int) $r['id'] ?>"><button class="btn btn-ghost btn-sm" type="submit"><?= $r['status'] === 'published' ? 'Unpublish' : 'Publish' ?></button></form>
            <form method="post" style="display:inline" data-confirm-submit="Delete this post permanently?"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="delete_id" value="<?= (int) $r['id'] ?>"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
