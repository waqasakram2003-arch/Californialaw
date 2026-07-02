<?php
/** admin/attorneys/edit.php — add/edit attorney (photo upload + profile JSON). */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    $pid = (int) ($_POST['id'] ?? 0);
    $name = trim((string) ($_POST['name'] ?? ''));
    $title = trim((string) ($_POST['title'] ?? ''));
    $bio = trim((string) ($_POST['bio'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $bar = trim((string) ($_POST['bar_number'] ?? ''));
    $slug = generateSlug((string) ($_POST['slug'] ?? $name));
    $details = trim((string) ($_POST['details'] ?? ''));
    $active = isset($_POST['active']) ? 1 : 0;
    $image = trim((string) ($_POST['current_image'] ?? '')) ?: null;
    if ($details !== '' && json_decode($details) === null) { admin_flash('error', 'Profile details must be valid JSON (or empty).'); admin_redirect('/admin/attorneys/edit.php?id=' . $pid); }
    if ($name === '') { admin_flash('error', 'Name is required.'); admin_redirect('/admin/attorneys/edit.php?id=' . $pid); }
    // Photo upload (optional)
    if (!empty($_FILES['photo']['name'])) {
        $up = admin_upload_image('photo');
        if ($up['ok']) { $image = $up['url']; }
        else { admin_flash('error', 'Photo: ' . $up['error']); }
    }
    if ($pid) {
        $pdo->prepare('UPDATE attorneys SET name=?, title=?, bio=?, image=?, email=?, bar_number=?, slug=?, details=?, active=? WHERE id=?')
            ->execute([$name, $title, $bio, $image, $email, $bar, $slug, $details ?: null, $active, $pid]);
    } else {
        $ord = (int) $pdo->query('SELECT COALESCE(MAX(order_num),0)+1 FROM attorneys')->fetchColumn();
        $pdo->prepare('INSERT INTO attorneys (name, title, bio, image, email, bar_number, slug, details, order_num, active) VALUES (?,?,?,?,?,?,?,?,?,?)')
            ->execute([$name, $title, $bio, $image, $email, $bar, $slug, $details ?: null, $ord, $active]);
    }
    admin_flash('success', 'Attorney saved.');
    admin_redirect('/admin/attorneys/');
}

$row = ['name'=>'','title'=>'','bio'=>'','image'=>'','email'=>'','bar_number'=>'','slug'=>'','details'=>'','active'=>1];
if ($id) { $st = $pdo->prepare('SELECT * FROM attorneys WHERE id=?'); $st->execute([$id]); $row = $st->fetch() ?: $row; }
$pageTitle = $id ? 'Edit Attorney' : 'New Attorney';
$activeNav = 'attorneys';
require __DIR__ . '/../includes/admin-head.php';
?>
<form method="post" enctype="multipart/form-data" class="adm-card" style="max-width:860px">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $id ?>">
  <input type="hidden" name="current_image" value="<?= e($row['image'] ?? '') ?>">
  <div class="adm-row">
    <div class="adm-field"><label for="name">Name</label><input class="adm-input" type="text" id="name" name="name" value="<?= e($row['name']) ?>" required data-slug-source="#slug"></div>
    <div class="adm-field"><label for="title">Title</label><input class="adm-input" type="text" id="title" name="title" value="<?= e($row['title'] ?? '') ?>"></div>
  </div>
  <div class="adm-row">
    <div class="adm-field"><label for="bar">Bar Number</label><input class="adm-input" type="text" id="bar" name="bar_number" value="<?= e($row['bar_number'] ?? '') ?>" placeholder="SBN 000000"></div>
    <div class="adm-field"><label for="slug">Slug</label><input class="adm-input" type="text" id="slug" name="slug" value="<?= e($row['slug']) ?>"></div>
  </div>
  <div class="adm-field"><label for="email">Email</label><input class="adm-input" type="text" id="email" name="email" value="<?= e($row['email'] ?? '') ?>"></div>
  <div class="adm-field"><label for="bio">Short bio / specialty line</label><textarea class="adm-textarea" id="bio" name="bio" rows="3"><?= e($row['bio'] ?? '') ?></textarea></div>
  <div class="adm-field">
    <label>Photo</label>
    <?php if (!empty($row['image'])): ?><img src="<?= e($row['image']) ?>" alt="" style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:10px"><?php endif; ?>
    <input class="adm-input" type="file" name="photo" accept="image/jpeg,image/png,image/webp">
    <span class="hint">Leave empty to keep the current photo. JPG/PNG/WebP, max 5MB.</span>
  </div>
  <div class="adm-field"><label for="det">Profile details (JSON — years, languages, practices, education, publications, bio_long)</label><textarea class="adm-textarea" id="det" name="details" rows="7" style="font-family:monospace;font-size:.85rem"><?= e(is_string($row['details']) ? $row['details'] : '') ?></textarea></div>
  <div class="adm-field"><label class="switch"><input type="checkbox" name="active" value="1" <?= $row['active'] ? 'checked' : '' ?>><span class="switch__track"></span><span>Active</span></label></div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Save</button><a class="btn btn-ghost" href="/admin/attorneys/">Cancel</a></div>
</form>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
