<?php
/** TEMP: expand batch 2 of thin legacy posts w/ verified authorities. Self-deleting. */
if (($_GET['key'] ?? '') !== 'expand2-4k8') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Authorities verified 2026-08-25 before publication:
 *   CCP 335.1        — 2 years, injury/death (leginfo)
 *   GOV 911.2        — 6 months injury/death vs 1 year other claims (leginfo)
 *   CIV 1431.2       — Prop 51: non-economic damages several only (leginfo)
 *   VEH 16000        — SR-1 10 days, >$1,000 / injury / death (leginfo)
 *   Li v. Yellow Cab Co. (1975) 13 Cal.3d 804 — pure comparative negligence (Justia)
 */
$LEG  = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';
$LI   = 'https://law.justia.com/cases/california/supreme-court/3d/13/804.html';

$POSTS = [];

/* --------------------------------------------------------- comparative fault */
$POSTS['how-comparative-fault-works-in-california'] = [
'excerpt' => 'California is a pure comparative fault state: being partly to blame reduces your recovery but never bars it. Here is how the percentages are set, what Proposition 51 changed, and how insurers use fault to cut claims.',
'meta_desc' => 'How comparative fault works in California: pure comparative negligence explained, how fault percentages reduce recovery, Proposition 51, and how insurers use it to lower payouts.',
'content' => <<<HTML
<p>Few rules affect the value of a California injury claim more than comparative fault — and few are more misunderstood. Being partly responsible for an accident does not end your claim in California. It changes the math. This is general information, not legal advice.</p>

<h2>California uses "pure" comparative negligence</h2>
<p>California adopted pure comparative negligence in the California Supreme Court's decision in <a href="{$LI}" target="_blank" rel="noopener"><em>Li v. Yellow Cab Co.</em> (1975) 13 Cal.3d 804</a>, which abolished the old contributory negligence rule that barred recovery entirely if the injured person was even slightly at fault.</p>
<p>Under the pure form, liability is apportioned in direct proportion to fault <strong>in all cases</strong>. The practical consequence is significant: there is no cutoff. Many states bar recovery once you are 50% or 51% at fault. California does not.</p>

<h3>How the arithmetic works</h3>
<ul>
  <li>Total <a href="/blog/damages-in-a-california-injury-claim/">damages</a> of $100,000, you are <strong>10%</strong> at fault → recover <strong>$90,000</strong></li>
  <li>Total damages of $100,000, you are <strong>50%</strong> at fault → recover <strong>$50,000</strong></li>
  <li>Total damages of $100,000, you are <strong>80%</strong> at fault → recover <strong>$20,000</strong></li>
</ul>
<p>That last line is what separates California from most of the country. Even a plaintiff who is primarily to blame retains a proportional claim.</p>

<h2>Who decides the percentages?</h2>
<p>At trial, the jury assigns fault percentages after hearing the evidence. But the overwhelming majority of claims never reach a jury — so in practice, the percentages are <strong>negotiated</strong> between the injured person and the insurer, in the shadow of what a jury might do.</p>
<p>This is why fault arguments are really money arguments. Every percentage point an adjuster can shift onto you reduces what they pay, and they are shifted through the evidence: the collision report, statements, physical damage, and witness accounts.</p>

<h2>Proposition 51 and multiple defendants</h2>
<p>When more than one party is responsible, California distinguishes between two categories of damages under <a href="{$LEG}?lawCode=CIV&sectionNum=1431.2" target="_blank" rel="noopener">Civil Code section 1431.2</a> (Proposition 51):</p>
<ul>
  <li><strong>Non-economic damages</strong> (pain and suffering, loss of enjoyment): liability is <strong>several only, not joint</strong>. Each defendant pays only the share matching their own percentage of fault.</li>
  <li><strong>Economic damages</strong> (medical bills, lost income): not limited by section 1431.2, and generally remain subject to joint and several liability principles.</li>
</ul>
<p>The distinction matters when one defendant is uninsured or insolvent: an injured person may still be able to look to a solvent defendant for the full economic losses, while non-economic damages stay tied to each defendant's own fault share.</p>

<h2>How insurers build a comparative fault argument</h2>
<p>Expect the other side to look for any of the following:</p>
<ul>
  <li>Speed, distraction, or phone use by the injured person</li>
  <li>Seat belt or helmet non-use</li>
  <li>Alleged failure to take an available evasive action</li>
  <li>Gaps or inconsistencies in medical treatment, used to argue an injury was made worse by inaction</li>
  <li>Statements made at the scene or in a recorded interview — a reflexive "I'm sorry" is routinely repurposed as an admission</li>
</ul>
<p>Being cautious with <a href="/blog/dealing-with-insurance-adjusters-california/">insurance adjusters</a> early matters, because initial characterizations of fault are hard to dislodge later.</p>

<h2>Pushing back on an unfair percentage</h2>
<p>Fault allocations are not facts handed down from above — they are conclusions drawn from evidence, and they can be contested with better evidence: scene photographs, video, vehicle damage analysis, independent witnesses, cell phone records showing the <em>other</em> driver was distracted, and, in serious cases, accident reconstruction.</p>
<p>If an insurer has assigned you a share of fault you believe is wrong, a free consultation can help you understand whether the allocation is supportable. Every case turns on its own facts.</p>
HTML,
'faqs' => [
  ['question' => 'Can I still recover if I was partly at fault in California?',
   'answer' => 'Yes. California follows pure comparative negligence, adopted in Li v. Yellow Cab Co. (1975). Your recovery is reduced by your percentage of fault but is not barred, even if you were mostly at fault.'],
  ['question' => 'What if I am more than 50% at fault?',
   'answer' => 'You can still recover in California. Unlike states with a 50% or 51% bar, California\'s pure comparative system apportions liability in direct proportion to fault in all cases, so a plaintiff who is 80% at fault may still recover 20% of their damages.'],
  ['question' => 'Who decides my percentage of fault?',
   'answer' => 'A jury assigns fault percentages at trial, but most claims settle. In practice the percentages are negotiated with the insurance company based on the evidence and what a jury would likely conclude.'],
  ['question' => 'What is Proposition 51?',
   'answer' => 'Civil Code section 1431.2, enacted by Proposition 51, makes each defendant severally liable for non-economic damages in proportion to their own fault, rather than jointly liable. Economic damages such as medical bills are not limited in the same way.'],
],
];

/* --------------------------------------------------- statute of limitations */
$POSTS['california-statute-of-limitations-injury-claims'] = [
'excerpt' => 'Most California injury claims must be filed within two years — but claims against a government entity can expire in six months. Here are the deadlines, the exceptions, and why waiting is the most common way a strong case is lost.',
'meta_desc' => 'California personal injury statute of limitations: the two-year rule under CCP 335.1, the six-month government claim deadline, key exceptions, and what happens if you miss them.',
'content' => <<<HTML
<p>Of all the ways a strong injury case can be lost, the most avoidable is running out of time. California's filing deadlines are strict, and once one passes, the merits of the case usually stop mattering. This is general information, not legal advice — deadlines vary with the facts, so specific dates should be confirmed with an attorney.</p>

<h2>The general rule: two years</h2>
<p>Under <a href="{$LEG}?lawCode=CCP&sectionNum=335.1" target="_blank" rel="noopener">California Code of Civil Procedure section 335.1</a>, an action "for assault, battery, or injury to, or for the death of, an individual caused by the wrongful act or neglect of another" must be brought <strong>within two years</strong>.</p>
<p>That two-year period covers the great majority of personal injury matters — car, motorcycle, pedestrian, bicycle, and rideshare collisions, slip and falls, dog bites, and wrongful death claims.</p>
<p>Claims for <strong>property damage</strong> are governed separately and generally carry a three-year deadline, which is why a vehicle-damage claim and an injury claim from the very same crash can expire on different dates.</p>

<h2>The exception that catches people: government claims</h2>
<p>If your claim is against a public entity — a city, county, transit agency, school district, or the state — the ordinary two-year clock is not the operative deadline. You must first present a written claim to the entity.</p>
<p>Under <a href="{$LEG}?lawCode=GOV&sectionNum=911.2" target="_blank" rel="noopener">Government Code section 911.2</a>, a claim for death or injury to person or personal property must be presented <strong>no later than six months</strong> after the cause of action accrues. Claims relating to other causes of action carry a one-year presentation deadline.</p>
<p>Six months arrives far sooner than most injured people expect. Collisions with a city bus, crashes caused by a dangerous roadway condition, and injuries on public property all fall into this category — which is why these matters need evaluation early rather than after an insurance negotiation stalls.</p>

<h2>Other situations that change the deadline</h2>
<ul>
  <li><strong>Minors.</strong> The limitations period is generally tolled during minority, so the clock typically does not run in the usual way while the injured person is under 18. Government claim requirements still demand prompt attention.</li>
  <li><strong>The discovery rule.</strong> For some injuries that could not reasonably have been discovered right away, the period may begin when the harm was discovered or should have been discovered.</li>
  <li><strong>Defendant absent from the state.</strong> Certain periods of absence may not count toward the limitations period.</li>
  <li><strong>Medical malpractice and claims against specific defendants</strong> follow their own specialized rules and shorter timelines.</li>
</ul>

<h2>Why waiting hurts long before the deadline</h2>
<p>Even inside the limitations period, delay quietly damages claims. Surveillance footage is overwritten in days. Witnesses move and memories fade. Vehicles are repaired or salvaged. Skid marks and roadway conditions change. And gaps in medical treatment give insurers a ready argument that the injury was not serious.</p>
<p>Preserving evidence early is often what separates a well-documented claim from a contested one — a point that also shapes how <a href="/blog/how-long-does-a-california-injury-case-take/">long a case ultimately takes</a>.</p>

<h2>What happens if the deadline passes</h2>
<p>If a lawsuit is filed after the limitations period, the defense will typically move to dismiss it, and courts generally enforce these deadlines strictly. Exceptions exist, but they are narrow and fact-specific — not something to count on.</p>
<p>If you are unsure which deadline applies to your situation, it costs nothing to ask. Our <a href="/practice-areas/car-accidents/">California injury attorneys</a> can identify the operative dates and whether a government claim requirement is in play.</p>
HTML,
'faqs' => [
  ['question' => 'How long do I have to file a personal injury claim in California?',
   'answer' => 'Generally two years from the date of injury under Code of Civil Procedure section 335.1. Property damage claims are typically subject to a three-year deadline, so two claims arising from the same crash can expire on different dates.'],
  ['question' => 'What is the deadline for a claim against a city or government agency?',
   'answer' => 'Government Code section 911.2 requires a written claim for death or personal injury to be presented no later than six months after the cause of action accrues. Other causes of action generally carry a one-year presentation deadline.'],
  ['question' => 'Does the deadline change if the injured person is a child?',
   'answer' => 'The limitations period is generally tolled while the injured person is a minor, so the ordinary clock typically does not run in the usual way before age 18. Government claim requirements still call for prompt action.'],
  ['question' => 'What happens if I miss the statute of limitations?',
   'answer' => 'A late lawsuit is usually subject to dismissal, and California courts generally enforce these deadlines strictly regardless of how strong the underlying case is. Narrow, fact-specific exceptions exist but should not be relied upon.'],
],
];

/* ------------------------------------------------------------------ damages */
$POSTS['damages-in-a-california-injury-claim'] = [
'excerpt' => 'California injury damages fall into economic and non-economic categories — and they are proven in very different ways. Here is what each covers, how future losses are valued, and what Proposition 51 means when several parties are at fault.',
'meta_desc' => 'What damages can you recover in a California injury claim? Economic and non-economic damages explained, how future losses are valued, punitive damages, and Proposition 51.',
'content' => <<<HTML
<p>"Damages" is the legal word for what a claim is actually worth — the money intended to put an injured person back in the position they would have been in. California sorts them into distinct categories, and understanding the difference explains why two claims with similar medical bills can settle for very different amounts. This is general information, not legal advice.</p>

<h2>Economic damages — the documented losses</h2>
<p>These are the losses with a paper trail. They are proven with records, and they are usually the foundation everything else is built on.</p>
<ul>
  <li><strong>Medical expenses</strong> — emergency treatment, hospitalization, surgery, imaging, medication, physical therapy, and assistive devices</li>
  <li><strong>Future medical care</strong> — treatment your providers expect will still be required, including revision surgery or long-term therapy</li>
  <li><strong>Lost income</strong> — wages, salary, tips, commissions, and self-employment earnings missed during recovery</li>
  <li><strong>Lost earning capacity</strong> — the reduction in what you are able to earn going forward if the injury limits your work</li>
  <li><strong>Property damage</strong> — vehicle repair or fair market value, plus rental costs</li>
  <li><strong>Out-of-pocket costs</strong> — mileage to appointments, home modifications, and paid household help</li>
</ul>

<h2>Non-economic damages — the human losses</h2>
<p>These compensate for harms that are real but have no invoice: physical pain, emotional distress, disfigurement and scarring, anxiety and sleep disruption, loss of enjoyment of life, and the strain an injury places on a marriage or family relationship.</p>
<p>Because there is no receipt, this is the most heavily contested part of most claims. California law does not prescribe a formula. In practice, negotiations often start from informal reference points — a multiplier applied to economic damages, or a per-day value across the recovery period — but these are negotiating conventions, not legal rules a jury must follow. Severity, permanence, and the quality of the documentation drive the outcome.</p>
<p>Our guide to <a href="/blog/how-much-is-my-california-car-accident-case-worth/">what a California car accident case is worth</a> walks through how these pieces combine.</p>

<h2>Punitive damages — a narrow category</h2>
<p>Punitive damages are not compensation; they exist to punish and deter, and California permits them only where the defendant's conduct rises to oppression, fraud, or malice. They are uncommon in ordinary negligence cases, though conduct such as drunk driving or a deliberate cover-up can put them in play.</p>

<h2>When several parties share the blame</h2>
<p>Where more than one defendant is responsible, <a href="{$LEG}?lawCode=CIV&sectionNum=1431.2" target="_blank" rel="noopener">Civil Code section 1431.2</a> (Proposition 51) draws a sharp line. Liability for <strong>non-economic</strong> damages is several only — each defendant is responsible solely for the share matching their own percentage of fault. Economic damages are not limited by that section and generally remain subject to joint and several liability principles.</p>

<h2>Two things that reduce what you actually receive</h2>
<p><strong>Comparative fault.</strong> Under California's pure <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> rule, your recovery is reduced by your own percentage of responsibility.</p>
<p><strong>Insurance limits.</strong> A claim can be fully documented and still collide with the ceiling of the at-fault party's policy. California's minimum liability limits are low, which is why identifying every available policy — including your own uninsured/underinsured motorist coverage — is part of valuing a claim honestly.</p>

<h2>Proving damages well</h2>
<p>Consistent medical treatment, complete records, employer documentation of lost income, and a contemporaneous account of how the injury affects daily life all make a measurable difference. Gaps and thin documentation, by contrast, are precisely what insurers use to discount a claim. Outcomes always depend on the specific facts.</p>
HTML,
'faqs' => [
  ['question' => 'What damages can I recover in a California injury claim?',
   'answer' => 'California allows recovery of economic damages such as medical expenses, future care, lost income, lost earning capacity, and property damage, plus non-economic damages such as pain and suffering, disfigurement, and loss of enjoyment of life. Punitive damages are available only in narrow circumstances.'],
  ['question' => 'How is pain and suffering calculated in California?',
   'answer' => 'California law does not set a formula. Negotiations often begin from informal reference points such as a multiplier of economic damages or a per-day figure, but these are conventions rather than legal rules. Severity, permanence, and documentation drive the result.'],
  ['question' => 'Are there caps on damages in California injury cases?',
   'answer' => 'There is no general cap on economic or non-economic damages in ordinary personal injury claims. Specialized rules apply in certain categories, such as medical malpractice, and Proposition 51 limits each defendant\'s share of non-economic damages to their own percentage of fault.'],
  ['question' => 'What are punitive damages?',
   'answer' => 'Punitive damages punish and deter rather than compensate. California permits them only where the defendant acted with oppression, fraud, or malice, so they are uncommon in ordinary negligence cases.'],
],
];

/* ------------------------------------------------------------------ run */
try {
    $pdo = db();
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
