<?php
/**
 * _blogs.php — ONE-TIME: publish two SEO blog posts (content by Zainab).
 *   #1 California 30/60/15 insurance minimums — published NOW.
 *   #2 California 2026 Move Over Law (AB 390) — scheduled +3 days.
 * Idempotent by slug. Self-deletes. DELETE after running.
 */
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');

$KEY = 'gsil-blogs-2q7m5x';
if (($_GET['key'] ?? '') !== $KEY) { http_response_code(403); exit("Forbidden. Append ?key=...\n"); }

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';

$pdo = db();

/* Resolve helpers */
function cat_id(PDO $pdo, array $slugs): ?int {
    foreach ($slugs as $s) {
        $q = $pdo->prepare('SELECT id FROM blog_categories WHERE slug = ? LIMIT 1');
        $q->execute([$s]);
        $id = $q->fetchColumn();
        if ($id) return (int) $id;
    }
    return null;
}
function author(PDO $pdo, string $slug): array {
    $q = $pdo->prepare('SELECT name FROM attorneys WHERE slug = ? LIMIT 1');
    $q->execute([$slug]);
    $n = $q->fetchColumn();
    return $n ? ['slug' => $slug, 'name' => $n] : ['slug' => null, 'name' => null];
}
function upsert(PDO $pdo, array $p): string {
    $q = $pdo->prepare('SELECT id FROM blog_posts WHERE slug = ? LIMIT 1');
    $q->execute([$p['slug']]);
    $id = $q->fetchColumn();
    $cols = ['title','slug','excerpt','content','featured_image','category_id','author_name','author_slug',
             'status','published_at','date_modified','meta_title','meta_desc','og_image','og_image_alt','faqs'];
    $vals = array_map(fn($c) => $p[$c] ?? null, $cols);
    if ($id) {
        $set = implode(', ', array_map(fn($c) => "`$c`=?", $cols));
        $pdo->prepare("UPDATE blog_posts SET $set WHERE id=?")->execute(array_merge($vals, [$id]));
        return "updated #$id";
    }
    $ph = implode(',', array_fill(0, count($cols), '?'));
    $pdo->prepare("INSERT INTO blog_posts (`" . implode('`,`', $cols) . "`, views) VALUES ($ph, 0)")->execute($vals);
    return 'inserted #' . $pdo->lastInsertId();
}

$auth = author($pdo, 'shannon-ramos');
$now  = date('Y-m-d H:i:s');
$plus3 = date('Y-m-d H:i:s', strtotime('+3 days'));

/* ============================ BLOG 1 ============================ */
$content1 = <<<'HTML'
<p>For 58 years, California let drivers carry some of the thinnest auto insurance in the country. The old minimums &mdash; $15,000 per injured person, $30,000 per accident, $5,000 for property damage &mdash; were set in 1967 and never updated, even as medical bills and vehicle prices multiplied many times over.</p>
<p>That finally changed on January 1, 2025. Under <a href="https://leginfo.legislature.ca.gov/faces/billNavClient.xhtml?bill_id=202120220SB1107">Senate Bill 1107</a>, known as the Protect California Drivers Act, California's minimum car insurance rose to 30/60/15 &mdash; double the old injury limits and triple the property damage coverage. A second increase is already written into law for 2035.</p>
<p>If you drive in California, this change affects your policy and probably your premium. If you have been hurt by another driver, it affects how much money is actually available to compensate you. Here is what the numbers mean, why they changed, and why the new minimums still fall short when someone is seriously injured.</p>

<h2>What Is California's Minimum Car Insurance Now?</h2>
<p>Since January 1, 2025, every standard auto policy issued or renewed in California must carry at least:</p>
<ul>
<li><strong>$30,000</strong> in bodily injury liability per person</li>
<li><strong>$60,000</strong> in bodily injury liability per accident</li>
<li><strong>$15,000</strong> in property damage liability per accident</li>
</ul>
<p>Together, those three figures make up the new California minimum car insurance 30/60/15 standard. Insurers were required to bring older minimum-limit policies up to the new floor at the first renewal on or after January 1, 2025 &mdash; so by now, virtually every active policy in the state reflects the change automatically.</p>
<p>Here's how the old, current, and future minimums stack up:</p>
<table>
<thead><tr><th>Coverage type</th><th>1967&ndash;2024 (old law)</th><th>Jan 1, 2025 &ndash; Dec 31, 2034 (current law)</th><th>Jan 1, 2035 and after</th></tr></thead>
<tbody>
<tr><td>Bodily injury liability, per person</td><td>$15,000</td><td>$30,000</td><td>$50,000</td></tr>
<tr><td>Bodily injury liability, per accident</td><td>$30,000</td><td>$60,000</td><td>$100,000</td></tr>
<tr><td>Property damage liability, per accident</td><td>$5,000</td><td>$15,000</td><td>$25,000</td></tr>
</tbody>
</table>
<p>The current requirements come from Senate Bill 1107, passed in 2022.</p>

<h2>What Does 30/60/15 Mean?</h2>
<p>&ldquo;What does 30/60/15 mean?&rdquo; is one of the most common questions we hear after a crash. The three numbers on your declarations page describe the caps on what your insurer will pay other people when you cause a crash. Read them left to right.</p>
<p>The first number &mdash; <strong>$30,000</strong> &mdash; is the most your insurer pays for any one person you injure. If you rear-end someone and their medical bills, lost wages, and pain and suffering add up to $45,000, your policy contributes $30,000. The remaining $15,000 doesn't disappear; it becomes your personal problem. More on that below.</p>
<p>The second number &mdash; <strong>$60,000</strong> &mdash; is the total available for everyone injured in a single accident. Injure three people and they all share $60,000, with no single person collecting more than $30,000. In a crash involving a family, that pool drains fast.</p>
<p>The third number &mdash; <strong>$15,000</strong> &mdash; covers damage to other people's property. Usually that means their vehicle, but it also applies to fences, guardrails, storefronts, and anything else you hit.</p>
<p>Two things trip people up here. First, liability coverage never pays you &mdash; it exists to pay the people you injure. Your own treatment and repairs come from other coverage (health insurance, med-pay, collision) or from the at-fault driver's policy when someone else caused the crash. Second, these numbers are ceilings, not payouts. The insurer pays proven damages up to the limit, not the limit automatically.</p>

<h2>What Changed Under SB 1107 (the Protect California Drivers Act)</h2>
<p>California adopted its 15/30/5 minimums in 1967 and then left them alone for nearly six decades. Nothing about the cost of a car crash stood still during those decades. By 2024, a $5,000 property damage limit couldn't handle a moderate fender-bender on a newer SUV, and a single emergency room visit could eat a meaningful share of the $15,000 injury limit before treatment even began.</p>
<p>Senate Bill 1107, authored by Senator Bill Dodd and signed by Governor Newsom in September 2022, finally raised the floor &mdash; in two scheduled steps. On January 1, 2025, the minimums rose from 15/30/5 to 30/60/15. And under SB 1107, California's minimums rise again on January 1, 2035, to 50/100/25.</p>
<p>The law reaches beyond liability coverage, too. Under <a href="https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=INS&sectionNum=11580.2.">Insurance Code section 11580.2</a>, every California auto insurer must offer uninsured and underinsured motorist coverage, and the required offer tracks the state's liability floor, so those minimums climbed along with the new law.</p>
<p>One practical effect worth knowing: drivers who carried bare-minimum policies saw premiums rise at renewal, because they're now buying two to three times the coverage they had before. Drivers who already carried higher limits &mdash; and most do &mdash; noticed little or no change from the law itself.</p>

<h2>Why 30/60/15 Still Isn't Enough for a Serious Injury</h2>
<p>The new California insurance minimums sound substantial until you price out a real injury. An ambulance ride plus an emergency room workup can run several thousand dollars before a single specialist gets involved. A broken bone that needs surgery can exceed the entire $30,000 per-person limit on its own. A few days in intensive care can pass six figures. And none of that touches lost income, future treatment, or pain and suffering &mdash; categories that often outweigh the medical bills in a serious case.</p>
<p>The shared per-accident cap makes things worse. Sixty thousand dollars sounds better than thirty until you remember it covers everyone hurt in the crash. A T-bone collision that injures a driver and two passengers leaves three people dividing a pool that might not fully compensate any one of them.</p>
<p>Property damage has the same math problem. The average new vehicle in the U.S. now sells for close to $50,000, so a $15,000 limit won't come near replacing a totaled late-model car &mdash; and it can vanish entirely in a chain-reaction crash involving several vehicles.</p>
<p>SB 1107 raised the legal floor. It didn't raise it anywhere near the real cost of a serious collision. For crash victims, that gap between the at-fault driver's limits and the actual damages is where most of the hard work in an injury claim happens.</p>

<h2>What Happens When Damages Exceed the Limits?</h2>
<p>This is the question that lands on a <a href="/practice-areas/car-accidents/">personal injury lawyer's</a> desk every week: the injuries are severe, the at-fault driver carries a minimum policy, and the math doesn't work. The at-fault insurer's obligation generally ends at the policy limits &mdash; but that's rarely the end of the claim. Several paths remain open.</p>
<p><strong>Your own underinsured motorist (UIM) coverage.</strong> If you carry UM/UIM, it steps in when the at-fault driver's limits fall short of your damages. One California quirk is worth understanding: UIM here works on an offset. Your UIM limit is reduced by whatever the other driver's insurer paid. Carry $100,000 in UIM and collect $30,000 from the at-fault driver, and your own policy has up to $70,000 left to offer &mdash; not $100,000 on top. It's one of the most misunderstood rules in California auto coverage, and it's the single best argument for carrying high UIM limits.</p>
<p><strong>A claim against the driver personally.</strong> You can sue an underinsured driver for the balance and win a judgment. Collecting it is another matter. Many minimum-coverage drivers have few assets, and a judgment against someone with nothing to take doesn't pay hospital bills. Still, when the at-fault driver has real income or property, personal recovery is a live option.</p>
<p><strong>Other responsible parties.</strong> Serious cases often have more than one source of recovery. Was the at-fault driver working at the time? Their employer may be on the hook. Did a vehicle defect or a dangerous road condition contribute? A manufacturer or public entity might share liability. Part of an attorney's job is locating every policy and every defendant &mdash; including umbrella policies the other driver never mentioned.</p>
<p><strong>Med-pay and health insurance.</strong> Medical payments coverage on your own policy handles early bills regardless of fault, and your health insurance covers treatment in the meantime &mdash; though health insurers usually assert a lien against your eventual settlement, which becomes its own negotiation.</p>

<h2>What Coverage Should You Actually Carry?</h2>
<p>We are a personal injury firm, not an insurance agency &mdash; but we see the aftermath of these decisions every day, and the pattern is consistent. The people who recover fully are usually the ones who bought coverage as if they might one day be the victim, not just the cause.</p>
<p>A few benchmarks worth discussing with your agent: liability limits of at least 100/300/100, since the premium jump from 30/60/15 is smaller than most people expect and it shields your own savings if you cause a serious crash. UM/UIM limits that match your liability limits, because this is the coverage that protects you when the other driver has too little or nothing &mdash; arguably the most valuable line on your declarations page. Medical payments coverage of $5,000 to $10,000 to handle immediate bills while a claim is pending. And if you own a home or have meaningful assets, an umbrella policy adds $1 million or more in protection for a relatively modest annual premium.</p>
<p>By some estimates, roughly one in six California drivers carries no insurance at all. Add in everyone driving on minimum limits, and the odds that the person who hits you can fully pay for the harm are worse than most people assume. In a serious crash, your own policy is often what makes or breaks the recovery.</p>

<h2>The Bottom Line</h2>
<p>California waited 58 years to update its auto insurance minimums, and SB 1107 finally dragged them into the present: 30/60/15 today, with 50/100/25 arriving in 2035. That is genuine progress for crash victims, who now have twice the coverage to draw on when a minimum-limits driver causes a wreck. But a raised floor is still a floor. Serious injuries routinely cost far more than $30,000, so treat the state minimum as a starting point &mdash; and build real protection on top of it, starting with uninsured and underinsured motorist coverage.</p>

<h2>Hurt by a Driver Who Didn't Carry Enough Insurance?</h2>
<p>The new minimums help, but &ldquo;better than 1967&rdquo; is a low bar. If you've been seriously injured and the at-fault driver's policy won't cover your losses, don't assume the first offer is the end of the road. Between underinsured motorist claims, additional defendants, and policy-limit strategy, there is often far more recovery available than it first appears.</p>
<p>Mason Law, P.C. represents injury victims in <a href="/personal-injury-lawyer-folsom-ca/">Folsom</a> and throughout the Sacramento region. We'll review every policy in play &mdash; theirs and yours &mdash; and give you a straight answer about what your claim is worth. Call (916) 587-2997 or reach us through our <a href="/contact.php">contact form</a> to get started.</p>
HTML;

$faqs1 = "What does 30/60/15 mean in my California policy? :: It is shorthand for your liability limits: \$30,000 for injuries to any one person, \$60,000 total for all injuries in one accident, and \$15,000 for damage to other people's property. These have been California's required minimums since January 1, 2025.\n"
. "When did the new California insurance minimums take effect? :: January 1, 2025. The higher limits under SB 1107 apply to every policy issued or renewed on or after that date, and insurers raised existing minimum-limit policies automatically at renewal.\n"
. "Do I need to do anything to comply with SB 1107? :: Probably not. Any policy that has renewed since January 1, 2025 was required to meet at least 30/60/15. It's still worth pulling your declarations page to confirm your limits, and to ask whether the minimum is really where you want to be.\n"
. "Will California's minimums increase again? :: Yes. SB 1107 schedules a second increase for January 1, 2035, when the minimums rise to 50/100/25 \xE2\x80\x94 \$50,000 per injured person, \$100,000 per accident, and \$25,000 in property damage.\n"
. "Is uninsured motorist coverage required in California? :: No. But under Insurance Code section 11580.2, insurers must offer it on every policy, and you can only reject it in writing. Given how many uninsured and underinsured drivers share California roads, declining it to save a few dollars a month is usually an expensive mistake.\n"
. "What if the driver who hit me only carries minimum coverage? :: Their insurer pays up to the policy limits, and then the search widens: your own UM/UIM coverage, a personal claim against the driver, or other liable parties such as an employer. An attorney can identify every available policy.\n"
. "What are the penalties for driving without insurance in California? :: Fines that grow substantially once court assessments are added, possible vehicle impoundment, and, if you cause an accident while uninsured, a license suspension. You would also be personally responsible for every dollar of harm you cause.\n"
. "Does the \$15,000 property damage minimum pay to fix my own car? :: No. Liability coverage only pays for other people's losses. Repairs to your own vehicle come from your collision coverage, or from the at-fault driver's property damage liability when someone else caused the crash.";

echo upsert($pdo, [
    'title'        => "California's New 30/60/15 Insurance Minimums, Explained",
    'slug'         => 'california-30-60-15-insurance-minimums',
    'excerpt'      => 'California raised its minimum auto insurance to 30/60/15 in 2025 under SB 1107. Here is what the new numbers mean, why they still fall short in a serious injury, and how it affects your claim.',
    'content'      => $content1,
    'featured_image' => '/assets/images/generated/blog-ca-insurance-minimums.webp',
    'category_id'  => cat_id($pdo, ['insurance-claims', 'insurance', 'auto-accidents']),
    'author_name'  => $auth['name'],
    'author_slug'  => $auth['slug'],
    'status'       => 'published',
    'published_at' => $now,
    'date_modified'=> null,
    'meta_title'   => "California's New 30/60/15 Insurance Minimums Explained",
    'meta_desc'    => "SB 1107 raised California's minimum auto insurance to 30/60/15 in 2025. What the change means, why minimums often aren't enough, and how it affects injury claims.",
    'og_image'     => null,
    'og_image_alt' => "California's new 30/60/15 auto insurance minimums explained — Mason Law, P.C.",
    'faqs'         => $faqs1,
]) . " (published now)\n";

/* ============================ BLOG 2 ============================ */
$content2 = <<<'HTML'
<p>A flat tire used to put you in a strange legal gap. If a police cruiser stopped on the shoulder of Highway 50, every passing driver owed it a full lane of space or a slower speed. If you stopped in the same spot with your hazards blinking, passing drivers owed you nothing &mdash; legally speaking.</p>
<p>That gap closed on January 1, 2026. Under Assembly Bill 390, California's slow down, move over law now protects <strong>any</strong> stopped vehicle displaying hazard lights or roadside warning devices &mdash; including ordinary drivers dealing with a breakdown or a blowout.</p>
<p>For most drivers, it is a traffic rule with a modest fine attached. For anyone struck while stranded on the shoulder, it's a statutory duty that can make or break an injury claim.</p>

<h2>What the California Move Over Law Requires in 2026</h2>
<p>The rule lives in <a href="https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=VEH&sectionNum=21809.">Vehicle Code section 21809</a>. When you're approaching a stopped vehicle on the shoulder &mdash; an emergency vehicle with its lights on, a tow truck or highway maintenance vehicle flashing amber, or now any vehicle displaying hazard lights, cones, flares, or reflective devices &mdash; you must approach with caution and do one of two things before passing in the adjacent lane: move over into a lane that isn't next to the stopped vehicle if you can do so safely and legally, or slow to a speed that's reasonable and prudent for the weather, road, and traffic conditions.</p>
<p>Together, those two options make up the California move over law that 2026 drivers are required to follow. Doing neither is a violation.</p>
<p>The statute applies on any &ldquo;highway,&rdquo; a term California's Vehicle Code defines broadly enough to cover public streets and roads generally, not just freeways. There's one built-in exception: the duty doesn't apply when the stopped vehicle isn't adjacent to the roadway or sits behind a protective physical barrier.</p>
<p>Here's the change at a glance:</p>
<table>
<thead><tr><th>&nbsp;</th><th>Through Dec 31, 2025 (old law)</th><th>From Jan 1, 2026 (AB 390)</th></tr></thead>
<tbody>
<tr><td>Who is protected</td><td>Emergency vehicles with emergency lights, tow trucks and marked Caltrans vehicles with flashing amber lights</td><td>All of the above, plus every marked highway maintenance vehicle &mdash; and any stopped vehicle showing hazard lights, cones, flares, or reflective devices</td></tr>
<tr><td>What drivers must do</td><td>Change lanes away, or slow to a safe speed if a lane change isn't possible</td><td>Unchanged &mdash; move over or slow down</td></tr>
<tr><td>Base fine (Veh. Code &sect;21809)</td><td>Up to $50, plus court assessments</td><td>Up to $50, plus court assessments</td></tr>
</tbody>
</table>
<p>You can read the <a href="https://leginfo.legislature.ca.gov/faces/billTextClient.xhtml?bill_id=202520260AB390">full chaptered text of AB 390</a> on the Legislature's website.</p>

<h2>What Changed Under AB 390</h2>
<p>California has had a move over law for years, but its protection depended on what was stopped rather than how dangerous the situation was. A tow truck operator got a legal buffer. The family whose minivan the tow truck was coming to rescue did not.</p>
<p>Assembly Bill 390, authored by Assemblymember Lori Wilson and sponsored by AAA, fixed that asymmetry. It passed both chambers without a single no vote and was signed by Governor Newsom on July 28, 2025, taking effect January 1, 2026. The bill extended protection from Caltrans crews to all marked highway maintenance vehicles &mdash; county, city, and contractor crews included &mdash; and, far more consequentially, to any stationary vehicle displaying hazard lights or another warning device.</p>
<p>The timing wasn't arbitrary. Roadside deaths in California rose nearly 77% between 2014 and 2023, according to the AAA Foundation's analysis of federal crash data. Under AB 390, California joins more than two dozen other states that already extend move-over protection to every stopped vehicle, not just official ones. The <a href="https://www.chp.ca.gov/news-alerts/news-list/new-year-new-laws--chp-highlights-public-safety-laws-taking-effect-in-2026/">California Highway Patrol</a> lists it among the public-safety laws that took effect in 2026.</p>

<h2>Penalties for Violating the Move Over Law</h2>
<p>Here is where a lot of the coverage gets it wrong, so let's go straight to the statute. Vehicle Code section 21809(b) makes a violation an infraction with a base fine of no more than $50. California's court assessments and fees then multiply that base, so the actual ticket lands around $230 to $240 in most counties, plus a point on your DMV record as a moving violation, which follows you into your insurance pricing for years.</p>
<p>You'll see claims of fines &ldquo;up to $1,000&rdquo; floating around news reports on the new law. That figure doesn't appear in section 21809 itself; it seems to conflate California's rule with other states' penalties.</p>
<p>But honestly, the ticket was never the point. The real exposure for a driver who blows past a stranded vehicle isn't a $238 fine &mdash; it's what happens in a civil courtroom if someone on that shoulder gets hurt.</p>

<h2>How an AB 390 Violation Helps Prove Negligence If You're Hit on the Shoulder</h2>
<p>This is the part of the new law that matters most to our clients, and it comes down to a doctrine called negligence per se. Under <a href="https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml?lawCode=EVID&sectionNum=669.">Evidence Code section 669</a>, a driver who violates a safety statute is presumed negligent when the violation causes the kind of harm the statute was written to prevent, to a person the statute was written to protect.</p>
<p>A shoulder crash fits that test cleanly. A stranded motorist beside a disabled car with its flashers on is exactly the person the Legislature set out to protect, and being struck by passing traffic is exactly the harm the law targets. When a driver who neither moved over nor slowed down causes that harm, the violation becomes the backbone of the injury case &mdash; and the burden shifts to that driver to explain why ignoring the statute was reasonable.</p>
<p>Before 2026, an ordinary driver hit while stopped on the shoulder in California couldn't invoke section 21809 at all &mdash; the statute didn't cover them, so attorneys built these cases from general principles like the basic speed law and unsafe passing. Those arguments still work. But now a specific statutory duty is owed directly to you, and that changes the posture of the entire claim.</p>
<p>The evidence that proves the violation is time-sensitive: whether your hazard lights were on (dashcam footage, vehicle data, witness statements, the CHP report), whether the adjacent lane was open, and how fast the driver was going. A CHP citation strengthens the case but isn't required &mdash; a civil claim can prove the violation on its own evidence.</p>
<p>Expect the insurer to push back with the barrier exception, an argument that your flashers weren't on, or a claim that you contributed by where you stopped or stood. California's pure comparative fault system means those arguments can reduce a recovery, but they don't erase one.</p>

<h2>What to Do If You're Hit Roadside on Highway 50 or I-80</h2>
<p>The Sacramento region's two major corridors &mdash; Highway 50 through <a href="/personal-injury-lawyer-folsom-ca/">Folsom</a> and <a href="/personal-injury-lawyer-el-dorado-hills-ca/">El Dorado Hills</a>, and I-80 through <a href="/personal-injury-lawyer-roseville-ca/">Roseville</a> toward the Sierra &mdash; combine everything this law was written for: commuter speeds, narrow shoulders, heavy traffic. If a passing driver hits you while you're stopped, what you do in the first hour matters.</p>
<p><strong>Get out of the traffic zone first.</strong> Behind the guardrail, up the embankment, away from the traffic side of your vehicle. The worst roadside injuries happen to people standing between two stopped cars or beside the driver's side.</p>
<p><strong>Call 911 and get a CHP report.</strong> Crashes on these corridors fall to the California Highway Patrol, and the officer's report will document vehicle positions and whether your hazards were on &mdash; often the single most important fact in an AB 390 claim.</p>
<p><strong>Document while the scene is fresh.</strong> Photograph your vehicle's position, the flashers, debris, skid marks, and the nearest exit or mile marker. Get witness names and numbers before they drive off.</p>
<p><strong>Preserve the electronics.</strong> Save your own dashcam footage immediately. An attorney can also send a preservation letter for the other driver's dashcam and vehicle event data before it's overwritten &mdash; evidence that often settles the moved-over-or-didn't question definitively.</p>
<p><strong>See a doctor the same day.</strong> A gap between the crash and your first treatment is the insurer's favorite argument that you weren't really hurt.</p>
<p><strong>Be careful with the insurance calls.</strong> Skip the recorded statement until you've had advice. And remember that the at-fault driver may carry only <a href="/blog/california-30-60-15-insurance-minimums/">California's minimum coverage</a> &mdash; even the new 30/60/15 limits fall well short of serious-injury costs &mdash; so identifying every available policy, including your own underinsured motorist coverage, needs to happen early.</p>

<h2>The Bottom Line</h2>
<p>For decades, California's slow down, move over law drew a strange line: it protected the tow truck but not the family the tow truck was coming to rescue. AB 390 erased that line on January 1, 2026. Every driver now owes every stranded vehicle with its flashers on a lane of space or a slower pass, and when someone ignores that duty and a person on the shoulder gets hurt, the violation itself becomes powerful evidence of negligence. Turn on your hazards every time you stop. It is now both your safety measure and your legal protection.</p>

<h2>Hit While Stopped on the Shoulder? Talk to a Folsom Injury Attorney</h2>
<p>If a passing driver struck you or a family member while your vehicle was stopped roadside, the new law may put the weight of proof on your side &mdash; but only if the evidence is preserved quickly. Mason Law, P.C. represents injury victims in <a href="/personal-injury-lawyer-folsom-ca/">Folsom</a> and throughout the Sacramento region, and we know the Highway 50 and I-80 corridors where these crashes happen. We will obtain the CHP report, preserve dashcam and vehicle data, and identify every insurance policy available for your recovery. Consultations are free, and you pay nothing unless we recover for you. Call (916) 587-2997 or reach us through our <a href="/contact.php">contact form</a>.</p>
HTML;

$faqs2 = "What is California's move over law in 2026? :: As of January 1, 2026, drivers approaching any stopped vehicle displaying hazard lights or warning devices, not just emergency vehicles, must change lanes away from it when safe, or slow to a reasonable and prudent speed when a lane change isn't possible. The rule is Vehicle Code section 21809, as amended by AB 390.\n"
. "Does the move over law apply to ordinary cars on the shoulder? :: Yes. That is the heart of the 2026 change. A commuter with a flat tire and flashers on now gets the same legal buffer as a police cruiser or tow truck. The trigger is hazard lights or warning devices such as cones, flares, or reflective triangles.\n"
. "Do I have to move over on city streets, or only on freeways? :: The statute applies on any highway, and the Vehicle Code defines that term broadly enough to reach public roads generally, not just freeways. The one exception: the duty doesn't apply when the stopped vehicle isn't adjacent to the roadway or is separated from it by a physical barrier.\n"
. "What's the fine for a move over violation in California? :: The statute sets a base fine of up to \$50, which court assessments turn into a real-world ticket of roughly \$230 to \$240 in most counties, plus a point on your driving record. Reports of \$1,000 fines don't match the statutory text.\n"
. "What if I couldn't change lanes safely? :: The law accounts for that. Moving over is only required when it's safe, practical, and legal. When it isn't, your obligation is to slow to a speed that's reasonable for the conditions. Doing neither is what creates the violation.\n"
. "Does the driver who hit me need a ticket for AB 390 to matter in my case? :: No. A citation helps, but a civil claim can establish the violation independently through witnesses, dashcam footage, and vehicle data. Once shown, California law presumes the driver was negligent.\n"
. "What if my hazard lights weren't on when I was hit? :: The statutory presumption becomes harder to invoke, but your case doesn't disappear. Every driver still owes general duties of care, and California's comparative fault rules reduce a recovery rather than eliminate it. Talk to an attorney before assuming a detail like this sinks your claim.";

echo upsert($pdo, [
    'title'        => "California's 2026 Move Over Law: What AB 390 Changed for Everyone on the Shoulder",
    'slug'         => 'california-move-over-law-2026',
    'excerpt'      => "As of January 1, 2026, California's Move Over law (AB 390) protects any stopped vehicle with hazard lights, not just emergency vehicles. Here is what changed and why it matters if you're hit roadside.",
    'content'      => $content2,
    'featured_image' => '/assets/images/generated/blog-ca-move-over-law.webp',
    'category_id'  => cat_id($pdo, ['auto-accidents', 'legal-tips', 'your-rights']),
    'author_name'  => $auth['name'],
    'author_slug'  => $auth['slug'],
    'status'       => 'published',
    'published_at' => $plus3,          // scheduled: auto-publishes in 3 days
    'date_modified'=> null,
    'meta_title'   => "California's 2026 Move Over Law: What Changed (AB 390)",
    'meta_desc'    => "California's Move Over law now covers any stopped vehicle with hazard lights on, not just emergency vehicles. What AB 390 means if you're hit roadside.",
    'og_image'     => null,
    'og_image_alt' => "California's 2026 Move Over Law (AB 390) explained — Mason Law, P.C.",
    'faqs'         => $faqs2,
]) . " (scheduled for {$plus3})\n";

echo "\nAuthor: " . ($auth['name'] ?: '(none found)') . "\n";
echo "Blog 1 category_id: " . (cat_id($pdo, ['insurance-claims','insurance','auto-accidents']) ?? 'null') . "\n";
echo "Blog 2 category_id: " . (cat_id($pdo, ['auto-accidents','legal-tips','your-rights']) ?? 'null') . "\n";

@unlink(__FILE__);
echo "\nDONE. This script has removed itself.\n";
