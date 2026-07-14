<?php
/**
 * calendly.php — Calendly popup integration.
 * Renders nothing unless `calendly_url` is set in Settings. When set, it loads
 * Calendly's widget assets and makes any link with [data-calendly] open the
 * scheduling popup instead of navigating away (plain link is the no-JS fallback).
 */
$calUrl = cfg('calendly_url', '');
if (!$calUrl) {
    return;
}
?>
<link rel="stylesheet" href="https://assets.calendly.com/assets/external/widget.css">
<script src="https://assets.calendly.com/assets/external/widget.js" async></script>
<script>
(function () {
  var url = <?= json_encode($calUrl, JSON_UNESCAPED_SLASHES) ?>;
  document.addEventListener('click', function (e) {
    var t = e.target.closest('[data-calendly]');
    if (!t) return;
    if (window.Calendly && typeof window.Calendly.initPopupWidget === 'function') {
      e.preventDefault();
      window.Calendly.initPopupWidget({ url: url });
    }
    // else: let the plain link (target=_blank) open Calendly normally
  }, false);
})();
</script>
