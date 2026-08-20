<?php
/** TEMP blog publisher for the Folsom accident-report post. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'folsom-7p2') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

$slug     = 'how-to-get-folsom-police-accident-report';
$title    = 'How to Get a Folsom Police Accident Report (2026 Guide)';
$excerpt  = "After a crash in Folsom, the police report is the first thing the insurance company asks for. Here's exactly how to get your Folsom PD or CHP accident report — who has it, what it costs, and how long it takes.";
$metaT    = 'How to Get a Folsom Police Accident Report (2026)';
$metaD    = "Step-by-step guide to requesting your Folsom PD or CHP accident report — where to go, what it costs, how long it takes, and what to do if there's no report.";
$featured = '/assets/images/generated/blog-folsom-accident-report.webp';
$aname    = 'Elena Marquez';
$aslug    = 'elena-marquez';

$content = <<<'HTML'
<p>After a crash, the police report is the first document the insurance company asks for — and often the first proof of what actually happened. If you're figuring out how to get an accident report in Folsom, CA, the process is straightforward once you answer one question: <strong>which agency wrote it?</strong> Get that wrong and you'll spend a week requesting a Folsom police accident report that Folsom PD never had. Here's the whole process, verified against both agencies' current procedures.</p>

<h2>Who Has Your Report: Folsom PD or CHP?</h2>
<p>The dividing line is the roadway itself. The <strong>Folsom Police Department</strong> handles crashes on the city's surface streets — East Bidwell Street, Folsom Boulevard, Iron Point Road, Riley Street, Blue Ravine Road, and everything in between. The <strong>California Highway Patrol</strong> handles Highway 50, including its ramps and shoulders, even where the freeway runs straight through the middle of town.</p>

<table>
  <thead><tr><th>Where your crash happened</th><th>Who has your report</th></tr></thead>
  <tbody>
    <tr><td>A Folsom surface street (East Bidwell, Folsom Blvd, Iron Point, Riley, Blue Ravine, etc.)</td><td>Folsom Police Department</td></tr>
    <tr><td>Highway 50 through Folsom — any lane, ramp, or shoulder</td><td>CHP (East Sacramento Area office)</td></tr>
    <tr><td>Highway 50 past the El Dorado County line (El Dorado Hills)</td><td>CHP (Placerville Area office)</td></tr>
    <tr><td>Private property — parking lots, apartment complexes</td><td>Often no dispatched report (see below)</td></tr>
  </tbody>
</table>

<p><strong>Not sure?</strong> The business card the officer handed you at the scene names the agency and usually the report number. No card? Call Folsom Police Records at <a href="tel:+19164616400">916-461-6400</a> with the date, time, and location — they'll tell you quickly whether the report is theirs.</p>

<figure>
  <img src="/assets/images/generated/blog-folsom-report-paperwork.webp" alt="A person reviewing a stack of collision report paperwork on a desk" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>The collision report is the first document an insurance adjuster asks for after a Folsom crash.</figcaption>
</figure>

<h2>How to Request a Report from the Folsom Police Department</h2>
<p>A Folsom PD records request for a collision report now runs through an online portal rather than a counter visit. Here's the process:</p>
<ol>
  <li><strong>Give the report time to be finished.</strong> Routine collision reports typically take several days to about a week to be approved and uploaded. Requesting on day two usually means searching for a report that doesn't exist yet.</li>
  <li><strong>Gather three things:</strong> the collision date, a driver's last name, and the report number. The city's search system requires all three — this trips up more people than anything else. The report number is on the officer's card; if you don't have it, call Records at <a href="tel:+19164616400">916-461-6400</a> and they can look it up.</li>
  <li><strong>Search and pay online.</strong> Folsom routes collision reports through the <a href="https://ecrash.lexisnexis.com/">LexisNexis eCrash portal</a>. The report costs <strong>$16</strong>, payable online, or you can call LexisNexis at 888-332-8244 for other payment options.</li>
  <li><strong>If it doesn't come up, call Records.</strong> Folsom Police Records (46 Natoma Street, 916-461-6400) can locate reports the portal can't find. Note that some reports can't be released while an investigation is still open, and the Records Division has up to ten days to respond to a request.</li>
</ol>

<h2>How to Request a CHP Collision Report</h2>
<p>If your crash happened on Highway 50, Folsom PD won't have anything — you need the CHP's report, formally called a <strong>CHP 555 Traffic Collision Report</strong>. You have three routes, and you must qualify as a &ldquo;party of interest&rdquo;: a driver, passenger, bicyclist, pedestrian, vehicle or property owner, parent of a minor, legal guardian, legal representative, or an insurer with a valid claim.</p>

<figure>
  <img src="/assets/images/generated/blog-folsom-chp-officer.webp" alt="A California law enforcement officer completing a traffic collision report at the roadside" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>For crashes on Highway 50 through Folsom, the CHP — not Folsom PD — holds the report.</figcaption>
</figure>

<h3>Online, through the CHP Crash Portal</h3>
<p>The fastest option for a CHP report for a Highway 50 crash is the <a href="https://crashes.chp.ca.gov/">Online Crash Report System</a>, which the CHP launched in 2024 to let parties of interest download a digital copy at no charge. If the officer gave you a &ldquo;crash card&rdquo; with a QR code at the scene, it links straight to your report; otherwise, register on the site and search by date, location, and the officer's ID number.</p>

<h3>In person, at any CHP office</h3>
<p>Bring photo ID and a small copying fee. For the Folsom stretch of 50, the investigating office is the <strong>CHP East Sacramento Area</strong> at 11336 Trade Center Drive in Rancho Cordova, (916) 464-1450, open weekdays 8 a.m. to 5 p.m. Crashes past the county line in El Dorado Hills belong to the Placerville Area office — but any CHP office can pull any CHP report, so use whichever is convenient.</p>

<h3>By mail, with form CHP 190</h3>
<p>Complete the CHP 190 request form, attach a photocopy of your ID (or have the form notarized if you have none), and mail it with the fee by check or money order. Copying fees scale with the report's page count, so call the office to confirm the exact amount before you send payment.</p>

<h2>How Long It Takes</h2>
<p>CHP crash reports are typically available about eight business days after the collision. Folsom PD reports for routine crashes generally land within one to two weeks. Build in mailing time if you request by mail.</p>
<p>Serious-injury and fatality investigations run on a different clock. When officers are reconstructing the crash, waiting on toxicology, or weighing charges, the report can take weeks or months — and it may be withheld entirely until the investigation closes. Don't let that delay your claim: insurance deadlines and evidence preservation can't wait for the paperwork.</p>

<h2>What's Actually in the Report — and Why Insurers Read It First</h2>
<p>A traffic collision report is more than a receipt that the crash happened. It contains every party's contact and insurance information, witness names, each driver's statement, a diagram of the scene, road and weather conditions, injury and damage descriptions — and the officer's summary, including a &ldquo;primary collision factor,&rdquo; which is the officer's preliminary opinion on who caused the crash.</p>
<p>That last item is why the adjuster orders the report before making any offer. It's the first fault opinion in the file, and it anchors the negotiation. It is not, however, the final word: officers make mistakes, supplemental reports can correct them, and civil liability is decided on the full evidence — not just the box checked at the scene. That's exactly how an <a href="/blog/california-move-over-law-2026/">AB 390 Move Over violation</a> noted in a Highway 50 report can strengthen a roadside-crash claim, and how a bad preliminary opinion can still be overcome.</p>

<h2>What If There's No Police Report?</h2>
<p>You can still have a claim. Plenty of real injuries come out of crashes nobody dispatched an officer to — parking-lot collisions, minor-looking impacts that turn out not to be minor. California still requires you to file an <strong>SR-1 report</strong> with the DMV within ten days if anyone was hurt or property damage tops $1,000, and your claim can be built on other evidence: photos, dashcam footage, witness statements, nearby business cameras, repair estimates, and medical records. We walk through the first moves in <a href="/blog/what-to-do-after-a-car-accident-in-california/">What to Do After a Car Accident in California</a>.</p>

<h2>Can a Lawyer Get the Report for Me?</h2>
<p>Yes, and it's routine. When Mason Law takes on a <a href="/practice-areas/car-accidents/">car accident case</a>, requesting the report is one of the first things we do, along with the materials most people never think to ask for: scene photos, dispatch logs, and supplemental reports. If the report contains an error about fault, we work to correct it before it hardens into the insurer's position. If you were hurt in a crash anywhere in Folsom or on Highway 50, we'll pull the report as part of a <a href="/case-evaluation.php">free case evaluation</a> — no fee unless we recover for you. Call <a href="tel:+19165872997">(916) 587-2997</a> to get started.</p>

<h2>The Bottom Line</h2>
<p>One question decides everything: city street or Highway 50? Answer that, and your report is a $16 portal search or a free CHP download away. And if the report is delayed, wrong about fault, or doesn't exist at all, none of those things end a legitimate injury claim — they just change how it gets proven.</p>
HTML;

$faqs = json_encode([
    ['question' => 'How do I request a Folsom police accident report?',
     'answer'   => "Search the LexisNexis eCrash portal with your collision date, a driver's last name, and the report number — all three are required — and pay the \$16 fee online. If the report doesn't appear, call Folsom Police Records at 916-461-6400."],
    ['question' => 'How long does it take to get the report?',
     'answer'   => 'Routine Folsom PD reports are usually available within one to two weeks; CHP reports typically post about eight business days after the crash. Serious-injury investigations take longer, sometimes months, and may be withheld until the investigation closes.'],
    ['question' => 'What does an accident report cost?',
     'answer'   => 'Folsom PD collision reports cost $16 through the city\'s online portal. CHP charges a page-based copying fee for paper copies, though a digital copy through the CHP Crash Portal is free for parties of interest.'],
    ['question' => 'Who has my report if the crash was on Highway 50?',
     'answer'   => 'The CHP, not Folsom PD — the freeway is CHP jurisdiction even inside city limits. For the Folsom stretch, that\'s the East Sacramento Area office in Rancho Cordova, though any CHP office can retrieve the report for you.'],
    ['question' => 'Can someone request the report for me?',
     'answer'   => 'Yes. A legal representative can obtain it on your behalf, and attorneys do this routinely. For CHP reports, the requester must qualify as a party of interest — which includes your lawyer, your insurer, and a parent or guardian if the injured person is a minor.'],
    ['question' => 'Do I need the police report to file an insurance claim?',
     'answer'   => 'No. You can and should open the claim right away and supply the report when it arrives. The insurer will order a copy themselves, which is one more reason to know what\'s in it before you discuss fault with an adjuster.'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

try {
    $pdo = db();
    // Ensure optional columns the post template consumes exist (matches seed-blog.php's own pattern).
    foreach (['faqs MEDIUMTEXT NULL', 'date_modified DATETIME NULL'] as $coldef) {
        try { $pdo->exec("ALTER TABLE blog_posts ADD COLUMN IF NOT EXISTS $coldef"); } catch (Throwable $e) {}
    }
    $cols = $pdo->query('SHOW COLUMNS FROM blog_posts')->fetchAll(PDO::FETCH_COLUMN);
    $has  = static fn($c) => in_array($c, $cols, true);

    // category
    $st = $pdo->prepare('SELECT id FROM blog_categories WHERE slug = ?');
    $st->execute(['auto-accidents']);
    $catId = $st->fetchColumn() ?: null;

    // build column set (only existing columns)
    $data = [
        'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'content' => $content,
        'featured_image' => $featured, 'category_id' => $catId,
        'author_name' => $aname, 'author_slug' => $aslug,
        'status' => 'published', 'published_at' => date('Y-m-d H:i:s'),
        'meta_title' => $metaT, 'meta_desc' => $metaD,
    ];
    if ($has('faqs'))          { $data['faqs'] = $faqs; }
    if ($has('date_modified')) { $data['date_modified'] = date('Y-m-d H:i:s'); }
    if ($has('og_image'))      { $data['og_image'] = $featured; }
    if ($has('og_image_alt'))  { $data['og_image_alt'] = 'A California officer completing a traffic collision report'; }

    // exists already?
    $ex = $pdo->prepare('SELECT id FROM blog_posts WHERE slug = ?');
    $ex->execute([$slug]);
    $id = $ex->fetchColumn();

    if ($id) {
        $set = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($data)));
        $data['id'] = $id;
        $pdo->prepare("UPDATE blog_posts SET $set WHERE id = :id")->execute($data);
        echo "UPDATED existing post id=$id\n";
    } else {
        $ks = array_keys($data);
        $sql = 'INSERT INTO blog_posts (' . implode(',', $ks) . ') VALUES (:' . implode(',:', $ks) . ')';
        $pdo->prepare($sql)->execute($data);
        echo "INSERTED post id=" . $pdo->lastInsertId() . "\n";
    }
    echo "category_id=" . ($catId ?: 'NULL') . "  faqs_col=" . ($has('faqs') ? 'yes' : 'no')
       . "  date_modified_col=" . ($has('date_modified') ? 'yes' : 'no') . "\n";
    echo "columns: " . implode(',', $cols) . "\n";
    echo "DONE.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
@unlink(__FILE__);
