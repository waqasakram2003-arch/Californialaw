<?php
/**
 * privacy.php — legacy alias. The canonical privacy policy now lives at
 * /privacy-policy.php (per the project file manifest). Permanent-redirect so
 * old links and bookmarks keep working.
 */
require_once __DIR__ . '/includes/functions.php';
redirect('/privacy-policy.php', 301);
