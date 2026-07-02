<?php
/** admin/leads/ — Form submissions (Contact + Case Evaluations). */
require_once __DIR__ . '/../includes/admin-bootstrap.php';
$pdo = db();

$tab = ($_GET['tab'] ?? 'contacts') === 'evaluations' ? 'evaluations' : 'contacts';

/* Status update */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['set_status'])) {
    admin_require_csrf();
    $table = $_POST['type'] === 'evaluations' ? 'case_evaluations' : 'contacts';
    $allowed = $table === 'contacts' ? ['new','contacted','closed'] : ['new','reviewing','contacted','closed'];
    $status = in_array($_POST['status'] ?? '', $allowed, true) ? $_POST['status'] : 'new';
    $pdo->prepare("UPDATE `$table` SET status = ? WHERE id = ?")->execute([$status, (int) $_POST['id']]);
    admin_flash('success', 'Status updated.');
    admin_redirect('/admin/leads/?tab=' . $_POST['type']);
}

/* CSV export */
if (isset($_GET['export'])) {
    if ($_GET['export'] === 'evaluations') {
        $rows = $pdo->query("SELECT id,name,email,phone,incident_date,incident_type,injuries,medical_treatment,police_report,status,created_at FROM case_evaluations ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        admin_send_csv('case-evaluations.csv', array_keys($rows[0] ?? ['id'=>'']), array_map('array_values', $rows));
    } else {
        $rows = $pdo->query("SELECT id,name,email,phone,case_type,message,consent,status,created_at FROM contacts ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        admin_send_csv('contacts.csv', array_keys($rows[0] ?? ['id'=>'']), array_map('array_values', $rows));
    }
}

$contacts = $pdo->query("SELECT *, INET6_NTOA(ip_address) AS ip FROM contacts ORDER BY id DESC")->fetchAll();
$evals    = $pdo->query("SELECT *, INET6_NTOA(ip_address) AS ip FROM case_evaluations ORDER BY id DESC")->fetchAll();

$pageTitle = 'Form Submissions';
$activeNav = 'leads';
require __DIR__ . '/../includes/admin-head.php';
$csrf = csrf_token();
?>

<div class="adm-tabs">
  <a class="adm-tab<?= $tab === 'contacts' ? ' is-active' : '' ?>" href="/admin/leads/?tab=contacts">Contact Forms (<?= count($contacts) ?>)</a>
  <a class="adm-tab<?= $tab === 'evaluations' ? ' is-active' : '' ?>" href="/admin/leads/?tab=evaluations">Case Evaluations (<?= count($evals) ?>)</a>
</div>

<div class="section-title">
  <h2><?= $tab === 'contacts' ? 'Contact Submissions' : 'Case Evaluations' ?></h2>
  <a class="btn btn-ghost btn-sm" href="/admin/leads/?export=<?= $tab ?>">⬇ Export CSV</a>
</div>

<?php
function statusForm($id, $type, $current, $opts, $csrf) {
    $h = '<form method="post" style="margin:0"><input type="hidden" name="csrf_token" value="' . e($csrf) . '"><input type="hidden" name="set_status" value="1"><input type="hidden" name="type" value="' . e($type) . '"><input type="hidden" name="id" value="' . (int)$id . '"><select class="adm-select" name="status" onchange="this.form.submit()" style="padding:5px 8px;font-size:.8rem">';
    foreach ($opts as $o) { $h .= '<option value="' . e($o) . '"' . ($o === $current ? ' selected' : '') . '>' . e(ucfirst($o)) . '</option>'; }
    return $h . '</select></form>';
}
?>

<div class="adm-table-wrap">
  <table class="adm-table">
    <?php if ($tab === 'contacts'): ?>
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Case Type</th><th>Date</th><th>IP</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (!$contacts): ?><tr><td colspan="7" class="adm-table__empty">No contact submissions.</td></tr><?php endif; ?>
        <?php foreach ($contacts as $c): ?>
          <tr data-lead-row style="cursor:pointer">
            <td class="num"><?= e($c['name']) ?></td>
            <td><?= e($c['email']) ?></td>
            <td><?= e($c['phone']) ?></td>
            <td><?= e($c['case_type'] ?? '—') ?></td>
            <td><?= e(formatDate($c['created_at'], 'M j, Y g:i A')) ?></td>
            <td><?= e($c['ip'] ?? '—') ?></td>
            <td><?= statusForm($c['id'], 'contacts', $c['status'], ['new','contacted','closed'], $csrf) ?></td>
          </tr>
          <tr class="lead-detail" hidden><td colspan="7">
            <dl><dt>Message</dt><dd><?= e($c['message'] ?: '—') ?></dd>
            <dt>Consent</dt><dd><?= $c['consent'] ? 'Yes' : 'No' ?></dd></dl>
          </td></tr>
        <?php endforeach; ?>
      </tbody>
    <?php else: ?>
      <thead><tr><th>Name</th><th>Phone</th><th>Incident</th><th>Date</th><th>Med?</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (!$evals): ?><tr><td colspan="6" class="adm-table__empty">No case evaluations.</td></tr><?php endif; ?>
        <?php foreach ($evals as $c): $d = $c['details'] ? json_decode($c['details'], true) : []; ?>
          <tr data-lead-row style="cursor:pointer">
            <td class="num"><?= e($c['name']) ?></td>
            <td><?= e($c['phone']) ?></td>
            <td><?= e($c['incident_type'] ?? '—') ?></td>
            <td><?= e(formatDate($c['created_at'], 'M j, Y')) ?></td>
            <td><?= $c['medical_treatment'] ? 'Yes' : 'No' ?></td>
            <td><?= statusForm($c['id'], 'evaluations', $c['status'], ['new','reviewing','contacted','closed'], $csrf) ?></td>
          </tr>
          <tr class="lead-detail" hidden><td colspan="6">
            <dl>
              <dt>Email</dt><dd><?= e($c['email']) ?></dd>
              <dt>Incident date</dt><dd><?= e($c['incident_date'] ?: '—') ?></dd>
              <dt>Location</dt><dd><?= e($d['location'] ?? '—') ?></dd>
              <dt>Injuries</dt><dd><?= e($c['injuries'] ?: '—') ?></dd>
              <dt>Treatment</dt><dd><?= e($d['treatment_type'] ?? '—') ?> <?= !empty($d['still_treating']) ? '(ongoing)' : '' ?></dd>
              <dt>Police report</dt><dd><?= $c['police_report'] ? 'Yes' : 'No' ?></dd>
              <dt>Insurance claim</dt><dd><?= !empty($d['insurance_claim']) ? 'Yes' : 'No' ?></dd>
              <dt>Preferred contact</dt><dd><?= e($d['preferred_contact'] ?? '—') ?> <?= !empty($d['best_time']) ? '· ' . e($d['best_time']) : '' ?></dd>
              <dt>Heard about us</dt><dd><?= e($d['hear_about'] ?? '—') ?></dd>
              <dt>Description</dt><dd><?= e($c['description'] ?: '—') ?></dd>
              <dt>IP</dt><dd><?= e($c['ip'] ?? '—') ?></dd>
            </dl>
          </td></tr>
        <?php endforeach; ?>
      </tbody>
    <?php endif; ?>
  </table>
</div>
<p class="hint" style="margin-top:12px;color:var(--a-muted);">Tip: click a row to expand full details.</p>

<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
