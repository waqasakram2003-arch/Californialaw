<?php
/** admin/settings/ — site settings (read by header/footer via cfg()). */
require_once __DIR__ . '/../includes/admin-bootstrap.php';

$fields = [
    'firm_name'         => ['Firm Name', 'text'],
    'site_phone'        => ['Phone (display)', 'text'],
    'site_phone_raw'    => ['Phone (tel: link, e.g. +12135550188)', 'text'],
    'site_email'        => ['Email', 'text'],
    'site_address'      => ['Address', 'text'],
    'maps_embed'        => ['Google Maps embed URL', 'text'],
    'social_facebook'   => ['Facebook URL', 'text'],
    'social_x'          => ['X (Twitter) URL', 'text'],
    'social_linkedin'   => ['LinkedIn URL', 'text'],
    'social_instagram'  => ['Instagram URL', 'text'],
    'ga_id'             => ['Google Analytics ID (e.g. G-XXXX)', 'text'],
    'pixel_id'          => ['Meta Pixel ID', 'text'],
    'footer_disclaimer' => ['Footer disclaimer text', 'textarea'],
];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    admin_require_csrf();
    foreach ($fields as $key => $f) {
        admin_set_setting($key, trim((string) ($_POST[$key] ?? '')));
    }
    admin_set_setting('maintenance_mode', isset($_POST['maintenance_mode']) ? '1' : '0');
    admin_flash('success', 'Settings saved.');
    admin_redirect('/admin/settings/');
}

$s = admin_all_settings();
$pageTitle = 'Site Settings';
$activeNav = 'settings';
require __DIR__ . '/../includes/admin-head.php';
?>

<form method="post" class="adm-card" style="max-width:760px">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

  <?php foreach ($fields as $key => $f): ?>
    <div class="adm-field">
      <label for="f-<?= e($key) ?>"><?= e($f[0]) ?></label>
      <?php if ($f[1] === 'textarea'): ?>
        <textarea class="adm-textarea" id="f-<?= e($key) ?>" name="<?= e($key) ?>" rows="4"><?= e($s[$key] ?? '') ?></textarea>
      <?php else: ?>
        <input class="adm-input" type="text" id="f-<?= e($key) ?>" name="<?= e($key) ?>" value="<?= e($s[$key] ?? '') ?>">
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <div class="adm-field">
    <label class="switch">
      <input type="checkbox" name="maintenance_mode" value="1" <?= ($s['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' ?>>
      <span class="switch__track"></span>
      <span><strong>Maintenance mode</strong> — show a holding page to visitors (admins still see the site)</span>
    </label>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save Settings</button>
    <a class="btn btn-ghost" href="/" target="_blank" rel="noopener">View Site</a>
  </div>
</form>

<?php require __DIR__ . '/../includes/admin-foot.php'; ?>
