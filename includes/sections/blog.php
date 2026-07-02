<?php /* Section 10 — Blog preview */
$posts = getRecentPosts(3);
?>
<section class="section blog-preview" aria-label="Latest articles">
  <div class="container container--wide">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">From Our Blog</p>
      <h2 class="has-underline">Legal Tips &amp; Resources</h2>
      <p class="lead">General information to help injured Californians understand their rights. Not legal advice.</p>
    </div>

    <div class="blog-grid stagger-children">
      <?php foreach ($posts as $i => $post): ?>
        <article class="blog-card">
          <a class="blog-card__media blog-card__media--<?= ($i % 3) + 1 ?>" href="/blog/<?= e($post['slug']) ?>/" aria-hidden="true" tabindex="-1">
            <?php if (!empty($post['featured_image'])): ?>
              <img src="<?= e($post['featured_image']) ?>" alt="" loading="lazy">
            <?php endif; ?>
            <span class="blog-card__cat"><?= e($post['category'] ?? 'Article') ?></span>
          </a>
          <div class="blog-card__body">
            <time class="blog-card__date" datetime="<?= e(formatDate($post['published_at'], 'Y-m-d')) ?>"><?= e(formatDate($post['published_at'], 'F j, Y')) ?></time>
            <h3 class="blog-card__title"><a href="/blog/<?= e($post['slug']) ?>/"><?= e($post['title']) ?></a></h3>
            <p class="blog-card__excerpt"><?= e($post['excerpt']) ?></p>
            <a class="blog-card__more" href="/blog/<?= e($post['slug']) ?>/">Read More
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="margin-top:var(--space-8);">
      <a class="btn btn--ghost" href="/blog/">View All Articles</a>
    </div>
  </div>
</section>
