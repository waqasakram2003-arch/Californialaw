<?php
/** admin/media/ — image library (upload jpg/png/webp <=5MB, copy URL, delete). */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$publicRoot = dirname(__DIR__, 2); // public_html

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    if (isset($_POST['delete_id'])) {
        $id = (int) $_POST['delete_id'];
        $row = $pdo->prepare('SELECT url FROM media WHERE id = ?');
        $row->execute([$id]);
        $url = $row->fetchColumn();
        if ($url) {
            $path = $publicRoot . $url;
            if (is_file($path)) { @unlink($path); }
            $pdo->prepare('DELETE FROM media WHERE id = ?')->execute([$id]);
            admin_flash('success', 'File deleted.');
        }
    } else {
        $res = admin_upload_image('file');
        admin_flash($res['ok'] ? 'success' : 'error', $res['ok'] ? 'Uploaded.' : $res['error']);
    }
    admin_redirect('/admin/media/');
}

$files = $pdo->query("SELECT id, url, filename, size_bytes, created_at FROM media ORDER BY id DESC")->fetchAll();
$pageTitle = 'Media Library';
$activeNav = 'media';
require __DIR__ . '/../includes/admin-head.php';
?>

<form method="post" enctype="multipart/form-data" class="adm-card" style="margin-bottom:24px;display:flex;gap:14px;align-items:center;flex-wrap:wrap">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <input class="adm-input" type="file" name="file" accept="image/jpeg,image/png,image/webp" required style="max-width:360px">
  <button type="submit" class="btn btn-primary">Upload Image</button>
  <span class="hint" style="color:var(--a-muted)">JPG, PNG, or WebP &middot; max 5MB</span>
</form>

<?php if (!$files): ?>
  <div class="adm-card adm-table__empty">No images uploaded yet.</div>
<?php else: ?>
  <div class="media-grid">
    <?php foreach ($files as $f): ?>
      <div class="media-item">
        <div class="media-item__thumb" style="background-image:url('<?= e($f['url']) ?>')"></div>
        <div class="media-item__body">
          <button class="btn btn-ghost btn-sm" type="button" data-copy="<?= e($f['url']) ?>">Copy URL</button>
          <form method="post" style="display:inline" data-confirm-submit="Delete this image permanently?">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="delete_id" value="<?= (int) $f['id'] ?>">
            <button class="btn btn-danger btn-sm btn-icon" type="submit" aria-label="Delete">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
