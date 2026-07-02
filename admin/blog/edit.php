<?php
/** admin/blog/edit.php — full post editor. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    $pid = (int) ($_POST['id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = generateSlug((string) ($_POST['slug'] ?? $title));
    $excerpt = trim((string) ($_POST['excerpt'] ?? ''));
    $content = (string) ($_POST['content'] ?? '');
    $catId = ($_POST['category_id'] ?? '') !== '' ? (int) $_POST['category_id'] : null;
    $mt = trim((string) ($_POST['meta_title'] ?? ''));
    $md = trim((string) ($_POST['meta_desc'] ?? ''));
    $action = $_POST['action'] ?? 'draft';
    $status = $action === 'publish' ? 'published' : 'draft';
    $pubInput = trim((string) ($_POST['published_at'] ?? ''));
    $pubAt = $pubInput !== '' ? date('Y-m-d H:i:s', strtotime($pubInput)) : ($status === 'published' ? date('Y-m-d H:i:s') : null);

    // Author (from attorney slug)
    $authorSlug = trim((string) ($_POST['author_slug'] ?? ''));
    $authorName = null;
    if ($authorSlug !== '') {
        $a = $pdo->prepare('SELECT name FROM attorneys WHERE slug=?'); $a->execute([$authorSlug]);
        $authorName = $a->fetchColumn() ?: null;
        if (!$authorName) { $authorSlug = null; }
    } else { $authorSlug = null; }

    // Featured image
    $image = trim((string) ($_POST['current_image'] ?? '')) ?: null;
    if (!empty($_FILES['featured']['name'])) {
        $up = admin_upload_image('featured');
        if ($up['ok']) { $image = $up['url']; } else { admin_flash('error', 'Image: ' . $up['error']); }
    }

    if ($title === '') { admin_flash('error', 'Title is required.'); admin_redirect('/admin/blog/edit.php' . ($pid ? '?id=' . $pid : '')); }

    if ($pid) {
        $pdo->prepare('UPDATE blog_posts SET title=?, slug=?, excerpt=?, content=?, featured_image=?, category_id=?, author_name=?, author_slug=?, status=?, published_at=?, meta_title=?, meta_desc=? WHERE id=?')
            ->execute([$title, $slug, $excerpt, $content, $image, $catId, $authorName, $authorSlug, $status, $pubAt, $mt, $md, $pid]);
    } else {
        $pdo->prepare('INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, category_id, author_name, author_slug, status, published_at, meta_title, meta_desc, views) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,0)')
            ->execute([$title, $slug, $excerpt, $content, $image, $catId, $authorName, $authorSlug, $status, $pubAt, $mt, $md]);
    }
    admin_flash('success', 'Post saved (' . $status . ').');
    admin_redirect('/admin/blog/');
}

$row = ['title'=>'','slug'=>'','excerpt'=>'','content'=>'','featured_image'=>'','category_id'=>null,'author_slug'=>'','status'=>'draft','published_at'=>'','meta_title'=>'','meta_desc'=>''];
if ($id) { $st = $pdo->prepare('SELECT * FROM blog_posts WHERE id=?'); $st->execute([$id]); $row = $st->fetch() ?: $row; }
$cats = $pdo->query("SELECT id, name FROM blog_categories ORDER BY name")->fetchAll();
$attorneys = $pdo->query("SELECT slug, name FROM attorneys ORDER BY order_num, id")->fetchAll();
$pubVal = $row['published_at'] ? date('Y-m-d\TH:i', strtotime($row['published_at'])) : '';
$pageTitle = $id ? 'Edit Post' : 'New Post';
$activeNav = 'blog';
$pageStyles = ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'];
$pageScripts = ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js'];
require __DIR__ . '/../includes/admin-head.php';
?>
<form method="post" enctype="multipart/form-data" class="adm-card" style="max-width:900px" id="postform">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $id ?>">
  <input type="hidden" name="current_image" value="<?= e($row['featured_image'] ?? '') ?>">
  <input type="hidden" name="action" id="action" value="draft">

  <div class="adm-row">
    <div class="adm-field"><label for="title">Title</label><input class="adm-input" type="text" id="title" name="title" value="<?= e($row['title']) ?>" required data-slug-source="#slug"></div>
    <div class="adm-field"><label for="slug">Slug</label><input class="adm-input" type="text" id="slug" name="slug" value="<?= e($row['slug']) ?>"></div>
  </div>

  <div class="adm-row">
    <div class="adm-field"><label for="cat">Category</label><select class="adm-select" id="cat" name="category_id"><option value="">— None —</option><?php foreach ($cats as $c): ?><option value="<?= (int) $c['id'] ?>" <?= (int) ($row['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
    <div class="adm-field"><label for="auth">Author</label><select class="adm-select" id="auth" name="author_slug"><option value="">— None —</option><?php foreach ($attorneys as $a): ?><option value="<?= e($a['slug']) ?>" <?= ($row['author_slug'] ?? '') === $a['slug'] ? 'selected' : '' ?>><?= e($a['name']) ?></option><?php endforeach; ?></select></div>
  </div>

  <div class="adm-field"><label for="excerpt">Excerpt</label><textarea class="adm-textarea" id="excerpt" name="excerpt" rows="2" maxlength="360" data-charcount="#exc"><?= e($row['excerpt'] ?? '') ?></textarea><span class="hint"><span id="exc"></span></span></div>

  <div class="adm-field">
    <label>Content</label>
    <div id="editor" style="background:#fff;min-height:240px"><?= $row['content'] ?></div>
    <textarea name="content" id="content" hidden></textarea>
  </div>

  <div class="adm-field">
    <label>Featured image</label>
    <?php if (!empty($row['featured_image'])): ?><img src="<?= e($row['featured_image']) ?>" alt="" style="max-width:240px;border-radius:8px;margin-bottom:10px"><?php endif; ?>
    <input class="adm-input" type="file" name="featured" accept="image/jpeg,image/png,image/webp">
    <span class="hint">Leave empty to keep current. JPG/PNG/WebP, max 5MB.</span>
  </div>

  <div class="adm-row">
    <div class="adm-field"><label for="mt">Meta title</label><input class="adm-input" type="text" id="mt" name="meta_title" maxlength="70" value="<?= e($row['meta_title'] ?? '') ?>" data-charcount="#mtc"><span class="hint"><span id="mtc"></span> / 70</span></div>
    <div class="adm-field"><label for="md">Meta description</label><input class="adm-input" type="text" id="md" name="meta_desc" maxlength="170" value="<?= e($row['meta_desc'] ?? '') ?>" data-charcount="#mdc"><span class="hint"><span id="mdc"></span> / 170</span></div>
  </div>

  <div class="adm-field" style="max-width:320px"><label for="pub">Publish date (future = scheduled)</label><input class="adm-input" type="datetime-local" id="pub" name="published_at" value="<?= e($pubVal) ?>"></div>

  <div class="form-actions">
    <button class="btn btn-ghost" type="submit" onclick="document.getElementById('action').value='draft'">Save as Draft</button>
    <button class="btn btn-primary" type="submit" onclick="document.getElementById('action').value='publish'">Publish</button>
    <?php if ($id && !empty($row['slug'])): ?><a class="btn btn-dark" href="/blog/<?= e($row['slug']) ?>/?preview=1" target="_blank" rel="noopener">Preview &#8599;</a><?php endif; ?>
    <a class="btn btn-ghost" href="/admin/blog/">Cancel</a>
  </div>
</form>

<script>window.addEventListener('load',function(){
  if(!window.Quill)return;
  var q=new Quill('#editor',{theme:'snow',modules:{toolbar:[['bold','italic','underline'],[{header:[2,3,false]}],['blockquote','link'],[{list:'ordered'},{list:'bullet'}],['clean']]}});
  document.getElementById('postform').addEventListener('submit',function(){document.getElementById('content').value=q.root.innerHTML;});
});</script>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
