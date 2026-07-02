<?php
/** blog/pagination.php — expects $pag (paginate() array) and $baseUrl. */
if (($pag['total_pages'] ?? 1) <= 1) { return; }
$qs = !empty($pagQuery) ? ('&' . http_build_query($pagQuery)) : '';
$link = function (int $p) use ($baseUrl, $qs) {
    return $p <= 1 ? ($baseUrl . ($qs ? '?' . ltrim($qs, '&') : '')) : ($baseUrl . '?page=' . $p . $qs);
};
?>
<nav class="pagination" aria-label="Blog pagination">
  <?php if ($pag['has_prev']): ?>
    <a class="pagination__btn" href="<?= e($link($pag['prev_page'])) ?>" aria-label="Previous page">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
  <?php endif; ?>
  <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
    <a class="pagination__btn<?= $i === $pag['page'] ? ' is-active' : '' ?>" href="<?= e($link($i)) ?>"<?= $i === $pag['page'] ? ' aria-current="page"' : '' ?>><?= $i ?></a>
  <?php endfor; ?>
  <?php if ($pag['has_next']): ?>
    <a class="pagination__btn" href="<?= e($link($pag['next_page'])) ?>" aria-label="Next page">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
  <?php endif; ?>
</nav>
