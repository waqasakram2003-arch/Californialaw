<?php
/**
 * config.secret.sample.php  —  TEMPLATE (safe to commit).
 *
 * On the SERVER, copy this to  config.secret.php  and fill in the real value.
 * config.secret.php is GITIGNORED and must NEVER be committed — the GitHub repo
 * is public, so the live database password lives only here on the server.
 */
declare(strict_types=1);

define('GSIL_DB_PASS', 'your-database-password-here');
