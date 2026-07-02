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
        'areaServed'  => ['@type' => 'State', 'name' => 'California'],
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => cfg('site_address', SITE_ADDRESS),
            'addressRegion'   => 'CA',
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
