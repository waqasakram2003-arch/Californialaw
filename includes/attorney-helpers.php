<?php
/**
 * attorney-helpers.php — Attorney data access for the profile + listing pages.
 * Defensive: returns sensible fallbacks if the DB is unavailable.
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/repo.php';

/** All active attorneys with details decoded (for the listing). */
function getAttorneysWithDetails(): array
{
    try {
        $rows = db()->query(
            'SELECT id, name, title, bio, details, image, bar_number, slug
             FROM attorneys WHERE active = 1 ORDER BY order_num, id'
        )->fetchAll();
        foreach ($rows as &$r) {
            $r['details'] = $r['details'] ? (json_decode($r['details'], true) ?: []) : [];
        }
        return $rows;
    } catch (Throwable $e) {
        return getAttorneys(); // basic fallback (no details)
    }
}

/** A single attorney by slug, details decoded. */
function getAttorneyBySlug(string $slug): ?array
{
    try {
        $stmt = db()->prepare(
            'SELECT id, name, title, bio, details, image, email, bar_number, slug
             FROM attorneys WHERE slug = :slug AND active = 1 LIMIT 1'
        );
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $row['details'] = $row['details'] ? (json_decode($row['details'], true) ?: []) : [];
        return $row;
    } catch (Throwable $e) {
        return null;
    }
}

/** Other active attorneys (excluding the given id). */
function getOtherAttorneys(int $excludeId, int $limit = 6): array
{
    try {
        $stmt = db()->prepare(
            'SELECT name, title, bio, image, bar_number, slug FROM attorneys
             WHERE active = 1 AND id <> :id ORDER BY order_num, id LIMIT ' . (int) $limit
        );
        $stmt->execute([':id' => $excludeId]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/** Testimonials linked to an attorney. */
function getTestimonialsForAttorney(int $attorneyId): array
{
    try {
        $stmt = db()->prepare(
            'SELECT client_name, case_type, rating, testimonial FROM testimonials
             WHERE active = 1 AND attorney_id = :id ORDER BY id'
        );
        $stmt->execute([':id' => $attorneyId]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Notable / representative matters for an attorney.
 * Returns case_type + description only (no dollar amounts) for the profile,
 * which is the conservative, compliant presentation for an individual attorney.
 */
function getNotableCasesForAttorney(int $attorneyId): array
{
    try {
        $stmt = db()->prepare(
            'SELECT case_type, description FROM case_results
             WHERE display = 1 AND attorney_id = :id ORDER BY order_num, id'
        );
        $stmt->execute([':id' => $attorneyId]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}
