<?php
/**
 * service-areas.php — data for the city/location landing pages.
 * URL pattern: /personal-injury-lawyer-<slug>-ca/  (SEO item 11)
 * Keep content factual + CA-Bar compliant (no guarantee/best/specialist).
 */
declare(strict_types=1);

/** @return array<string,array> keyed by URL city slug. */
function service_areas(): array
{
    return [
        'folsom' => [
            'name'   => 'Folsom',
            'county' => 'Sacramento County',
            'lat'    => '38.6779', 'lng' => '-121.1761',
            'intro'  => [
                'If you or a loved one has been injured in an accident in Folsom, Mason Law, P.C. is here to help. Our office is right here on Iron Point Road, and we represent injured people throughout Folsom and the surrounding Sacramento County communities.',
                'From collisions on Highway 50 and East Bidwell Street to injuries at local businesses, we handle the insurance companies and the paperwork so you can focus on recovering. Consultations are free and confidential, and injury cases are handled on a contingency fee — you generally pay no attorney fee unless there is a recovery in your case.',
            ],
            'neighborhoods' => 'Historic Folsom, Broadstone, Empire Ranch, Willow Creek, and the Folsom Lake area',
        ],
        'sacramento' => [
            'name'   => 'Sacramento',
            'county' => 'Sacramento County',
            'lat'    => '38.5816', 'lng' => '-121.4944',
            'intro'  => [
                'Sacramento\'s busy freeways and surface streets see thousands of collisions every year. If you have been hurt in an accident in the Sacramento area, Mason Law, P.C. can help you understand your options and pursue fair compensation.',
                'We represent injured people across Sacramento — from Downtown and Midtown to Natomas, Land Park, and the suburbs along I-5, I-80, and Highway 99. Your consultation is free, and you owe no attorney fee unless we recover for you.',
            ],
            'neighborhoods' => 'Downtown, Midtown, Natomas, Land Park, East Sacramento, and Arden-Arcade',
        ],
        'el-dorado-hills' => [
            'name'   => 'El Dorado Hills',
            'county' => 'El Dorado County',
            'lat'    => '38.6857', 'lng' => '-121.0819',
            'intro'  => [
                'Mason Law, P.C. represents injured residents of El Dorado Hills and the greater El Dorado County community. Whether your injury happened on Highway 50, El Dorado Hills Boulevard, or at a local business, our team is ready to help.',
                'We handle the insurers and the details so you can concentrate on healing. The initial consultation is free and confidential, and injury matters are handled on a contingency fee — no attorney fee unless there is a recovery.',
            ],
            'neighborhoods' => 'Serrano, Marina Village, Blackstone, Governors Village, and the Town Center area',
        ],
        'roseville' => [
            'name'   => 'Roseville',
            'county' => 'Placer County',
            'lat'    => '38.7521', 'lng' => '-121.2880',
            'intro'  => [
                'If you were injured in Roseville or elsewhere in Placer County, Mason Law, P.C. is ready to stand up for you. We help injured people across Roseville, from the busy corridors near the Galleria to the neighborhoods along I-80 and Douglas Boulevard.',
                'We take on the insurance companies so you don\'t have to. Consultations are always free, and injury cases are handled on a contingency fee — you generally pay nothing unless we recover for you.',
            ],
            'neighborhoods' => 'Fiddyment Farm, West Roseville, Highland Reserve, Sun City, and the Galleria area',
        ],
    ];
}

/** Shared FAQ for location pages (feeds FAQPage schema). */
function service_area_faqs(string $city): array
{
    return [
        ['question' => "Do you offer free consultations for {$city} injury cases?",
         'answer'   => "Yes. Mason Law, P.C. offers a free, confidential consultation to anyone injured in {$city} or the surrounding area. You can call (916) 587-2997 or request a consultation online."],
        ['question' => 'How much does a personal injury lawyer cost?',
         'answer'   => 'We handle injury cases on a contingency fee, which generally means you pay no attorney fee unless there is a recovery in your case. There is no cost to speak with us about your situation.'],
        ['question' => 'How long do I have to file a claim in California?',
         'answer'   => 'In most California injury cases you have two years from the date of the injury to file a lawsuit (Code of Civil Procedure section 335.1). Shorter deadlines can apply, so it is best to speak with an attorney promptly. This is general information, not legal advice.'],
        ['question' => "What types of injury cases do you handle in {$city}?",
         'answer'   => 'We help people injured in car, truck, motorcycle, pedestrian, and rideshare accidents, as well as slip-and-fall, dog bite, and wrongful death matters throughout the region.'],
    ];
}
