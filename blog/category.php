<?php
/**
 * blog/category.php — posts filtered by category (clean URL /blog/category/<slug>/).
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/blog-helpers.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['slug'])) : '';
$category = $slug !== '' ? getBlogCategoryBySlug($slug) : null;

if (!$category) {
    http_response_code(404);
    $page = ['title' => 'Category Not Found', 'robots' => 'noindex, follow', 'path' => '/blog/'];
    require __DIR__ . '/../includes/header.php';
    echo '<section class="section"><div class="container text-center"><p class="eyebrow">404</p><h1>Category not found</h1><p style="margin-top:2rem;"><a class="btn btn--primary" href="/blog/">View all articles</a></p></div></section>';
    require __DIR__ . '/../includes/footer.php';
    return;
}

$pageNum = max(1, (int) ($_GET['page'] ?? 1));
$result  = getPublishedPostsPaged($pageNum, BLOG_PER_PAGE, $slug);
$pag     = paginate($result['total'], $pageNum, BLOG_PER_PAGE);
$posts   = $result['posts'];
$cats    = getBlogCategoriesWithCounts();

$page = [
    'title'       => $category['name'] . ' Articles',
    'description' => $category['description'] ?: ('California injury law articles in ' . $category['name'] . '.'),
    'path'        => blog_category_url($slug),
    'styles'      => ['/assets/css/home.css', '/assets/css/blog.css'],
    'scripts'     => ['/assets/js/home.js', '/assets/js/blog.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Blog', 'path' => '/blog/'],
        ['name' => $category['name'], 'path' => blog_category_url($slug)],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<section class="pa-hero pa-hero--index blog-hero" aria-label="<?= e($category['name']) ?>">
  <div class="container pa-hero__inner">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <ol><li><a href="/">Home</a> <span aria-hidden="true">/</span></li><li><a href="/blog/">Blog</a> <span aria-hidden="true">/</span></li><li><span aria-current="page"><?= e($category['name']) ?></span></li></ol>
    </nav>
    <p class="eyebrow">Category</p>
    <h1 class="pa-hero__title"><?= e($category['name']) ?></h1>
    <?php if (!empty($category['description'])): ?><p class="pa-hero__subtext"><?= e($category['description']) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container container--wide">
    <div class="blog-filter">
      <a class="blog-filter__btn" href="/blog/">All</a>
      <?php foreach ($cats as $c): ?>
        <a class="blog-filter__btn<?= $c['slug'] === $slug ? ' is-active' : '' ?>" href="<?= e(blog_category_url($c['slug'])) ?>"><?= e($c['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="blog-layout">
      <div class="blog-main">
        <?php if ($posts): ?>
          <div class="blog-grid" data-blog-grid>
            <?php foreach ($posts as $post): require __DIR__ . '/../includes/blog/card.php'; endforeach; ?>
          </div>
          <?php $baseUrl = blog_category_url($slug); $pagQuery = []; require __DIR__ . '/../includes/blog/pagination.php'; ?>
        <?php else: ?>
          <p class="blog-empty">No articles in this category yet. <a href="/blog/">View all articles</a>.</p>
        <?php endif; ?>
      </div>

      <?php $sbActive = $slug; require __DIR__ . '/../includes/blog/sidebar.php'; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
