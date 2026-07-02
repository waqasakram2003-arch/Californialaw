<?php
/**
 * admin/login.php — email + password login with CSRF + rate limiting.
 */
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/form-security.php';

admin_session_start();
if (is_logged_in()) {
    header('Location: /admin/');
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security check failed. Please try again.';
    } elseif (rate_limited('admin-login', 5)) {
        $error = 'Too many login attempts. Please try again later.';
    } else {
        $id = trim((string) ($_POST['email'] ?? ''));
        $pw = (string) ($_POST['password'] ?? '');
        if ($id !== '' && $pw !== '' && attempt_login($id, $pw)) {
            $dest = $_SESSION['redirect_after_login'] ?? '/admin/';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $dest);
            exit;
        }
        $error = 'Invalid email or password.';
    }
}
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Admin Login &middot; <?= e(cfg('firm_name', SITE_NAME)) ?></title>
  <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin">
  <div class="admin-login">
    <form class="admin-login__card" method="post" action="/admin/login.php" novalidate>
      <div class="admin-login__logo">GS</div>
      <h1>Admin Panel</h1>
      <p class="admin-login__sub"><?= e(cfg('firm_name', SITE_NAME)) ?></p>

      <?php if ($error): ?><div class="flash flash--error"><?= e($error) ?></div><?php endif; ?>

      <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
      <div class="adm-field">
        <label for="email">Email</label>
        <input class="adm-input" type="email" id="email" name="email" required autocomplete="username" autofocus placeholder="you@firm.com">
      </div>
      <div class="adm-field">
        <label for="password">Password</label>
        <input class="adm-input" type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Sign In</button>
    </form>
  </div>
</body>
</html>
