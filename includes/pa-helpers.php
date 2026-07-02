<?php
/**
 * pa-helpers.php — Practice Areas data + shared content.
 * Per-area data lives in the practice_areas table (incl. details JSON);
 * content that is genuinely common to all California PI cases (the generic
 * "what to do" steps, the California-law accordion, and recoverable damages)
 * is defined here once, accurately, and reused by every area page.
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/repo.php';

/* ===========================================================================
   QUERIES
   =========================================================================== */

/** Fetch a single active practice area by slug, with details decoded. */
function getPracticeAreaBySlug(string $slug): ?array
{
    try {
        $stmt = db()->prepare(
            'SELECT title, slug, icon, image, short_desc, full_content, details, meta_title, meta_desc
             FROM practice_areas WHERE slug = :slug AND active = 1 LIMIT 1'
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

/** Fetch related areas by an ordered list of slugs. */
function getRelatedAreas(array $slugs): array
{
    $slugs = array_values(array_filter($slugs));
    if (!$slugs) {
        return [];
    }
    try {
        $in = implode(',', array_fill(0, count($slugs), '?'));
        $stmt = db()->prepare(
            "SELECT title, slug, icon, image, short_desc FROM practice_areas
             WHERE active = 1 AND slug IN ($in)"
        );
        $stmt->execute($slugs);
        $rows = $stmt->fetchAll();
        // Preserve the requested order.
        $bySlug = [];
        foreach ($rows as $r) {
            $bySlug[$r['slug']] = $r;
        }
        $ordered = [];
        foreach ($slugs as $s) {
            if (isset($bySlug[$s])) {
                $ordered[] = $bySlug[$s];
            }
        }
        return $ordered;
    } catch (Throwable $e) {
        return [];
    }
}

/** Case results whose case_type matches an area keyword (e.g. "Car Accident"). */
function getCaseResultsForArea(string $keyword): array
{
    if ($keyword === '') {
        return [];
    }
    try {
        $stmt = db()->prepare(
            'SELECT case_type, result_amount, description, disclaimer FROM case_results
             WHERE display = 1 AND case_type LIKE :kw ORDER BY order_num, id'
        );
        $stmt->execute([':kw' => $keyword . '%']);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/* ===========================================================================
   CATEGORY LABELS (for the index filter)
   =========================================================================== */
function pa_categories(): array
{
    return [
        'all'          => 'All Practice Areas',
        'motor-vehicle'=> 'Motor Vehicle',
        'premises'     => 'Premises & Animal',
        'catastrophic' => 'Catastrophic & Wrongful Death',
    ];
}
function pa_category_label(string $key): string
{
    $c = pa_categories();
    return $c[$key] ?? 'Practice Area';
}

/* ===========================================================================
   COMMON CONTENT (accurate for California personal injury cases)
   =========================================================================== */

/** Singular/adjective form of a plural area title for headings (e.g. "Car
 *  Accidents" -> "Car Accident", "Workplace Injuries" -> "Workplace Injury"). */
function pa_singular(string $title): string
{
    if (preg_match('/ies$/', $title)) {
        return preg_replace('/ies$/', 'y', $title);
    }
    if (preg_match('/(?<!s)s$/', $title)) {
        return substr($title, 0, -1);
    }
    return $title;
}

/** "What to do" steps. {incident} is replaced with the area title (lowercased). */
function pa_steps(string $incident): array
{
    $incident = strtolower($incident);
    return [
        ['n' => '01', 'title' => 'Get Medical Attention',
         'body' => 'Your health comes first. See a doctor promptly even if you feel fine — some injuries are not obvious right away, and prompt records also help document your claim.'],
        ['n' => '02', 'title' => 'Report the Incident',
         'body' => 'Notify the appropriate party — call law enforcement, or tell the property owner, manager, or employer — and make sure a written report is created.'],
        ['n' => '03', 'title' => 'Document Everything',
         'body' => 'If you can, photograph the scene, your injuries, and anything that contributed to the ' . $incident . '. Collect names and contact details for any witnesses.'],
        ['n' => '04', 'title' => 'Keep Your Records',
         'body' => 'Save medical bills, proof of lost wages, and out-of-pocket expenses. Keep a simple journal of how your injuries affect your daily life.'],
        ['n' => '05', 'title' => 'Talk to an Attorney First',
         'body' => 'Before giving a recorded statement or accepting any offer from an insurance company, speak with an attorney. The consultation is free.'],
    ];
}

/** California laws accordion — common to PI cases, accurate citations. */
function pa_laws(array $extra = []): array
{
    $base = [
        ['q' => 'How long do I have to file a claim? (Statute of Limitations)',
         'a' => 'In California, the statute of limitations for most personal injury claims is generally <strong>two years</strong> from the date of injury under Code of Civil Procedure section 335.1. If a government entity is involved, you may have as little as <strong>six months</strong> to file a claim under the California Government Claims Act. Because deadlines vary, it is best to speak with an attorney promptly.'],
        ['q' => 'What if I was partly at fault? (Pure Comparative Negligence)',
         'a' => 'California follows a <strong>pure comparative negligence</strong> rule, established in <em>Li v. Yellow Cab Co.</em> (1975). You may still recover compensation even if you were partially at fault — your recovery is simply reduced by your percentage of responsibility.'],
        ['q' => 'What duty did the other party owe me? (Duty of Care)',
         'a' => 'Under California Civil Code section 1714, everyone is generally responsible for harm caused by their failure to use reasonable care. Establishing that another party breached this duty is a key part of most injury claims.'],
        ['q' => 'What damages does California law allow? (Civil Code 3333)',
         'a' => 'California Civil Code section 3333 sets the measure of damages for most non-contract claims — the amount that will compensate for all the detriment caused, whether or not it could have been anticipated. This can include economic and non-economic losses. Compensation amounts vary based on the specific facts of your case.'],
    ];
    return array_merge($base, $extra);
}

/** Recoverable damages — two columns. */
function pa_damages(): array
{
    return [
        'Economic Damages' => [
            'Medical expenses (past and future)',
            'Lost wages and lost earning capacity',
            'Rehabilitation and therapy costs',
            'Out-of-pocket and travel expenses',
            'Property damage',
            'In-home assistance or future care',
        ],
        'Non-Economic Damages' => [
            'Pain and suffering',
            'Emotional distress',
            'Loss of enjoyment of life',
            'Disfigurement and scarring',
            'Loss of consortium or companionship',
            'Inconvenience and disruption',
        ],
    ];
}

/** A small rotating set of "cause" icons for the causes grid. */
function pa_cause_icon(int $i): string
{
    $set = [
        '<path d="M12 9v4M12 17h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/>', // alert triangle
        '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',                                                    // clock
        '<circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/>',                                              // no / x-circle
        '<path d="M3 12h4l3 8 4-16 3 8h4"/>',                                                                         // pulse
        '<path d="M12 2 4 7v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V7z"/>',                                                 // shield
        '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>', // hazard sun
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . $set[$i % count($set)] . '</svg>';
}
