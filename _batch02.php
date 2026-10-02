<?php
/** TEMP batch publisher 02 — slip & fall cluster, drip-scheduled. Self-deleting. */
if (($_GET['key'] ?? '') !== 'batch02-4m9') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Verified before publication (2026-10-02):
 *   CIV 1714(a) — "Everyone is responsible, not only for the result of his or her willful
 *     acts, but also for an injury occasioned to another by his or her want of ordinary
 *     care or skill in the management of his or her property or person," except so far as
 *     the injured person has brought the injury upon themselves by want of ordinary care.
 *   Rowland v. Christian (1968) 69 Cal.2d 108 — abolished the rigid trespasser/licensee/
 *     invitee categories as determinative; the test is whether the possessor acted as a
 *     reasonable person in managing the property in view of the probability of injury.
 *     (FindLaw 403s this case on BOTH path formats, so it is cited in text with its
 *     official reporter citation rather than hyperlinked.)
 *   CCP 335.1 (2 years) and GOV 911.2 (6 months, death/personal injury) — verified earlier.
 */
const START = '2026-10-04';
function slot(int $n): string { return date('Y-m-d 09:00:00', strtotime(START . ' +' . (($n - 1) * 2) . ' days')); }
$LEG = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';

$POSTS = [];

/* ───── 5 ───── */
$POSTS[] = [
'slot'=>5,'cat'=>'your-rights','author'=>['Elena Marquez','elena-marquez'],
'slug'=>'slip-and-fall-laws-california',
'title'=>'Slip and Fall Laws in California: Proving a Property Owner Was Negligent',
'metaT'=>'Slip and Fall Laws in California: Proving Negligence',
'metaD'=>'A fall alone is not a case. What California requires you to prove in a slip and fall claim, including notice, and how comparative fault affects recovery.',
'img'=>'/assets/images/generated/blog-slipfall-proving.webp','imgAlt'=>'A cracked and uneven concrete surface',
'excerpt'=>'Falling on someone else\'s property does not by itself create a claim. California asks a narrower question: did the owner manage the property as a reasonable person would have, and did they know about the hazard?',
'content'=> <<<HTML
<p>People often assume that falling on someone else's property automatically means that owner owes them money. California does not work that way, and understanding why is the difference between a claim that goes somewhere and one that stalls immediately.</p>
<p><strong>Short answer:</strong> a California slip and fall claim requires showing the property owner failed to use ordinary care — generally that a dangerous condition existed, the owner knew or should have known about it, and failed to fix or warn of it in reasonable time. A fall alone proves none of that. This is general information, not legal advice.</p>

<h2>The duty California actually imposes</h2>
<p><a href="{$LEG}?lawCode=CIV&sectionNum=1714." target="_blank" rel="noopener">Civil Code section 1714(a)</a> states the foundation: everyone is responsible for injury caused to another "by his or her want of ordinary care or skill in the management of his or her property or person," except so far as the injured person has brought the injury on themselves by their own want of ordinary care.</p>
<p>California applies that broadly. In <em>Rowland v. Christian</em> (1968) 69 Cal.2d 108, the California Supreme Court rejected the old rule under which a visitor's legal label — trespasser, licensee, or invitee — dictated the duty owed. The test became whether the possessor of land acted as a reasonable person in managing the property, in view of the probability of injury to others. A visitor's status can still bear on the analysis, but it is no longer decisive.</p>
<p>Practically, that means a store owes care to a customer, and a homeowner owes care to a guest, under the same general standard rather than three different ones.</p>

<h2>The four things a claim has to establish</h2>
<table>
  <thead><tr><th>Element</th><th>What it means in practice</th></tr></thead>
  <tbody>
    <tr><td>A dangerous condition existed</td><td>A spill, a torn mat, an unlit stairwell, a broken handrail, uneven flooring, ice or water tracked inside</td></tr>
    <tr><td>The owner owned, leased, occupied, or controlled the property</td><td>Often contested in multi-tenant buildings and shopping centres</td></tr>
    <tr><td>The owner was negligent in using or maintaining it</td><td>Usually the heart of the case: what they knew, and what they did about it</td></tr>
    <tr><td>That negligence substantially caused your harm</td><td>Links the hazard to the fall and the fall to the injury</td></tr>
  </tbody>
</table>

<h2>Notice: the element most cases turn on</h2>
<p>An owner is not an insurer of everyone who walks in. Liability generally depends on whether they <strong>knew or reasonably should have known</strong> about the hazard in time to do something about it. That breaks into three familiar patterns:</p>
<ul>
  <li><strong>Actual notice</strong> — someone told them, or an employee saw it. A prior complaint or an incident report is powerful.</li>
  <li><strong>Constructive notice</strong> — the hazard existed long enough that reasonable inspection would have found it. A spill two minutes old is treated very differently from one that has been tracked through and dried at the edges.</li>
  <li><strong>They created it</strong> — if the owner or an employee caused the condition, notice is not an obstacle at all.</li>
</ul>
<p>This is why inspection records matter so much. Many businesses log sweeps and walkthroughs; those logs either show a reasonable inspection routine or show gaps.</p>

<h2>What usually decides these cases</h2>
<p>Evidence, and it fades fast:</p>
<ol>
  <li><strong>Photograph the hazard immediately</strong>, before it is cleaned up — which often happens within minutes.</li>
  <li><strong>Report it and get an incident report</strong>; ask for a copy.</li>
  <li><strong>Note surveillance cameras.</strong> Retail footage frequently overwrites within days, so it has to be requested quickly.</li>
  <li><strong>Get witness names</strong> before they leave.</li>
  <li><strong>Keep the shoes and clothing</strong> you were wearing, unwashed.</li>
  <li><strong>Seek medical care the same day</strong> — gaps in treatment are the first thing used to minimise an injury.</li>
</ol>

<h2>Expect a comparative fault argument</h2>
<p>The defence in nearly every premises case is some version of "you should have been looking." California uses pure <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a>, so your own share reduces your recovery proportionally but never bars it — a person found 30% responsible still recovers 70%. Distraction, footwear, phone use, and ignoring a posted warning are all raised; none of them ends a claim.</p>

<h2>Deadlines</h2>
<p>Most California premises claims must be filed within two years of the injury. If the property belongs to a public entity, a written government claim is generally required within six months — covered in our guide to <a href="/blog/slip-and-fall-government-property-california/">injuries on government property</a>, and in more depth in our <a href="/blog/california-statute-of-limitations-injury-claims/">statute of limitations guide</a>.</p>
<p>If you were hurt on someone else's property, our <a href="/practice-areas/slip-and-fall/">California premises liability attorneys</a> can assess whether the notice element is provable in your situation. Start with a <a href="/case-evaluation.php">free case evaluation</a> — no fee unless we recover for you, and every case turns on its own facts.</p>
HTML,
'faqs'=>[
 ['question'=>'Is a property owner automatically liable if I fall?','answer'=>'No. California requires proof that the owner failed to use ordinary care — generally that a dangerous condition existed, the owner knew or should have known about it, and failed to repair or warn of it within a reasonable time. A fall by itself establishes none of those.'],
 ['question'=>'What do I have to prove in a California slip and fall case?','answer'=>'That a dangerous condition existed; that the defendant owned, leased, occupied, or controlled the property; that the defendant was negligent in using or maintaining it; and that the negligence was a substantial factor in causing your harm.'],
 ['question'=>'What is "notice" in a slip and fall claim?','answer'=>'Whether the owner knew or reasonably should have known about the hazard in time to act. Actual notice means someone knew; constructive notice means it existed long enough that reasonable inspection would have found it. If the owner or an employee created the hazard, notice is not an obstacle.'],
 ['question'=>'Does it matter whether I was a customer or a guest?','answer'=>'Less than it once did. In Rowland v. Christian (1968) 69 Cal.2d 108 the California Supreme Court held that a visitor\'s status as trespasser, licensee, or invitee is not determinative; the test is whether the property possessor acted reasonably in managing the property. Status can still bear on the analysis.'],
 ['question'=>'What if I was partly to blame for falling?','answer'=>'California applies pure comparative negligence, so your recovery is reduced by your percentage of fault but never eliminated. Someone found 30% at fault can still recover 70% of their damages.'],
 ['question'=>'What evidence matters most after a fall?','answer'=>'Photographs of the hazard before it is cleaned up, an incident report, surveillance footage requested before it overwrites, witness contact details, the footwear and clothing you were wearing, and prompt medical records.'],
 ['question'=>'How long do I have to file a slip and fall claim in California?','answer'=>'Generally two years from the date of injury. If the property is owned by a public entity, a written government claim is usually required within six months, which is far shorter.'],
 ['question'=>'Do businesses keep records of inspections?','answer'=>'Many do. Sweep logs, walkthrough checklists, and maintenance records often decide the notice question, because they either demonstrate a reasonable inspection routine or reveal gaps in it.'],
],
];

/* ───── 6 ───── */
$POSTS[] = [
'slot'=>6,'cat'=>'legal-tips','author'=>['Elena Marquez','elena-marquez'],
'slug'=>'slip-and-fall-statute-of-limitations-california',
'title'=>'How Long Do You Have to Sue After a Slip and Fall in California?',
'metaT'=>'Slip and Fall Deadlines in California',
'metaD'=>'Two years for most California slip and fall claims — but six months if a public entity owns the property. The deadlines, the exceptions, and why evidence expires sooner.',
'img'=>'/assets/images/generated/blog-slipfall-deadline.webp','imgAlt'=>'A person writing dates into a planner at a desk',
'excerpt'=>'Two years sounds generous until you learn that a government-owned property cuts it to six months — and that the evidence which proves a fall case disappears long before either deadline.',
'content'=> <<<HTML
<p>Of the ways a legitimate premises claim gets lost, running out of time is the most avoidable and the most final. The complication in slip and fall cases is that there is not one deadline — there are at least two, and they differ by a factor of four.</p>
<p><strong>Short answer:</strong> most California slip and fall claims must be filed within two years of the injury. If the property is owned or controlled by a public entity, a written government claim is generally required within <strong>six months</strong> first. This is general information, not legal advice.</p>

<h2>The general rule: two years</h2>
<p><a href="{$LEG}?lawCode=CCP&sectionNum=335.1" target="_blank" rel="noopener">Code of Civil Procedure section 335.1</a> gives two years for an action for "injury to, or for the death of, an individual caused by the wrongful act or neglect of another." That covers the ordinary premises case — a fall in a shop, a restaurant, an apartment building, or a private home.</p>
<p>Claims for damage to property run on a different track and generally carry three years, which is why a single incident can produce two different expiry dates.</p>

<h2>The six-month trap: public property</h2>
<p>If you fell on property owned or controlled by a city, county, school district, transit agency, or the state, the ordinary two-year clock is not the operative one. <a href="{$LEG}?lawCode=GOV&sectionNum=911.2" target="_blank" rel="noopener">Government Code section 911.2</a> requires a claim for death or injury to person to be presented <strong>no later than six months</strong> after the cause of action accrues. Claims relating to other causes of action carry a one-year presentation deadline.</p>
<p>Six months arrives far sooner than people expect, and public property is easy to misidentify — a library, a civic centre, a transit platform, a public pool, a courthouse walkway, a sidewalk adjoining a public building. Our guide to <a href="/blog/slip-and-fall-government-property-california/">injuries on government property</a> covers how those claims work.</p>

<table>
  <thead><tr><th>Where you fell</th><th>Operative deadline</th></tr></thead>
  <tbody>
    <tr><td>Private business, home, or apartment</td><td>Generally 2 years to file suit</td></tr>
    <tr><td>City, county, state, or district property</td><td>Generally 6 months to present a written government claim</td></tr>
    <tr><td>Property damage only</td><td>Generally 3 years</td></tr>
  </tbody>
</table>

<h2>Situations that change the date</h2>
<ul>
  <li><strong>Minors.</strong> The limitations period is generally tolled during minority, so the usual clock does not run in the ordinary way before age 18. Government claim requirements still demand prompt attention.</li>
  <li><strong>Delayed discovery.</strong> Where an injury could not reasonably have been discovered at the time, the period may begin when it was or should have been discovered. This is narrower than people hope.</li>
  <li><strong>Defendant absent from the state.</strong> Certain periods of absence may not count toward the limitations period.</li>
</ul>

<h2>Why the real deadline is sooner than the legal one</h2>
<p>This is the part that costs people more often than the statute does. The proof in a fall case has a much shorter life than the claim:</p>
<ul>
  <li><strong>Surveillance footage</strong> is frequently overwritten within days or weeks.</li>
  <li><strong>The hazard itself</strong> is usually cleaned, repaired, or replaced within hours.</li>
  <li><strong>Incident reports and sweep logs</strong> are subject to retention policies.</li>
  <li><strong>Witnesses</strong> — especially customers — become unreachable quickly.</li>
  <li><strong>Treatment gaps</strong> widen the longer someone waits to be seen.</li>
</ul>
<p>A claim filed comfortably inside two years, with the footage long gone, is a weaker claim than the facts deserved. The evidence steps are covered in our guide to <a href="/blog/slip-and-fall-laws-california/">proving a property owner was negligent</a>.</p>

<h2>If the deadline passes</h2>
<p>A late lawsuit is ordinarily subject to dismissal, and California courts enforce these deadlines strictly regardless of how strong the underlying claim is. Narrow, fact-specific exceptions exist, but they are not something to rely on.</p>
<p>If you are unsure which deadline applies — particularly if there is any chance the property is public — it costs nothing to find out. Our <a href="/practice-areas/slip-and-fall/">premises liability attorneys</a> can identify the operative dates in a <a href="/case-evaluation.php">free case evaluation</a>. Every case turns on its own facts.</p>
HTML,
'faqs'=>[
 ['question'=>'How long do I have to sue after a slip and fall in California?','answer'=>'Generally two years from the date of injury under Code of Civil Procedure section 335.1. Property damage claims typically carry three years, so one incident can produce two different deadlines.'],
 ['question'=>'What if I fell on government property?','answer'=>'Government Code section 911.2 generally requires a written claim for death or personal injury to be presented within six months of accrual, before any lawsuit is possible. Claims relating to other causes of action generally carry a one-year presentation deadline.'],
 ['question'=>'Does the deadline change for a child?','answer'=>'The limitations period is generally tolled while the injured person is a minor, so the ordinary clock does not run in the usual way before age 18. Government claim requirements still call for prompt action.'],
 ['question'=>'What is the delayed discovery rule?','answer'=>'For some injuries that could not reasonably have been discovered when they occurred, the limitations period may begin when the harm was discovered or should have been discovered. It is applied narrowly and is fact-specific.'],
 ['question'=>'Why should I act sooner than the legal deadline?','answer'=>'Because the evidence expires first. Surveillance footage often overwrites within days, the hazard is usually cleaned or repaired within hours, incident reports are subject to retention policies, and witnesses become unreachable.'],
 ['question'=>'What happens if I miss the deadline?','answer'=>'A late lawsuit is ordinarily subject to dismissal, and California courts enforce these deadlines strictly regardless of the merits. Narrow exceptions exist but should not be relied upon.'],
 ['question'=>'Is a sidewalk public or private property?','answer'=>'It depends on who owns and controls it, which is not always obvious from looking at it. Because a public entity triggers the six-month claim requirement, this is worth confirming early rather than assuming.'],
 ['question'=>'Does negotiating with the insurer extend my deadline?','answer'=>'No. The limitations period continues to run during negotiations. An open claim file does not pause the statute.'],
],
];

/* ───── 7 ───── */
$POSTS[] = [
'slot'=>7,'cat'=>'your-rights','author'=>['Elena Marquez','elena-marquez'],
'slug'=>'slip-and-fall-store-restaurant-california',
'title'=>'Slip and Fall in a Store, Restaurant, or Supermarket',
'metaT'=>'Slip and Fall in a Store or Restaurant in California',
'metaD'=>'Retail falls turn on inspection records and surveillance footage. How notice is proved against a business, and what to do in the first hours after you fall.',
'img'=>'/assets/images/generated/blog-slipfall-store.webp','imgAlt'=>'A shopper walking along a supermarket aisle',
'excerpt'=>'Retail and restaurant falls are the most common premises claims — and the most winnable, because businesses generate records. Sweep logs and camera footage usually decide them, if they are requested in time.',
'content'=> <<<HTML
<p>Supermarkets, restaurants, and big-box stores produce more premises claims than anywhere else, for obvious reasons: high foot traffic, liquids, constant restocking, and floors that are polished rather than textured.</p>
<p><strong>Short answer:</strong> a business is liable for a fall when it knew or should have known about the hazard and failed to fix or warn of it in reasonable time. Unlike a private home, a commercial defendant usually generates records — inspection logs, incident reports, and camera footage — that can prove exactly that. This is general information, not legal advice.</p>

<h2>Why retail cases are different</h2>
<p>The legal standard is the same one that applies to any property owner under <a href="{$LEG}?lawCode=CIV&sectionNum=1714." target="_blank" rel="noopener">Civil Code section 1714</a>: ordinary care in managing the property. What differs is the evidence available.</p>
<p>Commercial operations are documented. Many chains run scheduled floor sweeps and log them. Incident reports are standard. Cameras are everywhere. That documentation cuts both ways — it can demonstrate a reasonable inspection routine, or it can show that nobody checked the aisle for three hours.</p>

<h2>Common retail hazards</h2>
<ul>
  <li><strong>Spills and leaking refrigeration</strong> — produce misting, freezer condensation, dropped product</li>
  <li><strong>Tracked-in water</strong> at entrances during rain, especially where matting is missing or saturated</li>
  <li><strong>Freshly mopped floors</strong> without adequate warning signage</li>
  <li><strong>Merchandise, pallets, or stocking carts</strong> left in walkways</li>
  <li><strong>Torn mats, loose tiles, and transitions</strong> between flooring types</li>
  <li><strong>Poor lighting</strong> in stairwells, storerooms, and parking structures</li>
  <li><strong>Parking lot defects</strong> — potholes, wheel stops, unmarked kerbs</li>
</ul>

<h2>Proving the business should have known</h2>
<p>Notice is almost always the contested element. The practical questions:</p>
<table>
  <thead><tr><th>Question</th><th>Why it matters</th></tr></thead>
  <tbody>
    <tr><td>How long had the hazard been there?</td><td>A spill that is dried at the edges, tracked through, or has cart marks through it suggests time has passed</td></tr>
    <tr><td>When was the last documented sweep?</td><td>A gap between the log entry and the fall supports constructive notice</td></tr>
    <tr><td>Did an employee create it?</td><td>If so, notice is not an issue at all</td></tr>
    <tr><td>Were there prior complaints?</td><td>A recurring leak or known problem area is strong evidence</td></tr>
    <tr><td>Was there a warning sign?</td><td>Its presence, absence, and placement all matter</td></tr>
  </tbody>
</table>

<h2>What to do in the first hours</h2>
<ol>
  <li><strong>Report it to a manager</strong> and ask that an incident report be created. Request a copy, and note the manager's name.</li>
  <li><strong>Photograph the hazard before it is cleaned</strong> — this is the single most valuable thing you can do, and the window is minutes.</li>
  <li><strong>Photograph the surroundings</strong>: signage or its absence, lighting, matting, and the floor surface.</li>
  <li><strong>Look for cameras</strong> and note where they are. Footage is often on a short loop.</li>
  <li><strong>Get witness details</strong> — other customers will not be findable later.</li>
  <li><strong>Keep your shoes and clothing</strong> unwashed; footwear is frequently blamed.</li>
  <li><strong>Get examined the same day</strong>, even if you feel merely shaken.</li>
</ol>
<p>Be careful about recorded statements to the store's insurer or a claims administrator before you understand your injuries — the same dynamics described in our guide to <a href="/blog/dealing-with-insurance-adjusters-california/">dealing with insurance adjusters</a> apply here.</p>

<h2>What these claims are worth</h2>
<p>The same components as any injury claim: medical treatment and future care, lost income, and non-economic damages, explained in our guide to <a href="/blog/damages-in-a-california-injury-claim/">recoverable damages</a>. Expect the business to argue comparative fault — that you were distracted, wearing unsuitable footwear, or ignored a sign. Under California's pure <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> rule that reduces recovery proportionally rather than barring it.</p>
<p>Most retail falls must be filed within two years; see our guide to <a href="/blog/slip-and-fall-statute-of-limitations-california/">slip and fall deadlines</a>. If you were injured in a store or restaurant, our <a href="/practice-areas/slip-and-fall/">premises liability attorneys</a> can request footage and inspection records before they cycle. Start with a <a href="/case-evaluation.php">free case evaluation</a> — no fee unless we recover for you.</p>
HTML,
'faqs'=>[
 ['question'=>'What should I do immediately after falling in a store?','answer'=>'Report it to a manager and ask for an incident report, photograph the hazard before it is cleaned, photograph signage and lighting, note camera locations, collect witness details, keep your shoes and clothing unwashed, and get medical attention the same day.'],
 ['question'=>'How do you prove a store knew about a spill?','answer'=>'Through evidence of how long it had been there and what the business was doing about it: sweep and inspection logs, the condition of the spill itself such as drying edges or cart tracks, prior complaints about a recurring problem, and surveillance footage.'],
 ['question'=>'Does a wet floor sign defeat my claim?','answer'=>'Not automatically. Its presence, placement, and visibility all matter, and a sign that was behind the hazard or not visible from the direction of approach may not discharge the duty. It does typically become part of a comparative fault argument.'],
 ['question'=>'How long does store surveillance footage last?','answer'=>'It varies by business and system, but retail footage is frequently recorded on short loops and can be overwritten within days. A request needs to reach the business quickly to be useful.'],
 ['question'=>'What if I was looking at my phone when I fell?','answer'=>'That is a comparative fault argument, not a defence that ends the claim. California reduces recovery by the injured person\'s percentage of fault rather than barring it.'],
 ['question'=>'Should I give a statement to the store\'s insurance company?','answer'=>'Be cautious, particularly before you understand the extent of your injuries. Recorded statements are commonly used to establish comparative fault or to minimise the injury.'],
 ['question'=>'Can I claim if I fell in the parking lot?','answer'=>'Potentially. Parking areas are part of the premises, though who owns, leases, or maintains the lot can differ from the store itself, which affects who the proper defendant is.'],
 ['question'=>'How long do I have to bring a retail slip and fall claim?','answer'=>'Generally two years from the date of injury in California. If the property is publicly owned, a written government claim is usually required within six months instead.'],
],
];

/* ───── 8 ───── */
$POSTS[] = [
'slot'=>8,'cat'=>'your-rights','author'=>['Daniel Cho','daniel-cho'],
'slug'=>'slip-and-fall-government-property-california',
'title'=>'Injured on Government Property: California\'s Six-Month Rule',
'metaT'=>'Injured on Government Property: The Six-Month Rule',
'metaD'=>'Falls on city, county, or state property require a written government claim — generally within six months. How the process works and why it is missed so often.',
'img'=>'/assets/images/generated/blog-slipfall-government.webp','imgAlt'=>'A damaged road surface with exposed reinforcement',
'excerpt'=>'A claim against a public entity runs on a different clock from every other injury claim in California — generally six months, not two years. Miss it and a strong case usually ends before it begins.',
'content'=> <<<HTML
<p>Most people injured on public property assume they have the same two years everyone else has. They do not, and the gap between the assumption and the rule is where otherwise sound claims die.</p>
<p><strong>Short answer:</strong> before suing a California public entity for injury you must first present a written government claim, generally within <strong>six months</strong> of when the cause of action accrues. Only after that process can a lawsuit follow. This is general information, not legal advice.</p>

<h2>The rule</h2>
<p><a href="{$LEG}?lawCode=GOV&sectionNum=911.2" target="_blank" rel="noopener">Government Code section 911.2</a> requires that a claim relating to a cause of action for death or for injury to person, personal property, or growing crops be presented "not later than six months after the accrual of the cause of action." Claims relating to any other cause of action carry a one-year presentation deadline.</p>
<p>This is a precondition, not a formality. The claim goes to the entity first; the lawsuit comes later, and only within the timeframes that follow the entity's response or failure to respond.</p>

<h2>Where this catches people</h2>
<p>Public property is not always recognisable as public:</p>
<ul>
  <li>City and county <strong>sidewalks, kerbs, and crosswalks</strong></li>
  <li><strong>Parks, trails, playgrounds,</strong> and public pools</li>
  <li><strong>Transit</strong> platforms, stations, buses, and light rail</li>
  <li><strong>Schools, colleges,</strong> and district facilities</li>
  <li><strong>Libraries, civic centres, courthouses,</strong> and government offices</li>
  <li><strong>Public parking structures</strong> and municipal lots</li>
  <li><strong>Roadways</strong> — defective design, missing signage, broken signals, unrepaired hazards</li>
</ul>
<p>The same applies to crashes involving a government vehicle, which is covered in our <a href="/blog/folsom-car-accident-guide/">Folsom car accident guide</a>.</p>

<h2>What a government claim contains</h2>
<p>A claim is a specific document, not a letter of complaint. It generally needs to identify the claimant and an address for notices, state the date, place, and circumstances of the loss, give a general description of the injury or damage, name the public employees involved if known, and state the amount claimed or an indication of the jurisdictional classification. Entities commonly publish their own claim form, and using it avoids avoidable disputes about sufficiency.</p>

<h2>After it is filed</h2>
<p>The entity reviews and either accepts, rejects, or does nothing. A formal rejection starts a further, much shorter period in which suit must be filed — materially shorter than the ordinary two-year statute. Where the entity does not act, a different timeline applies. The practical point is that the six-month presentation deadline is the first of several, not the only one, which is why these matters benefit from early evaluation rather than late.</p>
<p>If the six months has already passed, a late-claim application is sometimes possible on specified grounds, but relief is discretionary and narrow. It is a remedy, not a plan.</p>

<h2>The other hurdle: immunities</h2>
<p>Public entities also have statutory immunities that private defendants do not, including protections relating to recreational trails and to certain discretionary decisions. That does not make these claims unwinnable — dangerous-condition claims against public entities are brought successfully — but it does mean the facts matter with unusual precision. Exactly where the fall happened, and exactly what caused it, can determine whether an immunity applies at all.</p>

<h2>Practical steps</h2>
<ol>
  <li><strong>Identify the owner early.</strong> If there is any possibility the property is public, treat the six-month clock as running from the date of injury.</li>
  <li><strong>Photograph the defect and its surroundings</strong>, including anything that fixes the location precisely — addresses, signage, landmarks.</li>
  <li><strong>Report it</strong> to the responsible agency and keep the reference number.</li>
  <li><strong>Request records</strong> of prior complaints about the same condition; a known, unrepaired hazard is a materially stronger case.</li>
  <li><strong>Get medical care promptly</strong> and keep documentation, as described in our guide to <a href="/blog/who-pays-medical-bills-after-car-accident-california/">who pays your medical bills</a>.</li>
</ol>
<p>Because the window is short and the rules are technical, these claims are worth evaluating quickly. Our <a href="/practice-areas/slip-and-fall/">California premises liability attorneys</a> can determine whether a public entity is involved and what deadline actually applies. Start with a <a href="/case-evaluation.php">free case evaluation</a> — no fee unless we recover for you, and outcomes depend on the facts of each case.</p>
HTML,
'faqs'=>[
 ['question'=>'How long do I have to sue a city in California for an injury?','answer'=>'You must generally present a written government claim within six months of the cause of action accruing, under Government Code section 911.2. A lawsuit can only follow that process, and further shorter deadlines apply after the entity responds.'],
 ['question'=>'What counts as government property?','answer'=>'City and county sidewalks and crosswalks, parks and trails, transit platforms and vehicles, schools and colleges, libraries and civic buildings, public parking structures, and roadways including signage and signals.'],
 ['question'=>'What must a government claim include?','answer'=>'Generally the claimant\'s name and an address for notices, the date, place, and circumstances of the loss, a general description of the injury or damage, the names of public employees involved if known, and the amount claimed or jurisdictional classification. Most entities publish their own form.'],
 ['question'=>'What happens after I file a government claim?','answer'=>'The entity may accept, reject, or not respond. A formal rejection begins a further period for filing suit that is considerably shorter than the ordinary two-year statute, and a non-response triggers a different timeline.'],
 ['question'=>'Can I still file if the six months has passed?','answer'=>'A late-claim application is sometimes possible on specified grounds, but relief is discretionary and narrow. It should be treated as a remedy of last resort rather than a plan.'],
 ['question'=>'Do public entities have special defences?','answer'=>'Yes. Statutory immunities apply that private defendants do not have, including protections relating to recreational trails and certain discretionary decisions. These claims are still brought successfully, but precise facts about location and cause matter.'],
 ['question'=>'Does the six-month rule apply to a crash with a government vehicle?','answer'=>'Yes. A claim involving a public entity\'s vehicle is subject to the same government claim requirement, not the ordinary two-year statute alone.'],
 ['question'=>'How do I know who owns the property where I fell?','answer'=>'It is not always apparent from looking, which is exactly why it is worth confirming early. Where there is any realistic possibility of public ownership, the safest approach is to assume the six-month clock is running.'],
],
];

/* ───── run ───── */
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
        $pub = slot($p['slot']);
        $data = [
            'title'=>$p['title'],'slug'=>$p['slug'],'excerpt'=>$p['excerpt'],'content'=>$p['content'],
            'featured_image'=>$p['img'],'category_id'=>$catId,
            'author_name'=>$p['author'][0],'author_slug'=>$p['author'][1],
            'status'=>'published','published_at'=>$pub,
            'meta_title'=>$p['metaT'],'meta_desc'=>$p['metaD'],
        ];
        if ($has('faqs'))          { $data['faqs'] = json_encode($p['faqs'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); }
        if ($has('date_modified')) { $data['date_modified'] = $pub; }
        if ($has('og_image'))      { $data['og_image'] = $p['img']; }
        if ($has('og_image_alt'))  { $data['og_image_alt'] = $p['imgAlt']; }
        $exStmt->execute([$p['slug']]);
        $id = $exStmt->fetchColumn();
        if ($id) {
            $set = implode(', ', array_map(fn($c)=>"$c = :$c", array_keys($data)));
            $data['id'] = $id;
            $pdo->prepare("UPDATE blog_posts SET $set WHERE id = :id")->execute($data);
            $verb = "UPDATED id=$id";
        } else {
            $ks = array_keys($data);
            $pdo->prepare('INSERT INTO blog_posts ('.implode(',',$ks).') VALUES (:'.implode(',:',$ks).')')->execute($data);
            $verb = 'INSERTED id='.$pdo->lastInsertId();
        }
        $n++;
        printf("%-11s slot %-2d  %s  %s (%d words)\n", $verb, $p['slot'], substr($pub,0,10), $p['slug'], str_word_count(strip_tags($p['content'])));
    }
    echo "\nposts in batch: $n\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: '.$e->getMessage()."\n"; }
@unlink(__FILE__);
