<?php
/**
 * repo.php — read-side data access for public pages.
 * Every function is defensive: on any DB error it returns a sensible fallback
 * so pages always render. Output is still escaped with e() at the template.
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

/* ===========================================================================
   SETTINGS
   =========================================================================== */
function getSettings(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    try {
        foreach (db()->query('SELECT `key`, `value` FROM settings') as $row) {
            $cache[$row['key']] = $row['value'];
        }
    } catch (Throwable $e) {
        $cache = [];
    }
    return $cache;
}

function setting(string $key, ?string $default = null): ?string
{
    $s = getSettings();
    return $s[$key] ?? $default;
}

/* ===========================================================================
   PRACTICE AREAS
   =========================================================================== */
function getPracticeAreas(?int $limit = null): array
{
    try {
        $sql = 'SELECT title, slug, icon, image, short_desc FROM practice_areas
                WHERE active = 1 ORDER BY order_num, id';
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        $rows = db()->query($sql)->fetchAll();
        if ($rows) {
            return $rows;
        }
    } catch (Throwable $e) {
        // fall through to fallback
    }
    $fallback = [
        ['title' => 'Car Accidents', 'slug' => 'car-accidents', 'icon' => 'car', 'short_desc' => 'Help for drivers and passengers injured in California auto collisions.'],
        ['title' => 'Truck Accidents', 'slug' => 'truck-accidents', 'icon' => 'truck', 'short_desc' => 'Complex commercial trucking cases involving serious injuries.'],
        ['title' => 'Motorcycle Accidents', 'slug' => 'motorcycle-accidents', 'icon' => 'motorcycle', 'short_desc' => 'Protecting California riders against insurers and unfair bias.'],
        ['title' => 'Slip & Fall', 'slug' => 'slip-and-fall', 'icon' => 'slip', 'short_desc' => 'Premises liability claims when negligent owners cause harm.'],
        ['title' => 'Workplace Injuries', 'slug' => 'workplace-injuries', 'icon' => 'workplace', 'short_desc' => 'On-the-job injury and third-party claims for California workers.'],
        ['title' => 'Wrongful Death', 'slug' => 'wrongful-death', 'icon' => 'wrongful-death', 'short_desc' => 'Compassionate representation for grieving families.'],
        ['title' => 'Dog Bites', 'slug' => 'dog-bites', 'icon' => 'dog', 'short_desc' => 'California strict-liability dog bite and animal attack claims.'],
        ['title' => 'Pedestrian Accidents', 'slug' => 'pedestrian-accidents', 'icon' => 'pedestrian', 'short_desc' => 'Advocating for pedestrians struck in crosswalks and streets.'],
        ['title' => 'Rideshare Accidents', 'slug' => 'rideshare-accidents', 'icon' => 'rideshare', 'short_desc' => 'Uber and Lyft collision claims and insurance coverage.'],
        ['title' => 'Traumatic Brain Injuries', 'slug' => 'traumatic-brain-injuries', 'icon' => 'brain', 'short_desc' => 'Serious TBI cases requiring lifelong care and advocacy.'],
    ];
    return $limit !== null ? array_slice($fallback, 0, $limit) : $fallback;
}

/* ===========================================================================
   CASE RESULTS
   =========================================================================== */
function getCaseResults(?int $limit = null): array
{
    try {
        $sql = 'SELECT case_type, result_amount, description, disclaimer FROM case_results
                WHERE display = 1 ORDER BY order_num, id';
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        $rows = db()->query($sql)->fetchAll();
        if ($rows) {
            return $rows;
        }
    } catch (Throwable $e) {
    }
    return [
        ['case_type' => 'Truck Accident', 'result_amount' => '$3.2M', 'description' => 'Recovery after a commercial truck collision on Interstate 5.', 'disclaimer' => 'Past results do not guarantee future outcomes.'],
        ['case_type' => 'Car Accident', 'result_amount' => '$2.1M', 'description' => 'Settlement for a client with spinal injuries from a rear-end collision.', 'disclaimer' => 'Past results do not guarantee future outcomes.'],
        ['case_type' => 'Traumatic Brain Injury', 'result_amount' => '$1.8M', 'description' => 'Resolution for a pedestrian who suffered a serious head injury.', 'disclaimer' => 'Past results do not guarantee future outcomes.'],
        ['case_type' => 'Slip & Fall', 'result_amount' => '$850K', 'description' => 'Premises liability recovery after an unsafe condition at a store.', 'disclaimer' => 'Past results do not guarantee future outcomes.'],
        ['case_type' => 'Motorcycle Accident', 'result_amount' => '$1.2M', 'description' => 'Settlement for a rider injured by a distracted driver.', 'disclaimer' => 'Past results do not guarantee future outcomes.'],
        ['case_type' => 'Wrongful Death', 'result_amount' => '$2.7M', 'description' => 'Recovery for a family following a fatal multi-vehicle crash.', 'disclaimer' => 'Past results do not guarantee future outcomes.'],
    ];
}

/* ===========================================================================
   TESTIMONIALS
   =========================================================================== */
function getTestimonials(?int $limit = null): array
{
    try {
        $sql = 'SELECT client_name, case_type, rating, testimonial FROM testimonials
                WHERE active = 1 ORDER BY id';
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        $rows = db()->query($sql)->fetchAll();
        if ($rows) {
            return $rows;
        }
    } catch (Throwable $e) {
    }
    return [
        ['client_name' => 'María G.', 'case_type' => 'Car Accident', 'rating' => 5, 'testimonial' => 'They guided me through every step after my accident and always explained my options. I never felt like just another case.'],
        ['client_name' => 'James T.', 'case_type' => 'Truck Accident', 'rating' => 5, 'testimonial' => 'Professional, responsive, and genuinely caring. They handled the insurance companies so I could focus on healing.'],
        ['client_name' => 'Aisha R.', 'case_type' => 'Slip & Fall', 'rating' => 5, 'testimonial' => 'From my first free consultation I felt heard and respected. They kept me informed throughout the process.'],
    ];
}

/* ===========================================================================
   ATTORNEYS
   =========================================================================== */
function getAttorneys(?int $limit = null): array
{
    try {
        $sql = 'SELECT name, title, bio, image, bar_number, slug FROM attorneys
                WHERE active = 1 ORDER BY order_num, id';
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        $rows = db()->query($sql)->fetchAll();
        if ($rows) {
            return $rows;
        }
    } catch (Throwable $e) {
    }
    return [
        ['name' => 'Elena Marquez', 'title' => 'Founding Partner', 'bio' => 'Catastrophic injury and wrongful death trial practice.', 'image' => null, 'bar_number' => 'SBN 245118', 'slug' => 'elena-marquez'],
        ['name' => 'Daniel Cho', 'title' => 'Senior Trial Attorney', 'bio' => 'Serious motor vehicle and trucking cases.', 'image' => null, 'bar_number' => 'SBN 268904', 'slug' => 'daniel-cho'],
        ['name' => 'Priya Nair', 'title' => 'Associate Attorney', 'bio' => 'Premises liability and rideshare injury claims.', 'image' => null, 'bar_number' => 'SBN 312557', 'slug' => 'priya-nair'],
        ['name' => 'Marcus Bell', 'title' => 'Associate Attorney', 'bio' => 'Motorcycle and pedestrian injury matters.', 'image' => null, 'bar_number' => 'SBN 329471', 'slug' => 'marcus-bell'],
    ];
}

/* ===========================================================================
   BLOG
   =========================================================================== */
function getRecentPosts(int $limit = 3): array
{
    try {
        $stmt = db()->prepare(
            'SELECT p.title, p.slug, p.excerpt, p.featured_image, p.published_at,
                    c.name AS category, c.slug AS category_slug
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status = "published" AND p.published_at IS NOT NULL
             ORDER BY p.published_at DESC
             LIMIT ' . (int) $limit
        );
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if ($rows) {
            return $rows;
        }
    } catch (Throwable $e) {
    }
    return [
        ['title' => 'What to Do After a Car Accident in California', 'slug' => 'what-to-do-after-a-car-accident-in-california', 'excerpt' => 'A step-by-step guide to protecting your health and your rights after a California collision.', 'featured_image' => null, 'published_at' => '2026-06-10 09:00:00', 'category' => 'Auto Accidents', 'category_slug' => 'auto-accidents'],
        ['title' => 'Understanding California\'s Statute of Limitations', 'slug' => 'california-statute-of-limitations-injury-claims', 'excerpt' => 'California law may limit how long you have to file a personal injury claim.', 'featured_image' => null, 'published_at' => '2026-06-03 09:00:00', 'category' => 'Legal Tips', 'category_slug' => 'legal-tips'],
        ['title' => 'How Comparative Fault Works in California', 'slug' => 'how-comparative-fault-works-in-california', 'excerpt' => 'California follows a comparative fault rule. Learn how shared responsibility affects a claim.', 'featured_image' => null, 'published_at' => '2026-05-27 09:00:00', 'category' => 'Your Rights', 'category_slug' => 'your-rights'],
    ];
}

/* ===========================================================================
   PRACTICE-AREA ICONS — inline SVG (stroke: currentColor). 24x24 line art.
   =========================================================================== */
function practice_icon(string $key): string
{
    $icons = [
        'car' => '<path d="M5 13l1.5-4.5A2 2 0 0 1 8.4 7h7.2a2 2 0 0 1 1.9 1.5L19 13M5 13h14M5 13v4m14-4v4M7 17h2m6 0h2"/><circle cx="7.5" cy="17" r="1.4"/><circle cx="16.5" cy="17" r="1.4"/>',
        'truck' => '<path d="M3 6h11v9H3zM14 9h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/>',
        'motorcycle' => '<circle cx="6" cy="16" r="3"/><circle cx="18" cy="16" r="3"/><path d="M6 16l4-5h4l2 5M10 11l-1-3h3"/>',
        'slip' => '<path d="M4 20h16M7 20l3-9 4 2-2 4M10 11l5-3M13 6a1.5 1.5 0 1 0 0-.01"/>',
        'workplace' => '<path d="M8 8V6a4 4 0 0 1 8 0v2M5 8h14l-1 12H6z"/>',
        'wrongful-death' => '<path d="M12 21s-7-4.5-7-10a4 4 0 0 1 7-2.5A4 4 0 0 1 19 11c0 5.5-7 10-7 10z"/>',
        'dog' => '<path d="M10 5l-3 2v3l-2 1v6h5v-3h4v3h5v-7l-2-1V7l-3-2-2 2h-2z"/>',
        'pedestrian' => '<circle cx="12" cy="5" r="2"/><path d="M12 8v6m0-6l-3 2m3-2l3 2M9 20l3-6 3 6"/>',
        'rideshare' => '<rect x="4" y="9" width="16" height="7" rx="2"/><path d="M8 9V7h8v2M9 19v-3m6 3v-3"/><circle cx="8" cy="13" r="0.8"/><circle cx="16" cy="13" r="0.8"/>',
        'brain' => '<path d="M9 7a3 3 0 0 0-3 3 2.5 2.5 0 0 0-1 4.5A3 3 0 0 0 9 18V7zM15 7a3 3 0 0 1 3 3 2.5 2.5 0 0 1 1 4.5A3 3 0 0 1 15 18V7zM12 6v12"/>',
    ];
    $inner = $icons[$key] ?? '<circle cx="12" cy="12" r="8"/>';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
}
