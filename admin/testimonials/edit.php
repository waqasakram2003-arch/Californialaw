<?php
/** admin/testimonials/edit.php — add/edit a testimonial. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    $name = trim((string) ($_POST['client_name'] ?? ''));
    $case = trim((string) ($_POST['case_type'] ?? ''));
    $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
    $text = trim((string) ($_POST['testimonial'] ?? ''));
    $active = isset($_POST['active']) ? 1 : 0;
    $verified = isset($_POST['verified']) ? 1 : 0;
    $aid = ($_POST['attorney_id'] ?? '') !== '' ? (int) $_POST['attorney_id'] : null;
    $pid = (int) ($_POST['id'] ?? 0);
    if ($name === '' || $text === '') { admin_flash('error', 'Name and testimonial are required.'); admin_redirect('/admin/testimonials/edit.php' . ($pid ? '?id=' . $pid : '')); }
    if ($pid) {
        $pdo->prepare('UPDATE testimonials SET client_name=?, case_type=?, rating=?, testimonial=?, active=?, verified=?, attorney_id=? WHERE id=?')
            ->execute([$name, $case, $rating, $text, $active, $verified, $aid, $pid]);
    } else {
        $pdo->prepare('INSERT INTO testimonials (client_name, case_type, rating, testimonial, active, verified, attorney_id) VALUES (?,?,?,?,?,?,?)')
            ->execute([$name, $case, $rating, $text, $active, $verified, $aid]);
    }
    admin_flash('success', 'Testimonial saved.');
    admin_redirect('/admin/testimonials/');
}

$row = ['client_name' => '', 'case_type' => '', 'rating' => 5, 'testimonial' => '', 'active' => 1, 'verified' => 0, 'attorney_id' => null];
if ($id) { $st = $pdo->prepare('SELECT * FROM testimonials WHERE id=?'); $st->execute([$id]); $row = $st->fetch() ?: $row; }
$attorneys = $pdo->query("SELECT id, name FROM attorneys ORDER BY order_num, id")->fetchAll();
$pageTitle = $id ? 'Edit Testimonial' : 'New Testimonial';
$activeNav = 'testimonials';
require __DIR__ . '/../includes/admin-head.php';
?>
<form method="post" class="adm-card" style="max-width:760px">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $id ?>">
  <div class="adm-row">
    <div class="adm-field"><label for="cn">Client Name (or initials)</label><input class="adm-input" type="text" id="cn" name="client_name" value="<?= e($row['client_name']) ?>" required></div>
    <div class="adm-field"><label for="ct">Case Type</label><input class="adm-input" type="text" id="ct" name="case_type" value="<?= e($row['case_type'] ?? '') ?>"></div>
  </div>
  <div class="adm-row">
    <div class="adm-field"><label for="rt">Rating</label><select class="adm-select" id="rt" name="rating"><?php for ($i = 5; $i >= 1; $i--): ?><option value="<?= $i ?>" <?= (int) $row['rating'] === $i ? 'selected' : '' ?>><?= str_repeat('★', $i) ?> (<?= $i ?>)</option><?php endfor; ?></select></div>
    <div class="adm-field"><label for="at">Associated Attorney (optional)</label><select class="adm-select" id="at" name="attorney_id"><option value="">— None —</option><?php foreach ($attorneys as $a): ?><option value="<?= (int) $a['id'] ?>" <?= (int) ($row['attorney_id'] ?? 0) === (int) $a['id'] ? 'selected' : '' ?>><?= e($a['name']) ?></option><?php endforeach; ?></select></div>
  </div>
  <div class="adm-field"><label for="tx">Testimonial</label><textarea class="adm-textarea" id="tx" name="testimonial" rows="5" required><?= e($row['testimonial']) ?></textarea></div>
  <div class="adm-row">
    <div class="adm-field"><label class="switch"><input type="checkbox" name="active" value="1" <?= $row['active'] ? 'checked' : '' ?>><span class="switch__track"></span><span>Approved (visible)</span></label></div>
    <div class="adm-field"><label class="switch"><input type="checkbox" name="verified" value="1" <?= $row['verified'] ? 'checked' : '' ?>><span class="switch__track"></span><span>Featured</span></label></div>
  </div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Save Testimonial</button><a class="btn btn-ghost" href="/admin/testimonials/">Cancel</a></div>
</form>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
