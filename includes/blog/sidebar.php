<?php
/** blog/sidebar.php — categories, recent posts, newsletter, CTA. */
$sbCats   = getBlogCategoriesWithCounts();
$sbRecent = getRecentBlogPosts(5);
$sbActive = $sbActive ?? '';
?>
<aside class="blog-sidebar" aria-label="Blog sidebar">

  <div class="sidebar-card">
    <h3 class="sidebar-card__title">Categories</h3>
    <ul class="sidebar-cats" role="list">
      <li><a href="/blog/"<?= $sbActive === '' ? ' class="is-active"' : '' ?>>All Articles</a></li>
      <?php foreach ($sbCats as $c): ?>
        <li>
          <a href="<?= e(blog_category_url($c['slug'])) ?>"<?= $sbActive === $c['slug'] ? ' class="is-active"' : '' ?>>
            <span><?= e($c['name']) ?></span><span class="sidebar-cats__count"><?= (int) $c['post_count'] ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="sidebar-card">
    <h3 class="sidebar-card__title">Recent Posts</h3>
    <ul class="sidebar-recent" role="list">
      <?php foreach ($sbRecent as $r): ?>
        <li>
          <a href="<?= e(blog_post_url($r['slug'])) ?>">
            <span class="sidebar-recent__title"><?= e($r['title']) ?></span>
            <time datetime="<?= e(formatDate($r['published_at'], 'Y-m-d')) ?>"><?= e(formatDate($r['published_at'], 'M j, Y')) ?></time>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="sidebar-card sidebar-card--newsletter">
    <h3 class="sidebar-card__title">Legal Insights Newsletter</h3>
    <p class="text-muted" style="font-size:var(--text-sm);">California injury law updates, no spam.</p>
    <form class="newsletter-form" data-ajax-form action="/api/form-handler.php" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="name" value="Newsletter Subscriber">
      <input type="hidden" name="source" value="newsletter">
      <div class="hp-field" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <div class="field">
        <label for="nl-email" class="visually-hidden">Email address</label>
        <input type="email" id="nl-email" name="email" required placeholder="Your email address">
        <span class="field__error" aria-live="polite"></span>
      </div>
      <button type="submit" class="btn btn--primary btn--block btn--sm">Subscribe</button>
      <div class="form-success" data-form-success hidden>
        <span class="form-success__check" aria-hidden="true"><svg viewBox="0 0 52 52"><circle class="fs-circle" cx="26" cy="26" r="24" fill="none"/><path class="fs-check" fill="none" d="M14 27l8 8 16-16"/></svg></span>
        <p class="form-success__msg" data-form-success-msg>You&rsquo;re subscribed. Thank you!</p>
      </div>
    </form>
  </div>

  <div class="sidebar-card sidebar-cta">
    <h3>Injured in California?</h3>
    <p>Get a free, confidential case evaluation. No fee unless we win.</p>
    <a class="btn btn--primary btn--block" href="/case-evaluation.php">Free Case Evaluation</a>
  </div>
</aside>
