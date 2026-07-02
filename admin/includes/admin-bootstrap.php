<?php
/**
 * admin-bootstrap.php — include at the very top of every admin page.
 * Enforces login, loads helpers, exposes $ADMIN.
 */
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/admin-helpers.php';

require_admin('/admin/login.php');
$ADMIN = current_admin();
