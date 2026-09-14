<?php
/** TEMP blog publisher: who pays medical bills after a CA car accident. Self-deleting. */
if (($_GET['key'] ?? '') !== 'medbills-7j4') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Verified 2026-09-14 before publication:
 *   CIV 3040 — health-plan lien capped at the LESSER of amounts paid or 1/3 of the moneys
 *     due where the enrollee has counsel (3040(c)(2)); 1/2 without counsel (3040(d)(2));
 *     reduced by the same comparative-fault percentage (3040(e)); pro rata reduction for
 *     reasonable attorney's fees and costs under the common fund doctrine (3040(f)).
 *     Does NOT apply to workers' compensation, Medi-Cal liens, or hospital liens.
 *   Howell v. Hamilton Meats & Provisions, Inc. (2011) 52 Cal.4th 541 — past medical
 *     damages limited to the LESSER of (1) the amount paid or incurred and (2) the
 *     reasonable value of the services; the negotiated rate differential never paid is not
 *     recoverable. (Both Justia and FindLaw 403 every agent for this case, so it is cited
 *     in text with its official reporter citation rather than hyperlinked to a URL I cannot
 *     confirm resolves for readers.)
 *   Medi-Cal / Medicare — described QUALITATIVELY by design per the brief; their reduction
 *     formulas are case-specific. No hard fractions invented.
 *
 * TONE RULE (from the brief): highest-anxiety topic on the site. Calm and practical
 * throughout. No urgency language anywhere, including the CTA — no "act now", "don't
 * wait", "critical", no exclamation points. Copy below written to that constraint.
 */
$LEG = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';

$slug     = 'who-pays-medical-bills-after-car-accident-california';
$title    = 'Who Pays Your Medical Bills After a California Car Accident?';
$excerpt  = 'The at-fault driver\'s insurer does not pay your bills as they arrive — it pays once, at the end. Here is what carries you in the meantime, what gets repaid from the settlement, and the legal caps that limit those repayments.';
$metaT    = 'Who Pays Medical Bills After a California Car Accident?';
$metaD    = 'Med Pay, health insurance, medical liens, and Medi-Cal reimbursement explained — who actually pays your accident bills in California, and when.';
$featured = '/assets/images/generated/blog-medbills-featured.webp';
$aname    = 'Elena Marquez';
$aslug    = 'elena-marquez';

$content = <<<HTML
<p>The bills start arriving before the bruises fade — the ambulance, the ER, the imaging, each in its own envelope, each with its own due date. The natural assumption, the one almost everyone makes, is that these go to the driver who caused the crash.</p>
<p>The honest answer has a timeline attached, and understanding it turns a frightening stack of paper into a manageable process. This is general information, not legal advice.</p>

<h2>The correction most people need first</h2>
<p><strong>The at-fault driver's insurance company does not pay your medical bills as they arrive.</strong> It pays once, at the end, in a lump-sum settlement — and only in exchange for a signed release closing the claim. Until that day, the hospital does not bill the other driver's insurer, and that insurer would not pay if it did.</p>
<p>This is not the system malfunctioning; it is how the system is built. The settlement is meant to cover everything at once — bills already incurred, treatment still to come, lost income, and pain and suffering — which is exactly why it cannot be paid out piecemeal while the full picture is still developing.</p>
<p>So the practical question is not really "who pays?" It is <strong>"who pays now, and who gets paid back later?"</strong> That is the framework for everything below.</p>

<h2>Your four payment sources before settlement</h2>
<p>While the claim is pending, medical bills are paid through some combination of four sources:</p>
<ol>
  <li>Med Pay coverage on your own auto policy</li>
  <li>Your own health insurance</li>
  <li>A letter of protection or lien arrangement with a provider</li>
  <li>Out of pocket</li>
</ol>
<p>Here is how each behaves — when it pays, and whether it is repaid from your settlement:</p>

<table>
  <thead><tr><th>Payment source</th><th>Pays when</th><th>Repaid from your settlement?</th></tr></thead>
  <tbody>
    <tr><td>At-fault driver's insurer</td><td>Once, at the end — a lump sum against a release</td><td>It <em>is</em> the settlement</td></tr>
    <tr><td>Med Pay (your auto policy)</td><td>As bills arrive, regardless of fault</td><td>Sometimes — depends on your policy's terms</td></tr>
    <tr><td>Your health insurance</td><td>As you treat, with normal copays and deductibles</td><td>Usually — through a lien or subrogation claim, with legal caps</td></tr>
    <tr><td>Letter of protection / lien provider</td><td>Provider treats now, bills nothing yet</td><td>Yes — paid directly out of the settlement</td></tr>
    <tr><td>Out of pocket</td><td>Immediately</td><td>Recovered as part of your damages</td></tr>
  </tbody>
</table>

<h3>Med Pay: the quiet workhorse</h3>
<p>Med Pay is optional medical payments coverage on your own auto policy — commonly &#36;1,000 to &#36;10,000 — and it pays crash-related medical bills for you and your passengers regardless of who caused the collision. No fault fight, no waiting: bills get submitted and paid. It also follows you as a pedestrian or cyclist struck by a car.</p>
<p>Many people do not know whether they carry it; the declarations page answers that in one line. One note for later: some policies include a provision claiming reimbursement out of your settlement, so it is worth keeping a copy of the policy language handy for your attorney to read.</p>

<figure>
  <img src="/assets/images/generated/blog-medbills-treatment.webp" alt="A patient speaking with a doctor in a consultation room" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>Your health plan covers crash injuries like any other medical need — and pays at negotiated network rates.</figcaption>
</figure>

<h3>Your health insurance: yes, use it</h3>
<p>A surprising number of people set their health insurance aside after a car accident, reasoning that a crash is "an auto insurance matter." It is not. Your health plan covers crash injuries the same as any other injury, and using it is almost always the right move.</p>
<p>Your bills get paid at the plan's negotiated network rates, which are considerably lower than a hospital's list prices, and your accounts stay current while the injury claim proceeds at its own pace. The plan will likely have a reimbursement right at settlement — covered in the next section — but that repayment comes with meaningful legal limits, and paying network rates now generally beats owing full charges later.</p>

<h3>A letter of protection: treatment now, payment at settlement</h3>
<p>A <strong>letter of protection</strong> is a written arrangement — usually issued by your attorney — in which a medical provider agrees to treat you now and wait for payment out of your eventual settlement or judgment. Some providers work on a similar "lien basis" as their standard practice.</p>
<p>These arrangements make real treatment possible for people with no insurance or unmanageable deductibles, and they are common and legitimate. They come with trade-offs worth understanding: lien-based charges are typically higher than insurance network rates, the provider's payment is secured against your recovery, and the balances get negotiated at the end alongside everything else. It is a tool — a good one in the right situation — not a free pass.</p>

<h3>Out of pocket</h3>
<p>Copays, deductibles, prescriptions, the occasional bill that slips through — some spending lands on you directly during the claim. Keep every receipt and explanation of benefits in one folder. These amounts are not lost; they are part of your <a href="/blog/damages-in-a-california-injury-claim/">economic damages</a>, and they are counted when the claim is valued.</p>

<h2>What happens at settlement: liens and paybacks</h2>
<p>When the settlement arrives, some of the sources that carried you get repaid — that is the arrangement that made the earlier payments possible. A medical lien on a personal injury settlement sounds ominous; in practice it is a known, regulated, and negotiable line item.</p>

<figure>
  <img src="/assets/images/generated/blog-medbills-policy.webp" alt="An insurance policy document with a magnifying glass resting on it" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>What kind of plan paid your bills often matters more than negotiation — it determines which caps apply.</figcaption>
</figure>

<h3>Private health plan subrogation — and the section 3040 caps</h3>
<p>Most health plans have a contractual right to reimbursement for crash-related care they paid for. California meets that right with real limits. Under <a href="{$LEG}?lawCode=CIV&sectionNum=3040." target="_blank" rel="noopener">Civil Code section 3040</a>, a plan's lien is capped at the <strong>lesser of</strong> what the plan actually paid or <strong>one-third</strong> of the money due to you where you are represented by counsel — <strong>one-half</strong> where you are not.</p>
<p>Two further reductions are built in. If a judge, jury, or arbitrator finds you partially at fault, the lien is <strong>reduced by the same comparative fault percentage</strong> by which your recovery was reduced. And the plan must take a <strong>pro rata reduction commensurate with your reasonable attorney's fees and costs</strong>, under the common fund doctrine — meaning the plan shares in the cost of producing the settlement it is being paid from.</p>
<p>Section 3040 does not reach everything. By its own terms it does not apply to workers' compensation claims, Medi-Cal liens, or hospital liens. Separately, self-funded employer plans governed by federal ERISA law often claim exemption from these state caps — which is why one of the first quiet tasks in any case is identifying exactly what kind of plan paid your bills. Classification, more than negotiation, often determines how much a lien shrinks.</p>

<h3>Medi-Cal</h3>
<p>If Medi-Cal paid for crash-related treatment, the state has a statutory right to reimbursement from your settlement, and the claim must be reported so the department can state its amount.</p>
<p>Two calming facts belong next to that sentence. First, the law builds reductions into the state's recovery — it shares proportionately in the attorney's fees and costs that created the settlement, and its reach is limited relative to what you actually receive; it does not simply take its full number off the top. Second, the figure is reviewable and frequently negotiated, particularly where the settlement was limited by <a href="/blog/california-30-60-15-insurance-minimums/">insurance policy caps</a>. Medi-Cal reimbursement is a process with rules.</p>

<h3>Medicare</h3>
<p>Medicare works differently but follows the same logic: payments it made for crash treatment are "conditional," and federal law requires that they be reported and repaid out of a liability settlement. Medicare reduces its demand to account for its share of attorney's fees and costs, and disputed charges can be challenged.</p>
<p>The main practical point is administrative — Medicare's process runs on its own timeline and paperwork, and coordinating it is standard work in any case involving a Medicare beneficiary. Handled properly, it is routine.</p>

<h3>Provider liens and the letter-of-protection payoff</h3>
<p>Providers who treated you on a lien or letter of protection are paid directly from the settlement — that was the arrangement. Hospitals have their own statutory lien rights under California's Hospital Lien Act, with notice requirements and their own limits. These balances, like the others, are negotiated as part of finalizing the settlement, and reductions obtained here flow straight to your net recovery. The worked example in our guide to <a href="/blog/personal-injury-lawyer-fees-california/">what a personal injury lawyer costs</a> shows exactly where that line lands in the final accounting.</p>

<h2>Why your bills and your recoverable damages are different numbers</h2>
<p>Here is the piece that genuinely confuses people, including some professionals. In California, what you can recover for past medical care is <strong>not</strong> the amount the hospital billed.</p>
<p>Under <em>Howell v. Hamilton Meats &amp; Provisions, Inc.</em> (2011) 52 Cal.4th 541, an award of past medical expenses is limited to the <strong>lesser of</strong> (1) the amount paid or incurred for the treatment and (2) the reasonable value of the services rendered. Where a provider has agreed in advance to accept a lower amount as payment in full, the full billed figure is not recoverable, because the difference was never an economic loss to you.</p>
<p>A simple example. The emergency room bills &#36;24,000 for your visit. Your health plan's negotiated rate for that care is &#36;6,800, the plan pays it, and the hospital accepts it as payment in full. Your recoverable past medical damages for that visit are &#36;6,800 — not &#36;24,000. Under the follow-on case law, the &#36;24,000 figure generally cannot even be placed before a jury.</p>
<p>Why this matters practically: it changes how cases are valued, and it removes a common false hope — the idea that a large billed number automatically means a large case. It also reframes the health insurance decision from earlier: using your plan does not shrink your case; it pays your bills at the rates the law recognizes anyway.</p>
<p>What it does not change is the rest of your claim. Future care, lost income, and pain and suffering are valued on their own terms — and in serious injuries, particularly <a href="/practice-areas/brain-injuries/">brain injuries</a> with long recovery arcs, those categories usually matter far more than any past bill.</p>

<h2>Bills going to collections mid-claim</h2>
<p>A collections notice during a pending claim feels alarming. It is usually a paperwork problem with a paperwork solution, so here is the steady sequence.</p>
<p>Confirm the bill was routed correctly — a large share of "unpaid" crash bills were simply never submitted to health insurance or Med Pay, and resubmitting resolves them. For balances that are genuinely yours, call the provider's billing office, explain that an injury claim is pending, and ask for a hold or a modest payment plan; billing departments hear this daily and most have a process for it. If you are represented, your attorney's letter of representation often pauses collection activity on its own, and lien-based providers have already agreed to wait.</p>
<p>Two background facts take further pressure off. The at-fault settlement will account for these balances — a bill in collections is still just a bill, and it gets negotiated and resolved from the recovery like the rest. And the credit-reporting treatment of medical debt has softened considerably in recent years, with small balances and paid medical collections handled far more gently than they once were. None of this means ignoring envelopes; it means opening them without dread, and keeping every one in the folder.</p>

<h2>Getting treatment with no insurance</h2>
<p>No health coverage after a crash narrows the options; it does not eliminate them. The letter-of-protection and lien-basis arrangements described above exist largely for this situation — attorneys in this field maintain working relationships with physicians, imaging centers, and specialists who accept them, which is one of the quieter benefits of representation. Whether a particular provider will agree depends on the case, so treat lien-based care as something to arrange rather than something guaranteed.</p>
<p>Beyond lien care: Medi-Cal enrollment is open year-round and, for those who qualify, can reach back to cover recent months of treatment — worth exploring soon after a crash rather than months later. California law also requires hospitals to maintain financial assistance and discount policies for uninsured and high-cost patients under income thresholds; the application lives in the billing office, and asking for it is normal. And Med Pay, if it is on your auto policy, pays without regard to health coverage at all.</p>
<p>The throughline: bills from a crash you did not cause should shape your treatment decisions as little as possible, and there is nearly always a path to being seen.</p>

<h2>The bottom line</h2>
<p>The system pays for crash injuries in two phases. Your own sources — Med Pay, health insurance, lien arrangements — carry the bills during the claim, and the at-fault settlement pays once at the end, with the repayment claims against it capped, reduced, and negotiated under California law. Your billed numbers and your legal damages are different figures by design.</p>
<p>None of this requires memorizing statutes. It requires routing bills to the right place, keeping the folder, and letting the settlement do the job it exists to do. If your crash happened locally, our <a href="/blog/folsom-car-accident-guide/">Folsom car accident guide</a> covers the rest of the process.</p>
<p>This is what we untangle every day at Mason Law, P.C. — which coverage should be paying now, what the liens will really be at the end, and what a <a href="/practice-areas/car-accidents/">car accident claim</a> is worth once the <em>Howell</em> math is applied. Bring the folder of bills to a <a href="/case-evaluation.php">free case evaluation</a> and we will map it out with you, calmly and specifically. You pay nothing unless we recover for you, and every case turns on its own facts. Call <a href="tel:+19165872997">(916) 587-2997</a> when you are ready.</p>
HTML;

$faqs = json_encode([
    ['question' => 'Does the at-fault driver pay my medical bills right away?',
     'answer'   => 'No. The at-fault driver\'s insurer pays once, at the end of the claim, as a lump-sum settlement in exchange for a release. While the claim is pending, bills are handled through Med Pay, your health insurance, provider lien arrangements, or out of pocket — and the settlement squares the accounts at the end.'],
    ['question' => 'What is Med Pay in California?',
     'answer'   => 'Optional medical payments coverage on your own auto policy — commonly $1,000 to $10,000 — that pays crash-related medical bills for you and your passengers regardless of fault, usually quickly and without a fault dispute. Your declarations page shows whether you carry it.'],
    ['question' => 'Will my health insurance cover injuries from a car accident?',
     'answer'   => 'Yes. Health plans cover crash injuries like any other medical need, and using yours keeps bills paid at negotiated network rates while the claim proceeds. The plan may have a reimbursement claim at settlement, but Civil Code section 3040 caps most such claims and requires the plan to share pro rata in your attorney\'s fees and costs.'],
    ['question' => 'How much can a health plan take from my settlement in California?',
     'answer'   => 'Under Civil Code section 3040, a plan\'s lien is generally capped at the lesser of what it actually paid or one-third of the money due to you if you are represented by counsel, and one-half if you are not. The lien is also reduced by any comparative fault percentage and by a pro rata share of your attorney\'s fees under the common fund doctrine. The section does not apply to workers\' compensation, Medi-Cal, or hospital liens.'],
    ['question' => 'Does Medi-Cal take money from my settlement?',
     'answer'   => 'Medi-Cal has a legal right to reimbursement for crash-related care it paid for, and the claim must be reported. Its recovery comes with built-in reductions, including a proportionate share of the attorney\'s fees and costs that produced the settlement, and the amount is frequently reviewed and negotiated.'],
    ['question' => 'Why is my recoverable medical damage less than the amount billed?',
     'answer'   => 'Under Howell v. Hamilton Meats & Provisions, Inc. (2011) 52 Cal.4th 541, past medical damages are limited to the lesser of the amount paid or incurred and the reasonable value of the services. Where a provider accepted a negotiated rate as payment in full, the higher billed figure was never an economic loss and is not recoverable.'],
    ['question' => 'What if bills go to collections during my claim?',
     'answer'   => 'Confirm the bill was submitted to the right coverage, ask the provider for a hold or payment plan given the pending claim, and let your attorney\'s office contact the biller if you are represented. The balances are resolved from the settlement, and medical debt is treated more gently in credit reporting than it once was.'],
    ['question' => 'Can I get medical care with no health insurance after a crash?',
     'answer'   => 'Usually yes — through letter-of-protection or lien-based arrangements with providers, Medi-Cal enrollment where you qualify, hospital financial assistance programs, and Med Pay if your auto policy includes it. The right combination depends on your situation.'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

try {
    $pdo = db();
    $cols = $pdo->query('SHOW COLUMNS FROM blog_posts')->fetchAll(PDO::FETCH_COLUMN);
    $has = static fn($c) => in_array($c, $cols, true);
    $st = $pdo->prepare('SELECT id FROM blog_categories WHERE slug = ?');
    $st->execute(['insurance-claims']);
    $catId = $st->fetchColumn() ?: null;

    $data = [
        'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'content' => $content,
        'featured_image' => $featured, 'category_id' => $catId,
        'author_name' => $aname, 'author_slug' => $aslug,
        'status' => 'published', 'published_at' => date('Y-m-d H:i:s'),
        'meta_title' => $metaT, 'meta_desc' => $metaD,
    ];
    if ($has('faqs'))         { $data['faqs'] = $faqs; }
    if ($has('date_modified')){ $data['date_modified'] = date('Y-m-d H:i:s'); }
    if ($has('og_image'))     { $data['og_image'] = $featured; }
    if ($has('og_image_alt')) { $data['og_image_alt'] = 'Reviewing an insurance claim form at a desk'; }

    $ex = $pdo->prepare('SELECT id FROM blog_posts WHERE slug = ?');
    $ex->execute([$slug]);
    $id = $ex->fetchColumn();
    if ($id) {
        $set = implode(', ', array_map(fn($c) => "$c = :$c", array_keys($data)));
        $data['id'] = $id;
        $pdo->prepare("UPDATE blog_posts SET $set WHERE id = :id")->execute($data);
        echo "UPDATED id=$id\n";
    } else {
        $ks = array_keys($data);
        $pdo->prepare('INSERT INTO blog_posts (' . implode(',', $ks) . ') VALUES (:' . implode(',:', $ks) . ')')->execute($data);
        echo "INSERTED id=" . $pdo->lastInsertId() . "\n";
    }
    echo 'category=insurance-claims(' . ($catId ?: 'NULL') . ') words=' . str_word_count(strip_tags($content)) . " faqs=8\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
