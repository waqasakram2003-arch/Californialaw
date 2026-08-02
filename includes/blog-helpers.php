<?php
/**
 * blog-helpers.php — Blog data access + small presentation helpers.
 * Defensive: returns empty results on DB error.
 */
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

const BLOG_PER_PAGE = 9;

/** Paginated published posts, optionally filtered by category slug or search. */
function getPublishedPostsPaged(int $page, int $perPage = BLOG_PER_PAGE, ?string $categorySlug = null, ?string $search = null, ?int $excludeId = null): array
{
    try {
        $where  = ["p.status = 'published'", 'p.published_at IS NOT NULL', 'p.published_at <= NOW()'];
        $params = [];
        if ($categorySlug) { $where[] = 'c.slug = :cat'; $params[':cat'] = $categorySlug; }
        if ($search) {
            // Real prepared statements can't reuse a named placeholder — use distinct ones.
            $like = '%' . $search . '%';
            $where[] = '(p.title LIKE :q1 OR p.excerpt LIKE :q2 OR p.content LIKE :q3)';
            $params[':q1'] = $like; $params[':q2'] = $like; $params[':q3'] = $like;
        }
        if ($excludeId)    { $where[] = 'p.id <> :ex'; $params[':ex'] = $excludeId; }
        $wsql = implode(' AND ', $where);

        $countStmt = db()->prepare("SELECT COUNT(*) FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id WHERE $wsql");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $page    = max(1, $page);
        $offset  = ($page - 1) * $perPage;
        $sql = "SELECT p.id, p.title, p.slug, p.excerpt, p.content, p.featured_image, p.published_at, p.views,
                       p.author_name, p.author_slug, c.name AS cat_name, c.slug AS cat_slug
                FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id
                WHERE $wsql ORDER BY p.published_at DESC
                LIMIT " . (int) $perPage . " OFFSET " . (int) $offset;
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return ['posts' => $stmt->fetchAll(), 'total' => $total];
    } catch (Throwable $e) {
        return ['posts' => [], 'total' => 0];
    }
}

/** Single post by slug. $preview (admins) ignores status + future dates. */
function getBlogPostBySlug(string $slug, bool $preview = false): ?array
{
    try {
        $cond = $preview ? '' : " AND p.status = 'published' AND p.published_at <= NOW()";
        $stmt = db()->prepare(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.slug = :slug" . $cond . " LIMIT 1"
        );
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/** Increment a post's view counter (best-effort). */
function bumpPostViews(int $id): void
{
    try { db()->prepare('UPDATE blog_posts SET views = views + 1 WHERE id = ?')->execute([$id]); }
    catch (Throwable $e) { /* ignore */ }
}

/**
 * Related posts: same category first, then filled with recent posts from OTHER
 * categories (cross-category) up to $limit. Improves internal linking. (SEO item 9)
 */
function getRelatedBlogPosts(?int $categoryId, int $excludeId, int $limit = 6): array
{
    $sel = "SELECT p.title, p.slug, p.excerpt, p.featured_image, p.published_at, p.author_name, c.name AS cat_name, c.slug AS cat_slug
            FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id
            WHERE p.status='published' AND p.published_at <= NOW() AND p.id <> :ex";
    try {
        $rows = [];
        $seen = [];
        // 1) same category
        if ($categoryId) {
            $stmt = db()->prepare($sel . " AND p.category_id = :cat ORDER BY p.published_at DESC LIMIT " . (int) $limit);
            $stmt->execute([':ex' => $excludeId, ':cat' => $categoryId]);
            foreach ($stmt->fetchAll() as $r) { $rows[] = $r; $seen[$r['slug']] = true; }
        }
        // 2) fill from other categories, most recent
        if (count($rows) < $limit) {
            $stmt = db()->prepare($sel . " ORDER BY p.published_at DESC LIMIT " . (int) ($limit * 3));
            $stmt->execute([':ex' => $excludeId]);
            foreach ($stmt->fetchAll() as $r) {
                if (count($rows) >= $limit) { break; }
                if (isset($seen[$r['slug']])) { continue; }
                $rows[] = $r; $seen[$r['slug']] = true;
            }
        }
        return array_slice($rows, 0, $limit);
    } catch (Throwable $e) {
        return [];
    }
}

/** Categories with published-post counts. */
function getBlogCategoriesWithCounts(): array
{
    try {
        return db()->query(
            "SELECT c.name, c.slug, c.description, COUNT(p.id) AS post_count
             FROM blog_categories c
             LEFT JOIN blog_posts p ON p.category_id = c.id AND p.status='published'
             GROUP BY c.id ORDER BY c.name"
        )->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/** A category row by slug. */
function getBlogCategoryBySlug(string $slug): ?array
{
    try {
        $stmt = db()->prepare('SELECT name, slug, description FROM blog_categories WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/** Recent posts (sidebar). */
function getRecentBlogPosts(int $limit = 5): array
{
    try {
        $stmt = db()->prepare(
            "SELECT title, slug, published_at FROM blog_posts
             WHERE status='published' AND published_at <= NOW() ORDER BY published_at DESC LIMIT " . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/** Live search (titles/excerpts). */
function searchBlogPosts(string $q, int $limit = 8): array
{
    $q = trim($q);
    if (mb_strlen($q) < 2) { return []; }
    try {
        $stmt = db()->prepare(
            "SELECT p.title, p.slug, p.excerpt, c.name AS cat_name
             FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status='published' AND p.published_at <= NOW() AND (p.title LIKE :q1 OR p.excerpt LIKE :q2)
             ORDER BY p.published_at DESC LIMIT " . (int) $limit
        );
        $like = '%' . $q . '%';
        $stmt->execute([':q1' => $like, ':q2' => $like]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/* ---- presentation helpers ---- */

/**
 * Process post body HTML once: (1) inject id="" on every H2/H3 and collect a
 * Table of Contents; (2) make external links open in a new tab with
 * rel="noopener" (NEVER nofollow — internal + external links stay followed).
 * Returns ['html' => processed, 'toc' => [['level'=>2|3,'id'=>,'text'=>], ...]].
 * SEO items 7 (TOC) + 10 (in-body links).
 */
function blog_process_content(string $html): array
{
    $toc = [];
    if (trim($html) === '') { return ['html' => $html, 'toc' => $toc]; }
    if (!class_exists('DOMDocument')) { return ['html' => $html, 'toc' => $toc]; }

    $host = parse_url(BASE_URL, PHP_URL_HOST) ?: '';
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8"?><div id="__wrap">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    foreach (iterator_to_array($doc->childNodes) as $node) {
        if ($node->nodeType === XML_PI_NODE) { $doc->removeChild($node); }
    }
    $xp   = new DOMXPath($doc);
    $wrap = $xp->query('//div[@id="__wrap"]')->item(0);
    if (!$wrap) { return ['html' => $html, 'toc' => $toc]; }

    // Headings → ids + TOC
    $used = [];
    foreach ($xp->query('.//h2 | .//h3', $wrap) as $h) {
        $text = trim($h->textContent);
        if ($text === '') { continue; }
        $id = $h->getAttribute('id');
        if ($id === '') {
            $base = generateSlug($text); $id = $base; $n = 2;
            while (isset($used[$id])) { $id = $base . '-' . $n; $n++; }
            $h->setAttribute('id', $id);
        }
        $used[$id] = true;
        $toc[] = ['level' => strtolower($h->nodeName) === 'h3' ? 3 : 2, 'id' => $id, 'text' => $text];
    }

    // External links → target/rel (no nofollow)
    foreach ($xp->query('.//a[@href]', $wrap) as $a) {
        $href = $a->getAttribute('href');
        if (!preg_match('#^https?://#i', $href)) { continue; }
        $lhost = parse_url($href, PHP_URL_HOST) ?: '';
        if ($lhost === '' || ($host !== '' && stripos($lhost, $host) !== false)) { continue; } // internal
        $a->setAttribute('target', '_blank');
        $rel = array_filter(array_unique(array_merge(
            preg_split('/\s+/', trim($a->getAttribute('rel'))) ?: [],
            ['noopener']
        )));
        $a->setAttribute('rel', implode(' ', $rel));
    }

    $out = '';
    foreach ($wrap->childNodes as $c) { $out .= $doc->saveHTML($c); }
    return ['html' => $out, 'toc' => $toc];
}

/** Estimated read time in minutes from HTML content. */
function blog_read_time(string $content): int
{
    $words = str_word_count(strip_tags($content));
    return max(1, (int) ceil($words / 200));
}

/** Build a blog post URL (clean). */
function blog_post_url(string $slug): string { return '/blog/' . $slug . '/'; }
function blog_category_url(string $slug): string { return '/blog/category/' . $slug . '/'; }

/** Short category key for CSS color-coding (data-cat attribute). */
function blog_cat_key(?string $slug): string { return $slug ?: 'default'; }
