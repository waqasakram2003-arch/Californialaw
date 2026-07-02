<?php
/** admin/index.php — dashboard. */
require_once __DIR__ . '/includes/admin-bootstrap.php';

$pdo = db();
function scalar(PDO $pdo, string $sql): int { try { return (int) $pdo->query($sql)->fetchColumn(); } catch (Throwable $e) { return 0; } }

$leadsToday = scalar($pdo, "SELECT (SELECT COUNT(*) FROM contacts WHERE DATE(created_at)=CURDATE()) + (SELECT COUNT(*) FROM case_evaluations WHERE DATE(created_at)=CURDATE())");
$leadsWeek  = scalar($pdo, "SELECT (SELECT COUNT(*) FROM contacts WHERE created_at>=NOW()-INTERVAL 7 DAY) + (SELECT COUNT(*) FROM case_evaluations WHERE created_at>=NOW()-INTERVAL 7 DAY)");
$postCount  = scalar($pdo, "SELECT COUNT(*) FROM blog_posts WHERE status='published'");
$paCount    = scalar($pdo, "SELECT COUNT(*) FROM practice_areas WHERE active=1");
$attCount   = scalar($pdo, "SELECT COUNT(*) FROM attorneys WHERE active=1");

$recentContacts = $pdo->query("SELECT id,name,email,phone,case_type,status,created_at FROM contacts ORDER BY id DESC LIMIT 10")->fetchAll();
$recentEvals    = $pdo->query("SELECT id,name,phone,incident_type,status,created_at FROM case_evaluations ORDER BY id DESC LIMIT 10")->fetchAll();

/* Leads per day, last 30 days */
$series = [];
for ($i = 29; $i >= 0; $i--) { $series[date('Y-m-d', strtotime("-$i day"))] = 0; }
foreach (['contacts', 'case_evaluations'] as $t) {
    foreach ($pdo->query("SELECT DATE(created_at) d, COUNT(*) c FROM $t WHERE created_at>=NOW()-INTERVAL 29 DAY GROUP BY d") as $r) {
        if (isset($series[$r['d']])) { $series[$r['d']] += (int) $r['c']; }
    }
}
$chartLabels = array_map(fn ($d) => date('M j', strtotime($d)), array_keys($series));
$chartData   = array_values($series);

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
$pageScripts = ['https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js'];
$pageInlineScript = '';
require __DIR__ . '/includes/admin-head.php';
?>

<p style="color:var(--a-muted);margin-top:-6px;margin-bottom:24px;">
  Welcome back, <strong><?= e($ADMIN['username']) ?></strong>.
  <?php if (!empty($ADMIN['last_login'])): ?>Last login: <?= e(formatDate($ADMIN['last_login'], 'M j, Y g:i A')) ?>.<?php endif; ?>
</p>

<div class="adm-grid adm-grid--stats">
  <div class="adm-card stat-card">
    <span class="stat-card__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 6 12 13 2 6M2 6h20v12H2z"/></svg></span>
    <span class="stat-card__num"><?= $leadsWeek ?></span>
    <span class="stat-card__label">New Leads (7 days) &middot; <?= $leadsToday ?> today</span>
  </div>
  <div class="adm-card stat-card">
    <span class="stat-card__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/></svg></span>
    <span class="stat-card__num"><?= $postCount ?></span>
    <span class="stat-card__label">Published Posts</span>
  </div>
  <div class="adm-card stat-card">
    <span class="stat-card__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 7v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V7z"/></svg></span>
    <span class="stat-card__num"><?= $paCount ?></span>
    <span class="stat-card__label">Practice Areas</span>
  </div>
  <div class="adm-card stat-card">
    <span class="stat-card__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/></svg></span>
    <span class="stat-card__num"><?= $attCount ?></span>
    <span class="stat-card__label">Attorneys</span>
  </div>
</div>

<div class="section-title"><h2>Leads — Last 30 Days</h2></div>
<div class="adm-card"><canvas id="leadsChart" height="90"></canvas></div>

<div class="section-title"><h2>Recent Contact Submissions</h2><a class="btn btn-ghost btn-sm" href="/admin/leads/">View all</a></div>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Case Type</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      <?php if (!$recentContacts): ?><tr><td colspan="6" class="adm-table__empty">No contact submissions yet.</td></tr><?php endif; ?>
      <?php foreach ($recentContacts as $c): ?>
        <tr>
          <td class="num"><?= e($c['name']) ?></td>
          <td><?= e($c['email']) ?></td>
          <td><?= e($c['phone']) ?></td>
          <td><?= e($c['case_type'] ?? '—') ?></td>
          <td><?= e(formatDate($c['created_at'], 'M j, g:i A')) ?></td>
          <td><?= admin_status_badge($c['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="section-title"><h2>Recent Case Evaluations</h2><a class="btn btn-ghost btn-sm" href="/admin/leads/?tab=evaluations">View all</a></div>
<div class="adm-table-wrap">
  <table class="adm-table">
    <thead><tr><th>Name</th><th>Phone</th><th>Incident</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      <?php if (!$recentEvals): ?><tr><td colspan="5" class="adm-table__empty">No case evaluations yet.</td></tr><?php endif; ?>
      <?php foreach ($recentEvals as $c): ?>
        <tr>
          <td class="num"><?= e($c['name']) ?></td>
          <td><?= e($c['phone']) ?></td>
          <td><?= e($c['incident_type'] ?? '—') ?></td>
          <td><?= e(formatDate($c['created_at'], 'M j, g:i A')) ?></td>
          <td><?= admin_status_badge($c['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="section-title"><h2>Quick Actions</h2></div>
<div class="adm-grid adm-grid--stats">
  <a class="adm-card btn-ghost" href="/admin/blog/edit.php" style="text-align:center;font-weight:600;">+ New Blog Post</a>
  <a class="adm-card btn-ghost" href="/admin/results/edit.php" style="text-align:center;font-weight:600;">+ New Case Result</a>
  <a class="adm-card btn-ghost" href="/admin/testimonials/edit.php" style="text-align:center;font-weight:600;">+ New Testimonial</a>
  <a class="adm-card btn-ghost" href="/admin/settings/" style="text-align:center;font-weight:600;">Edit Site Settings</a>
</div>

<?php
$pageInlineScript = 'window.addEventListener("load",function(){var c=document.getElementById("leadsChart");if(!c||!window.Chart)return;new Chart(c,{type:"line",data:{labels:' . json_encode($chartLabels) . ',datasets:[{label:"Leads",data:' . json_encode($chartData) . ',borderColor:"#D4AF6A",backgroundColor:"rgba(212,175,106,.15)",fill:true,tension:.35,pointRadius:2}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{precision:0}}}}});});';
require __DIR__ . '/includes/admin-foot.php';
