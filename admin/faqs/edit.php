<?php
/** admin/faqs/edit.php — add/edit a FAQ. */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();
$id = (int) ($_GET['id'] ?? 0);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    $q = trim((string) ($_POST['question'] ?? ''));
    $a = trim((string) ($_POST['answer'] ?? ''));
    $cat = trim((string) ($_POST['category'] ?? ''));
    $active = isset($_POST['active']) ? 1 : 0;
    $pid = (int) ($_POST['id'] ?? 0);
    if ($q === '' || $a === '') {
        admin_flash('error', 'Question and answer are required.');
        admin_redirect('/admin/faqs/edit.php' . ($pid ? '?id=' . $pid : ''));
    }
    if ($pid) {
        $pdo->prepare('UPDATE faq_items SET question=?, answer=?, category=?, active=? WHERE id=?')->execute([$q, $a, $cat, $active, $pid]);
    } else {
        $ord = (int) $pdo->query('SELECT COALESCE(MAX(order_num),0)+1 FROM faq_items')->fetchColumn();
        $pdo->prepare('INSERT INTO faq_items (question, answer, category, order_num, active) VALUES (?,?,?,?,?)')->execute([$q, $a, $cat, $ord, $active]);
    }
    admin_flash('success', 'FAQ saved.');
    admin_redirect('/admin/faqs/');
}

$row = ['question' => '', 'answer' => '', 'category' => '', 'active' => 1];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM faq_items WHERE id = ?'); $st->execute([$id]);
    $row = $st->fetch() ?: $row;
}
$cats = $pdo->query("SELECT DISTINCT category FROM faq_items WHERE category<>'' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = $id ? 'Edit FAQ' : 'New FAQ';
$activeNav = 'faqs';
require __DIR__ . '/../includes/admin-head.php';
?>
<form method="post" class="adm-card" style="max-width:760px">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="id" value="<?= (int) $id ?>">
  <div class="adm-field"><label for="q">Question</label><input class="adm-input" type="text" id="q" name="question" value="<?= e($row['question']) ?>" required></div>
  <div class="adm-field"><label for="a">Answer (HTML allowed)</label><textarea class="adm-textarea" id="a" name="answer" rows="5" required><?= e($row['answer']) ?></textarea></div>
  <div class="adm-field">
    <label for="cat">Category</label>
    <input class="adm-input" type="text" id="cat" name="category" value="<?= e($row['category']) ?>" list="catlist" placeholder="e.g. General Questions">
    <datalist id="catlist"><?php foreach ($cats as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?></datalist>
  </div>
  <div class="adm-field"><label class="switch"><input type="checkbox" name="active" value="1" <?= $row['active'] ? 'checked' : '' ?>><span class="switch__track"></span><span>Active (visible on site)</span></label></div>
  <div class="form-actions"><button class="btn btn-primary" type="submit">Save FAQ</button><a class="btn btn-ghost" href="/admin/faqs/">Cancel</a></div>
</form>
<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
