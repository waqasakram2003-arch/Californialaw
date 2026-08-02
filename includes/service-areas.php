<?php
/**
 * service-areas.php — data for the city/location landing pages.
 * URL pattern: /personal-injury-lawyer-<slug>-ca/  (SEO item 11)
 * Content is UNIQUE per city (avoids thin/doorway pages) + CA-Bar compliant
 * (no guarantee/best/specialist/expert/win-your-case).
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
                'If you or someone you love has been injured in an accident in Folsom, Mason Law, P.C. is right here in your community. Our office is on Iron Point Road, minutes from Historic Folsom and the Highway 50 corridor, and we represent injured people throughout Folsom and eastern Sacramento County.',
                'Serious injuries change everything — the medical bills, the missed work, the phone calls from insurance adjusters who want a quick, low statement. We handle those companies and the paperwork so you can focus on healing. Your consultation is free and confidential, and we handle injury cases on a contingency fee: you generally pay no attorney fee unless there is a recovery in your case.',
            ],
            'roads_intro' => 'Folsom pairs heavy commuter traffic with fast-moving highways and busy retail corridors. The crashes we see most often happen along a handful of predictable spots:',
            'hotspots' => [
                'Highway 50 between the Folsom Boulevard, Prairie City and Scott Road interchanges',
                'East Bidwell Street through the Broadstone and Palladio shopping districts',
                'Iron Point Road near the light-rail station and business parks',
                'Folsom-Auburn Road and the Rainbow Bridge approach',
                'Blue Ravine Road and Green Valley Road',
            ],
            'local'  => 'Folsom has grown quickly, and with growth comes more traffic, more construction zones, and more weekend visitors heading to Folsom Lake and the American River bike trail. Cyclists and pedestrians share the road with drivers who are often in a hurry, which makes intersection and crosswalk collisions a recurring problem in the area.',
            'neighborhoods' => 'Historic Folsom, Broadstone, Empire Ranch, Willow Creek, American River Canyon, and the Folsom Lake area',
            'faqs_local' => [
                ['question' => 'Where is your Folsom office located?',
                 'answer'   => 'Our office is at 1024 Iron Point Road, Folsom, CA 95630. Consultations are free, and if you cannot travel because of your injuries, we can speak with you by phone or come to you.'],
            ],
        ],

        'sacramento' => [
            'name'   => 'Sacramento',
            'county' => 'Sacramento County',
            'lat'    => '38.5816', 'lng' => '-121.4944',
            'intro'  => [
                'Sacramento is one of the busiest driving cities in Northern California, and its freeways and surface streets see thousands of collisions every year. If you have been hurt in an accident in the Sacramento area, Mason Law, P.C. can help you understand your rights and pursue fair compensation from the at-fault party and their insurer.',
                'We represent injured people across the capital region and take the pressure off you — dealing with the adjusters, gathering the evidence, and building your claim. The initial consultation is free and confidential, and you owe no attorney fee unless we recover for you.',
            ],
            'roads_intro' => 'The Sacramento area combines major interstates, an aging freeway grid, and dense downtown traffic. Collisions cluster around a few well-known problem areas:',
            'hotspots' => [
                'Interstate 5 through Downtown and the I-5 / Highway 50 interchange',
                'The I-80 / Business 80 (Capital City Freeway) split and the "curve"',
                'Highway 99 south toward Elk Grove',
                'Watt Avenue, Florin Road, and Stockton Boulevard arterials',
                'Downtown and Midtown one-way grid intersections',
            ],
            'local'  => 'With state offices, two universities, and a growing downtown, Sacramento sees a constant mix of commuters, rideshare vehicles, bicycles, and light-rail crossings. That mix produces everything from high-speed freeway pileups to low-speed but serious pedestrian and cyclist collisions on the surface streets.',
            'neighborhoods' => 'Downtown, Midtown, Natomas, Land Park, East Sacramento, Oak Park, and Arden-Arcade',
            'faqs_local' => [
                ['question' => 'Do you represent clients throughout the Sacramento area?',
                 'answer'   => 'Yes. From our Folsom office we represent injured people across Sacramento and the surrounding communities. Your consultation is free, and we can meet by phone or video if that is easier for you.'],
            ],
        ],

        'el-dorado-hills' => [
            'name'   => 'El Dorado Hills',
            'county' => 'El Dorado County',
            'lat'    => '38.6857', 'lng' => '-121.0819',
            'intro'  => [
                'Mason Law, P.C. represents injured residents of El Dorado Hills and the wider El Dorado County foothills. Just up Highway 50 from our Folsom office, El Dorado Hills combines fast commuter traffic with quiet residential streets — and both produce serious injury collisions.',
                'When you are dealing with an injury, the last thing you need is a fight with an insurance company. We take that on for you, from the first adjuster call to the final resolution. Your consultation is free and confidential, and injury matters are handled on a contingency fee — no attorney fee unless there is a recovery.',
            ],
            'roads_intro' => 'El Dorado Hills funnels a lot of daily traffic onto a few key routes, and that is where most of the crashes we handle happen:',
            'hotspots' => [
                'Highway 50 at the El Dorado Hills Boulevard and Bass Lake Road interchanges',
                'El Dorado Hills Boulevard through the Town Center',
                'Latrobe Road and White Rock Road',
                'Green Valley Road and Salmon Falls Road',
                'Serrano Parkway and residential collectors',
            ],
            'local'  => 'El Dorado Hills is largely a commuter community, so mornings and evenings bring heavy, fast-moving traffic onto Highway 50. Add weekend traffic heading to Folsom Lake and the foothills, and you get a pattern of rear-end, merging, and left-turn collisions that can cause lasting injuries.',
            'neighborhoods' => 'Serrano, Marina Village, Blackstone, Governors Village, Promontory, and the Town Center area',
            'faqs_local' => [
                ['question' => 'Can you help with an El Dorado County accident even though your office is in Folsom?',
                 'answer'   => 'Absolutely. El Dorado Hills is a short drive from our Folsom office, and we regularly represent injured people throughout El Dorado County. The consultation is free and can be handled remotely if needed.'],
            ],
        ],

        'roseville' => [
            'name'   => 'Roseville',
            'county' => 'Placer County',
            'lat'    => '38.7521', 'lng' => '-121.2880',
            'intro'  => [
                'If you were injured in Roseville or elsewhere in Placer County, Mason Law, P.C. is ready to stand up for you. Roseville is one of the fastest-growing cities in the region, and its busy retail corridors and freeway interchanges produce a steady stream of serious collisions.',
                'We take on the insurance companies so you do not have to, and we do not get paid unless you do. Consultations are always free and confidential, and injury cases are handled on a contingency fee — you generally pay nothing unless we recover for you.',
            ],
            'roads_intro' => 'Roseville\'s growth has put heavy pressure on its roads. The collisions we see most often happen around its shopping districts and the Interstate 80 corridor:',
            'hotspots' => [
                'Interstate 80 at the Douglas Boulevard, Riverside Avenue and Eureka Road exits',
                'Douglas Boulevard near the Westfield Galleria and Fountains',
                'Highway 65 toward Rocklin and Lincoln',
                'Roseville Parkway and Pleasant Grove Boulevard',
                'Sunrise Avenue and Cirby Way',
            ],
            'local'  => 'The Galleria and Fountains shopping areas draw shoppers from across Placer County, which means crowded parking lots, busy left-turn intersections, and frequent pedestrian traffic. Combined with commuter congestion on I-80, Roseville sees a wide range of injury collisions, from parking-lot backovers to high-speed freeway crashes.',
            'neighborhoods' => 'Fiddyment Farm, West Roseville, Highland Reserve, Sun City, Diamond Oaks, and the Galleria area',
            'faqs_local' => [
                ['question' => 'Do you handle Placer County injury cases?',
                 'answer'   => 'Yes. We represent injured people throughout Roseville and Placer County. Your consultation is free, and we can meet in person or remotely, whatever is easier while you recover.'],
            ],
        ],
    ];
}

/** Per-city FAQ (city-specific first, then shared) — feeds FAQPage schema. */
function service_area_faqs(string $city, array $localFaqs = []): array
{
    $shared = [
        ['question' => "How much does it cost to hire a {$city} personal injury lawyer?",
         'answer'   => 'We handle injury cases on a contingency fee, which generally means you pay no attorney fee unless there is a recovery in your case. The consultation is always free, so there is no cost to find out where you stand.'],
        ['question' => 'How long do I have to file a claim in California?',
         'answer'   => 'In most California injury cases you have two years from the date of the injury to file a lawsuit (Code of Civil Procedure section 335.1). Shorter deadlines apply to some claims — for example, claims against a government agency generally require a written claim within six months — so it is best to speak with an attorney promptly. This is general information, not legal advice.'],
        ['question' => "What should I do after an accident in {$city}?",
         'answer'   => 'Get medical attention, document the scene and your injuries, keep every record and bill, and avoid discussing fault or posting about the crash online. Speak with an attorney before giving a recorded statement to an insurance company.'],
        ['question' => "What types of cases do you handle in {$city}?",
         'answer'   => 'We help people injured in car, truck, motorcycle, pedestrian, bicycle, and rideshare accidents, as well as slip-and-fall, dog bite, and wrongful death matters throughout the region.'],
    ];
    return array_merge($localFaqs, $shared);
}
