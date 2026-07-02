<?php
/** admin/practice-areas/edit.php — edit a practice area (rich-text content). */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);
$iconKeys = ['car','truck','motorcycle','slip','workplace','wrongful-death','dog','pedestrian','rideshare','brain'];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    $pid = (int) ($_POST['id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = generateSlug((string) ($_POST['slug'] ?? $title));
    $icon = trim((string) ($_POST['icon'] ?? ''));
    $short = trim((string) ($_POST['short_desc'] ?? ''));
    $content = (string) ($_POST['full_content'] ?? '');
    $mt = trim((string) ($_POST['meta_title'] ?? ''));
    $md = trim((string) ($_POST['meta_desc'] ?? ''));
    $details = trim((string) ($_POST['details'] ?? ''));
    if ($details !== '' && json_decode($details) === null) { admin_flash('error', 'Details must be valid JSON (or empty).'); admin_redirect('/admin/practice-areas/edit.php?id=' . $pid); }
    $active = isset($_POST['active']) ? 1 : 0;
    if ($title === '') { admin_flash('error', 'Title is required.'); admin_redirect('/admin/practice-areas/edit.php?id=' . $pid); }
    if ($pid) {
        $pdo->prepare('UPDATE practice_areas SET title=?, slug=?, icon=?, short_desc=?, full_content=?, details=?, meta_title=?, meta_desc=?, active=? WHERE id=?')
            ->execute([$title, $slug, $icon, $short, $content, $details ?: null, $mt, $md, $active, $pid]);
    } else {
        $ord = (int) $pdo->query('SELECT COALESCE(MAX(order_num),0)+1 FROM practice_areas')->fetchColumn();
        $pdo->prepare('INSERT INTO practice_areas (title, slug, icon, short_desc, full_content, details, meta_title, meta_desc, order_num, active) VALUES (?,?,?,?,?,?,?,?,?,?)')
            ->execute([$title, $slug, $icon, $short, $content, $details ?: null, $mt, $md, $ord, $active]);
    }
    admin_flash('success', 'Practice area saved.');
    admin_redirect('/admin/practice-areas/');
}

$row = ['title'=>'','slug'=>'','icon'=>'car','short_desc'=>'','full_content'=>'','details'=>'','meta_title'=>'','meta_desc'=>'','active'=>1];
if ($id) { $st = $pdo->prepare('SELECT * FROM practice_areas WHERE id=?'); $st->execute([$id]); $row = $st->fetch() ?: $row; }
$pageTitle = $id ? 'Edit Practice Area' : 'New Practice Area';
$activeNav = 'practice-areas';
$pageStyles = ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css'];
$pageScripts = ['https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js'];
require __DIR__ . '/../includes/admin-head.php';
?>
<form method="post" class="adm-card" style="max-width:860px" id="paform">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $id ?>">
  <div class="adm-row">
    <div class="adm-field"><label for="title">Title</label><input class="adm-input" type="text" id="title" name="title" value="<?= e($row['title']) ?>" required data-slug-source="#slug"></div>
    <div class="adm-field"><label for="slug">Slug</label><input class="adm-input" type="text" id="slug" name="slug" value="<?= e($row['slug']) ?>"></div>
  </div>
  <div class="adm-row">
    <div class="adm-field"><label for="icon">Icon</label><select class="adm-select" id="icon" name="icon"><?php foreach ($iconKeys as $k): ?><option value="<?= e($k) ?>" <?= $row['icon'] === $k ? 'selected' : '' ?>><?= e($k) ?></option><?php endforeach; ?></select></div>
    <div class="adm-field"><label class="switch" style="margin-top:28px"><input type="checkbox" name="active" value="1" <?= $row['active'] ? 'checked' : '' ?>><span class="switch__track"></span><span>Active</span></label></div>
  </div>
  <div class="adm-field"><label for="short">Short description</label><input class="adm-input" type="text" id="short" name="short_desc" value="<?= e($row['short_desc']) ?>"></div>
  <div class="adm-field">
    <label>Full content</label>
    <div id="editor" style="background:#fff"><?= $row['full_content'] ?></div>
    <textarea name="full_content" id="content" hidden></textarea>
  </div>
  <div class="adm-row">
    <div class="adm-field"><label for="mt">Meta title</label><input class="adm-input" type="text" id="mt" name="meta_title" maxlength="70" value="<?= e($row['meta_title'] ?? '') ?>" data-charcount="#mtc"><span class="hint">SEO &middot; <span id="mtc"></span></span></div>
    <div class="adm-field"><label for="md">Meta description</label><input class="adm-input" type="text" id="md" name="meta_desc" maxlength="170" value="<?= e($row['meta_desc'] ?? '') ?>" data-charcount="#mdc"><span class="hint">SEO &middot; <span id="mdc"></span></span></div>
  </div>
  <div class="adm-field"><label for="det">Details (JSON — causes, related, category, etc.)</label><textarea class="adm-textarea" id="det" name="details" rows="5" style="font-family:monospace;font-size:.85rem"><?= e(is_string($row['details']) ? $row['details'] : '') ?></textarea></div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Save</button><a class="btn btn-ghost" href="/admin/practice-areas/">Cancel</a></div>
</form>

<script>window.addEventListener('load',function(){
  if(!window.Quill)return;
  var q=new Quill('#editor',{theme:'snow',modules:{toolbar:[['bold','italic','underline'],[{header:[2,3,false]}],['blockquote','link'],[{list:'ordered'},{list:'bullet'}],['clean']]}});
  document.getElementById('paform').addEventListener('submit',function(){document.getElementById('content').value=q.root.innerHTML;});
});</script>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
