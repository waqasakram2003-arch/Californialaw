<?php
/**
 * seed-blog-slots-9-30.php — ADDITIVE, IDEMPOTENT, REVERSIBLE.
 * Completes the 30-post drip campaign: slots 9–30 (22 posts), one every 2 days.
 * Slots 1–8 (truck + slip-&-fall) are already live. Native drip via future
 * published_at (public queries filter `published_at <= NOW()`), no cron.
 *
 *   CLI :  php seed-blog-slots-9-30.php            (add --rollback to undo)
 *   Web :  copy into web root, open ?key=mlx-blog930-7d3f9a  (&rollback=1 to undo); then DELETE
 *
 * Spec per post (plan CONTENT-PLAN-30.md): 40–55 word answer block under H1,
 * ~700–900 word body, 8 FAQs (→FAQPage), >=1 table, bidirectional cluster links,
 * real-attorney byline (shannon-ramos/elena-marquez/daniel-cho), verified citations.
 *
 * §12: every statute verified against leginfo before publishing. §2/§26: no invented
 * stats, case results, or firm-specific claims. CA Bar: no guarantee/best/win language.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
    if (($_GET['key'] ?? '') !== 'mlx-blog930-7d3f9a') { http_response_code(403); exit("Forbidden.\n"); }
    $argv = isset($_GET['rollback']) ? ['web', '--rollback'] : ['web'];
}
$__cands = [__DIR__.'/../public_html/includes/db.php', __DIR__.'/includes/db.php', __DIR__.'/../includes/db.php'];
$__db = null; foreach ($__cands as $c) { if (is_file($c)) { $__db = $c; break; } }
if ($__db === null) { fwrite(STDERR, "db.php not found from ".__DIR__."\n"); exit(1); }
require_once $__db;

$ROLLBACK = in_array('--rollback', $argv ?? [], true);

/* Slot schedule: slot N publishes 2026-10-04 + (N-1)*2 days, 09:00. */
$SLOT1 = new DateTimeImmutable('2026-10-04 09:00:00');
function slot_date(DateTimeImmutable $s1, int $slot): string {
    return $s1->add(new DateInterval('P' . (($slot - 1) * 2) . 'D'))->format('Y-m-d H:i:s');
}

/* leginfo helper for citations (external; post-processor adds target/rel). */
function leg(string $code, string $sec): string {
    return 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode='.$code.'&sectionNum='.$sec.'.';
}

$posts = [];
function p(int $slot, string $title, string $slug, string $cat, string $author, string $img,
           string $excerpt, string $metaDesc, string $content, string $faqs): array {
    return compact('slot','title','slug','cat','author','img','excerpt','metaDesc','content','faqs');
}

/* ===================== WRONGFUL DEATH (slots 9–12) ===================== */

$posts[] = p(9,
 'Who Can File a Wrongful Death Claim in California?',
 'who-can-file-wrongful-death-california','wrongful-death','shannon-ramos','blog-damages.webp',
 'California limits who may bring a wrongful death claim to a specific order of family members. Here is who qualifies and why it matters.',
 'Who can file a wrongful death claim in California? The family members the law allows, in priority order, and what the claim can recover.',
 <<<'C'
<p><strong>In California, only specific people may file a wrongful death claim.</strong> Code of Civil Procedure section 377.60 lists them in order: first the surviving spouse or domestic partner and children; if none, those who would inherit under intestate succession; and certain financial dependents such as putative spouses, stepchildren, or parents.</p>
<h2>The statutory order of eligibility</h2>
<p>California does not let just any grieving person sue. The right belongs to a defined class under <a href="{$l377}">Code of Civil Procedure &sect; 377.60</a>. The claim generally runs first to the closest family, then outward only if no one in the prior tier exists.</p>
<table>
<thead><tr><th>Tier</th><th>Who may file</th></tr></thead>
<tbody>
<tr><td>1</td><td>Surviving spouse or domestic partner, and children (or grandchildren if a child has died)</td></tr>
<tr><td>2</td><td>If none above: those entitled to the decedent's property by intestate succession</td></tr>
<tr><td>3</td><td>Certain dependents: a putative spouse and their children, stepchildren, or parents who depended on the decedent</td></tr>
</tbody>
</table>
<h2>Why eligibility is often contested</h2>
<p>Blended families, estrangement, and domestic partnerships can make "who qualifies" genuinely complex. Getting it right early matters, because the wrong plaintiff can derail an otherwise strong <a href="/practice-areas/wrongful-death/">wrongful death case</a>.</p>
<blockquote class="pullquote">The question is not who loved the person most — it is who the statute names.</blockquote>
<h2>One action for the whole family</h2>
<p>California treats wrongful death as a single, joint action. Eligible family members generally join one case rather than filing separately, and a court apportions any recovery among them. This is separate from a <a href="/blog/survival-action-vs-wrongful-death-california/">survival action</a>, which belongs to the decedent's estate.</p>
<h2>Deadlines apply</h2>
<p>A wrongful death claim must be filed within the deadline — generally two years, but far shorter when a government entity is involved. See our guide to <a href="/blog/wrongful-death-statute-of-limitations-california/">wrongful death deadlines</a>. A free, confidential consultation can help a family understand who should bring the claim.</p>
C
 ,
 <<<'F'
[
 {"question":"Who can file a wrongful death claim in California?","answer":"Under Code of Civil Procedure section 377.60, first the surviving spouse or domestic partner and children; if none, those who would inherit under intestate succession; and certain dependents such as putative spouses, stepchildren, or dependent parents."},
 {"question":"Can siblings file a wrongful death claim?","answer":"Usually only if they would inherit by intestate succession because there is no surviving spouse, domestic partner, or children, and they are not otherwise excluded. It depends on the family structure."},
 {"question":"Can parents sue for an adult child's death?","answer":"Parents may file if there is no surviving spouse, domestic partner, or children, or if they were financially dependent on the decedent, depending on the circumstances."},
 {"question":"Do all eligible family members file separately?","answer":"No. California treats wrongful death as a single joint action; eligible relatives generally join one case, and the court apportions any recovery among them."},
 {"question":"Is a wrongful death claim the same as a survival action?","answer":"No. A wrongful death claim belongs to the family for their own losses; a survival action belongs to the decedent's estate for claims the decedent had before death."},
 {"question":"How long do I have to file?","answer":"Generally two years, but a claim involving a government entity can require a formal claim within six months. Deadlines are strict, so act promptly."},
 {"question":"What if the family disagrees about who should file?","answer":"Because it is one joint action, disputes over participation and apportionment can arise. A court can resolve apportionment, and legal guidance early helps avoid procedural problems."},
 {"question":"Does a criminal case have to happen first?","answer":"No. A wrongful death claim is a separate civil matter and does not depend on whether anyone is criminally charged or convicted."}
]
F);

$posts[] = p(10,
 'Wrongful Death vs. Survival Actions in California',
 'survival-action-vs-wrongful-death-california','wrongful-death','elena-marquez','blog-damages.webp',
 'These two claims often arise from the same death but compensate different losses. Here is how they differ in California.',
 'Wrongful death vs. survival action in California: who brings each claim, what each recovers, and the 2026 change to survival damages.',
 <<<'C'
<p><strong>A wrongful death claim and a survival action are different.</strong> The wrongful death claim belongs to the family for <em>their</em> losses after a death. A survival action belongs to the decedent's estate and pursues the claim the decedent personally had for the period between injury and death.</p>
<h2>Two claims, two owners</h2>
<p>They frequently arise from the same event but are legally distinct. The <a href="/blog/who-can-file-wrongful-death-california/">wrongful death claim</a> compensates eligible relatives; the survival action is brought by the estate's personal representative or successor in interest under California's survival statute.</p>
<table>
<thead><tr><th></th><th>Wrongful death</th><th>Survival action</th></tr></thead>
<tbody>
<tr><td>Who brings it</td><td>Eligible family members</td><td>The decedent's estate</td></tr>
<tr><td>Compensates</td><td>The family's losses (support, companionship)</td><td>The decedent's own losses before death</td></tr>
<tr><td>Typical damages</td><td>Lost financial support, funeral costs, loss of companionship</td><td>Pre-death medical bills, lost earnings, and (where available) punitive damages</td></tr>
</tbody>
</table>
<h2>The 2026 change to survival damages</h2>
<p>From 2022 through 2025, California temporarily allowed a survival action to recover the decedent's pre-death pain, suffering, or disfigurement under <a href="{$l37734}">Code of Civil Procedure &sect; 377.34</a>, as amended by Senate Bill 447. That provision was scheduled to <strong>sunset on January 1, 2026</strong>. Because the rule in this area changed recently and may change again, the exact damages available in a survival action should be confirmed for the current year.</p>
<blockquote class="pullquote">Same tragedy, two claims — one for what the family lost, one for what the person endured.</blockquote>
<h2>Why both often matter</h2>
<p>Pursued together, the two claims capture a fuller picture of the harm. A free consultation can help a family understand which claims fit their situation and how any recovery is handled.</p>
C
 ,
 <<<'F'
[
 {"question":"What is the difference between wrongful death and a survival action?","answer":"Wrongful death compensates the family for their own losses after a death. A survival action is brought by the estate for the claim the decedent personally had between injury and death."},
 {"question":"Who brings a survival action?","answer":"The decedent's personal representative or successor in interest, on behalf of the estate."},
 {"question":"Can a survival action recover pain and suffering?","answer":"California temporarily allowed it for 2022–2025 under CCP 377.34 (SB 447), but that provision was set to sunset January 1, 2026. Confirm the current rule, because it changed recently."},
 {"question":"Can both claims be filed at once?","answer":"Yes. The same death often supports both a wrongful death claim and a survival action, and they are frequently pursued together."},
 {"question":"What does a survival action typically recover?","answer":"The decedent's pre-death economic losses such as medical bills and lost earnings, and punitive damages where the conduct supports them, subject to current law."},
 {"question":"Who gets the money from each claim?","answer":"Wrongful death damages go to the eligible family members; survival-action damages go to the estate and pass according to the will or intestate succession."},
 {"question":"Do the same deadlines apply?","answer":"Both are subject to filing deadlines, and claims against a government entity can require notice within six months. Confirm the applicable deadline promptly."},
 {"question":"Which claim should my family bring?","answer":"Often both, because they compensate different losses. A consultation can clarify which claims fit the specific facts."}
]
F);

$posts[] = p(11,
 'What Damages Are Available in a Wrongful Death Case?',
 'wrongful-death-damages-california','your-rights','elena-marquez','blog-damages.webp',
 'Wrongful death damages in California cover both financial losses and the harder-to-measure loss of a loved one. Here is what may be recoverable.',
 'What damages can a California wrongful death case recover? Financial support, funeral costs, and loss of love, companionship, and guidance.',
 <<<'C'
<p><strong>California wrongful death damages fall into two groups:</strong> measurable financial losses — lost support the decedent would have provided, funeral and burial costs, and the value of lost household services — and non-economic losses, such as the loss of the decedent's love, companionship, comfort, and guidance.</p>
<h2>Economic losses</h2>
<p>These are the quantifiable losses the family suffers: the financial support the decedent would reasonably have contributed, the loss of gifts or benefits, funeral and burial expenses, and the reasonable value of household services the decedent provided. Proving them often involves earnings history and economic analysis.</p>
<table>
<thead><tr><th>Category</th><th>Examples</th></tr></thead>
<tbody>
<tr><td>Financial support</td><td>Lost income and benefits the decedent would have provided</td></tr>
<tr><td>Services</td><td>Household work, childcare, and other services now lost</td></tr>
<tr><td>Expenses</td><td>Funeral and burial costs</td></tr>
<tr><td>Non-economic</td><td>Loss of love, companionship, moral support, and guidance</td></tr>
</tbody>
</table>
<h2>Non-economic losses</h2>
<p>California recognizes that losing a parent, spouse, or child is a profound loss beyond money. There is no fixed formula; the loss of companionship, affection, and guidance is weighed on the specific circumstances, much like <a href="/blog/damages-in-a-california-injury-claim/">non-economic damages</a> in an injury case.</p>
<blockquote class="pullquote">The law cannot replace a person — it can account for what their absence costs the family.</blockquote>
<h2>What is generally not recovered</h2>
<p>A wrongful death claim compensates the survivors' losses, not the family's own grief as a separate item, and the decedent's own pre-death pain is handled through a <a href="/blog/survival-action-vs-wrongful-death-california/">survival action</a>. A free consultation can help identify every category that fits your family's situation.</p>
C
 ,
 <<<'F'
[
 {"question":"What damages can a wrongful death claim recover in California?","answer":"Economic losses (lost financial support, lost household services, funeral and burial costs) and non-economic losses (loss of the decedent's love, companionship, comfort, and guidance)."},
 {"question":"Can we recover funeral and burial costs?","answer":"Yes. Reasonable funeral and burial expenses are recoverable economic damages in a California wrongful death claim."},
 {"question":"Is there compensation for loss of companionship?","answer":"Yes. California allows non-economic damages for the loss of the decedent's love, companionship, comfort, care, and moral support."},
 {"question":"Is grief itself compensable?","answer":"Wrongful death damages focus on the survivors' losses such as support and companionship rather than grief as a separate standalone item."},
 {"question":"How are non-economic damages calculated?","answer":"There is no fixed formula. The value depends on the relationship and circumstances, supported by testimony about the role the decedent played in the family."},
 {"question":"Can the estate recover the decedent's lost future earnings?","answer":"The family's lost support is part of wrongful death; the decedent's own pre-death losses go through a separate survival action. Which applies depends on the facts and current law."},
 {"question":"Are punitive damages available?","answer":"Punitive damages are generally pursued through the estate's survival action where the conduct supports them, not through the wrongful death claim itself."},
 {"question":"How is the recovery divided among family members?","answer":"Wrongful death is one joint action; a court apportions the recovery among eligible family members based on their respective losses."}
]
F);

$posts[] = p(12,
 'Wrongful Death Deadlines and the Government Claim Trap',
 'wrongful-death-statute-of-limitations-california','legal-tips','daniel-cho','blog-statute.webp',
 'Miss the deadline and a wrongful death claim can be lost entirely. Government cases carry a much shorter trap most families never see coming.',
 'What is the wrongful death filing deadline in California? The two-year rule and the six-month government-claim trap families must not miss.',
 <<<'C'
<p><strong>Most California wrongful death claims must be filed within two years of the death</strong> under <a href="{$l3351}">Code of Civil Procedure &sect; 335.1</a>. But when a government entity may be responsible, a formal claim is generally due within <strong>six months</strong> under the Government Claims Act — a trap that can end a case before the family even realizes a deadline existed.</p>
<h2>The general two-year rule</h2>
<p>For most wrongful death claims, the clock runs two years from the date of death. That is often later than the date of the underlying injury, which matters when a loved one survives for a time before passing.</p>
<h2>The six-month government trap</h2>
<p>If a city, county, state agency, public hospital, or public employee may be at fault — a crash with a government vehicle, a dangerous public road, or a public facility — a written claim must usually be presented to the entity within six months under <a href="{$l9112}">Government Code &sect; 911.2</a>. Only after it is denied can a lawsuit proceed.</p>
<table>
<thead><tr><th>Situation</th><th>Typical deadline</th></tr></thead>
<tbody>
<tr><td>Most wrongful death claims</td><td>2 years from the date of death</td></tr>
<tr><td>Claim against a government entity</td><td>6-month government claim, then suit</td></tr>
<tr><td>Minors among the claimants</td><td>Special rules may apply; confirm promptly</td></tr>
</tbody>
</table>
<blockquote class="pullquote">The deadline that ends the most cases is the one families never knew was running.</blockquote>
<h2>Do not wait to find out which applies</h2>
<p>Because the six-month window is so short and easy to miss, the safest step is to confirm the deadline right away — the same lesson as our <a href="/blog/california-statute-of-limitations-injury-claims/">statute of limitations guide</a>. A free, confidential consultation can identify which deadline applies to a <a href="/practice-areas/wrongful-death/">wrongful death</a> case.</p>
C
 ,
 <<<'F'
[
 {"question":"What is the deadline to file a wrongful death claim in California?","answer":"Generally two years from the date of death under Code of Civil Procedure section 335.1, but shorter deadlines apply when a government entity is involved."},
 {"question":"Is the deadline measured from the injury or the death?","answer":"For wrongful death, the two-year period generally runs from the date of death, which can be later than the date of the original injury."},
 {"question":"What is the government-claim deadline?","answer":"When a public entity may be at fault, a written claim is generally due within six months under Government Code section 911.2 before a lawsuit can be filed."},
 {"question":"What happens if I miss the deadline?","answer":"Missing the statute of limitations usually bars the claim entirely, which is why confirming the correct deadline early is critical."},
 {"question":"Can the deadline ever be extended?","answer":"Limited exceptions exist, including certain rules for minors, but they are narrow. Do not assume one applies without legal confirmation."},
 {"question":"Does a government claim replace the lawsuit?","answer":"No. The government claim is a required first step; if it is denied, a lawsuit may then be filed within the applicable time limit."},
 {"question":"What if several family members have claims?","answer":"Wrongful death is one joint action, but the deadline still applies to the case as a whole, so prompt action protects everyone's interests."},
 {"question":"How soon should I speak with an attorney?","answer":"As soon as possible, because the six-month government deadline is easy to miss and can bar an otherwise strong claim."}
]
F);

/* ===================== WORKPLACE INJURIES (slots 13–16) ===================== */

$posts[] = p(13,
 "Workers' Comp or Personal Injury? In California, Sometimes Both",
 'workers-comp-vs-personal-injury-california','your-rights','daniel-cho','blog-damages.webp',
 'A work injury is not always just a workers’ comp claim. California law sometimes allows a separate personal injury case too.',
 'Workers’ comp vs. personal injury in California: why a job injury can support both, and when a third-party claim adds recovery comp cannot.',
 <<<'C'
<p><strong>A California work injury usually goes through workers' compensation, but not always only that.</strong> Workers' comp is generally the exclusive remedy against your employer, yet when someone <em>other than</em> your employer caused the injury, you may also bring a separate personal injury claim for losses comp does not pay.</p>
<h2>Workers' comp: no-fault, but limited</h2>
<p>Under <a href="{$l3600}">Labor Code &sect; 3600</a>, workers' compensation is generally the exclusive remedy against your employer. You do not have to prove fault, but the benefits are limited — medical care and partial wage replacement, with no payment for pain and suffering.</p>
<table>
<thead><tr><th></th><th>Workers' comp</th><th>Personal injury (third party)</th></tr></thead>
<tbody>
<tr><td>Fault required?</td><td>No</td><td>Yes</td></tr>
<tr><td>Against</td><td>Your employer</td><td>A non-employer who caused the harm</td></tr>
<tr><td>Pain & suffering?</td><td>No</td><td>Yes</td></tr>
<tr><td>Full lost wages?</td><td>Partial</td><td>Yes, where proven</td></tr>
</tbody>
</table>
<h2>When a personal injury claim is also possible</h2>
<p>If a third party — a negligent driver, an equipment manufacturer, a subcontractor, or a property owner — caused your on-the-job injury, a separate <a href="/blog/third-party-work-injury-claim-california/">third-party claim</a> may recover what comp does not, including full lost earnings and pain and suffering.</p>
<blockquote class="pullquote">Comp pays quickly but partially; a third-party claim can fill the gap comp leaves behind.</blockquote>
<h2>Pursuing both at once</h2>
<p>The two can proceed together, though coordination matters because an employer's comp insurer may seek reimbursement from a third-party recovery. A free consultation can help identify every avenue for a <a href="/practice-areas/workplace-injuries/">workplace injury</a>.</p>
C
 ,
 <<<'F'
[
 {"question":"Can I file both workers' comp and a personal injury claim?","answer":"Sometimes. Workers' comp is generally the exclusive remedy against your employer, but if a third party caused the injury you may also bring a separate personal injury claim."},
 {"question":"Why file a personal injury claim if I have workers' comp?","answer":"Workers' comp does not pay for pain and suffering and only replaces part of lost wages. A third-party claim can recover those additional losses."},
 {"question":"Do I have to prove fault for workers' comp?","answer":"No. Workers' comp is a no-fault system, so you generally receive benefits regardless of who caused the injury, within its limits."},
 {"question":"Who counts as a third party?","answer":"Someone other than your employer or co-employee — for example a negligent driver, a product manufacturer, a subcontractor, or a property owner."},
 {"question":"Will my employer's insurer take part of my recovery?","answer":"Often the comp insurer can seek reimbursement from a third-party recovery for benefits it paid, so coordination between the claims matters."},
 {"question":"Can I sue my employer directly?","answer":"Usually not, because of the exclusive-remedy rule, though narrow exceptions exist. See our guide on suing an employer directly."},
 {"question":"What does workers' comp cover?","answer":"Generally medical treatment for the injury and partial wage replacement, plus certain disability benefits, but not pain and suffering."},
 {"question":"How long do I have to act?","answer":"Workers' comp and personal injury claims have different deadlines, and both can be short, so it is wise to confirm them promptly."}
]
F);

$posts[] = p(14,
 'Third-Party Work Injury Claims in California',
 'third-party-work-injury-claim-california','your-rights','daniel-cho','blog-damages.webp',
 'When someone other than your employer causes a job injury, California lets you pursue them directly — for losses workers’ comp will not pay.',
 'What is a third-party work injury claim in California? Suing a non-employer for full damages when workers’ comp falls short.',
 <<<'C'
<p><strong>A third-party claim lets an injured worker sue someone other than their employer</strong> for an on-the-job injury. Because workers' comp does not pay for pain and suffering or full lost wages, this separate claim — preserved by <a href="{$l3852}">Labor Code &sect; 3852</a> — is often where the real recovery is.</p>
<h2>What makes a claim "third party"</h2>
<p>The at-fault party is not your employer or a co-worker. Common examples: a driver who hits you while you are working, a defective machine or tool, a negligent subcontractor on a shared site, or an unsafe property you were sent to.</p>
<table>
<thead><tr><th>Scenario</th><th>Possible third party</th></tr></thead>
<tbody>
<tr><td>Crash while driving for work</td><td>The at-fault motorist</td></tr>
<tr><td>Injured by a machine</td><td>The equipment manufacturer</td></tr>
<tr><td>Hurt on a multi-employer site</td><td>A subcontractor or general contractor</td></tr>
<tr><td>Fall at a delivery location</td><td>The property owner</td></tr>
</tbody>
</table>
<h2>What it adds beyond comp</h2>
<p>A third-party claim can recover full lost earnings, future losses, and pain and suffering — the categories <a href="/blog/workers-comp-vs-personal-injury-california/">workers' comp</a> leaves out. It requires proving fault, unlike no-fault comp.</p>
<blockquote class="pullquote">Workers' comp asks "were you working?" A third-party claim asks "who actually caused this?"</blockquote>
<h2>Coordinating the two claims</h2>
<p>When both exist, the employer's comp insurer may assert a lien or credit against the third-party recovery for benefits it paid. Handling that correctly protects your net result. A free consultation can map out a <a href="/practice-areas/workplace-injuries/">workplace injury</a> that involves a third party.</p>
C
 ,
 <<<'F'
[
 {"question":"What is a third-party work injury claim?","answer":"A personal injury claim against someone other than your employer — such as a negligent driver, manufacturer, subcontractor, or property owner — who caused your on-the-job injury."},
 {"question":"Why pursue a third-party claim?","answer":"It can recover damages workers' comp does not pay, including pain and suffering and full lost earnings, because it is a fault-based personal injury claim."},
 {"question":"Can I file a third-party claim and keep workers' comp?","answer":"Yes. They are separate. Labor Code section 3852 preserves the right to pursue a liable third party in addition to comp benefits."},
 {"question":"Will the comp insurer take part of the recovery?","answer":"Often it can seek reimbursement through a lien or credit for benefits it paid. Proper coordination is important to protect your net recovery."},
 {"question":"Who are common third parties?","answer":"At-fault drivers, equipment manufacturers, subcontractors or general contractors on shared sites, and owners of unsafe property you were sent to."},
 {"question":"Do I have to prove fault?","answer":"Yes. Unlike no-fault workers' comp, a third-party claim requires proving the third party was negligent or otherwise liable."},
 {"question":"What if my co-worker caused it?","answer":"Claims against co-employees are generally limited by the workers' comp system, though narrow exceptions exist depending on the facts."},
 {"question":"How long do I have to file?","answer":"A third-party personal injury claim has its own deadline, generally two years, separate from the workers' comp timeline. Confirm both promptly."}
]
F);

$posts[] = p(15,
 'Construction Site Accidents in California',
 'construction-accident-claims-california','your-rights','daniel-cho','blog-car-accident.webp',
 'Construction sites involve many companies and heavy risk. California law may allow claims beyond workers’ comp when another party is at fault.',
 'Injured on a California construction site? How multi-employer sites create third-party claims beyond workers’ comp, and who may be liable.',
 <<<'C'
<p><strong>Construction injuries often support more than a workers' comp claim.</strong> Because job sites involve many companies — general contractors, subcontractors, equipment suppliers — an injured worker frequently has a <a href="/blog/third-party-work-injury-claim-california/">third-party claim</a> against a non-employer whose negligence caused the harm, on top of comp benefits.</p>
<h2>Why construction sites are different</h2>
<p>Multiple employers share one site, each responsible for different work and hazards. When a subcontractor, general contractor, property owner, or equipment maker creates a danger that injures a worker employed by someone else, that party can be liable in a personal injury claim.</p>
<table>
<thead><tr><th>Hazard</th><th>Potentially responsible party</th></tr></thead>
<tbody>
<tr><td>Falls from height / scaffolding</td><td>Scaffold provider, GC, site controller</td></tr>
<tr><td>Defective tools or machinery</td><td>Equipment manufacturer or renter</td></tr>
<tr><td>Falling objects</td><td>Another trade or subcontractor</td></tr>
<tr><td>Unsafe premises conditions</td><td>Property owner or site controller</td></tr>
</tbody>
</table>
<h2>Safety rules and evidence</h2>
<p>California workplace-safety regulations set standards for construction work, and a violation can be powerful evidence of negligence. Site photos, safety records, and witness accounts matter — and they can disappear quickly as work continues.</p>
<blockquote class="pullquote">On a busy site, the evidence of what went wrong is often gone by the next shift.</blockquote>
<h2>Comp plus a third-party claim</h2>
<p>An injured worker may receive comp benefits while also pursuing a liable third party for pain and suffering and full lost earnings. A free consultation can help sort out a <a href="/practice-areas/workplace-injuries/">construction injury</a> with several possible defendants.</p>
C
 ,
 <<<'F'
[
 {"question":"Can I sue after a construction accident if I have workers' comp?","answer":"Often yes. If a party other than your employer — a subcontractor, general contractor, property owner, or equipment maker — caused the injury, you may bring a third-party claim in addition to comp."},
 {"question":"Who can be liable on a construction site?","answer":"Depending on the facts, a general contractor, subcontractor, equipment manufacturer or renter, or the property owner, among others."},
 {"question":"Do safety violations matter?","answer":"Yes. California construction-safety standards are important, and a documented violation can be strong evidence of negligence."},
 {"question":"What if I am an undocumented worker?","answer":"California law generally extends workplace injury protections regardless of immigration status. The facts of the injury control the claim."},
 {"question":"What evidence should be preserved?","answer":"Photos of the hazard and scene, safety and inspection records, equipment information, and witness contacts — gathered quickly before the site changes."},
 {"question":"What damages can a third-party construction claim recover?","answer":"Full lost earnings, future losses, and pain and suffering, which workers' comp does not pay."},
 {"question":"Will pursuing a third party affect my comp benefits?","answer":"You can pursue both, but the comp insurer may seek reimbursement from a third-party recovery, so the claims should be coordinated."},
 {"question":"How long do I have to file a construction injury claim?","answer":"A third-party personal injury claim generally has a two-year deadline, separate from comp timelines. Confirm both as early as possible."}
]
F);

$posts[] = p(16,
 'When Can You Sue Your Employer Directly in California?',
 'can-i-sue-my-employer-california','legal-tips','daniel-cho','blog-statute.webp',
 'Workers’ comp usually blocks suing your employer — but California recognizes narrow exceptions. Here is what they are.',
 'Can you sue your employer directly in California? The exclusive-remedy rule and the narrow exceptions that may allow a lawsuit.',
 <<<'C'
<p><strong>Usually you cannot sue your employer for a work injury in California</strong> — workers' compensation is the exclusive remedy under <a href="{$l3600}">Labor Code &sect; 3600</a>. But narrow exceptions exist, and in those situations a direct claim against the employer may be possible.</p>
<h2>The exclusive-remedy rule</h2>
<p>In exchange for no-fault benefits, workers generally give up the right to sue their employer in civil court. This trade-off is the foundation of the comp system, and courts apply it broadly.</p>
<h2>Recognized exceptions</h2>
<p>California recognizes limited exceptions where a civil claim against an employer may proceed. These are narrow and fact-specific, such as certain physical assaults, fraudulent concealment of a known injury, defective products the employer also makes for sale, and situations where the employer carries no comp insurance.</p>
<table>
<thead><tr><th>Possible exception</th><th>General idea</th></tr></thead>
<tbody>
<tr><td>Uninsured employer</td><td>Employer illegally carried no comp insurance</td></tr>
<tr><td>Fraudulent concealment</td><td>Employer concealed a known injury, worsening it</td></tr>
<tr><td>Dual capacity / defective product</td><td>Employer also acted as a product maker to the public</td></tr>
<tr><td>Certain intentional conduct</td><td>Narrow categories such as a physical assault</td></tr>
</tbody>
</table>
<blockquote class="pullquote">The exceptions are real but narrow — most roads back to the employer are closed by design.</blockquote>
<h2>The more common path: a third party</h2>
<p>Because employer exceptions are limited, the more frequent route to full damages is a <a href="/blog/third-party-work-injury-claim-california/">third-party claim</a> against a non-employer. A free consultation can tell you which path, if any, fits your <a href="/practice-areas/workplace-injuries/">workplace injury</a>.</p>
C
 ,
 <<<'F'
[
 {"question":"Can I sue my employer for a work injury in California?","answer":"Usually not, because workers' comp is the exclusive remedy under Labor Code section 3600. Narrow, fact-specific exceptions can allow a direct claim."},
 {"question":"What are the exceptions to the exclusive-remedy rule?","answer":"Limited situations such as an uninsured employer, fraudulent concealment of a known injury, certain dual-capacity product claims, and narrow categories of intentional conduct."},
 {"question":"What if my employer had no workers' comp insurance?","answer":"If an employer illegally failed to carry comp insurance, you may be able to pursue a civil claim in addition to other remedies. The facts control."},
 {"question":"Does an intentional assault count?","answer":"Certain physical assaults can fall outside the exclusive-remedy bar, but the category is narrow and depends on the specific circumstances."},
 {"question":"Is suing the employer the usual route to full damages?","answer":"No. Because employer exceptions are limited, the more common route is a third-party claim against a non-employer who caused the injury."},
 {"question":"Can I lose my job for filing a claim?","answer":"California law prohibits retaliation for pursuing a legitimate workers' comp claim. Retaliation can create a separate claim."},
 {"question":"How do I know which exception applies?","answer":"These exceptions are technical and fact-specific; a legal evaluation of the circumstances is the reliable way to know."},
 {"question":"What is the deadline?","answer":"Deadlines vary by the type of claim and can be short. Confirm the applicable timeline as soon as possible."}
]
F);

/* ===================== MOTORCYCLE (slots 17–19) ===================== */

$posts[] = p(17,
 'California Motorcycle Accident Claims: What Riders Face',
 'motorcycle-accident-claims-california','auto-accidents','daniel-cho','blog-lane-splitting.webp',
 'Motorcyclists face serious injuries and unfair bias after a crash. Here is what California riders should know about their claims.',
 'California motorcycle accident claims: rider rights, the bias riders face, lane-splitting, and how comparative fault affects recovery.',
 <<<'C'
<p><strong>Motorcyclists have the same right to recover as any other road user in California</strong> — but they face a steeper fight. Injuries are often severe, and insurers frequently lean on bias that "riders are reckless." Lane splitting is legal here, and fault is decided on evidence, not stereotypes.</p>
<h2>The bias riders face</h2>
<p>Adjusters and jurors sometimes assume a rider was speeding or careless. That assumption can quietly reduce a claim. Countering it with real evidence — camera footage, witness accounts, and scene data — is central to a <a href="/practice-areas/motorcycle-accidents/">motorcycle accident</a> claim.</p>
<h2>Lane splitting is legal</h2>
<p>California is unique: lane splitting is expressly allowed under <a href="{$l21658}">Vehicle Code &sect; 21658.1</a>, which authorizes CHP safety guidance but sets no fixed speed limit. A rider who was lane splitting is not automatically at fault, as our <a href="/blog/california-lane-splitting-laws/">lane-splitting guide</a> explains.</p>
<table>
<thead><tr><th>Myth</th><th>Reality</th></tr></thead>
<tbody>
<tr><td>Lane splitting is illegal</td><td>It is legal in California under VEH 21658.1</td></tr>
<tr><td>A rider hurt while splitting is at fault</td><td>Fault depends on the evidence, not the maneuver</td></tr>
<tr><td>Not wearing gear ends the claim</td><td>It may be argued, but comparative fault can still allow recovery</td></tr>
</tbody>
</table>
<h2>Comparative fault still applies</h2>
<p>Even if a rider shares some blame, California's <a href="/blog/how-comparative-fault-works-in-california/">pure comparative fault</a> rule allows recovery reduced by their share. A free consultation can help a rider understand the real strength of a claim.</p>
C
 ,
 <<<'F'
[
 {"question":"Do motorcyclists have the same rights as drivers in California?","answer":"Yes. Riders have the same right to use the road and to recover for injuries caused by another's negligence, subject to comparative fault."},
 {"question":"Is lane splitting legal in California?","answer":"Yes. Vehicle Code section 21658.1 expressly permits lane splitting and authorizes CHP guidelines; it does not set a fixed speed limit."},
 {"question":"Will I be blamed just for riding a motorcycle?","answer":"Insurers sometimes rely on bias, but fault must be based on evidence. Camera footage, witnesses, and scene data help counter unfair assumptions."},
 {"question":"Can I recover if I was partly at fault?","answer":"Yes. California's pure comparative fault rule lets an injured rider recover, reduced by their percentage of responsibility."},
 {"question":"Does not wearing a helmet affect my claim?","answer":"California requires helmets, and the defense may raise it, but comparative fault can still allow recovery depending on how the injuries relate to the helmet."},
 {"question":"Why are motorcycle injuries often severe?","answer":"Riders lack the protection of an enclosed vehicle, so crashes frequently cause fractures, road rash, and head or spinal injuries."},
 {"question":"What evidence helps a motorcycle claim?","answer":"Photos, traffic and business camera footage, witness statements, the bike and gear, and medical records documenting the injuries."},
 {"question":"How long do I have to file?","answer":"Generally two years from the crash for a personal injury claim, with shorter deadlines if a government entity is involved."}
]
F);

$posts[] = p(18,
 'California Motorcycle Helmet Law and Your Injury Claim',
 'california-motorcycle-helmet-law-claim','auto-accidents','daniel-cho','blog-lane-splitting.webp',
 'California requires helmets for every rider. Here is what the law says and how helmet use can affect a motorcycle injury claim.',
 'California motorcycle helmet law explained: VEH 27803 requires DOT helmets for all riders, and how helmet use affects an injury claim.',
 <<<'C'
<p><strong>California requires every motorcycle rider and passenger to wear a DOT-compliant helmet.</strong> Under <a href="{$l27803}">Vehicle Code &sect; 27803</a>, both the driver and any passenger must wear a safety helmet meeting U.S. DOT standards, regardless of age. It is a universal helmet law.</p>
<h2>What the law requires</h2>
<p>The helmet must be certified to the federal safety standard (FMVSS 218). This applies on motorcycles, motor-driven cycles, and motorized bicycles. Unlike some states, California does not limit the requirement to younger riders.</p>
<h2>How helmet use affects a claim</h2>
<p>Wearing a required helmet removes a common defense argument. If a rider was not wearing one, the insurer may argue that comparative fault reduces recovery — but typically only to the extent the lack of a helmet actually contributed to the specific injuries.</p>
<table>
<thead><tr><th>Scenario</th><th>Effect on the claim</th></tr></thead>
<tbody>
<tr><td>Helmet worn, head injury</td><td>No helmet-based defense; claim proceeds on the merits</td></tr>
<tr><td>No helmet, leg/arm injury</td><td>Helmet likely irrelevant to unrelated injuries</td></tr>
<tr><td>No helmet, head injury</td><td>Insurer may argue comparative fault for that harm</td></tr>
</tbody>
</table>
<blockquote class="pullquote">A missing helmet matters only where it actually changed the injury — not as a blanket excuse.</blockquote>
<h2>Fault is still comparative</h2>
<p>Even where a helmet argument applies, California's <a href="/blog/how-comparative-fault-works-in-california/">pure comparative fault</a> rule may still allow recovery, reduced by the rider's share. For the broader picture, see our <a href="/blog/motorcycle-accident-claims-california/">motorcycle claims guide</a>. A free consultation can explain how helmet use affects your situation.</p>
C
 ,
 <<<'F'
[
 {"question":"Does California require motorcycle helmets?","answer":"Yes. Vehicle Code section 27803 requires both the driver and any passenger to wear a U.S. DOT-compliant safety helmet, regardless of age."},
 {"question":"What kind of helmet is required?","answer":"A helmet certified to the federal safety standard (FMVSS 218). Novelty helmets that do not meet DOT standards do not satisfy the law."},
 {"question":"Can I still recover if I wasn't wearing a helmet?","answer":"Often yes, under comparative fault, but the insurer may argue a reduction to the extent the lack of a helmet actually contributed to the specific injuries."},
 {"question":"Does no helmet matter for a broken leg?","answer":"Generally not, because a helmet protects the head. It is typically relevant only to head or brain injuries it could have reduced."},
 {"question":"Does the helmet law apply to passengers?","answer":"Yes. Both the operator and any passenger must wear a compliant helmet."},
 {"question":"Is California a universal helmet state?","answer":"Yes. The requirement applies to all riders regardless of age, unlike states that only require helmets for minors."},
 {"question":"Can a helmet argument defeat my whole claim?","answer":"No. At most it may reduce recovery for injuries the helmet could have prevented; comparative fault still allows recovery of the rest."},
 {"question":"How long do I have to file a claim?","answer":"Generally two years from the crash, with shorter deadlines if a government entity is involved."}
]
F);

$posts[] = p(19,
 'Left-Turn Motorcycle Crashes in California',
 'left-turn-motorcycle-accident-california','auto-accidents','daniel-cho','blog-lane-splitting.webp',
 'The most common and dangerous motorcycle crash is a car turning left across a rider’s path. Here is how fault usually works.',
 'Left-turn motorcycle accidents in California: why cars turning left cause so many crashes and how right-of-way shapes fault.',
 <<<'C'
<p><strong>The most common serious motorcycle crash is a car turning left across a rider's path.</strong> A driver turning left generally must yield to oncoming traffic, so when they turn into an approaching motorcycle, the turning driver is frequently at fault — though the evidence always controls.</p>
<h2>Why these crashes happen</h2>
<p>Drivers often "look but fail to see" a motorcycle because it is narrower than a car, or they misjudge its speed and distance. The result is a left turn directly into the rider's path at an intersection or driveway.</p>
<h2>Right of way and fault</h2>
<p>A left-turning driver must yield to oncoming vehicles close enough to be a hazard. When they do not, they are commonly found at fault. But a rider who was speeding, ran a light, or was not visible may share responsibility under <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a>.</p>
<table>
<thead><tr><th>Factor</th><th>Points toward</th></tr></thead>
<tbody>
<tr><td>Driver turned across oncoming rider</td><td>Driver fault (failure to yield)</td></tr>
<tr><td>Rider well over the speed limit</td><td>Shared fault</td></tr>
<tr><td>Rider ran a red or was hidden</td><td>Shared fault</td></tr>
</tbody>
</table>
<blockquote class="pullquote">"I didn't see the motorcycle" is an explanation, not a defense to failing to yield.</blockquote>
<h2>Proving what happened</h2>
<p>Intersection and business cameras, witness accounts, skid and debris patterns, and the point of impact help reconstruct a left-turn crash. For the bigger picture, see our <a href="/blog/motorcycle-accident-claims-california/">motorcycle claims guide</a> and <a href="/practice-areas/motorcycle-accidents/">motorcycle accidents</a> page. A free consultation can help a rider understand fault in their crash.</p>
C
 ,
 <<<'F'
[
 {"question":"Who is at fault in a left-turn motorcycle crash?","answer":"Usually the driver turning left, because they must yield to oncoming traffic. But fault depends on the evidence, and a rider can share it if speeding or running a signal."},
 {"question":"Why do drivers hit motorcycles when turning left?","answer":"Motorcycles are narrower and easy to overlook, and drivers often misjudge a bike's speed and distance, turning across its path."},
 {"question":"Is 'I didn't see the motorcycle' a defense?","answer":"No. Failing to see an oncoming motorcycle generally does not excuse a driver's duty to yield before turning left."},
 {"question":"Can the rider be partly at fault?","answer":"Yes, if the rider was speeding, ran a signal, or was not visible. California's comparative fault rule reduces recovery by the rider's share."},
 {"question":"What evidence helps?","answer":"Intersection and business camera footage, witness statements, skid and debris patterns, and the vehicles' points of impact."},
 {"question":"Are these crashes usually serious?","answer":"Often yes. A left-turn impact exposes the rider to direct contact, frequently causing fractures and head or spinal injuries."},
 {"question":"Does a green light settle fault?","answer":"Not always. A driver with a solid green (not a protected arrow) must still yield to oncoming traffic before turning left."},
 {"question":"How long do I have to file?","answer":"Generally two years from the crash, with shorter deadlines when a government entity is involved."}
]
F);

/* ===================== PEDESTRIAN & BICYCLE (slots 20–22) ===================== */

$posts[] = p(20,
 'Hit by a Car While Walking in California: Your Claim',
 'pedestrian-accident-claim-california','auto-accidents','elena-marquez','blog-pedestrian.webp',
 'Pedestrians have strong protections under California law. Here is what an injured pedestrian’s claim involves and how fault is decided.',
 'Hit by a car while walking in California? Pedestrian right-of-way, how fault works, and the coverage that may pay your injuries.',
 <<<'C'
<p><strong>Pedestrians have strong right-of-way protections in California.</strong> Drivers must yield to people in marked and unmarked crosswalks and use due care at all times under <a href="{$l21950}">Vehicle Code &sect; 21950</a>. When a driver fails to do so, an injured pedestrian generally has a claim for their losses.</p>
<h2>The crosswalk rule</h2>
<p>Drivers must yield to pedestrians crossing in a marked crosswalk or an unmarked crosswalk at an intersection, and must always use reasonable care for people on foot. Pedestrians, in turn, must not suddenly leave a curb into a vehicle's path, as our <a href="/blog/pedestrian-right-of-way-laws-california/">right-of-way guide</a> explains.</p>
<h2>How fault is decided</h2>
<p>Drivers sometimes claim the pedestrian "came out of nowhere." California's <a href="/blog/how-comparative-fault-works-in-california/">pure comparative fault</a> rule still allows an injured pedestrian to recover even if partly at fault, reduced by their share.</p>
<table>
<thead><tr><th>Situation</th><th>Typical fault direction</th></tr></thead>
<tbody>
<tr><td>Driver failed to yield in a crosswalk</td><td>Driver fault</td></tr>
<tr><td>Pedestrian darted mid-block</td><td>Shared or pedestrian fault</td></tr>
<tr><td>Both inattentive</td><td>Split by comparative fault</td></tr>
</tbody>
</table>
<h2>Which coverage pays</h2>
<p>Pedestrian injuries are frequently severe. The at-fault driver's liability coverage usually applies, and if the driver is uninsured or fled, your own <a href="/blog/uninsured-underinsured-motorist-claims-california/">uninsured motorist coverage</a> may help. A free consultation can explain your options after a <a href="/practice-areas/pedestrian-accidents/">pedestrian accident</a>.</p>
C
 ,
 <<<'F'
[
 {"question":"Does the pedestrian always have the right of way in California?","answer":"Drivers must yield to pedestrians in marked and unmarked crosswalks and use due care, but pedestrians also must not suddenly step into a vehicle's path. Both owe reasonable care."},
 {"question":"Can I recover if I jaywalked?","answer":"Possibly. California's comparative fault rule may still allow recovery reduced by your share of responsibility, depending on the facts."},
 {"question":"What if the driver says I came out of nowhere?","answer":"That is a common defense. Evidence such as camera footage, witnesses, and the point of impact helps establish what actually happened."},
 {"question":"Who pays if the driver has no insurance?","answer":"Your own uninsured motorist coverage may apply, which also helps in hit-and-run pedestrian cases where the driver fled."},
 {"question":"What should I do after being hit while walking?","answer":"Get medical care, call police, photograph the scene if you can, and gather witness information. Prompt care also documents your injuries."},
 {"question":"Are pedestrian injuries usually serious?","answer":"Often yes, because a pedestrian has no protection against a vehicle. Head, spine, and orthopedic injuries are common."},
 {"question":"What does the driver's duty of care mean?","answer":"Drivers must watch for pedestrians and exercise reasonable care at all times, even outside marked crosswalks."},
 {"question":"How long do I have to file?","answer":"Generally two years from the incident, with shorter deadlines if a government entity may be responsible."}
]
F);

$posts[] = p(21,
 'California Bicycle Accident Claims: Rights of Injured Cyclists',
 'bicycle-accident-claims-california','auto-accidents','daniel-cho','blog-pedestrian.webp',
 'Cyclists have the same road rights as drivers in California. Learn how injury claims work when a driver hits a bicyclist.',
 'What are an injured cyclist’s rights in California? Driver duties, safe-passing, and how comparative fault applies to bicycle claims.',
 <<<'C'
<p><strong>Bicyclists in California generally have the same rights and duties as drivers.</strong> Motorists must share the road and pass at a safe distance, and when a driver's negligence injures a cyclist, the rider usually has a claim — even if the driver argues the cyclist was partly to blame.</p>
<h2>Cyclists belong on the road</h2>
<p>Bicycles are vehicles for most purposes under California law, so riders have the right to the road and drivers must use reasonable care around them, including giving a safe berth when passing.</p>
<h2>Common crash patterns</h2>
<p>Many crashes involve a driver turning across a bike lane, opening a door into a cyclist's path ("dooring"), or failing to yield at an intersection. Each raises its own fault questions under <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a>.</p>
<table>
<thead><tr><th>Scenario</th><th>Often at fault</th></tr></thead>
<tbody>
<tr><td>Right hook across a bike lane</td><td>Turning driver</td></tr>
<tr><td>Dooring</td><td>Person who opened the door</td></tr>
<tr><td>Failure to yield at intersection</td><td>Driver who failed to yield</td></tr>
</tbody>
</table>
<blockquote class="pullquote">A cyclist's vulnerability does not lower a driver's duty to watch for and yield to them.</blockquote>
<h2>After a bicycle crash</h2>
<p>Get prompt medical care — head injuries can be serious even with a helmet — and preserve the bike, gear, and photos. For e-bikes specifically, see our <a href="/blog/e-bike-accident-liability-california/">e-bike liability guide</a>; for serious head trauma, our <a href="/practice-areas/brain-injuries/">brain injury</a> page. A free consultation can explain a cyclist's options.</p>
C
 ,
 <<<'F'
[
 {"question":"Do cyclists have the same rights as drivers in California?","answer":"Generally yes. Bicycles are treated as vehicles for most purposes, so riders have the right to the road and motorists must share it and pass safely."},
 {"question":"Can I recover if I wasn't wearing a helmet?","answer":"Helmet use can be raised by the defense, but California's comparative fault rule may still allow recovery depending on the injuries and facts."},
 {"question":"What is 'dooring'?","answer":"When someone opens a car door into the path of a passing cyclist. The person who opened the door is commonly at fault for a resulting crash."},
 {"question":"What are common causes of bike crashes?","answer":"Drivers turning across bike lanes, opening doors into a rider's path, and failing to yield at intersections are frequent causes."},
 {"question":"What should I do after being hit on my bike?","answer":"Get medical care, call police, photograph the scene, and preserve your bicycle and gear as evidence."},
 {"question":"Does the driver's insurance cover a cyclist?","answer":"Yes, an at-fault driver's auto liability coverage generally applies to a cyclist they injure; your own UM coverage may help if the driver is uninsured."},
 {"question":"Are e-bike claims the same?","answer":"They are similar, but e-bike class and speed can add issues. See our dedicated e-bike liability guide for the details."},
 {"question":"How long do I have to file?","answer":"Generally two years from the crash, with shorter deadlines if a government entity may be responsible."}
]
F);

$posts[] = p(22,
 'Children Injured in Crosswalks and School Zones in California',
 'child-pedestrian-accident-california','your-rights','elena-marquez','blog-pedestrian.webp',
 'Child pedestrian cases carry special protections — including extra time to file. Here is what California parents should know.',
 'Child hit in a California crosswalk or school zone? Driver duties, how fault is judged for kids, and the extra filing time for minors.',
 <<<'C'
<p><strong>Children hit while walking are treated with special care under California law.</strong> Drivers owe heightened attention in school zones and near children, and the deadline to file is paused while the injured child is a minor — the statute of limitations is tolled under <a href="{$l352}">Code of Civil Procedure &sect; 352</a>.</p>
<h2>Heightened driver duty near children</h2>
<p>Drivers must always yield to pedestrians in crosswalks under <a href="{$l21950}">Vehicle Code &sect; 21950</a>, and must use extra caution where children are present, such as school zones, parks, and residential streets. Children can be unpredictable, and the law expects drivers to account for that.</p>
<h2>Fault is judged differently for kids</h2>
<p>A young child is generally not held to an adult's standard of care. That affects how <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> is applied, often placing more responsibility on the driver.</p>
<table>
<thead><tr><th>Issue</th><th>For a child pedestrian</th></tr></thead>
<tbody>
<tr><td>Standard of care</td><td>Measured against a child of similar age, not an adult</td></tr>
<tr><td>Filing deadline</td><td>Generally tolled during minority (CCP 352)</td></tr>
<tr><td>Settlement</td><td>Court approval typically required for a minor</td></tr>
</tbody>
</table>
<blockquote class="pullquote">The law does not expect a seven-year-old to judge traffic like a grown driver should.</blockquote>
<h2>Extra time, special handling</h2>
<p>Because the deadline is tolled while the child is a minor, families often have more time than they expect — but earlier action preserves evidence. A minor's settlement also generally needs court approval. A free, confidential consultation can guide a family through a <a href="/practice-areas/pedestrian-accidents/">child pedestrian</a> claim.</p>
C
 ,
 <<<'F'
[
 {"question":"Is there more time to file a claim for an injured child?","answer":"Usually yes. Under Code of Civil Procedure section 352, the statute of limitations is generally tolled while the injured person is a minor, extending the time to file."},
 {"question":"Are children held to the same fault standard as adults?","answer":"No. A child is generally judged against the care expected of a child of similar age and experience, not an adult, which affects comparative fault."},
 {"question":"Do drivers owe extra care near schools?","answer":"Yes. Drivers must yield to pedestrians in crosswalks and use heightened caution in school zones and where children are likely to be present."},
 {"question":"Does a child's settlement need court approval?","answer":"Generally yes. Settlements for minors typically require court approval to protect the child's interests and preserve the funds."},
 {"question":"Should we still act quickly even with more time?","answer":"Yes. Evidence like camera footage and witness memories fade, so prompt action protects the claim even when the deadline is tolled."},
 {"question":"Who can bring the claim for a child?","answer":"A parent or guardian generally brings the claim on the child's behalf, often as a guardian ad litem in litigation."},
 {"question":"What if the child was partly at fault?","answer":"Comparative fault still applies, but a child's conduct is judged by a child's standard, which often reduces the share assigned to them."},
 {"question":"What should I do after my child is hit?","answer":"Get immediate medical care, call police, document the scene, and gather witness information."}
]
F);

/* ===================== BRAIN & CATASTROPHIC (slots 23–25) ===================== */

$posts[] = p(23,
 'Spinal Cord Injuries After a California Crash',
 'spinal-cord-injury-claim-california','your-rights','elena-marquez','blog-brain-injury.webp',
 'Spinal cord injuries can change life permanently and carry lifelong costs. Here is why these California claims demand future-focused proof.',
 'How are spinal cord injury claims handled in California? Why lifetime medical costs and future care drive the value of these serious cases.',
 <<<'C'
<p><strong>A spinal cord injury often means lifelong care, and the claim has to look decades ahead.</strong> California law allows recovery for future medical treatment and lost earning capacity under its damages rules (<a href="{$l3333}">Civil Code &sect; 3333</a>) — not just the bills already received.</p>
<h2>Why these cases are different</h2>
<p>Unlike injuries that heal in months, spinal cord injuries can require lifelong treatment, adaptive equipment, home modifications, and personal care. Valuing the claim means projecting a lifetime of need, not totaling today's expenses.</p>
<h2>Future damages and life-care planning</h2>
<p>Proving future needs usually involves treating physicians and life-care planners who project care over time, plus economists who value lost earning capacity. These experts translate a lifelong impact into documented figures, as with other <a href="/blog/catastrophic-injury-claim-california/">catastrophic injuries</a>.</p>
<table>
<thead><tr><th>Loss</th><th>Often projected over a lifetime</th></tr></thead>
<tbody>
<tr><td>Medical & surgical care</td><td>Ongoing treatment and future procedures</td></tr>
<tr><td>Equipment & home</td><td>Wheelchairs, modifications, assistive tech</td></tr>
<tr><td>Attendant care</td><td>Daily personal assistance</td></tr>
<tr><td>Lost earning capacity</td><td>Reduced or ended ability to work</td></tr>
</tbody>
</table>
<blockquote class="pullquote">In a spinal cord case, the bills already paid are only the first chapter of the loss.</blockquote>
<h2>Protecting the full value</h2>
<p>Because insurers may push to settle before the long-term picture is clear, waiting until the medical condition stabilizes often protects the claim. A free consultation can explain how future <a href="/practice-areas/brain-injuries/">catastrophic injury</a> damages are established.</p>
C
 ,
 <<<'F'
[
 {"question":"Why are spinal cord injury claims so complex?","answer":"Because the costs often last a lifetime — ongoing care, equipment, and lost earning ability — so valuing the claim requires projecting future needs, not just past bills."},
 {"question":"Can I recover future medical costs?","answer":"Yes. California law allows recovery for future medical care and lost earning capacity, typically supported by medical experts and life-care planners."},
 {"question":"What is a life-care plan?","answer":"A detailed projection of the future treatment, equipment, and personal care a seriously injured person will need, used to value the claim."},
 {"question":"Should I settle quickly?","answer":"Settling before the long-term picture is clear can be risky, because future needs may not yet be fully understood."},
 {"question":"What non-economic damages may apply?","answer":"California recognizes damages for pain, suffering, and loss of enjoyment of life, which can be significant in a spinal cord case."},
 {"question":"Who proves the future costs?","answer":"Treating physicians, life-care planners, and economists who project care needs and lost earning capacity over the person's lifetime."},
 {"question":"Does paralysis change the claim?","answer":"The extent of the injury drives the future-care projection; more severe impairment generally means greater lifetime needs to document."},
 {"question":"How long do I have to file?","answer":"Generally two years from the injury, with shorter deadlines if a government entity may be responsible."}
]
F);

$posts[] = p(24,
 'Proving a Mild Traumatic Brain Injury When Scans Look Normal',
 'mild-traumatic-brain-injury-claim-california','your-rights','elena-marquez','blog-brain-injury.webp',
 'A "mild" TBI can be life-altering — and hard to prove when imaging looks normal. Here is how these California claims are built.',
 'How do you prove a mild TBI in California when CT and MRI look normal? The evidence that documents a concussion insurers try to dismiss.',
 <<<'C'
<p><strong>A mild traumatic brain injury can be serious even when a CT or MRI looks normal.</strong> Standard scans often miss the kind of damage a concussion causes, so these claims are built on documented symptoms, neurocognitive testing, and the accounts of people who knew the person before and after.</p>
<h2>Why "mild" is misleading</h2>
<p>"Mild" describes the initial presentation, not the impact. A concussion can cause lasting headaches, memory and concentration problems, mood changes, and fatigue. The CDC notes that symptoms may appear hours or days after the injury, which is why early, consistent documentation matters.</p>
<h2>Why normal scans do not end the claim</h2>
<p>CT and MRI are good at finding bleeds and fractures but can miss the microscopic and functional changes of a concussion. Insurers often point to a "normal" scan to dismiss a real injury, as our <a href="/blog/understanding-traumatic-brain-injuries/">brain injury overview</a> explains.</p>
<table>
<thead><tr><th>Evidence</th><th>What it shows</th></tr></thead>
<tbody>
<tr><td>Symptom records</td><td>Consistent headaches, memory, mood, sleep issues</td></tr>
<tr><td>Neurocognitive testing</td><td>Measured deficits in attention and memory</td></tr>
<tr><td>Before/after testimony</td><td>How the person changed after the injury</td></tr>
<tr><td>Specialized imaging</td><td>Advanced studies where appropriate</td></tr>
</tbody>
</table>
<blockquote class="pullquote">A clean CT scan rules out a bleed — it does not rule out a brain injury.</blockquote>
<h2>Building the proof</h2>
<p>Prompt medical care, honest symptom reporting, follow-up with the right specialists, and testimony from family and coworkers all help. See our <a href="/practice-areas/brain-injuries/">brain injury</a> page for more. A free, confidential consultation can explain how a mild TBI claim is documented.</p>
C
 ,
 <<<'F'
[
 {"question":"Can I have a brain injury if my scan was normal?","answer":"Yes. CT and MRI are good at finding bleeds and fractures but can miss the functional damage of a concussion, so a normal scan does not rule out a mild TBI."},
 {"question":"How is a mild TBI proven?","answer":"Through consistent symptom documentation, neurocognitive testing, testimony about changes before and after the injury, and specialized imaging where appropriate."},
 {"question":"Why do symptoms appear later?","answer":"The CDC notes concussion symptoms can emerge hours or days after the injury, which is why prompt care and ongoing documentation matter."},
 {"question":"What are common mild TBI symptoms?","answer":"Headaches, memory and concentration problems, mood changes, dizziness, light sensitivity, and sleep disturbance, among others."},
 {"question":"Why do insurers dismiss these injuries?","answer":"Because scans often look normal, insurers may argue nothing is wrong. Objective testing and credible before-and-after accounts counter that."},
 {"question":"Does 'mild' mean minor?","answer":"No. 'Mild' describes the initial classification, not the long-term impact, which can be significant and lasting."},
 {"question":"What should I do after a head injury?","answer":"Get prompt medical care, report every symptom honestly, follow up with the recommended specialists, and keep a symptom record."},
 {"question":"How long do I have to file?","answer":"Generally two years from the injury, with shorter deadlines if a government entity may be responsible."}
]
F);

$posts[] = p(25,
 'Catastrophic Injury Claims in California: Life-Care and Future Costs',
 'catastrophic-injury-claim-california','your-rights','elena-marquez','blog-brain-injury.webp',
 'Catastrophic injuries carry costs that last for years. Here is how future damages are proven in serious California claims.',
 'How are future damages proven in a California catastrophic injury case? Why medical experts and life-care plans are essential.',
 <<<'C'
<p><strong>Catastrophic injuries have costs that outlast the case itself.</strong> California law lets you recover not only losses already incurred but those reasonably certain to occur in the future — ongoing treatment, assistive care, and reduced earning capacity under <a href="{$l3333}">Civil Code &sect; 3333</a>. Proving those future losses is central.</p>
<h2>What counts as catastrophic</h2>
<p>These are injuries with lasting, life-altering effects — severe <a href="/practice-areas/brain-injuries/">brain and spinal injuries</a>, amputations, severe burns, and multiple trauma. The common thread is that recovery is incomplete and the consequences continue for years.</p>
<h2>Why future damages matter most</h2>
<p>For a catastrophic injury, the biggest losses usually lie ahead, not behind. A claim that counts only past bills can leave a seriously injured person far short of what their future actually costs.</p>
<table>
<thead><tr><th>Expert</th><th>What they establish</th></tr></thead>
<tbody>
<tr><td>Treating physicians</td><td>The expected future course of treatment</td></tr>
<tr><td>Life-care planner</td><td>Future care, equipment, and support needs</td></tr>
<tr><td>Vocational expert</td><td>Impact on the ability to work</td></tr>
<tr><td>Economist</td><td>Present value of future losses</td></tr>
</tbody>
</table>
<blockquote class="pullquote">In a catastrophic case, the number that matters most is the one no bill has been written for yet.</blockquote>
<h2>Protecting the full value</h2>
<p>Because insurers may seek an early, low resolution, waiting until the medical picture stabilizes often protects the claim, and the full range of <a href="/blog/damages-in-a-california-injury-claim/">damages</a> should be documented. A free consultation can explain how future losses are established.</p>
C
 ,
 <<<'F'
[
 {"question":"What is a catastrophic injury?","answer":"An injury with lasting, life-altering effects on health, independence, or the ability to work — such as severe brain or spinal injuries, amputations, or severe burns."},
 {"question":"Can I recover for future losses?","answer":"Yes. California law allows recovery for losses reasonably certain to occur in the future, including ongoing care and lost earning capacity."},
 {"question":"How are future damages proven?","answer":"Through treating physicians, life-care planners, vocational experts, and economists who project and value future treatment, needs, and lost earnings."},
 {"question":"What is a life-care plan?","answer":"A detailed projection of a seriously injured person's future medical care, equipment, and support, used to document and value the claim."},
 {"question":"Why not settle right away?","answer":"Settling before the medical picture stabilizes can undervalue a claim, because the full extent of future needs may not yet be known."},
 {"question":"What non-economic damages apply?","answer":"California recognizes pain, suffering, and loss of enjoyment of life, which can be substantial in catastrophic cases."},
 {"question":"Who pays for lifelong care?","answer":"A properly valued claim seeks to account for lifelong needs from the at-fault party's available coverage; documenting those needs is essential."},
 {"question":"How long do I have to file?","answer":"Generally two years from the injury, with shorter deadlines if a government entity may be responsible."}
]
F);

/* ===================== DOG BITES (slots 26–27) ===================== */

$posts[] = p(26,
 'What to Do After a Dog Bite in California',
 'what-to-do-after-dog-bite-california','your-rights','shannon-ramos','blog-dog-bite.webp',
 'The steps you take after a dog bite protect both your health and your claim. Here is a clear checklist for California bite victims.',
 'What to do after a dog bite in California: the medical, reporting, and evidence steps that protect your health and your claim.',
 <<<'C'
<p><strong>After a dog bite in California, get medical care, identify the dog and owner, and report the bite.</strong> California holds owners strictly liable for bites under <a href="{$l3342}">Civil Code &sect; 3342</a>, so preserving who, where, and how matters as much as treating the wound.</p>
<h2>First steps at the scene</h2>
<p>Dog bites carry a real infection risk, so clean the wound and seek medical care promptly. Then identify the dog's owner and get their contact and, if possible, the dog's vaccination information. Photograph your injuries and the location.</p>
<h2>Report the bite</h2>
<p>Reporting to animal control or local authorities creates an official record and helps confirm the dog's rabies status. That record can be important evidence later, as our <a href="/blog/california-dog-bite-laws-strict-liability/">dog bite law guide</a> explains.</p>
<table>
<thead><tr><th>Step</th><th>Why it matters</th></tr></thead>
<tbody>
<tr><td>Seek medical care</td><td>Prevents infection; documents the injury</td></tr>
<tr><td>Identify owner & dog</td><td>Establishes who is responsible and rabies status</td></tr>
<tr><td>Report to animal control</td><td>Creates an official record</td></tr>
<tr><td>Photograph & keep records</td><td>Preserves evidence of the bite and injuries</td></tr>
</tbody>
</table>
<blockquote class="pullquote">California's strict liability rule is strong — but it still rests on knowing which dog and which owner.</blockquote>
<h2>Why strict liability helps</h2>
<p>Because California is a strict liability state, an owner is generally responsible even if the dog never bit before. Children are especially vulnerable, which we cover in our <a href="/blog/child-dog-bite-claim-california/">child dog bite guide</a>. A free, confidential consultation can explain your <a href="/practice-areas/dog-bites/">dog bite</a> options.</p>
C
 ,
 <<<'F'
[
 {"question":"What should I do first after a dog bite?","answer":"Get medical care to prevent infection and document the injury, then identify the dog's owner and vaccination status, and photograph the wound and scene."},
 {"question":"Should I report a dog bite in California?","answer":"Yes. Reporting to animal control or local authorities creates an official record and helps confirm the dog's rabies status, which can be important evidence."},
 {"question":"Is the owner liable even if the dog never bit before?","answer":"Generally yes. Under Civil Code section 3342, California owners are strictly liable for bites to people lawfully present, regardless of the dog's history."},
 {"question":"What if I can't identify the dog's owner?","answer":"Identifying the owner is important for a claim and for rabies follow-up. Witnesses, cameras, and an animal-control report can help locate them."},
 {"question":"What information should I collect?","answer":"The owner's name and contact, the dog's vaccination records if available, witness information, and photographs of the injuries and location."},
 {"question":"Does homeowner's insurance cover dog bites?","answer":"Often an owner's homeowner's or renter's policy provides coverage for a bite, depending on the policy. The facts and policy terms control."},
 {"question":"What if the dog's owner was a friend or family member?","answer":"A claim is generally against the insurance, not the person directly, which is why identifying coverage matters. The strict liability rule still applies."},
 {"question":"How long do I have to file?","answer":"Generally two years from the bite for an adult, and longer for a minor because the deadline is tolled during childhood."}
]
F);

$posts[] = p(27,
 'Dog Bite Injuries to Children in California',
 'child-dog-bite-claim-california','your-rights','elena-marquez','blog-dog-bite.webp',
 'Children are the most common and most seriously affected dog bite victims. Here is what California’s strict liability law means for families.',
 'Dog bite injuries to children in California: how strict liability protects kids, the extra filing time for minors, and what parents should do.',
 <<<'C'
<p><strong>Children are the most common dog bite victims, and California law strongly protects them.</strong> Owners are strictly liable under <a href="{$l3342}">Civil Code &sect; 3342</a>, and because the victim is a minor, the filing deadline is tolled during childhood under <a href="{$l352}">Code of Civil Procedure &sect; 352</a>.</p>
<h2>Why children's cases are different</h2>
<p>Bites to children often strike the face and head and can cause scarring, infection, and lasting emotional effects. Documenting both the physical and psychological impact is important, as our <a href="/blog/california-dog-bite-laws-strict-liability/">dog bite law guide</a> explains.</p>
<h2>Strict liability protects kids</h2>
<p>An owner is generally liable when a dog bites a child who is lawfully present, even if the dog never bit before. The "one-bite" excuse used in some states does not apply in California.</p>
<table>
<thead><tr><th>Issue</th><th>For a child victim</th></tr></thead>
<tbody>
<tr><td>Owner liability</td><td>Strict liability under CIV 3342</td></tr>
<tr><td>Filing deadline</td><td>Tolled during the child's minority (CCP 352)</td></tr>
<tr><td>Settlement</td><td>Court approval generally required for a minor</td></tr>
<tr><td>Damages</td><td>May include scarring and emotional harm</td></tr>
</tbody>
</table>
<blockquote class="pullquote">For a child, a bite's deepest scars are often the ones that don't show.</blockquote>
<h2>Special handling for a child's claim</h2>
<p>A minor's claim is usually brought by a parent, and any settlement generally needs court approval to protect the child. The extra filing time helps, but early action preserves evidence — our <a href="/blog/what-to-do-after-dog-bite-california/">dog bite checklist</a> covers the first steps. A free, confidential consultation can guide a family through a <a href="/practice-areas/dog-bites/">child dog bite</a> claim.</p>
C
 ,
 <<<'F'
[
 {"question":"Is a dog owner liable if their dog bites my child?","answer":"Generally yes. Under California's strict liability rule (Civil Code section 3342), an owner is liable when a dog bites a child lawfully present, even without any prior bite."},
 {"question":"Is there extra time to file for a child?","answer":"Yes. Under Code of Civil Procedure section 352, the deadline is tolled while the victim is a minor, giving families more time to file."},
 {"question":"Does the 'one-bite' rule apply in California?","answer":"No. California does not require a prior bite for liability; the owner is generally responsible for the first bite."},
 {"question":"Does a child's dog bite settlement need court approval?","answer":"Generally yes. Settlements for minors typically require court approval to protect the child and preserve the funds."},
 {"question":"What damages can a child recover?","answer":"Medical care, future treatment such as scar revision, and non-economic damages for pain and emotional harm, depending on the facts."},
 {"question":"What should parents do after a bite?","answer":"Get immediate medical care, identify the dog and owner, report the bite to animal control, and photograph the injuries."},
 {"question":"Who pays a child's dog bite claim?","answer":"Often the owner's homeowner's or renter's insurance, depending on the policy. The strict liability rule still governs responsibility."},
 {"question":"Should we wait since there is more time?","answer":"No. Although the deadline is tolled, evidence fades, so acting early protects the claim even with the extra time."}
]
F);

/* ===================== RIDESHARE & DELIVERY (slots 28–29) ===================== */

$posts[] = p(28,
 'Delivery Driver Accidents in California: Amazon, DoorDash, Instacart',
 'delivery-driver-accident-claim-california','insurance-claims','daniel-cho','blog-rideshare.webp',
 'Crashes involving gig delivery drivers raise tricky coverage questions. Here is how fault and insurance work in California.',
 'Hit by a delivery driver in California? How coverage works for Amazon, DoorDash, and Instacart crashes, and who may be responsible.',
 <<<'C'
<p><strong>A crash involving a gig delivery driver can involve several layers of insurance.</strong> Depending on the platform and whether the driver was actively working, the driver's personal policy, a company commercial policy, or your own coverage may apply — and the correct one is often the hard part.</p>
<h2>Why delivery coverage is complicated</h2>
<p>Many personal auto policies exclude commercial delivery use. Platforms may provide coverage only while the driver is actively on a delivery, and the available limits can differ sharply from the rideshare framework that applies to <a href="/blog/uber-lyft-accidents-california-insurance/">Uber and Lyft</a>.</p>
<table>
<thead><tr><th>Driver status</th><th>Coverage that may apply</th></tr></thead>
<tbody>
<tr><td>App off (personal errand)</td><td>Driver's personal auto policy</td></tr>
<tr><td>Logged in, awaiting an order</td><td>May be limited; varies by platform</td></tr>
<tr><td>Actively delivering</td><td>Platform's commercial coverage may apply</td></tr>
</tbody>
</table>
<h2>Who may be responsible</h2>
<p>The at-fault driver is the starting point, but establishing their work status at the moment of the crash — and which policy that triggers — is usually the key issue. Each platform structures coverage differently.</p>
<blockquote class="pullquote">The first question after a delivery crash isn't "who hit me" — it's "was the app on?"</blockquote>
<h2>Protecting your claim</h2>
<p>Document the crash, note the delivery company and any signage, and preserve evidence of the driver's status (app screens, receipts, witnesses). If coverage is thin, your own <a href="/blog/uninsured-underinsured-motorist-claims-california/">uninsured/underinsured coverage</a> may help. A free consultation can untangle a <a href="/practice-areas/rideshare-accidents/">gig-driver</a> crash.</p>
C
 ,
 <<<'F'
[
 {"question":"Who pays if a delivery driver hits me?","answer":"It depends on the driver's status and platform. The driver's personal policy, the platform's commercial coverage, or your own coverage may apply."},
 {"question":"Does the driver's personal insurance cover delivery work?","answer":"Often not. Many personal auto policies exclude commercial delivery use, which is why the platform's coverage and your own policy matter."},
 {"question":"Is delivery coverage the same as Uber/Lyft?","answer":"Not necessarily. Rideshare has a specific insurance framework; delivery platforms structure coverage differently, so the analysis changes."},
 {"question":"What evidence should I gather?","answer":"The delivery company name and any signage, the driver's information, app or receipt evidence of their status, witnesses, and photos."},
 {"question":"What if the driver had the app off?","answer":"Then their personal auto policy is generally the relevant coverage, assuming it applies to the circumstances."},
 {"question":"What if coverage is too low for my injuries?","answer":"Your own uninsured or underinsured motorist coverage may help fill the gap, depending on your policy."},
 {"question":"Why does 'app status' matter so much?","answer":"Because platform coverage often applies only during active delivery, the driver's status at the moment of the crash can decide which policy pays."},
 {"question":"How long do I have to file?","answer":"Generally two years from the crash for a personal injury claim, with shorter deadlines if a government entity is involved."}
]
F);

$posts[] = p(29,
 'Injured as an Uber or Lyft Passenger in California',
 'uber-lyft-passenger-injury-california','insurance-claims','daniel-cho','blog-rideshare.webp',
 'As a rideshare passenger, you are almost never at fault — but coverage can still be confusing. Here is how it works in California.',
 'Injured as an Uber or Lyft passenger in California? Why a $1M policy often applies and how to protect your claim as a blameless rider.',
 <<<'C'
<p><strong>As an Uber or Lyft passenger, you are almost never at fault</strong> — your focus is which insurance pays. In California, when a rideshare driver is carrying a passenger, a $1 million third-party liability policy is generally available under the state's transportation-network rules (<a href="{$lpuc}">Public Utilities Code &sect; 5433</a>).</p>
<h2>You are the blameless party</h2>
<p>A passenger did not cause the crash, so the real question is whose negligence did — the rideshare driver, another motorist, or both — and which policy responds. That differs from the driver-focused analysis in our <a href="/blog/uber-lyft-accidents-california-insurance/">rideshare insurance guide</a>.</p>
<h2>The $1 million passenger policy</h2>
<p>When a driver is actively transporting a passenger, a $1 million liability policy typically applies. If another driver caused the crash and is underinsured, the rideshare company's uninsured/underinsured coverage may also come into play.</p>
<table>
<thead><tr><th>Who was at fault</th><th>Coverage that may respond</th></tr></thead>
<tbody>
<tr><td>Your rideshare driver</td><td>The $1M third-party policy (passenger aboard)</td></tr>
<tr><td>Another driver</td><td>Their policy; rideshare UM/UIM may supplement</td></tr>
<tr><td>Both drivers</td><td>Multiple policies, apportioned by fault</td></tr>
</tbody>
</table>
<blockquote class="pullquote">As a passenger, you rarely fight over fault — you navigate which policy pays.</blockquote>
<h2>Protecting your claim</h2>
<p>Save your trip record, report the crash in the app, get the drivers' information, and seek prompt medical care. Multiple insurers may be involved and may point at each other. A free consultation can help a <a href="/practice-areas/rideshare-accidents/">rideshare passenger</a> sort out coverage.</p>
C
 ,
 <<<'F'
[
 {"question":"Am I at fault if I'm hurt as a rideshare passenger?","answer":"Almost never. As a passenger you did not cause the crash; the issue is which driver was negligent and which insurance policy applies."},
 {"question":"How much coverage applies to a rideshare passenger?","answer":"When a driver is actively transporting a passenger, a $1 million third-party liability policy is generally available under California's transportation-network rules."},
 {"question":"What if another driver caused the crash?","answer":"Their insurance applies, and if they are uninsured or underinsured, the rideshare company's UM/UIM coverage may supplement, depending on the facts."},
 {"question":"What should I do right after the crash?","answer":"Report it in the app, save your trip record, get both drivers' information, gather witnesses, and seek prompt medical care."},
 {"question":"Do I sue the driver or the company?","answer":"Claims typically proceed against the available insurance policies rather than individuals; identifying the right policy is the key step."},
 {"question":"What if both drivers share fault?","answer":"Multiple policies may respond, apportioned by each driver's share under California's comparative fault rule."},
 {"question":"Does it matter that the app was on?","answer":"Yes. The $1 million policy generally applies while a passenger is aboard, so your trip record documenting the active ride is important."},
 {"question":"How long do I have to file?","answer":"Generally two years from the crash, with shorter deadlines if a government entity is involved."}
]
F);

/* ===================== LOCAL / GEO (slot 30) ===================== */

$posts[] = p(30,
 'Highway 50 Accidents: The Folsom–Sacramento Corridor',
 'highway-50-accident-attorney-california','legal-tips','shannon-ramos','blog-folsom-guide-featured.webp',
 'Highway 50 carries heavy daily traffic between Folsom and Sacramento. Here is a local guide to your rights after a crash on the corridor.',
 'Injured in a Highway 50 crash near Folsom or Sacramento? A local guide to your rights, deadlines, and the steps after a corridor collision.',
 <<<'C'
<p><strong>Highway 50 is the main artery between Folsom and Sacramento, and it carries heavy, fast-moving traffic every day.</strong> A crash here is governed by the same California rules as anywhere else, but a local perspective helps — from the corridor's commuter congestion to the region's emergency care.</p>
<h2>A high-volume commuter corridor</h2>
<p>US-50 links El Dorado Hills, Folsom, Rancho Cordova, and downtown Sacramento, with daily commuter surges and interchanges that see frequent collisions. Serious crashes in the region are routed to area trauma and emergency facilities.</p>
<h2>What to do after a corridor crash</h2>
<p>Check for injuries and call 911, move to safety where possible, report the crash as required, and document the scene and vehicles. Our statewide guide to <a href="/blog/what-to-do-after-a-car-accident-in-california/">what to do after a car accident</a> walks through each step, and our <a href="/blog/folsom-car-accident-guide/">Folsom car accident guide</a> covers the local picture in depth.</p>
<table>
<thead><tr><th>Rule</th><th>What applies on US-50</th></tr></thead>
<tbody>
<tr><td>Filing deadline</td><td>Generally 2 years (CCP 335.1)</td></tr>
<tr><td>Fault</td><td>Pure comparative negligence</td></tr>
<tr><td>Government-related crash</td><td>Possible 6-month claim deadline</td></tr>
</tbody>
</table>
<blockquote class="pullquote">The statute is statewide, but the traffic, the interchanges, and the hospitals are local.</blockquote>
<h2>Local help for your claim</h2>
<p>California's two-year <a href="/blog/california-statute-of-limitations-injury-claims/">deadline</a> under <a href="{$l3351}">Code of Civil Procedure &sect; 335.1</a> and its pure comparative fault rule apply on US-50 just as everywhere else. Mason Law, P.C. serves injured people across the Folsom–Sacramento corridor from its Folsom office. A free, confidential consultation can explain your options after a Highway 50 crash.</p>
C
 ,
 <<<'F'
[
 {"question":"Do California injury rules apply on Highway 50?","answer":"Yes. The statewide two-year filing deadline and pure comparative fault rule apply to US-50 crashes just as they do everywhere in California."},
 {"question":"What should I do after a crash on US-50?","answer":"Check for injuries and call 911, move to safety if possible, report the crash as required, and photograph the scene, vehicles, and any injuries."},
 {"question":"Which cities does the corridor cover?","answer":"US-50 links El Dorado Hills, Folsom, Rancho Cordova, and downtown Sacramento, with heavy commuter traffic throughout."},
 {"question":"Are deadlines different for a government-related crash?","answer":"Yes. If a public entity may be at fault, a claim can be due within six months, much shorter than the general two-year deadline."},
 {"question":"What makes corridor crashes serious?","answer":"High speeds and commuter congestion at interchanges can produce severe collisions, so prompt medical care and documentation matter."},
 {"question":"Does Mason Law serve the Folsom-Sacramento area?","answer":"Yes. The firm serves injured people across the Folsom-Sacramento corridor from its Folsom office."},
 {"question":"How long do I have to file after a Highway 50 crash?","answer":"Generally two years from the crash under Code of Civil Procedure section 335.1, with shorter deadlines when a government entity is involved."},
 {"question":"What if the other driver was uninsured?","answer":"Your own uninsured or underinsured motorist coverage may help. Review your policy and report the crash to your insurer promptly."}
]
F);

/* --- resolve {$...} citation placeholders in content (kept out of nowdoc) --- */
$CITE = [
    '{$l377}'    => leg('CCP','377.60'),
    '{$l37734}'  => leg('CCP','377.34'),
    '{$l3351}'   => leg('CCP','335.1'),
    '{$l9112}'   => leg('GOV','911.2'),
    '{$l3600}'   => leg('LAB','3600'),
    '{$l3852}'   => leg('LAB','3852'),
    '{$l21658}'  => leg('VEH','21658.1'),
    '{$l27803}'  => leg('VEH','27803'),
    '{$l21950}'  => leg('VEH','21950'),
    '{$l352}'    => leg('CCP','352'),
    '{$l3333}'   => leg('CIV','3333'),
    '{$l3342}'   => leg('CIV','3342'),
    '{$lpuc}'    => leg('PUC','5433'),
];
foreach ($posts as &$pp) { $pp['content'] = strtr($pp['content'], $CITE); } unset($pp);

/* ======================================================================= *
 *  ENGINE
 * ======================================================================= */
$slugs = array_map(static fn($x) => $x['slug'], $posts);

try {
    $pdo = db();

    if ($ROLLBACK) {
        $in = implode(',', array_fill(0, count($slugs), '?'));
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE slug IN ($in)");
        $stmt->execute($slugs);
        echo "Rollback: removed {$stmt->rowCount()} of " . count($slugs) . " posts.\n";
        exit(0);
    }

    foreach ([
        "ALTER TABLE blog_posts ADD COLUMN IF NOT EXISTS faqs MEDIUMTEXT NULL AFTER content",
        "ALTER TABLE blog_posts ADD COLUMN IF NOT EXISTS og_image VARCHAR(255) NULL AFTER featured_image",
        "ALTER TABLE blog_posts ADD COLUMN IF NOT EXISTS og_image_alt VARCHAR(255) NULL AFTER og_image",
        "ALTER TABLE blog_posts ADD COLUMN IF NOT EXISTS date_modified DATETIME NULL AFTER published_at",
    ] as $ddl) { try { $pdo->exec($ddl); } catch (Throwable $e) {} }

    // category slug -> id
    $catId = [];
    foreach ($pdo->query("SELECT id,slug FROM blog_categories") as $c) { $catId[$c['slug']] = (int) $c['id']; }

    // valid authors on this DB
    $valid = [];
    foreach ($pdo->query("SELECT slug,name FROM attorneys WHERE active=1") as $a) { $valid[$a['slug']] = $a['name']; }
    $fallbackNames = ['shannon-ramos'=>'Shannon Ramos','elena-marquez'=>'Elena Marquez','daniel-cho'=>'Daniel Cho'];
    $pick = static function (string $want) use ($valid, $fallbackNames): array {
        if (isset($valid[$want])) return ['slug'=>$want,'name'=>$valid[$want]];
        foreach (['elena-marquez','daniel-cho','shannon-ramos'] as $f) if (isset($valid[$f])) return ['slug'=>$f,'name'=>$valid[$f]];
        if ($valid) { $s = array_key_first($valid); return ['slug'=>$s,'name'=>$valid[$s]]; }
        return ['slug'=>$want,'name'=>$fallbackNames[$want] ?? 'Mason Law, P.C.'];
    };

    $exists = $pdo->prepare('SELECT 1 FROM blog_posts WHERE slug = ? LIMIT 1');
    $ins = $pdo->prepare(
        'INSERT INTO blog_posts
          (title,slug,excerpt,content,faqs,featured_image,og_image_alt,category_id,
           author_name,author_slug,status,published_at,date_modified,meta_title,meta_desc,views)
         VALUES (:title,:slug,:excerpt,:content,:faqs,:img,:ogalt,:cat,
           :aname,:aslug,"published",:pub,:pub2,:mtitle,:mdesc,0)'
    );

    $added=0; $skipped=0;
    foreach ($posts as $pp) {
        $exists->execute([$pp['slug']]);
        if ($exists->fetchColumn()) { echo "skip (exists): {$pp['slug']}\n"; $skipped++; continue; }
        $pub = slot_date($SLOT1, $pp['slot']);
        $auth = $pick($pp['author']);
        $img = '/assets/images/generated/' . $pp['img'];
        $ins->execute([
            ':title'=>$pp['title'], ':slug'=>$pp['slug'], ':excerpt'=>$pp['excerpt'],
            ':content'=>$pp['content'], ':faqs'=>$pp['faqs'], ':img'=>$img,
            ':ogalt'=>$pp['title'].' — Mason Law, P.C.', ':cat'=>$catId[$pp['cat']] ?? null,
            ':aname'=>$auth['name'], ':aslug'=>$auth['slug'],
            ':pub'=>$pub, ':pub2'=>$pub, ':mtitle'=>$pp['title'], ':mdesc'=>$pp['metaDesc'],
        ]);
        echo sprintf("+ slot %-2d  %s  %-52s (%s)\n", $pp['slot'], substr($pub,0,10), $pp['slug'], $auth['slug']);
        $added++;
    }
    echo "\nDone. Added $added, skipped $skipped. Campaign slots 9-30 (publish 2026-10-20 -> 2026-12-01).\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Seed failed: ' . $e->getMessage() . "\n");
    exit(1);
}
