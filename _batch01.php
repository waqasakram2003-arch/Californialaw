<?php
/** TEMP batch publisher — drip-scheduled posts. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'batch01-7q4') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * CADENCE: slot N publishes on START + (N-1)*2 days at 09:00 local.
 * Scheduling is native — status='published' with a future published_at keeps the post
 * out of the listing, the sitemap and its own URL (verified 404) until that moment.
 *
 * Verified before publication (2026-10-02):
 *   49 C.F.R. part 395 (eCFR, 200) — property-carrying HOS: 11-hour driving limit after
 *     10 consecutive hours off duty; no driving beyond the 14th consecutive hour on duty;
 *     30-minute break required once 8 hours have passed since the last qualifying break;
 *     60 hours/7 days or 70 hours/8 days; 34+ consecutive hours off may restart the week.
 *   49 C.F.R. § 387.9 — $750,000 minimum financial responsibility for for-hire interstate
 *     carriers of non-hazardous property, GVWR 10,001+ lbs; higher tiers for hazmat.
 *     (fmcsa.dot.gov 403s every agent, so part 387 on eCFR is the cited link.)
 *   CCP 335.1 / GOV 911.2 / CIV 1431.2 — previously verified this project.
 */
const START = '2026-10-04';
function slot(int $n): string { return date('Y-m-d 09:00:00', strtotime(START . ' +' . (($n - 1) * 2) . ' days')); }

$ECFR395 = 'https://www.ecfr.gov/current/title-49/part-395';
$ECFR387 = 'https://www.ecfr.gov/current/title-49/part-387';
$LEG     = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';

$POSTS = [];

/* ───────────────────────────── 1 ───────────────────────────── */
$POSTS[] = [
'slot' => 1, 'cat' => 'auto-accidents', 'author' => ['Daniel Cho', 'daniel-cho'],
'slug' => 'truck-accident-liability-california',
'title' => 'Who Is Liable in a California Truck Accident?',
'metaT' => 'Who Is Liable in a California Truck Accident?',
'metaD' => 'A truck crash can have several liable parties — driver, carrier, broker, shipper, maintenance contractor, or manufacturer. How California sorts out responsibility.',
'img' => '/assets/images/generated/blog-truck-liability.webp',
'imgAlt' => 'A semi truck travelling along an open highway',
'excerpt' => 'A car crash usually has one defendant. A truck crash often has five. Here is every party who can be on the hook in a California commercial truck case, and why finding all of them changes what you recover.',
'content' => <<<HTML
<p>In an ordinary car accident there is usually one defendant: the other driver. Commercial truck cases rarely work that way. A single collision can implicate the driver, the motor carrier that employed them, the broker that arranged the load, the company that loaded the trailer, the contractor that serviced the brakes, and the manufacturer of a failed component.</p>
<p><strong>Short answer:</strong> liability in a California truck accident falls on whoever's negligence caused the crash — which commonly includes the driver and, through employment or its own conduct, the trucking company. Other parties enter the case depending on what actually failed. This is general information, not legal advice.</p>

<h2>The driver</h2>
<p>The most direct defendant. Ordinary negligence applies — speed, following distance, lane changes, distraction, impairment, failure to yield. Commercial drivers are also bound by federal safety rules that do not apply to passenger cars, so a violation of those rules can supply powerful evidence of negligence in a way that has no equivalent in a car-versus-car case.</p>

<h2>The trucking company</h2>
<p>Carriers can be liable two separate ways, and the distinction matters.</p>
<p><strong>Through the driver (vicarious liability).</strong> An employer is generally responsible for the negligence of an employee acting within the scope of employment. If the driver was on the job, the carrier is ordinarily on the hook for what the driver did — no proof of carrier wrongdoing required.</p>
<p><strong>Through its own conduct (direct liability).</strong> Separately, a carrier can be liable for its own failures: negligent hiring of a driver with a disqualifying record, inadequate training, negligent retention after known violations, pressuring schedules that cannot be met legally, failing to maintain equipment, or failing to supervise hours. These claims survive even where the driver's own conduct looks defensible, and they often reach conduct a jury finds far more troubling than a single bad lane change.</p>

<h2>The broker, shipper, and loader</h2>
<p>Freight is frequently arranged by a broker and loaded by someone other than the driver. Where a load was improperly secured, overloaded, or unbalanced — and that is what caused a rollover or a shifting-cargo crash — responsibility can run to the party that loaded or secured it. Brokers can face claims where the selection of an unsafe carrier is at issue, though those claims involve additional legal questions about the broker's role.</p>

<h2>Maintenance contractors and manufacturers</h2>
<p>Brake failure, tire separation, steering or coupling failure: when the equipment itself fails, the shop that serviced it or the manufacturer of the defective component can be brought in. A product liability claim does not require proving anyone was careless — only that the product was defective and the defect caused the harm.</p>

<h2>Government entities</h2>
<p>Where a dangerous roadway condition contributed — a defective design, a missing sign, an unrepaired hazard — a public entity may share responsibility. These claims run on a far shorter clock: <a href="{$LEG}?lawCode=GOV&sectionNum=911.2" target="_blank" rel="noopener">Government Code section 911.2</a> generally requires a written claim within <strong>six months</strong> of the injury, long before the ordinary two-year deadline.</p>

<h2>Why finding every party matters</h2>
<p>This is not legal trivia. Each additional defendant typically brings its own insurance policy, and truck cases regularly involve damages that outrun any single policy. Federal rules require for-hire interstate carriers of non-hazardous property to maintain at least <strong>&#36;750,000</strong> in financial responsibility under <a href="{$ECFR387}" target="_blank" rel="noopener">49 C.F.R. part 387</a>, with higher minimums for hazardous materials — but a catastrophic injury can exceed even that, which is exactly when the broker's, loader's, or manufacturer's coverage becomes the difference between a partial and a full recovery.</p>
<p>California also apportions responsibility by percentage. Under the state's pure <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> rule your own share reduces but never bars recovery, and under <a href="{$LEG}?lawCode=CIV&sectionNum=1431.2" target="_blank" rel="noopener">Civil Code section 1431.2</a> each defendant is severally liable for non-economic damages in proportion to its own fault — so leaving a responsible party out of the case can quietly cost you that share.</p>

<h2>What this means practically</h2>
<p>Identifying defendants is early work, not late work. Carrier records, the driver's qualification file, maintenance logs, the bill of lading, and electronic data all name the parties involved — and several of those records are only retained for limited periods. Our guide to <a href="/blog/truck-accident-evidence-california/">evidence that disappears after a truck crash</a> covers what to preserve and how quickly.</p>
<p>If you or a family member was hurt by a commercial vehicle, our <a href="/practice-areas/truck-accidents/">California truck accident attorneys</a> can identify who belongs in the case and what coverage exists. Start with a <a href="/case-evaluation.php">free case evaluation</a> — you pay nothing unless we recover for you, and every case turns on its own facts.</p>
HTML,
'faqs' => [
 ['question'=>'Who can be sued after a truck accident in California?','answer'=>'Potentially the driver, the motor carrier that employed them, the broker that arranged the load, the party that loaded or secured the cargo, a maintenance contractor, a component manufacturer, and a public entity where a dangerous road condition contributed. Which parties belong in the case depends on what actually caused the crash.'],
 ['question'=>'Is the trucking company responsible for its driver?','answer'=>'Generally yes. An employer is ordinarily responsible for an employee\'s negligence committed within the scope of employment, so a carrier is typically liable for a driver who was on the job. A carrier can also be liable for its own conduct, such as negligent hiring, training, retention, supervision, or maintenance.'],
 ['question'=>'How much insurance do trucking companies carry?','answer'=>'Federal rules require for-hire interstate carriers of non-hazardous property with a gross vehicle weight rating of 10,001 pounds or more to maintain at least $750,000 in financial responsibility under 49 C.F.R. § 387.9, with higher minimums for hazardous materials. Many carriers carry more.'],
 ['question'=>'What if the cargo was loaded wrong?','answer'=>'Where an improperly secured, overloaded, or unbalanced load caused a rollover or shifting-cargo crash, the party that loaded or secured the freight can be responsible — which is often a different company from the driver or carrier.'],
 ['question'=>'Does being partly at fault stop me recovering from a trucking company?','answer'=>'No. California uses pure comparative negligence, so your recovery is reduced by your percentage of fault but is not barred, even if you were substantially at fault.'],
 ['question'=>'What if a road defect contributed to the truck crash?','answer'=>'A public entity may share responsibility, but Government Code section 911.2 generally requires a written government claim within six months of the injury — far shorter than the ordinary two-year deadline. That possibility needs evaluating early.'],
 ['question'=>'Why does it matter how many parties are liable?','answer'=>'Each defendant typically brings its own insurance. Truck cases frequently involve damages exceeding any single policy, so identifying every responsible party can be the difference between a partial and a full recovery. Under Civil Code section 1431.2 each defendant is also severally liable for non-economic damages in proportion to its own fault.'],
 ['question'=>'How soon should a truck accident be investigated?','answer'=>'As soon as possible. Driver qualification files, maintenance records, logs, and electronic data are retained only for limited periods, and some are overwritten in days or weeks. Early preservation requests are standard practice in commercial vehicle cases.'],
],
];

/* ───────────────────────────── 2 ───────────────────────────── */
$POSTS[] = [
'slot' => 2, 'cat' => 'auto-accidents', 'author' => ['Daniel Cho', 'daniel-cho'],
'slug' => 'truck-driver-fatigue-hours-of-service-california',
'title' => 'Truck Driver Fatigue and Hours-of-Service Rules in California',
'metaT' => 'Truck Driver Fatigue and Hours-of-Service Rules',
'metaD' => 'Federal hours-of-service limits cap how long a trucker can drive. What the 11-hour and 14-hour rules say, and how a violation becomes evidence in an injury claim.',
'img' => '/assets/images/generated/blog-truck-hos.webp',
'imgAlt' => 'A commercial truck on a highway under a clear sky',
'excerpt' => 'Federal law caps how long a commercial driver may be behind the wheel — 11 hours of driving inside a 14-hour window, with mandatory breaks. When those limits are broken, the logs become some of the strongest evidence in the case.',
'content' => <<<HTML
<p>Fatigue is not a vague accusation in a truck case. It is a regulated condition with numeric limits, electronic records, and a paper trail — which is why hours-of-service violations are among the most useful evidence an injured person can have.</p>
<p><strong>Short answer:</strong> federal rules limit property-carrying commercial drivers to 11 hours of driving after 10 consecutive hours off duty, inside a 14-hour on-duty window, with a required 30-minute break and weekly caps of 60 or 70 hours. Exceeding those limits is a violation that can support a negligence claim. This is general information, not legal advice.</p>

<h2>What the hours-of-service rules actually require</h2>
<p>The limits for property-carrying drivers appear in <a href="{$ECFR395}" target="_blank" rel="noopener">49 C.F.R. part 395</a>:</p>

<table>
  <thead><tr><th>Rule</th><th>What it requires</th></tr></thead>
  <tbody>
    <tr><td>11-hour driving limit</td><td>A driver may drive a maximum of 11 hours after 10 consecutive hours off duty.</td></tr>
    <tr><td>14-hour window</td><td>A driver may not drive beyond the 14th consecutive hour after coming on duty. Off-duty time during the day does not extend that window.</td></tr>
    <tr><td>30-minute break</td><td>Driving is not permitted once more than 8 hours have passed since the end of the last off-duty, sleeper-berth, or on-duty-not-driving period of at least 30 minutes.</td></tr>
    <tr><td>60/70-hour limit</td><td>No driving after 60 hours on duty in 7 consecutive days, or 70 hours in 8 consecutive days, depending on whether the carrier runs every day of the week.</td></tr>
    <tr><td>34-hour restart</td><td>An off-duty period of 34 or more consecutive hours may restart the 7- or 8-day period.</td></tr>
  </tbody>
</table>

<p>Two features of these rules matter for a claim. First, they are objective: a violation is a number out of range, not a judgment call. Second, the 14-hour window is a <em>clock</em>, not a budget — once it starts, loading delays, traffic, and waiting all consume it, which is precisely the pressure that pushes drivers to keep going when they should stop.</p>

<h2>Why fatigue produces severe crashes</h2>
<p>A fatigued driver's reaction time lengthens, lane position degrades, and the ability to judge closing speed deteriorates — all of which matter enormously in a vehicle that may weigh many times what a passenger car does and needs correspondingly more distance to stop. Fatigue-related crashes also skew toward the worst kinds: drift-out-of-lane collisions, failure to slow for stopped traffic, and rear-end impacts at highway speed.</p>

<h2>How a violation becomes evidence</h2>
<p>Hours are no longer recorded only on paper. Electronic logging devices capture duty status automatically, and they can be cross-checked against records the driver cannot edit:</p>
<ul>
  <li><strong>Electronic logging device (ELD) data</strong> — duty status, driving time, and the sequence of the day</li>
  <li><strong>Engine control module data</strong> — speed, braking, throttle, and often the moments before impact</li>
  <li><strong>Fuel receipts, toll records, and weigh-station timestamps</strong> — which place the truck at a time and location</li>
  <li><strong>Bills of lading and dispatch records</strong> — which show what the schedule actually demanded</li>
  <li><strong>GPS and telematics</strong> — movement that either corroborates or contradicts the log</li>
</ul>
<p>Where the log says one thing and the fuel receipts say another, the discrepancy is the finding. That is also why these records need to be requested quickly — see our guide to <a href="/blog/truck-accident-evidence-california/">evidence that disappears after a truck crash</a>.</p>

<h2>The carrier's role in fatigue</h2>
<p>Fatigue claims frequently reach past the driver. A dispatch schedule that cannot be completed within legal hours, a pay structure that rewards running over, a pattern of unaddressed log violations, or a failure to supervise hours at all can all support a claim against the carrier for its own conduct — not merely for the driver's. Our guide to <a href="/blog/truck-accident-liability-california/">who is liable in a California truck accident</a> covers how those separate theories work.</p>

<h2>If you were hit by a commercial truck</h2>
<p>Get medical care promptly, make sure the crash is reported, and photograph the scene including the tractor, trailer, and any company markings or USDOT number — those identify the carrier. Then move quickly on records: the most probative evidence in a fatigue case is electronic, and electronic evidence has retention limits.</p>
<p>Our <a href="/practice-areas/truck-accidents/">truck accident attorneys</a> can send preservation demands and obtain the logs before they age out. Start with a <a href="/case-evaluation.php">free case evaluation</a> — no fee unless we recover for you. Outcomes depend on the specific facts of each case.</p>
HTML,
'faqs' => [
 ['question'=>'How many hours can a truck driver drive in California?','answer'=>'Under federal rules for property-carrying drivers, a maximum of 11 hours of driving after 10 consecutive hours off duty, and no driving beyond the 14th consecutive hour after coming on duty. Weekly limits are 60 hours in 7 days or 70 hours in 8 days depending on the carrier\'s operation.'],
 ['question'=>'What is the 14-hour rule?','answer'=>'A property-carrying driver may not drive beyond the 14th consecutive hour after coming on duty following 10 consecutive hours off. It runs as a continuous clock, so breaks, loading delays, and traffic consume the window without extending it.'],
 ['question'=>'Do truck drivers have to take breaks?','answer'=>'Yes. Driving is not permitted once more than 8 hours have passed since the end of the driver\'s last off-duty, sleeper-berth, or on-duty-not-driving period of at least 30 minutes, subject to limited short-haul exceptions.'],
 ['question'=>'How do you prove a truck driver was fatigued?','answer'=>'Through records rather than speculation: electronic logging device data, engine control module data, GPS and telematics, fuel and toll receipts, weigh-station timestamps, and dispatch records. Discrepancies between the log and independent timestamps are often the decisive evidence.'],
 ['question'=>'What is an ELD?','answer'=>'An electronic logging device records a commercial driver\'s duty status automatically rather than relying on handwritten logs, making driving time far easier to verify and far harder to alter after the fact.'],
 ['question'=>'Can the trucking company be liable for driver fatigue?','answer'=>'Yes. Beyond responsibility for the driver, a carrier can be liable for its own conduct — scheduling that cannot be met within legal hours, pay structures that reward running over, ignoring repeated log violations, or failing to supervise hours.'],
 ['question'=>'Does an hours-of-service violation automatically win the case?','answer'=>'No. A violation is strong evidence of negligence, but the claim still requires showing the violation helped cause the crash and the resulting harm. It does, however, change the posture of a case considerably.'],
 ['question'=>'How quickly do truck records need to be requested?','answer'=>'Quickly. Electronic logs, engine data, and telematics are retained only for limited periods and can be overwritten. Preservation letters are typically among the first steps taken in a commercial vehicle case.'],
],
];

/* ───────────────────────────── 3 ───────────────────────────── */
$POSTS[] = [
'slot' => 3, 'cat' => 'auto-accidents', 'author' => ['Elena Marquez', 'elena-marquez'],
'slug' => 'truck-accident-claims-bigger-harder-california',
'title' => 'Why Truck Accident Claims Are Bigger — and Harder to Win',
'metaT' => 'Why Truck Accident Claims Are Bigger and Harder',
'metaD' => 'Truck claims involve more insurance, more defendants, and a rapid-response defense team. What makes them different from a car accident claim in California.',
'img' => '/assets/images/generated/blog-truck-claims.webp',
'imgAlt' => 'Heavy traffic including commercial trucks on a multi-lane freeway',
'excerpt' => 'Truck claims carry larger policies and larger injuries — and they are defended far more aggressively, often by investigators who reach the scene before the vehicles are moved. Here is what that asymmetry means for you.',
'content' => <<<HTML
<p>People often assume a truck claim is simply a car accident claim with bigger numbers. The numbers are bigger, but that is the least important difference.</p>
<p><strong>Short answer:</strong> truck claims involve more available insurance and more severe injuries, but also more defendants, federal regulations, electronic evidence with short retention, and a defense that mobilises within hours of the crash. The value is higher; so is the difficulty. This is general information, not legal advice.</p>

<h2>Why the numbers are larger</h2>
<p>Three things push truck claims above ordinary car claims.</p>
<p><strong>Injury severity.</strong> A loaded commercial vehicle carries far more energy into a collision than a passenger car, which is why truck crashes produce a disproportionate share of catastrophic outcomes — spinal injuries, traumatic brain injuries, amputations, and deaths.</p>
<p><strong>Available coverage.</strong> California's minimum auto liability limits are low enough that a serious injury routinely outruns them. Commercial carriers operate under a different regime: <a href="{$ECFR387}" target="_blank" rel="noopener">49 C.F.R. part 387</a> requires for-hire interstate carriers of non-hazardous property to maintain at least &#36;750,000 in financial responsibility, with higher minimums for hazardous materials, and many carry substantially more.</p>
<p><strong>Multiple policies.</strong> Because several parties can be responsible — carrier, broker, loader, maintenance contractor, manufacturer — a truck case often reaches more than one insurer. Our guide to <a href="/blog/truck-accident-liability-california/">who is liable in a California truck accident</a> maps those parties.</p>

<h2>Why they are harder</h2>

<h3>The defense starts before you do</h3>
<p>This is the asymmetry that surprises people most. Serious commercial crashes frequently trigger a rapid-response protocol: investigators, and sometimes counsel, dispatched to the scene while the vehicles are still there. By the time an injured person is out of hospital and thinking about a claim, the other side may already have scene measurements, photographs, witness statements, and downloaded vehicle data.</p>
<p>Nothing about that is improper — it is competent claims handling. But it means the injured person is behind from the first day unless someone is preserving evidence on their behalf.</p>

<h3>The evidence has an expiry date</h3>
<p>The most valuable records in a truck case are electronic and are not kept indefinitely. Logs, engine data, telematics, and dispatch records are subject to retention schedules, and routine overwriting is not misconduct — it is ordinary data management that happens to destroy proof. Our guide to <a href="/blog/truck-accident-evidence-california/">evidence that disappears after a truck crash</a> covers what to request and how fast.</p>

<h3>More defendants means more fault-shifting</h3>
<p>With several parties in a case, each has an incentive to point at the others — and at you. California apportions responsibility by percentage under its pure <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> rule, so every point of blame shifted onto the injured person reduces the recovery. Under <a href="{$LEG}?lawCode=CIV&sectionNum=1431.2" target="_blank" rel="noopener">Civil Code section 1431.2</a>, each defendant is severally liable for non-economic damages only in proportion to its own fault, which makes the apportionment fight consequential rather than academic.</p>

<h3>Regulations cut both ways</h3>
<p>Federal safety rules give an injured person objective standards to point to. They also give the defense a framework to argue compliance. A carrier that can show a clean log, a current inspection, and a qualified driver has a materially stronger position than one that cannot.</p>

<h2>What actually drives value</h2>
<p>The same components as any injury claim, on a larger scale: documented medical care and future treatment needs, lost income and lost earning capacity, and non-economic damages — explained in our guide to <a href="/blog/damages-in-a-california-injury-claim/">recoverable damages</a>. What differs is that future-care projections carry far more weight in catastrophic cases, and that identifying every policy is often the deciding factor in whether those damages are collectable at all.</p>
<p>Note also that the deadlines do not scale with the complexity. Most California injury actions must be filed within two years under <a href="{$LEG}?lawCode=CCP&sectionNum=335.1" target="_blank" rel="noopener">Code of Civil Procedure section 335.1</a>, and a public-entity angle can compress that to six months.</p>

<h2>Getting help</h2>
<p>If a commercial vehicle caused serious injury to you or a family member, the practical priority is preserving evidence while it still exists. Our <a href="/practice-areas/truck-accidents/">California truck accident attorneys</a> handle these claims and offer a <a href="/case-evaluation.php">free case evaluation</a>. Representation is contingency-based, as explained in our guide to <a href="/blog/personal-injury-lawyer-fees-california/">what a personal injury lawyer costs</a> — no fee unless we recover for you. Every case turns on its own facts.</p>
HTML,
'faqs' => [
 ['question'=>'Are truck accident settlements bigger than car accident settlements?','answer'=>'They often are, because commercial vehicles cause more severe injuries and carriers must maintain substantially more insurance than ordinary drivers. But value always depends on documented damages, available coverage, and apportioned fault rather than the vehicle type alone.'],
 ['question'=>'Why are truck accident cases harder to win?','answer'=>'More defendants, federal regulations on both sides of the argument, electronic evidence with short retention periods, and a defense that frequently investigates within hours of the crash. The injured person starts behind unless evidence is preserved early.'],
 ['question'=>'Do trucking companies send investigators to the scene?','answer'=>'Serious commercial crashes commonly trigger a rapid-response protocol, which can include investigators and counsel attending the scene and downloading vehicle data. This is lawful claims handling, but it means the carrier may hold key evidence before an injured person has begun a claim.'],
 ['question'=>'How much insurance must a trucking company carry?','answer'=>'Federal rules require at least $750,000 in financial responsibility for for-hire interstate carriers of non-hazardous property with a gross vehicle weight rating of 10,001 pounds or more, with higher minimums for hazardous materials. Many carriers maintain more.'],
 ['question'=>'Can more than one insurance policy apply to a truck crash?','answer'=>'Yes. Because the driver, carrier, broker, cargo loader, maintenance contractor, or manufacturer may each bear responsibility, a truck case can reach several policies — which matters when damages exceed any single one.'],
 ['question'=>'Does being partly at fault reduce a truck accident claim?','answer'=>'Yes. California applies pure comparative negligence, so recovery is reduced by the injured person\'s percentage of fault but never barred. With multiple defendants, apportionment is often heavily contested.'],
 ['question'=>'How long do I have to file a truck accident claim in California?','answer'=>'Generally two years from the date of injury under Code of Civil Procedure section 335.1. If a public entity contributed, a written government claim is generally required within six months, which is far shorter.'],
 ['question'=>'What should I do first after a truck crash?','answer'=>'Get medical care, make sure the crash is reported, and photograph the tractor, trailer, and any company markings or USDOT number, which identify the carrier. Then act quickly on preserving electronic records, which are retained only for limited periods.'],
],
];

/* ───────────────────────────── 4 ───────────────────────────── */
$POSTS[] = [
'slot' => 4, 'cat' => 'auto-accidents', 'author' => ['Daniel Cho', 'daniel-cho'],
'slug' => 'truck-accident-evidence-california',
'title' => 'The Evidence That Disappears After a Truck Crash',
'metaT' => 'Evidence That Disappears After a Truck Crash',
'metaD' => 'Logs, engine data, and telematics in a truck case are kept only for limited periods. What to preserve after a California truck crash, and how quickly.',
'img' => '/assets/images/generated/blog-truck-evidence.webp',
'imgAlt' => 'A commercial box truck travelling on a road',
'excerpt' => 'The records that prove a truck case are mostly electronic — and most of them are on retention schedules. Here is what exists, how long it typically survives, and what a preservation letter does.',
'content' => <<<HTML
<p>In most injury cases, evidence fades gradually: memories blur, bruises heal, a vehicle gets repaired. In commercial truck cases, evidence often does not fade at all — it is simply deleted on schedule, by systems doing exactly what they were designed to do.</p>
<p><strong>Short answer:</strong> the records that decide truck cases — electronic logs, engine data, telematics, dispatch records, and camera footage — are kept only for limited periods and are routinely overwritten. A preservation letter sent early is what stops the clock. This is general information, not legal advice.</p>

<h2>What exists after a commercial crash</h2>
<p>A truck generates far more documentation than a passenger car. The categories worth knowing about:</p>
<ul>
  <li><strong>Electronic logging device (ELD) data</strong> — duty status and driving time, recorded automatically under <a href="{$ECFR395}" target="_blank" rel="noopener">49 C.F.R. part 395</a></li>
  <li><strong>Engine control module / event data recorder</strong> — speed, braking, throttle position, and often the seconds before impact</li>
  <li><strong>Telematics and GPS</strong> — the truck's movement, independent of what any log says</li>
  <li><strong>Dash and cab-facing cameras</strong> — increasingly standard, frequently on short loops</li>
  <li><strong>Driver qualification file</strong> — licensing, medical certification, training, and prior violations</li>
  <li><strong>Maintenance and inspection records</strong> — including pre- and post-trip inspection reports</li>
  <li><strong>Dispatch records and the bill of lading</strong> — what the schedule required and what the trailer carried</li>
  <li><strong>Drug and alcohol testing records</strong> — where post-crash testing was required</li>
</ul>

<h2>Why it goes away</h2>
<p>Retention periods vary by record type, carrier policy, and system. Some categories are kept for years; others — particularly camera footage and certain telematics — can cycle in a matter of days or weeks. Engine data can also be lost when a vehicle is repaired, returned to service, or sold for salvage.</p>
<p>None of this requires anyone to act in bad faith. A camera that overwrites on a seven-day loop will overwrite the crash on day eight whether or not a claim is coming. That is precisely why the timing of a preservation request matters more than its wording.</p>

<h2>What a preservation letter does</h2>
<p>A spoliation or preservation letter notifies the carrier and its insurer that specific categories of evidence are relevant to an anticipated claim and must not be destroyed, overwritten, or altered. Sent promptly, it converts routine deletion into a deliberate act — which changes both the carrier's obligations and the consequences if the material disappears anyway.</p>
<p>Because the letter has to name what it wants with some precision, and because it is most effective when it arrives before the next retention cycle, this is typically among the first substantive steps taken in a commercial vehicle case.</p>

<h2>What you can preserve yourself</h2>
<p>Independent of the carrier's records, your own documentation matters and nobody else controls it:</p>
<ol>
  <li><strong>Photograph the tractor and trailer</strong> — including the company name, USDOT number, and plate. These identify the carrier, which may not be the name painted on the door.</li>
  <li><strong>Photograph the scene</strong> before vehicles move: positions, debris field, skid marks, cargo, road and lighting conditions.</li>
  <li><strong>Get witness names and numbers.</strong> Commercial-route witnesses are often passing through and unreachable later.</li>
  <li><strong>Note nearby cameras</strong> — businesses, traffic, and residential systems, all of which recycle quickly. Ask about footage within days, not weeks.</li>
  <li><strong>Keep the police report number</strong> and obtain the report when available; our <a href="/blog/how-to-get-folsom-police-accident-report/">accident report guide</a> covers the process.</li>
  <li><strong>Start a treatment and symptom record</strong> immediately, and keep every bill and explanation of benefits — see <a href="/blog/who-pays-medical-bills-after-car-accident-california/">who pays your medical bills</a>.</li>
</ol>

<h2>The deadline underneath all of it</h2>
<p>Evidence retention is a separate clock from the legal filing deadline, and it runs much faster. The filing deadline still applies: most California injury actions must be brought within two years under <a href="{$LEG}?lawCode=CCP&sectionNum=335.1" target="_blank" rel="noopener">Code of Civil Procedure section 335.1</a>, and a public-entity claim is generally due within six months. But a case filed inside the statute with the proof already deleted is a weaker case than the facts deserve.</p>
<p>If a commercial vehicle was involved in your crash, our <a href="/practice-areas/truck-accidents/">truck accident attorneys</a> can issue preservation demands and begin collecting records while they still exist. Start with a <a href="/case-evaluation.php">free case evaluation</a> — no fee unless we recover for you, and results depend on the facts of each case.</p>
HTML,
'faqs' => [
 ['question'=>'What evidence matters most in a truck accident case?','answer'=>'Electronic records usually carry the most weight: electronic logging device data, engine control module data, telematics and GPS, and camera footage. Driver qualification files, maintenance records, dispatch records, and the bill of lading also matter.'],
 ['question'=>'How long do trucking companies keep records?','answer'=>'It varies by record type, carrier policy, and system. Some records are retained for years, while camera footage and certain telematics can cycle within days or weeks. Engine data can also be lost when a vehicle is repaired or sold.'],
 ['question'=>'What is a spoliation or preservation letter?','answer'=>'A letter notifying the carrier and its insurer that specific evidence is relevant to an anticipated claim and must not be destroyed, overwritten, or altered. Sent early, it converts routine deletion into a deliberate act with legal consequences.'],
 ['question'=>'What is an engine control module in a truck?','answer'=>'An onboard system that records operating data such as speed, braking, and throttle position, often including the moments before an impact. It can corroborate or contradict accounts of how the crash happened.'],
 ['question'=>'What should I photograph after a truck crash?','answer'=>'The tractor and trailer including the company name, USDOT number, and plate, which identify the carrier; vehicle positions before anything moves; the debris field, skid marks, cargo, road surface, signage, and lighting; and any visible injuries.'],
 ['question'=>'Can I get the truck\'s camera footage?','answer'=>'Often yes, but speed matters. Cab and dash cameras frequently record on short loops and overwrite within days, so a request needs to reach the carrier before the next cycle.'],
 ['question'=>'Does the evidence deadline differ from the filing deadline?','answer'=>'Yes, and the evidence clock runs much faster. Most California injury actions must be filed within two years, but the records that prove a truck case can be gone within weeks of the crash.'],
 ['question'=>'What if the trucking company destroyed evidence?','answer'=>'Destruction after notice of a claim can carry consequences, which is one reason a prompt preservation letter matters. How a court responds depends on the circumstances, including when notice was given and whether the loss was routine or deliberate.'],
],
];

/* ───────────────────────────── run ───────────────────────────── */
try {
    $pdo = db();
    $cols = $pdo->query('SHOW COLUMNS FROM blog_posts')->fetchAll(PDO::FETCH_COLUMN);
    $has = static fn($c) => in_array($c, $cols, true);
    $catStmt = $pdo->prepare('SELECT id FROM blog_categories WHERE slug = ?');
    $exStmt  = $pdo->prepare('SELECT id FROM blog_posts WHERE slug = ?');
    $n = 0;

    foreach ($POSTS as $p) {
        $catStmt->execute([$p['cat']]);
        $catId = $catStmt->fetchColumn() ?: null;
        $pub   = slot($p['slot']);

        $data = [
            'title' => $p['title'], 'slug' => $p['slug'], 'excerpt' => $p['excerpt'],
            'content' => $p['content'], 'featured_image' => $p['img'], 'category_id' => $catId,
            'author_name' => $p['author'][0], 'author_slug' => $p['author'][1],
            'status' => 'published', 'published_at' => $pub,
            'meta_title' => $p['metaT'], 'meta_desc' => $p['metaD'],
        ];
        if ($has('faqs'))         { $data['faqs'] = json_encode($p['faqs'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); }
        if ($has('date_modified')){ $data['date_modified'] = $pub; }
        if ($has('og_image'))     { $data['og_image'] = $p['img']; }
        if ($has('og_image_alt')) { $data['og_image_alt'] = $p['imgAlt']; }

        $exStmt->execute([$p['slug']]);
        $id = $exStmt->fetchColumn();
        if ($id) {
            $set = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($data)));
            $data['id'] = $id;
            $pdo->prepare("UPDATE blog_posts SET $set WHERE id = :id")->execute($data);
            $verb = "UPDATED id=$id";
        } else {
            $ks = array_keys($data);
            $pdo->prepare('INSERT INTO blog_posts (' . implode(',', $ks) . ') VALUES (:' . implode(',:', $ks) . ')')->execute($data);
            $verb = 'INSERTED id=' . $pdo->lastInsertId();
        }
        $n++;
        printf("%-11s slot %-2d  %s  %s (%d words)\n", $verb, $p['slot'], substr($pub, 0, 10), $p['slug'], str_word_count(strip_tags($p['content'])));
    }
    echo "\nposts in batch: $n\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
