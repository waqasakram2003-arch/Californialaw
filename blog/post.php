<?php
/**
 * blog/post.php — single blog post (clean URL /blog/<slug>/).
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/repo.php';
require_once __DIR__ . '/../includes/schema.php';
require_once __DIR__ . '/../includes/blog-helpers.php';
require_once __DIR__ . '/../includes/attorney-helpers.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['slug'])) : '';

// Admin preview: allows viewing drafts / scheduled posts.
$preview = false;
if (!empty($_GET['preview'])) {
    require_once __DIR__ . '/../includes/auth.php';
    $preview = is_logged_in();
}
$post = $slug !== '' ? getBlogPostBySlug($slug, $preview) : null;

if (!$post) {
    http_response_code(404);
    $page = ['title' => 'Article Not Found', 'robots' => 'noindex, follow', 'path' => '/blog/'];
    require __DIR__ . '/../includes/header.php';
    echo '<section class="section"><div class="container text-center"><p class="eyebrow">404</p><h1>Article not found</h1><p style="margin-top:2rem;"><a class="btn btn--primary" href="/blog/">Browse the blog</a></p></div></section>';
    require __DIR__ . '/../includes/footer.php';
    return;
}

if (!$preview) { bumpPostViews((int) $post['id']); }

$readTime = blog_read_time($post['content'] ?? '');
$url      = blog_post_url($post['slug']);
$author   = !empty($post['author_slug']) ? getAttorneyBySlug($post['author_slug']) : null;
$related  = getRelatedBlogPosts($post['category_id'] ? (int) $post['category_id'] : null, (int) $post['id'], 3);

$page = [
    'title'       => $post['meta_title'] ?: $post['title'],
    'description' => $post['meta_desc'] ?: mb_substr(strip_tags($post['excerpt'] ?? ''), 0, 160),
    'path'        => $url,
    'og_image'    => $post['featured_image'] ?: '/assets/images/og-default.jpg',
    'og_type'     => 'article',
    'styles'      => ['/assets/css/home.css', '/assets/css/blog.css'],
    'scripts'     => ['/assets/js/blog.js'],
    'schema'      => [schemaArticle($post)],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Blog', 'path' => '/blog/'],
        ['name' => $post['cat_name'] ?? 'Article', 'path' => $post['cat_slug'] ? blog_category_url($post['cat_slug']) : '/blog/'],
        ['name' => $post['title'], 'path' => $url],
    ],
];

require __DIR__ . '/../includes/header.php';
?>

<!-- Reading progress (R8.7) -->
<div class="reading-progress" aria-hidden="true"><span class="reading-progress__fill" data-reading-progress></span></div>

<!-- 1. HERO -->
<article class="post" data-reading-target>
  <header class="post-hero">
    <div class="container container--narrow">
      <nav class="breadcrumbs breadcrumbs--light" aria-label="Breadcrumb">
        <ol>
          <li><a href="/">Home</a> <span aria-hidden="true">/</span></li>
          <li><a href="/blog/">Blog</a> <span aria-hidden="true">/</span></li>
          <?php if (!empty($post['cat_name'])): ?><li><a href="<?= e(blog_category_url($post['cat_slug'])) ?>"><?= e($post['cat_name']) ?></a> <span aria-hidden="true">/</span></li><?php endif; ?>
          <li><span aria-current="page"><?= e($post['title']) ?></span></li>
        </ol>
      </nav>
      <?php if (!empty($post['cat_name'])): ?><a class="blog-card__badge" href="<?= e(blog_category_url($post['cat_slug'])) ?>"><?= e($post['cat_name']) ?></a><?php endif; ?>
      <h1 class="post-hero__title"><?= e($post['title']) ?></h1>
      <div class="post-hero__meta">
        <span class="blog-card__author"><span class="avatar"><?= e(initials($post['author_name'] ?? 'GS')) ?></span><?= e($post['author_name'] ?? SITE_NAME) ?></span>
        <span aria-hidden="true">&middot;</span>
        <time datetime="<?= e(formatDate($post['published_at'], 'Y-m-d')) ?>"><?= e(formatDate($post['published_at'], 'F j, Y')) ?></time>
        <span aria-hidden="true">&middot;</span>
        <span><?= $readTime ?> min read</span>
      </div>
      <?php
        $shareUrl   = rawurlencode(url($url));
        $shareText  = rawurlencode($post['title']);
      ?>
      <div class="post-share" data-share data-share-url="<?= e(url($url)) ?>">
        <span class="post-share__label">Share:</span>
        <a class="post-share__btn post-share__btn--icon" href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener nofollow" aria-label="Share on Facebook">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
        </a>
        <a class="post-share__btn post-share__btn--icon" href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareText ?>" target="_blank" rel="noopener nofollow" aria-label="Share on X">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.22-6.82-5.96 6.82H1.68l7.73-8.84L1.25 2.25h6.82l4.71 6.23 5.46-6.23zm-1.16 17.52h1.83L7.02 4.13H5.06l12.02 15.64z"/></svg>
        </a>
        <a class="post-share__btn post-share__btn--icon" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" target="_blank" rel="noopener nofollow" aria-label="Share on LinkedIn">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13zm1.78 13.02H3.55V9h3.57v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.22.79 24 1.77 24h20.45c.98 0 1.78-.78 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/></svg>
        </a>
        <button type="button" class="post-share__btn" data-share-copy aria-label="Copy link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg>
          <span data-share-label>Copy link</span>
        </button>
      </div>
    </div>
  </header>

  <!-- 2. FEATURED IMAGE -->
  <div class="post-featured" data-cat="<?= e(blog_cat_key($post['cat_slug'])) ?>" aria-hidden="true">
    <?php if (!empty($post['featured_image'])): ?><img src="<?= e($post['featured_image']) ?>" alt="" loading="lazy"><?php endif; ?>
  </div>

  <!-- 3. CONTENT -->
  <div class="container container--narrow">
    <div class="post-content">
      <?= $post['content'] ?: ('<p>' . e($post['excerpt'] ?? '') . '</p>') ?>

      <!-- 4. DISCLAIMER -->
      <div class="post-disclaimer">
        <strong>Disclaimer:</strong> This article is for informational purposes only and does not constitute legal advice.
        Laws may change. Consult a qualified California attorney for advice on your specific situation.
      </div>
    </div>

    <!-- 5. AUTHOR BIO -->
    <?php if ($author): ?>
      <div class="author-box">
        <span class="author-box__avatar"><?= e(initials($author['name'])) ?></span>
        <div>
          <p class="author-box__name"><?= e($author['name']) ?></p>
          <p class="author-box__title"><?= e($author['title']) ?></p>
          <p class="author-box__bio"><?= e($author['bio']) ?></p>
          <a class="author-box__link" href="/attorney/<?= e($author['slug']) ?>/">View full profile &rarr;</a>
        </div>
      </div>
    <?php endif; ?>

    <!-- 8. COMMENTS DISABLED → CTA -->
    <div class="post-cta">
      <h3>Have a question about your situation?</h3>
      <p>Our California personal injury attorneys offer free, confidential consultations.</p>
      <div class="post-cta__actions">
        <a class="btn btn--primary" href="/case-evaluation.php" data-ripple>Free Case Evaluation</a>
        <a class="btn btn--ghost" href="/contact.php">Contact Our Attorneys</a>
      </div>
    </div>
  </div>

  <!-- 6. RELATED -->
  <?php if ($related): ?>
  <section class="section section--muted">
    <div class="container container--wide">
      <div class="section-head section-head--center"><p class="eyebrow">Keep Reading</p><h2 class="has-underline">Related Articles</h2></div>
      <div class="blog-grid">
        <?php foreach ($related as $post): require __DIR__ . '/../includes/blog/card.php'; endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
</article>

<?php require __DIR__ . '/../includes/footer.php'; ?>
