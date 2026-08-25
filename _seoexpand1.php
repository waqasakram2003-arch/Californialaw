<?php
/** TEMP: expand thin legacy posts w/ verified CA statutes + citations + FAQ. Self-deleting. */
if (($_GET['key'] ?? '') !== 'expand-9j5') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Every statutory claim below was verified against leginfo.legislature.ca.gov
 * (official CA Legislative Information) on 2026-08-25 before publication:
 *   VEH 21950     — driver yield duty + pedestrian due-care duties
 *   VEH 16000     — SR-1: 10 days, injury/death/property damage over $1,000
 *   CIV 3342(a)   — dog-bite strict liability, no prior-viciousness knowledge needed
 *   VEH 21658.1   — lane splitting defined; CHP guidelines; NO statutory speed limit
 * Internal links added in the Cycle-1 pass are preserved inline.
 */
$LEG = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';

$POSTS = [];

/* ------------------------------------------------------------------ 1 */
$POSTS['pedestrian-right-of-way-laws-california'] = [
'excerpt' => 'California drivers must yield to pedestrians in marked and unmarked crosswalks — but pedestrians have duties too. Here is what Vehicle Code section 21950 actually says, and how fault is decided when someone is hit.',
'meta_desc' => 'California pedestrian right-of-way law explained: what Vehicle Code 21950 requires of drivers, the duties pedestrians owe, and how fault is decided in a crosswalk accident.',
'content' => <<<HTML
<p>Pedestrians are among the most vulnerable people on California's roads. When a person on foot is struck by a vehicle, the case almost always turns on one question: who had the right of way? The answer is set out in the Vehicle Code — and it is more nuanced than "the pedestrian is always right." This is general information, not legal advice.</p>

<h2>The core rule: Vehicle Code section 21950</h2>
<p><a href="{$LEG}?lawCode=VEH&sectionNum=21950" target="_blank" rel="noopener">California Vehicle Code section 21950(a)</a> requires that "the driver of a vehicle shall yield the right-of-way to a pedestrian crossing the roadway within any marked crosswalk or within any unmarked crosswalk at an intersection."</p>
<p>Two words in that sentence do a great deal of work:</p>
<ul>
  <li><strong>Marked</strong> crosswalk — the painted lines most people picture.</li>
  <li><strong>Unmarked</strong> crosswalk — and this is the part drivers routinely miss. An unmarked crosswalk generally exists at an intersection even with no paint on the road at all. A pedestrian crossing at a normal corner is usually in a crosswalk in the eyes of the law.</li>
</ul>
<p>The statute goes further. Subdivision (c) requires a driver approaching a pedestrian in a crosswalk to "exercise all due care" and to "reduce the speed of the vehicle or take any other action" needed to protect that person's safety. Simply not hitting someone is not the standard — slowing down is part of the duty.</p>

<h2>Pedestrians have duties too</h2>
<p>Section 21950(b) places real obligations on people on foot. A pedestrian must use due care for their own safety and may not "suddenly leave a curb or other place of safety and walk or run into the path of a vehicle that is so close as to constitute an immediate hazard." Pedestrians also may not unnecessarily stop or delay traffic while in a crosswalk.</p>
<p>Importantly, subdivision (d) makes clear that a pedestrian's failure to follow those rules does not relieve a driver of the duty to exercise due care. Both duties exist at the same time.</p>

<h2>What about crossing outside a crosswalk?</h2>
<p>Crossing mid-block between adjacent signalized intersections generally requires yielding to vehicles. But — and this surprises many people — being outside a crosswalk does not automatically end a claim. Drivers still owe a duty of due care to everyone on the road, and factors like speed, distraction, visibility, and whether the driver could have avoided the collision all remain in play.</p>

<h2>How fault actually gets decided</h2>
<p>California does not treat these cases as all-or-nothing. Under the state's pure <a href="/blog/how-comparative-fault-works-in-california/">comparative</a> negligence rule, responsibility is divided by percentage. If a pedestrian is found partly at fault, their recovery is reduced by that share — but it is not eliminated. Someone found 25% at fault can still recover 75% of their damages.</p>
<p>This is exactly why insurers work so hard to shift a percentage onto the injured person: every point of fault they assign is money off the claim.</p>

<h2>Evidence that decides crosswalk cases</h2>
<p>Because these cases so often come down to competing accounts, the evidence gathered early matters enormously:</p>
<ul>
  <li>The traffic collision report and any citation issued</li>
  <li>Signal timing and whether a walk signal was displayed</li>
  <li>Nearby business, doorbell, or traffic camera footage — often overwritten within days</li>
  <li>Vehicle damage and point of impact, which can indicate speed and position</li>
  <li>Independent witness statements</li>
  <li>Medical records connecting injuries to the collision</li>
</ul>

<h2>If you were hit while walking</h2>
<p>Get medical attention the same day, even if you feel able to walk away — internal and head injuries frequently present later. Report the collision, photograph the scene and the crosswalk markings, and be cautious about giving a recorded statement before you understand how fault is being assessed. Our <a href="/practice-areas/pedestrian-accidents/">pedestrian accident</a> attorneys can review what happened at no cost. Every case is different, and outcomes depend on the specific facts.</p>
HTML,
'faqs' => [
  ['question' => 'Do drivers have to yield to pedestrians in California?',
   'answer' => 'Yes. Vehicle Code section 21950(a) requires drivers to yield the right-of-way to pedestrians crossing in any marked crosswalk, or in an unmarked crosswalk at an intersection. Drivers must also reduce speed and exercise all due care when approaching a pedestrian in a crosswalk.'],
  ['question' => 'Is there a crosswalk if there are no painted lines?',
   'answer' => 'Often yes. California recognizes unmarked crosswalks at intersections, so a pedestrian crossing at an ordinary corner is generally treated as being in a crosswalk even when no lines are painted on the pavement.'],
  ['question' => 'Can I still recover if I was jaywalking?',
   'answer' => 'Potentially. California uses pure comparative negligence, so being partly at fault reduces your recovery by your percentage of fault rather than barring it. A driver still owes a duty of due care to pedestrians outside crosswalks.'],
  ['question' => 'What should I do after being hit by a car while walking?',
   'answer' => 'Seek medical care the same day even if you feel fine, make sure a police report is created, photograph the scene and crosswalk markings, collect witness contact information, and be careful about giving a recorded statement to the driver\'s insurer before you understand how fault is being evaluated.'],
],
];

/* ------------------------------------------------------------------ 2 */
$POSTS['do-i-need-a-police-report-california'] = [
'excerpt' => 'A police report helps an injury claim, but it is not legally required to bring one. Here is when California law requires a report, the separate DMV SR-1 deadline most drivers miss, and how to prove a claim without one.',
'meta_desc' => 'Do you need a police report for a California injury claim? When reporting is required, the 10-day DMV SR-1 rule, and how to prove a claim when no report exists.',
'content' => <<<HTML
<p>Many people assume that without a police report, an injury claim is dead. That is not how California works. A report is powerful evidence — but it is not a legal prerequisite to recovering compensation. What matters is whether you can prove what happened and what it cost you. This is general information, not legal advice.</p>

<h2>Two different reporting duties people confuse</h2>
<p>There are actually two separate obligations after a California collision, and mixing them up causes real problems.</p>

<h3>1. The police report</h3>
<p>Law enforcement generally responds to and documents collisions involving injury, death, or significant property damage. Officers do not always write a report for minor crashes, and on private property — parking lots, apartment complexes — they frequently do not respond at all.</p>

<h3>2. The DMV SR-1 — the deadline most drivers miss</h3>
<p>This one is a legal requirement with a hard clock. Under <a href="{$LEG}?lawCode=VEH&sectionNum=16000" target="_blank" rel="noopener">California Vehicle Code section 16000</a>, a driver involved in a crash must report it to the DMV <strong>within 10 days</strong> if the collision caused injury to any person, death, or property damage exceeding <strong>$1,000</strong>. That threshold is low enough that most modern collisions clear it. This duty exists whether or not police came, and it is separate from telling your insurer.</p>

<h2>Why insurers want the report anyway</h2>
<p>A traffic collision report is usually the first document an adjuster orders, because it packages the key facts in one place: the parties and their insurance, witnesses, a scene diagram, road and weather conditions, statements, and the officer's opinion on the "primary collision factor" — a preliminary view of who caused the crash.</p>
<p>That opinion carries weight in negotiations, but it is not the final word. Officers do not always witness the crash, they can make mistakes, supplemental reports can correct the record, and civil liability is ultimately decided on the whole body of evidence. A bad preliminary conclusion is a hurdle, not a verdict.</p>

<h2>Proving a claim when no report exists</h2>
<p>Plenty of legitimate claims proceed without one. What replaces it is documentation:</p>
<ul>
  <li><strong>Photographs and video</strong> — vehicle positions, damage, skid marks, signage, lighting, and road conditions</li>
  <li><strong>Dashcam and surveillance footage</strong> — nearby businesses and doorbell cameras often overwrite within days, so these must be requested quickly</li>
  <li><strong>Witness statements</strong> with contact details captured at the scene</li>
  <li><strong>Medical records</strong> creating a documented, consistent treatment history</li>
  <li><strong>Repair estimates and property damage appraisals</strong></li>
  <li><strong>Your own written account</strong> made while details are fresh</li>
</ul>

<h2>If a report does exist, get a copy</h2>
<p>Reports are typically available within one to two weeks for routine collisions, and requesting one is straightforward once you know which agency wrote it — city police for surface streets, CHP for the highway. Our <a href="/blog/how-to-get-folsom-police-accident-report/">step-by-step guide to requesting an accident report</a> covers the process, fees, and what to do if the report cannot be found.</p>

<h2>The practical takeaway</h2>
<p>Do not let the absence of a police report stop you from pursuing a legitimate <a href="/practice-areas/car-accidents/">car accident</a> claim — and do not let its presence lull you into assuming fault is settled. Either way, the SR-1 clock is running from the date of the crash. If you are unsure where your claim stands, a free consultation costs nothing and can clarify your options.</p>
HTML,
'faqs' => [
  ['question' => 'Do I need a police report to file an injury claim in California?',
   'answer' => 'No. A police report is valuable evidence but is not legally required to bring a personal injury claim. Claims can be proven with photographs, video, witness statements, medical records, and repair documentation.'],
  ['question' => 'How long do I have to report an accident to the California DMV?',
   'answer' => 'Vehicle Code section 16000 requires the driver to file an SR-1 report with the DMV within 10 days if the crash caused injury, death, or property damage over $1,000. This obligation applies whether or not police responded.'],
  ['question' => 'What if the police did not come to my accident?',
   'answer' => 'This is common for minor collisions and crashes on private property such as parking lots. You can still pursue a claim by preserving other evidence — photos, video, witness contacts, medical records — and you may still owe a DMV SR-1 report.'],
  ['question' => 'Is the police officer\'s fault determination final?',
   'answer' => 'No. The officer\'s "primary collision factor" is a preliminary opinion, often formed without witnessing the crash. Supplemental reports can correct errors, and civil liability is decided on the complete evidence rather than the report alone.'],
],
];

/* ------------------------------------------------------------------ 3 */
$POSTS['california-dog-bite-laws-strict-liability'] = [
'excerpt' => 'California is a strict liability dog bite state — an owner can be responsible even if the dog never bit anyone before. Here is what Civil Code 3342 actually says, who it protects, and the defenses that still apply.',
'meta_desc' => 'California dog bite law explained: Civil Code 3342 strict liability, why the "one free bite" rule does not apply, who is covered, defenses, and the filing deadline.',
'content' => <<<HTML
<p>California is one of the more protective states for dog bite victims. Unlike jurisdictions that effectively give a dog "one free bite," California imposes strict liability on owners by statute. This is general information, not legal advice.</p>

<h2>What Civil Code section 3342 says</h2>
<p><a href="{$LEG}?lawCode=CIV&sectionNum=3342" target="_blank" rel="noopener">California Civil Code section 3342(a)</a> provides that the owner of any dog is liable for damages suffered by a person bitten "while in a public place or lawfully in a private place, including the property of the owner of the dog."</p>
<p>The critical language follows: liability applies <strong>regardless of the former viciousness of the dog or the owner's knowledge of such viciousness</strong>. That single clause is what makes California a strict liability state. An owner cannot escape responsibility by saying the dog had never shown aggression before.</p>

<h2>What a bite victim must show</h2>
<p>Under the statute, the elements are relatively contained:</p>
<ul>
  <li>The defendant <strong>owned</strong> the dog</li>
  <li>The dog <strong>bit</strong> the person</li>
  <li>The bite happened in a <strong>public place</strong>, or while the person was <strong>lawfully</strong> in a private place</li>
  <li>The bite caused <strong>damages</strong></li>
</ul>
<p>Notice what is absent: there is no need to prove the owner was careless, ignored warnings, or knew the dog was dangerous.</p>

<h2>Being "lawfully" on private property</h2>
<p>The statute protects people who are on private property — including the owner's own property — when they are there lawfully. That covers those performing a duty required by law (mail carriers and delivery drivers, for example) and those present by the owner's express or implied invitation, such as invited guests.</p>
<p>Someone trespassing generally falls outside the statute's protection, which is one of the more common defenses raised.</p>

<h2>Where strict liability stops</h2>
<p>Strict liability under 3342 is powerful but not unlimited:</p>
<ul>
  <li><strong>It applies to bites.</strong> Injuries caused another way — a large dog knocking someone down, or a fall while fleeing — generally proceed under ordinary negligence rather than 3342.</li>
  <li><strong>Trespassers</strong> are typically not covered.</li>
  <li><strong>Provocation</strong> may be raised, and a victim's own conduct can reduce recovery under California's <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> rules.</li>
  <li><strong>Certain working dogs</strong> — including police and military dogs performing their duties under specified conditions — have statutory exceptions.</li>
</ul>

<h2>Who actually pays</h2>
<p>Dog bite claims are frequently paid through homeowners or renters insurance rather than by the owner personally. Some policies exclude particular breeds or cap animal-related liability, so identifying the applicable coverage early matters.</p>

<h2>Damages and deadlines</h2>
<p>Recoverable <a href="/blog/damages-in-a-california-injury-claim/">damages</a> can include emergency and reconstructive treatment, scarring and disfigurement, lost income, and the psychological effects that often follow an attack — which can be significant, particularly for children.</p>
<p>The timing matters as well: most California personal injury claims are subject to a two-year deadline under Code of Civil Procedure section 335.1, and claims involving a public entity run on much shorter timelines. Our <a href="/practice-areas/dog-bites/">dog bite</a> attorneys can evaluate the specific circumstances at no cost.</p>
HTML,
'faqs' => [
  ['question' => 'Is California a strict liability state for dog bites?',
   'answer' => 'Yes. Civil Code section 3342 makes a dog owner liable for bite injuries regardless of the dog\'s former viciousness or the owner\'s knowledge of it, so the victim does not need to prove the owner was negligent.'],
  ['question' => 'Does California follow the "one free bite" rule?',
   'answer' => 'No. Because section 3342 imposes liability regardless of the owner\'s knowledge of prior viciousness, an owner generally cannot avoid responsibility on the basis that the dog had never bitten anyone before.'],
  ['question' => 'What if the dog bit me on the owner\'s property?',
   'answer' => 'The statute still applies if you were lawfully on the property — for example, as an invited guest or while performing a duty imposed by law, such as delivering mail. Trespassers are generally not protected by section 3342.'],
  ['question' => 'Does strict liability cover injuries other than bites?',
   'answer' => 'Generally no. Section 3342 addresses bites. Injuries caused another way, such as being knocked down by a large dog, are usually pursued under ordinary negligence principles instead.'],
  ['question' => 'Who pays a California dog bite claim?',
   'answer' => 'These claims are often covered by the owner\'s homeowners or renters liability insurance. Some policies contain breed exclusions or limits on animal-related liability, so identifying the available coverage early is important.'],
],
];

/* ------------------------------------------------------------------ 4 */
$POSTS['california-lane-splitting-laws'] = [
'excerpt' => 'California is the only state to formally define lane splitting in statute — and the law sets no specific speed limit. Here is what Vehicle Code 21658.1 actually says, the CHP guidance, and how insurers use rider bias.',
'meta_desc' => 'California lane splitting law explained: what Vehicle Code 21658.1 says, why there is no statutory speed limit, CHP safety guidance, and how fault works in a lane-splitting crash.',
'content' => <<<HTML
<p>California occupies a unique place in American motorcycle law: it is the state that formally recognized lane splitting in statute. But there is a great deal of misinformation about what the law actually permits — including a widely repeated "speed limit" that does not appear in the statute at all. This is general information, not legal advice.</p>

<h2>What the statute actually says</h2>
<p><a href="{$LEG}?lawCode=VEH&sectionNum=21658.1" target="_blank" rel="noopener">California Vehicle Code section 21658.1</a> defines lane splitting as "driving a motorcycle ... that has two wheels in contact with the ground, between rows of stopped or moving vehicles in the same lane."</p>
<p>The statute then authorizes the California Highway Patrol to "develop educational guidelines relating to lane splitting" in consultation with traffic safety and motorcycle organizations.</p>

<h3>The myth: a legal speed limit</h3>
<p>You will frequently read that lane splitting is legal "up to 10 mph faster than traffic" or "under 30 mph." Those figures come from safety guidance, <strong>not from the statute</strong>. Section 21658.1 does not establish any specific speed limit for lane splitting.</p>
<p>That distinction matters after a crash. Because the statute sets no numeric threshold, a rider is not automatically violating 21658.1 by exceeding a guidance figure — though speed and reasonableness remain central to whether the rider was operating safely, and ordinary Vehicle Code rules on speed and safe operation still apply.</p>

<h2>The CHP safety guidance</h2>
<p>The CHP's educational guidance generally encourages riders to keep speed differentials modest, to avoid splitting next to large vehicles like trucks and buses, to be wary of blind spots, and to recognize that splitting between the two leftmost lanes is typically safer than elsewhere. This is safety advice designed to reduce risk — useful for riders, and often cited by both sides after a collision.</p>

<h2>How fault is decided in a lane-splitting crash</h2>
<p>California uses pure <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a>, so responsibility is apportioned by percentage rather than assigned entirely to one party. In lane-splitting cases the argument usually centers on a handful of factors:</p>
<ul>
  <li>The <strong>speed differential</strong> between the motorcycle and surrounding traffic</li>
  <li>Whether the driver <strong>changed lanes without signaling</strong> or checking mirrors and blind spots</li>
  <li><strong>Visibility</strong>, lighting, and lane width</li>
  <li>Whether a driver <strong>deliberately blocked</strong> or moved toward the rider — which is itself unlawful conduct</li>
  <li>Traffic conditions and how much room actually existed</li>
</ul>
<p>Even a rider found partly responsible can recover a reduced amount. Being assigned 20% of the fault reduces the recovery by 20% — it does not eliminate the claim.</p>

<h2>Rider bias is a real obstacle</h2>
<p><a href="/blog/dealing-with-insurance-adjusters-california/">Insurance adjusters</a> know that some jurors arrive with assumptions about motorcyclists being reckless, and claims involving lane splitting attract that bias directly. Expect early attempts to characterize the rider as weaving, speeding, or appearing "out of nowhere."</p>
<p>Countering that generally takes objective evidence: helmet or dashcam footage, ECU and telemetry data, physical evidence about the point of impact, independent witnesses, and sometimes accident reconstruction.</p>

<h2>After a lane-splitting collision</h2>
<p>Get medical care promptly — protective gear can mask injuries that surface later. Preserve the motorcycle and your gear in their post-crash condition, photograph lane positions and traffic conditions, and gather witness information before people disperse. Our <a href="/practice-areas/motorcycle-accidents/">motorcycle accident</a> attorneys handle these claims and offer a free case review. Outcomes depend on the specific facts of each case.</p>
HTML,
'faqs' => [
  ['question' => 'Is lane splitting legal in California?',
   'answer' => 'California formally defines lane splitting in Vehicle Code section 21658.1 as riding a two-wheeled motorcycle between rows of stopped or moving vehicles in the same lane, and authorizes the CHP to issue educational safety guidelines.'],
  ['question' => 'Is there a speed limit for lane splitting in California?',
   'answer' => 'Section 21658.1 does not set a specific speed limit for lane splitting. Commonly cited figures such as 10 mph over traffic come from safety guidance rather than the statute, though general Vehicle Code rules on speed and safe operation still apply.'],
  ['question' => 'Can I still recover if I was lane splitting when the crash happened?',
   'answer' => 'Yes, potentially. California applies pure comparative negligence, so a rider who is partly at fault has their recovery reduced by their percentage of fault rather than barred entirely.'],
  ['question' => 'What if a driver blocked me on purpose?',
   'answer' => 'Deliberately blocking or moving toward a lane-splitting motorcyclist is unlawful conduct and can weigh heavily in the fault analysis. Dashcam or helmet-camera footage and independent witnesses are especially valuable in proving it.'],
],
];

/* ------------------------------------------------------------------ run */
try {
    $pdo = db();
    foreach (['faqs MEDIUMTEXT NULL', 'date_modified DATETIME NULL'] as $c) {
        try { $pdo->exec("ALTER TABLE blog_posts ADD COLUMN IF NOT EXISTS $c"); } catch (Throwable $e) {}
    }
    $cols = $pdo->query('SHOW COLUMNS FROM blog_posts')->fetchAll(PDO::FETCH_COLUMN);
    $hasFaq = in_array('faqs', $cols, true);
    $hasMod = in_array('date_modified', $cols, true);

    $sel = $pdo->prepare('SELECT id, content FROM blog_posts WHERE slug = ?');
    $n = 0;
    foreach ($POSTS as $slug => $p) {
        $sel->execute([$slug]);
        $row = $sel->fetch();
        if (!$row) { echo "SKIP (missing): $slug\n"; continue; }
        $oldW = str_word_count(strip_tags($row['content']));
        $newW = str_word_count(strip_tags($p['content']));

        $set = ['content = :c', 'excerpt = :e', 'meta_desc = :m'];
        $bind = [':c' => $p['content'], ':e' => $p['excerpt'], ':m' => $p['meta_desc'], ':id' => $row['id']];
        if ($hasFaq) { $set[] = 'faqs = :f'; $bind[':f'] = json_encode($p['faqs'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); }
        if ($hasMod) { $set[] = 'date_modified = :d'; $bind[':d'] = date('Y-m-d H:i:s'); }

        $pdo->prepare('UPDATE blog_posts SET ' . implode(', ', $set) . ' WHERE id = :id')->execute($bind);
        $n++;
        echo "OK  $slug: {$oldW} -> {$newW} words, faqs=" . count($p['faqs']) . "\n";
    }
    echo "\nposts expanded: $n\nDONE.\n";
} catch (Throwable $e) { echo "ERROR: " . $e->getMessage() . "\n"; }
@unlink(__FILE__);
