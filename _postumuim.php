<?php
/** TEMP blog publisher: UM/UIM claims in California. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'umuim-5n8') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Every statutory claim verified against leginfo.legislature.ca.gov on 2026-08-25:
 *   INS 11580.2(b) — hit-and-run UM: physical contact + police report within 24 hours
 *                    + statement under oath filed with insurer within 30 days
 *   INS 11580.2(i) — no cause of action accrues unless, within 2 years: suit filed,
 *                    agreement as to amount concluded, OR arbitration formally instituted
 *   INS 11580.2(a) — UM included unless deleted by written agreement
 *   INS 11580.26   — UMPD: lesser of actual cash value or $3,500; requires actual direct
 *                    physical contact AND the uninsured owner/operator or vehicle identified;
 *                    report within 10 business days.  (NOTE: the draft attached the $3,500
 *                    figure to 11580.2, which expressly excludes the insured's property
 *                    damage in (c)(1). Corrected to cite 11580.26.)
 *   CIV 3333.4     — Prop 213: bars non-economic damages for uninsured owners/operators;
 *                    (c) DUI exception restores them; bars run to owners/operators
 *   INS 491        — rating plan shall not increase premium based on a not-at-fault accident
 *   IRC/III        — uninsured-driver rate; national figure cited, no unverified CA figure
 */
$LEG = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';
$III = 'https://www.iii.org/fact-statistic/facts-statistics-uninsured-motorists';

$slug     = 'uninsured-underinsured-motorist-claims-california';
$title    = 'How You Actually Get Paid If Hit by an Uninsured or Underinsured Driver in California';
$excerpt  = "When the driver who hit you has no insurance — or nowhere near enough — the claim that pays is usually against your own policy. Here is how UM/UIM works, the deadlines that quietly kill claims, and what Prop 213 means for your recovery.";
$metaT    = 'Hit by an Uninsured Driver in California? Your Options';
$metaD    = 'How uninsured and underinsured motorist claims work in California, what Prop 213 means for your recovery, and what to do after a hit-and-run.';
$featured = '/assets/images/generated/blog-um-uim-featured.webp';
$aname    = 'Daniel Cho';
$aslug    = 'daniel-cho';

$content = <<<HTML
<p>Here is a scenario that plays out constantly: the crash clearly was not your fault, your injuries are real, and the person who caused all of it either has no policy or a policy that will not come close to covering the damage. Nationally, <a href="{$III}" target="_blank" rel="noopener">industry research</a> puts the uninsured-driver rate at roughly one in seven, with wide variation between states — and a large share of insured drivers carry only the bare state minimum.</p>
<p>If you have been hit by an uninsured driver, the case does not end there. It changes shape, because the insurance company that ends up paying is usually <strong>your own</strong>. This is general information, not legal advice.</p>

<h2>The short answer</h2>
<p>If you carry uninsured/underinsured motorist coverage, it steps in to pay what the at-fault driver cannot — medical bills, lost income, and pain and suffering, up to your UM/UIM limits. An uninsured motorist claim in California is, in effect, a claim against your own insurer standing in the at-fault driver's shoes.</p>
<p>But here is the part that catches people: <strong>the deadlines do not come from the familiar two-year personal injury statute.</strong> They come from your policy and from <a href="{$LEG}?lawCode=INS&sectionNum=11580.2." target="_blank" rel="noopener">Insurance Code section 11580.2</a>, and they are often far shorter.</p>

<h2>UM vs. UIM: what is the difference?</h2>
<p>The two coverages sit on the same line of your policy, but they work differently — and the difference decides how much money is actually available.</p>

<table>
  <thead><tr><th>&nbsp;</th><th>Uninsured motorist (UM)</th><th>Underinsured motorist (UIM)</th></tr></thead>
  <tbody>
    <tr><td>When it applies</td><td>The at-fault driver has no insurance, or fled and was never identified</td><td>The at-fault driver has insurance, but their limits are lower than your UIM limits — and lower than your damages</td></tr>
    <tr><td>Who pays</td><td>Your own insurer</td><td>Your own insurer, after the at-fault driver's policy pays out in full</td></tr>
    <tr><td>Key condition</td><td>Uninsured status, or the hit-and-run rules below</td><td>You must first exhaust the at-fault driver's policy limits</td></tr>
    <tr><td>The catch</td><td>The two-year preservation rule</td><td>The offset — what the other insurer paid comes off your limit</td></tr>
  </tbody>
</table>

<p><strong>UM</strong> is the cleaner case: the other driver has nothing, so your insurer pays your damages up to your UM limits. It also covers you as a pedestrian or cyclist struck by an uninsured car — the coverage follows you, not your vehicle.</p>
<p><strong>UIM</strong> is the two-step version: first you collect the at-fault driver's full policy limits, then your own UIM coverage addresses the shortfall. One threshold rule surprises everyone — UIM only helps if your UIM limits are <em>higher</em> than the at-fault driver's liability limits. Match them exactly and UIM pays nothing.</p>

<h2>How the UIM offset works — with real numbers</h2>
<p>California UIM is an <strong>offset</strong> system, not a stacking system. Your UIM limit is reduced, dollar for dollar, by what the at-fault driver's insurer paid:</p>

<table>
  <thead><tr><th>The math</th><th>&nbsp;</th></tr></thead>
  <tbody>
    <tr><td>Your UIM limit</td><td>&#36;100,000</td></tr>
    <tr><td>Paid by the at-fault driver's insurer</td><td>&minus; &#36;30,000</td></tr>
    <tr><td><strong>Maximum available from your UIM coverage</strong></td><td><strong>&#36;70,000</strong></td></tr>
  </tbody>
</table>

<p>Not &#36;100,000 on top of the &#36;30,000 — &#36;70,000 from your own carrier, for a combined ceiling of &#36;100,000. Run the same math with 30/60 UIM against a 30/60 at-fault policy and you get the <strong>equal-limits trap</strong>: &#36;30,000 &minus; &#36;30,000 = &#36;0.</p>
<p>This is the single best argument for carrying UIM limits well above the state minimum — and almost nobody explains it until after a crash, when it is too late to change.</p>

<figure>
  <img src="/assets/images/generated/blog-um-uim-policy.webp" alt="An auto insurance policy document beside a laptop" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>Your declarations page shows whether you carry UM/UIM — and the limits that cap your own protection.</figcaption>
</figure>

<h2>Do you even have this coverage?</h2>
<p>Pull your <strong>declarations page</strong> — the summary sheet at the front of your policy — and look for a line reading "Uninsured Motorist Bodily Injury" or "UM/UIM," followed by two numbers like 100/300. That is your per-person and per-accident protection.</p>
<p>Most drivers have it, because section 11580.2 provides that the coverage is included unless the insurer and the named insured delete it <strong>by written agreement</strong>. If you never signed a waiver, you very likely have the coverage. If someone in your household waived it years ago to shave a few dollars off the premium, that signature is worth revisiting — particularly now that <a href="/blog/california-30-60-15-insurance-minimums/">California's minimum liability limits sit at 30/60/15</a>, an amount a single surgery can outrun.</p>
<p>Separately, some policies carry <strong>uninsured motorist property damage</strong>. Under <a href="{$LEG}?lawCode=INS&sectionNum=11580.26." target="_blank" rel="noopener">Insurance Code section 11580.26</a> that coverage pays the lesser of actual cash value or <strong>&#36;3,500</strong>, requires actual direct physical contact, requires that the uninsured owner, operator, or vehicle be <em>identified</em>, and must be reported to your insurer within 10 business days. It is a narrow benefit, and it does not cover a hit-and-run by an unidentified driver.</p>

<h2>Hit-and-run and phantom vehicles: special rules, shorter deadlines</h2>
<p>When the driver who hit you disappears, California treats the unknown driver as uninsured, which makes your UM coverage the path to recovery. But a hit-and-run UM claim carries three statutory conditions under section 11580.2(b), and they are enforced literally.</p>

<h3>1. Physical contact</h3>
<p>The statute requires that the bodily injury "arisen out of physical contact of the automobile with the insured or with an automobile that the insured is occupying." A pure "phantom" driver who runs you off the road without touching anything generally does not qualify, no matter how many witnesses saw it.</p>

<h3>2. A police report within 24 hours</h3>
<p>The accident must be reported <strong>within 24 hours</strong> to the police. This is a bright line, not a guideline. Call it in from the scene and get the report number — our <a href="/blog/how-to-get-folsom-police-accident-report/">accident report guide</a> covers how to obtain the copy afterward.</p>

<h3>3. A sworn statement within 30 days</h3>
<p>You must file with the insurer, <strong>within 30 days</strong>, a statement under oath that you were injured and setting out the supporting facts. A phone call to the claims line does not satisfy this — it has to be in writing, sworn, and on time.</p>
<p>Miss any of the three and the claim can fail before anyone argues about fault or damages.</p>

<h2>The deadline that quietly kills UM claims</h2>
<p>This one deserves its own heading. Section 11580.2(i) provides that <strong>no cause of action accrues</strong> under a UM policy unless, within <strong>two years of the accident</strong>, one of three things has happened:</p>
<ul>
  <li>A <strong>suit for bodily injury</strong> has been filed against the uninsured motorist;</li>
  <li>An <strong>agreement as to the amount due</strong> under the policy has been concluded; or</li>
  <li>The insured has <strong>formally instituted arbitration proceedings</strong>.</li>
</ul>
<p>Simply having a claim open and trading phone calls with an adjuster does <em>not</em> stop that clock. It is a trap that has quietly ended legitimate claims — the file feels active right up until the day it is worthless.</p>

<figure>
  <img src="/assets/images/generated/blog-um-uim-adjuster.webp" alt="An insurance adjuster passing paperwork across a desk to a claimant" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>In a UM claim, the friendly adjuster on the other side of the table works for your own insurer.</figcaption>
</figure>

<h2>Filing the claim: your own insurer is now the opposing party</h2>
<p>This is the mindset shift that decides these cases. You have paid premiums for years and the adjuster is pleasant, so it feels like the company is on your side. Structurally, it is not: every dollar your UM claim is worth is a dollar your insurer pays. The same tactics used against third-party claimants — minimizing injuries, blaming preexisting conditions, lowball anchoring — appear here too, in a warmer tone.</p>
<p>Two things make this trickier than an ordinary claim. First, you owe your own insurer duties you do not owe the other side: cooperation, documentation, and sometimes a recorded statement or examination under oath, plus a medical examination. You cannot simply refuse the way you might with the at-fault carrier's adjuster — but you can prepare, and you are entitled to have counsel involved. Our guide to <a href="/blog/dealing-with-insurance-adjusters-california/">dealing with insurance adjusters</a> covers the conversational traps.</p>
<p>Second, if the insurer will not pay fairly, UM/UIM disputes generally do not go to a jury — they go to <strong>binding arbitration</strong> under the policy, typically before a neutral arbitrator who decides both fault and damages. Arbitration is faster than trial and very winnable with good preparation, but it is a formal proceeding with evidence, experts, and cross-examination. It is not a phone negotiation with extra steps.</p>
<p>Your insurer also owes you a duty of good faith, and an unreasonably denied or slow-walked UM claim can create exposure beyond the policy itself.</p>

<h2>Proposition 213: the rule nobody warns uninsured drivers about</h2>
<p>Under <a href="{$LEG}?lawCode=CIV&sectionNum=3333.4." target="_blank" rel="noopener">Civil Code section 3333.4</a> — enacted by Proposition 213 in 1996 — a person who owned an uninsured vehicle involved in the accident, or who cannot establish financial responsibility, generally <strong>cannot recover non-economic damages</strong>, even if the crash was entirely the other driver's fault.</p>
<p>It is as harsh as it sounds. An uninsured driver rear-ended at a red light by a texting motorist can still recover <strong>economic</strong> damages — medical bills, lost wages, vehicle damage. But the non-economic damages that often make up the largest share of a serious injury claim are off the table. In a major-injury case, Prop 213 can erase most of the claim's value in one stroke.</p>
<p>The exceptions are narrow but real. Subdivision (c) provides that an uninsured owner injured by a driver who violates the DUI statutes is <strong>not barred</strong> from recovering non-economic damages. And because the statute's bars run to owners and operators, passengers generally fall outside them. Details matter enormously at the margins — whose car it was, who was insured under which policy, and exactly when coverage lapsed can each change the outcome. "I was uninsured, so I have no case" is the wrong conclusion to reach on your own.</p>

<h2>Will your rates go up if you file?</h2>
<p>The fear that stops people from using coverage they paid for deserves a straight answer. <a href="{$LEG}?lawCode=INS&sectionNum=491." target="_blank" rel="noopener">Insurance Code section 491</a> provides that a motor vehicle liability insurer's rating plan "shall not provide for an increase in the premium if based upon an accident in which the insured is not at fault." The statute also requires an insurer to investigate before concluding you were at fault contrary to an accident report.</p>
<p>Insurers do raise rates across whole classes of customers at renewal for reasons unrelated to your claim, and disputes over the "not at fault" determination occasionally arise. But declining to file a five- or six-figure UM claim to avoid a surcharge the law forbids is a trade no one should make.</p>

<h2>Can you sue the uninsured driver directly?</h2>
<p>Yes — and sometimes you must, since filing suit is one of the three ways to preserve the UM claim inside the two-year window. But go in clear-eyed about collection. A judgment against a driver with no insurance and no assets is often just a piece of paper.</p>
<p>It is not always worthless. California can suspend the license of a driver who leaves an injury judgment unpaid, which creates real pressure toward a payment plan, and a defendant with steady income or property can be pursued through wage garnishment and liens. In practice the UM claim is the reliable recovery and the direct suit is the pressure valve — an experienced attorney runs both tracks in parallel rather than choosing one.</p>

<h2>The bottom line</h2>
<p>When an uninsured or underinsured driver hurts you, the claim that pays is usually the one against your own policy — governed by shorter deadlines, an offset that shrinks the available money, and an insurer that is now, politely, your adversary.</p>
<p>Three numbers to remember: <strong>24 hours</strong> for the hit-and-run police report, <strong>30 days</strong> for the sworn statement, <strong>two years</strong> to sue, settle, or demand arbitration. And one decision to make long before any of it matters: carry UM/UIM limits high enough that the offset math still protects you.</p>
<p>Mason Law, P.C. handles uninsured and underinsured motorist claims throughout Folsom and the Sacramento region — including the sworn statements, exhaustion letters, and UM arbitrations that decide these cases. We will read your policy, run the offset math, calendar every deadline, and deal with your insurer. Start with a <a href="/case-evaluation.php">free case evaluation</a> — you pay nothing unless we recover for you, and every case turns on its own facts. Our <a href="/practice-areas/car-accidents/">California car accident attorneys</a> can be reached at <a href="tel:+19165872997">(916) 587-2997</a>. Not sure what to do first? Start with <a href="/blog/what-to-do-after-a-car-accident-in-california/">what to do after a car accident in California</a>.</p>
HTML;

$faqs = json_encode([
    ['question' => 'What if the driver who hit me has no insurance?',
     'answer'   => 'Your own uninsured motorist coverage takes over and pays your damages — medical bills, lost income, and pain and suffering — up to your UM limits, with your insurer standing in the at-fault driver\'s shoes. Without UM coverage, the options narrow to suing the driver personally and using health insurance or medical payments coverage.'],
    ['question' => 'Does UM coverage pay for pain and suffering?',
     'answer'   => 'Yes. Unlike medical payments coverage, UM/UIM covers the full range of injury damages including non-economic damages, which is what makes it so valuable. The exception is Proposition 213: if you were an uninsured owner or operator yourself, non-economic recovery is generally barred.'],
    ['question' => 'Will filing a UM claim raise my rates?',
     'answer'   => 'Insurance Code section 491 provides that an insurer\'s rating plan shall not increase your premium based on an accident in which you were not at fault, and a UM claim by definition involves someone else\'s fault. Rates can still move at renewal for market-wide reasons unrelated to your claim.'],
    ['question' => 'What is Proposition 213?',
     'answer'   => 'A 1996 ballot measure, now Civil Code section 3333.4, which generally bars uninsured vehicle owners and operators from recovering non-economic damages even in crashes they did not cause. Economic damages remain recoverable, the statutory bars run to owners and operators rather than passengers, and subdivision (c) restores non-economic damages where the at-fault driver violated the DUI statutes.'],
    ['question' => 'How long do I have to file a UM claim in California?',
     'answer'   => 'Notify your insurer promptly and watch the hard deadlines. Hit-and-run claims require a police report within 24 hours and a sworn statement to the insurer within 30 days. Separately, Insurance Code section 11580.2(i) requires that within two years of the accident you either file suit against the uninsured motorist, conclude an agreement on the amount due, or formally institute arbitration. Open negotiations do not stop that clock.'],
    ['question' => 'Do I need a police report for a hit-and-run claim?',
     'answer'   => 'Effectively yes. The 24-hour police report is a statutory condition of hit-and-run uninsured motorist coverage under Insurance Code section 11580.2(b), not merely good practice. Report from the scene if you can, keep the report number, and request the copy as soon as it is available.'],
    ['question' => 'What is the UIM offset in California?',
     'answer'   => 'California underinsured motorist coverage is an offset system rather than a stacking system: your UIM limit is reduced dollar for dollar by what the at-fault driver\'s insurer paid. A $100,000 UIM limit against a $30,000 payment leaves $70,000 available, for a combined ceiling of $100,000. If your UIM limits equal the at-fault driver\'s liability limits, UIM pays nothing.'],
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
    if ($has('og_image_alt')) { $data['og_image_alt'] = 'A wrecked, abandoned car after a collision'; }

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
    echo 'category=insurance-claims(' . ($catId ?: 'NULL') . ') words=' . str_word_count(strip_tags($content))
       . ' faqs=7' . "\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
