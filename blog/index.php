<?php
/**
 * blog/index.php — blog listing: hero+search, featured, grid, sidebar, pagination.
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/blog-helpers.php';

$q       = trim((string) ($_GET['q'] ?? ''));
$pageNum = max(1, (int) ($_GET['page'] ?? 1));
$search  = $q !== '' ? $q : null;

$featured = null; $excludeId = null;
if (!$search) {
    $fr = getPublishedPostsPaged(1, 1);
    $featured = $fr['posts'][0] ?? null;
    if ($featured) { $excludeId = (int) $featured['id']; }
}
$result = getPublishedPostsPaged($pageNum, BLOG_PER_PAGE, null, $search, $excludeId);
$pag    = paginate($result['total'], $pageNum, BLOG_PER_PAGE);
$posts  = $result['posts'];
$showFeatured = $featured && $pageNum === 1 && !$search;

$cats = getBlogCategoriesWithCounts();

$page = [
    'title'       => $search ? ('Search: ' . $q) : 'Legal Insights & Resources',
    'description' => 'California personal injury law insights, tips, and resources from the attorneys at '
                   . 'Golden State Injury Lawyers. Informational only — not legal advice.',
    'path'        => '/blog/',
    'styles'      => ['/assets/css/home.css', '/assets/css/blog.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/blog.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Blog', 'path' => '/blog/'],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<section class="pa-hero pa-hero--index blog-hero" aria-label="Blog">
  <div class="container pa-hero__inner">
    <p class="eyebrow">From Our Blog</p>
    <h1 class="pa-hero__title">Legal Insights &amp; Resources</h1>
    <p class="pa-hero__subtext">Plain-English guidance for injured Californians. This information is educational and is not legal advice.</p>
    <form class="blog-search" action="/blog/" method="get" role="search" data-blog-search>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search articles…" autocomplete="off" aria-label="Search articles" data-blog-search-input>
      <button type="submit" class="btn btn--primary btn--sm">Search</button>
      <div class="blog-search__results" data-blog-search-results hidden></div>
    </form>
  </div>
</section>

<section class="section">
  <div class="container container--wide">

    <!-- Category filter -->
    <div class="blog-filter">
      <a class="blog-filter__btn is-active" href="/blog/">All</a>
      <?php foreach ($cats as $c): ?>
        <a class="blog-filter__btn" href="<?= e(blog_category_url($c['slug'])) ?>" data-cat="<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if ($search): ?>
      <p class="blog-results-note"><?= (int) $result['total'] ?> result<?= $result['total'] == 1 ? '' : 's' ?> for &ldquo;<?= e($q) ?>&rdquo;. <a href="/blog/">Clear search</a></p>
    <?php endif; ?>

    <div class="blog-layout">
      <div class="blog-main">

        <?php if ($showFeatured): ?>
          <a class="blog-featured" data-cat="<?= e(blog_cat_key($featured['cat_slug'])) ?>" href="<?= e(blog_post_url($featured['slug'])) ?>">
            <span class="blog-featured__media" aria-hidden="true">
              <?php if (!empty($featured['featured_image'])): ?><img src="<?= e($featured['featured_image']) ?>" alt=""><?php endif; ?>
              <span class="blog-featured__tag">Featured</span>
            </span>
            <span class="blog-featured__body">
              <?php if (!empty($featured['cat_name'])): ?><span class="blog-card__badge"><?= e($featured['cat_name']) ?></span><?php endif; ?>
              <h2 class="blog-featured__title"><?= e($featured['title']) ?></h2>
              <p class="blog-featured__excerpt"><?= e($featured['excerpt']) ?></p>
              <span class="blog-card__meta">
                <span class="blog-card__author"><span class="avatar"><?= e(initials($featured['author_name'] ?? 'GS')) ?></span><?= e($featured['author_name'] ?? '') ?></span>
                <span aria-hidden="true">&middot;</span>
                <time datetime="<?= e(formatDate($featured['published_at'], 'Y-m-d')) ?>"><?= e(formatDate($featured['published_at'], 'M j, Y')) ?></time>
                <span aria-hidden="true">&middot;</span>
                <span><?= blog_read_time($featured['content'] ?? '') ?> min read</span>
              </span>
            </span>
          </a>
        <?php endif; ?>

        <?php if ($posts): ?>
          <div class="blog-grid" data-blog-grid>
            <?php foreach ($posts as $post): require __DIR__ . '/../includes/blog/card.php'; endforeach; ?>
          </div>
          <?php $baseUrl = '/blog/'; $pagQuery = $search ? ['q' => $q] : []; require __DIR__ . '/../includes/blog/pagination.php'; ?>
        <?php else: ?>
          <p class="blog-empty">No articles found<?= $search ? ' for your search' : '' ?>. <a href="/blog/">View all articles</a>.</p>
        <?php endif; ?>
      </div>

      <?php $sbActive = ''; require __DIR__ . '/../includes/blog/sidebar.php'; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
