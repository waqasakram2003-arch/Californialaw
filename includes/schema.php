<?php
/**
 * schema.php — JSON-LD structured data helpers.
 * Each function returns a complete <script type="application/ld+json"> string.
 * Pages add page-specific schema via $page['schema'][] before including header.
 */
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

/** Wrap a data array as a JSON-LD <script>. */
function schema_script(array $data): string
{
    return '<script type="application/ld+json">'
        . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . '</script>';
}

/** 1. Sitewide: LegalService + LocalBusiness. */
function schemaSitewide(): string
{
    return schema_script([
        '@context'    => 'https://schema.org',
        '@type'       => ['LegalService', 'LocalBusiness'],
        '@id'         => BASE_URL . '/#firm',
        'name'        => cfg('firm_name', SITE_NAME),
        'description' => 'California personal injury law firm representing injured Californians.',
        'url'         => BASE_URL,
        'telephone'   => cfg('site_phone', SITE_PHONE),
        'email'       => cfg('site_email', SITE_EMAIL),
        'image'       => url('/assets/images/og-default.jpg'),
        'logo'        => url('/assets/images/icon-512.png'),
        'priceRange'  => 'Free Consultation',
        'serviceType' => 'Personal Injury Law',
        'areaServed'  => array_merge(
            array_map(
                static fn ($n) => ['@type' => 'City', 'name' => $n],
                ['Folsom', 'El Dorado Hills', 'Roseville', 'Rocklin', 'Granite Bay', 'Fair Oaks',
                 'Orangevale', 'Citrus Heights', 'Rancho Cordova', 'Elk Grove', 'Sacramento',
                 'Placerville', 'Cameron Park']
            ),
            [
                ['@type' => 'AdministrativeArea', 'name' => 'Sacramento County'],
                ['@type' => 'AdministrativeArea', 'name' => 'Placer County'],
                ['@type' => 'AdministrativeArea', 'name' => 'El Dorado County'],
            ]
        ),
        'geo'         => ['@type' => 'GeoCoordinates', 'latitude' => '38.6779', 'longitude' => '-121.1761'],
        'openingHoursSpecification' => [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens'     => '09:00',
            'closes'    => '17:00',
        ],
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => cfg('site_address', SITE_ADDRESS),
            'addressLocality' => 'Folsom',
            'addressRegion'   => 'CA',
            'postalCode'      => '95630',
            'addressCountry'  => 'US',
        ],
        'sameAs'      => array_values(array_filter([
            cfg('social_facebook') !== '#' ? cfg('social_facebook') : null,
            cfg('social_x') !== '#' ? cfg('social_x') : null,
            cfg('social_linkedin') !== '#' ? cfg('social_linkedin') : null,
            cfg('social_instagram') !== '#' ? cfg('social_instagram') : null,
        ])),
    ]);
}

/** 2. Breadcrumbs from [['name'=>, 'path'=>], ...]. */
function schemaBreadcrumb(array $items): string
{
    if (!$items) { return ''; }
    $list = [];
    foreach ($items as $i => $it) {
        $list[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $it['name'],
            'item'     => url($it['path'] ?? '/'),
        ];
    }
    return schema_script(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list]);
}

/** 3. Attorney: Person. $attorney from DB (+ optional decoded details). */
function schemaAttorney(array $attorney): string
{
    $d = is_array($attorney['details'] ?? null) ? $attorney['details']
        : (json_decode((string) ($attorney['details'] ?? ''), true) ?: []);
    $person = [
        '@context'   => 'https://schema.org',
        '@type'      => 'Person',
        'name'       => $attorney['name'],
        'jobTitle'   => $attorney['title'] ?? 'Attorney',
        'url'        => url('/attorney/' . ($attorney['slug'] ?? '') . '/'),
        'worksFor'   => ['@type' => 'LegalService', 'name' => cfg('firm_name', SITE_NAME)],
        'knowsLanguage' => $d['languages'] ?? ['English'],
    ];
    if (!empty($attorney['email'])) { $person['email'] = $attorney['email']; }
    // alumniOf from the most recent education entry's school.
    if (!empty($d['education'][0]['school'])) {
        $person['alumniOf'] = ['@type' => 'EducationalOrganization', 'name' => $d['education'][0]['school']];
    }
    // memberOf — admitted to the State Bar of California.
    $person['memberOf'] = ['@type' => 'Organization', 'name' => 'State Bar of California'];
    return schema_script($person);
}

/** 4. FAQ: FAQPage from [['question'=>,'answer'=>], ...]. */
function schemaFAQ(array $faqs): string
{
    if (!$faqs) { return ''; }
    $main = [];
    foreach ($faqs as $f) {
        $main[] = [
            '@type' => 'Question',
            'name'  => $f['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f['answer']))],
        ];
    }
    return schema_script(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $main]);
}

/** 5. Article / BlogPosting. */
function schemaArticle(array $post): string
{
    $a = [
        '@context'      => 'https://schema.org',
        '@type'         => 'BlogPosting',
        'headline'      => $post['title'],
        'description'   => $post['meta_desc'] ?: mb_substr(strip_tags($post['excerpt'] ?? ''), 0, 160),
        'datePublished' => formatDate($post['published_at'], 'c'),
        'dateModified'  => formatDate($post['updated_at'] ?? $post['published_at'], 'c'),
        'url'           => url('/blog/' . $post['slug'] . '/'),
        'mainEntityOfPage' => url('/blog/' . $post['slug'] . '/'),
        'articleSection'=> $post['cat_name'] ?? '',
        'author'        => ['@type' => 'Person', 'name' => $post['author_name'] ?: cfg('firm_name', SITE_NAME)]
                           + (!empty($post['author_slug']) ? ['url' => url('/attorney/' . $post['author_slug'] . '/')] : []),
        'publisher'     => [
            '@type' => 'Organization',
            'name'  => cfg('firm_name', SITE_NAME),
            'logo'  => ['@type' => 'ImageObject', 'url' => url('/assets/images/icon-512.png')],
        ],
    ];
    if (!empty($post['featured_image'])) { $a['image'] = url($post['featured_image']); }
    return schema_script($a);
}

/**
 * 5b. Blog post as a single @graph: BlogPosting + author/reviewer Person(s)
 *     + BreadcrumbList + (optional) FAQPage, all cross-referenced by @id and
 *     pointing publisher at the sitewide LegalService (#firm). Preferred over
 *     emitting several separate <script> blocks.
 *
 * @param array $post       blog row (+ cat_name/cat_slug)
 * @param ?array $author    attorney row (+ decoded/raw details) or null
 * @param array $faqs       [['question'=>,'answer'=>], ...]
 * @param array $crumbs     [['name'=>,'path'=>], ...]
 */
function schemaBlogGraph(array $post, ?array $author, array $faqs, array $crumbs): string
{
    $postUrl = url('/blog/' . $post['slug'] . '/');
    $graph   = [];

    // Author Person (with sameAs from the attorney's linked profiles).
    $authorRef = ['@type' => 'Person', 'name' => $post['author_name'] ?: cfg('firm_name', SITE_NAME)];
    if ($author) {
        $ad = is_array($author['details'] ?? null) ? $author['details']
            : (json_decode((string) ($author['details'] ?? ''), true) ?: []);
        $authorId = url('/attorney/' . $author['slug'] . '/') . '#person';
        $sameAs = array_values(array_filter([
            $ad['links']['linkedin'] ?? null,
            $ad['links']['avvo'] ?? null,
            $ad['links']['statebar'] ?? null,
        ]));
        $personNode = [
            '@type'    => 'Person',
            '@id'      => $authorId,
            'name'     => $author['name'],
            'jobTitle' => $author['title'] ?? 'Attorney',
            'url'      => url('/attorney/' . $author['slug'] . '/'),
            'worksFor' => ['@id' => BASE_URL . '/#firm'],
        ];
        if ($sameAs) { $personNode['sameAs'] = $sameAs; }
        $graph[] = $personNode;
        $authorRef = ['@id' => $authorId];
    }

    // BlogPosting.
    $img = $post['og_image'] ?: $post['featured_image'];
    $blog = [
        '@type'            => 'BlogPosting',
        '@id'              => $postUrl . '#article',
        'headline'         => $post['title'],
        'description'      => $post['meta_desc'] ?: mb_substr(strip_tags($post['excerpt'] ?? ''), 0, 160),
        'datePublished'    => formatDate($post['published_at'], 'c'),
        'dateModified'     => formatDate($post['date_modified'] ?: ($post['updated_at'] ?? $post['published_at']), 'c'),
        'url'              => $postUrl,
        'mainEntityOfPage' => ['@id' => $postUrl],
        'articleSection'   => $post['cat_name'] ?? '',
        'author'           => $authorRef,
        'publisher'        => ['@id' => BASE_URL . '/#firm'],
    ];
    if ($img) { $blog['image'] = url($img); }
    if ($author) { $blog['reviewedBy'] = ['@id' => url('/attorney/' . $author['slug'] . '/') . '#person']; }
    $graph[] = $blog;

    // BreadcrumbList.
    if ($crumbs) {
        $items = [];
        foreach ($crumbs as $i => $c) {
            $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => url($c['path'] ?? '/')];
        }
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => $postUrl . '#breadcrumb', 'itemListElement' => $items];
    }

    // FAQPage.
    if ($faqs) {
        $main = [];
        foreach ($faqs as $f) {
            if (empty($f['question'])) { continue; }
            $main[] = ['@type' => 'Question', 'name' => $f['question'],
                       'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f['answer'] ?? ''))]];
        }
        if ($main) { $graph[] = ['@type' => 'FAQPage', '@id' => $postUrl . '#faq', 'mainEntity' => $main]; }
    }

    return schema_script(['@context' => 'https://schema.org', '@graph' => $graph]);
}

/** Parse the CMS faqs field (JSON, or "Question :: Answer" per line) to [[q,a],...]. */
function parse_faqs($raw): array
{
    $raw = trim((string) $raw);
    if ($raw === '') { return []; }
    $j = json_decode($raw, true);
    if (is_array($j)) {
        return array_values(array_filter(array_map(static function ($f) {
            return ['question' => trim((string) ($f['question'] ?? '')), 'answer' => trim((string) ($f['answer'] ?? ''))];
        }, $j), static fn ($f) => $f['question'] !== ''));
    }
    $out = [];
    foreach (preg_split('/\r?\n/', $raw) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '::') === false) { continue; }
        [$q, $a] = array_map('trim', explode('::', $line, 2));
        if ($q !== '') { $out[] = ['question' => $q, 'answer' => $a]; }
    }
    return $out;
}

/** 6. AggregateRating — ONLY for real verified reviews. $testimonials = verified rows. */
function schemaReview(array $testimonials): string
{
    $rated = array_filter($testimonials, static fn ($t) => (int) ($t['rating'] ?? 0) >= 1);
    if (count($rated) < 1) { return ''; }
    $sum = 0;
    foreach ($rated as $t) { $sum += (int) $t['rating']; }
    $avg = round($sum / count($rated), 1);
    return schema_script([
        '@context'        => 'https://schema.org',
        '@type'           => 'LegalService',
        'name'            => cfg('firm_name', SITE_NAME),
        'aggregateRating' => [
            '@type'       => 'AggregateRating',
            'ratingValue' => (string) $avg,
            'reviewCount' => (string) count($rated),
            'bestRating'  => '5',
        ],
    ]);
}

/** 7. Practice area: Service. */
function schemaPracticeArea(array $area): string
{
    return schema_script([
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'serviceType' => $area['title'],
        'name'        => ($area['title'] ?? '') . ' Representation',
        'description' => $area['short_desc'] ?? '',
        'url'         => url('/practice-areas/' . ($area['slug'] ?? '') . '/'),
        'areaServed'  => ['@type' => 'State', 'name' => 'California'],
        'provider'    => ['@type' => 'LegalService', 'name' => cfg('firm_name', SITE_NAME), 'url' => BASE_URL],
    ]);
}
