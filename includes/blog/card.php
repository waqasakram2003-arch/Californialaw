<?php
/** blog/card.php — one post card. Expects $post (array). */
$readSrc = $post['content'] ?? ($post['excerpt'] ?? '');
$url = blog_post_url($post['slug']);
?>
<article class="blog-card">
  <a class="blog-card__media" data-cat="<?= e(blog_cat_key($post['cat_slug'] ?? '')) ?>" href="<?= e($url) ?>" tabindex="-1" aria-hidden="true">
    <?php if (!empty($post['featured_image'])): ?>
      <img src="<?= e($post['featured_image']) ?>" alt="" loading="lazy">
    <?php endif; ?>
    <?php if (!empty($post['cat_name'])): ?>
      <span class="blog-card__badge"><?= e($post['cat_name']) ?></span>
    <?php endif; ?>
  </a>
  <div class="blog-card__body">
    <div class="blog-card__meta">
      <time datetime="<?= e(formatDate($post['published_at'], 'Y-m-d')) ?>"><?= e(formatDate($post['published_at'], 'M j, Y')) ?></time>
      <span aria-hidden="true">&middot;</span>
      <span><?= blog_read_time($readSrc) ?> min read</span>
    </div>
    <h3 class="blog-card__title"><a href="<?= e($url) ?>"><?= e($post['title']) ?></a></h3>
    <p class="blog-card__excerpt"><?= e($post['excerpt']) ?></p>
    <div class="blog-card__foot">
      <?php if (!empty($post['author_name'])): ?>
        <span class="blog-card__author"><span class="avatar"><?= e(initials($post['author_name'])) ?></span><?= e($post['author_name']) ?></span>
      <?php else: ?><span></span><?php endif; ?>
      <a class="blog-card__more" href="<?= e($url) ?>">Read More
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</article>
