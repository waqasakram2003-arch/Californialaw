<?php
/** TEMP: expand final 5 thin posts w/ verified authorities. Self-deleting. */
if (($_GET['key'] ?? '') !== 'expand3-8t2') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Authorities verified against official sources on 2026-08-25 BEFORE publication:
 *   VEH 20008     — injury/death crash reported within 24 HOURS to CHP or local police
 *   VEH 16000     — DMV SR-1 within 10 days; injury/death/property damage over $1,000
 *   PUC 5433      — TNC: period 1 = 50k/100k/30k + 200k excess; en route/passenger = $1M
 *   INS 790.03(h) — unfair claims settlement practices
 *   CCP 335.1     — 2-year limitations period
 *   CDC           — concussion signs/symptoms may take hours or days to appear
 */
$LEG = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';
$CDI = 'https://www.insurance.ca.gov/01-consumers/101-help/index.cfm';
$CDC = 'https://www.cdc.gov/traumatic-brain-injury/signs-symptoms/index.html';

$POSTS = [];

/* ------------------------------------------------- what to do after a crash */
$POSTS['what-to-do-after-a-car-accident-in-california'] = [
'excerpt' => 'The hours after a California crash shape both your recovery and any claim. Here are the steps that matter, the two legal reporting deadlines most drivers miss, and the mistakes that quietly reduce a claim.',
'meta_desc' => 'What to do after a car accident in California: steps that protect your health and your claim, the 24-hour police report and 10-day DMV SR-1 deadlines, and mistakes to avoid.',
'content' => <<<HTML
<p>A car accident leaves most people shaken and unsure what to do next. The steps you take in the first hours and days can affect both your physical recovery and any future claim. This is general information, not legal advice.</p>

<h2>At the scene</h2>
<ol>
  <li><strong>Check for injuries and call 911.</strong> If anyone is hurt, medical care comes first. Requesting law enforcement also creates an official record.</li>
  <li><strong>Move to safety if you can.</strong> On a freeway or busy road, staying in traffic is often the greater danger.</li>
  <li><strong>Exchange information</strong> — names, contact details, driver's license numbers, insurance carriers and policy numbers, license plates, and vehicle descriptions.</li>
  <li><strong>Photograph everything</strong> before vehicles are moved: all vehicles from several angles, final positions, damage, debris, skid marks, traffic controls, road conditions, and lighting.</li>
  <li><strong>Get witness names and numbers.</strong> Independent witnesses are frequently decisive, and they disperse within minutes.</li>
  <li><strong>Be careful what you say.</strong> Do not speculate about fault or apologize reflexively. A polite "I'm sorry" is regularly repurposed later as an admission.</li>
</ol>

<h2>The two deadlines most drivers do not know</h2>
<p>California imposes two separate reporting duties after a collision, and they run on different clocks.</p>

<h3>1. Report to police within 24 hours (injury or death)</h3>
<p>Under <a href="{$LEG}?lawCode=VEH&sectionNum=20008" target="_blank" rel="noopener">Vehicle Code section 20008</a>, the driver of a vehicle involved in an accident resulting in injury to or death of any person must report it <strong>within 24 hours</strong> to the CHP — or, where the crash occurred within a city, to either the CHP or the local police department.</p>

<h3>2. File a DMV SR-1 within 10 days</h3>
<p><a href="{$LEG}?lawCode=VEH&sectionNum=16000" target="_blank" rel="noopener">Vehicle Code section 16000</a> requires a driver to report the crash to the DMV <strong>within 10 days</strong> where it caused injury, death, or property damage exceeding <strong>&#36;1,000</strong>. That threshold is low enough that most modern collisions meet it. This duty applies whether or not police responded, and is separate from notifying your insurer.</p>

<h2>In the days that follow</h2>
<ul>
  <li><strong>Get examined promptly</strong>, even if you feel fine. Adrenaline masks injuries, and soft-tissue and head injuries often surface later. Treatment gaps are among the first things an insurer uses to argue an injury was not serious.</li>
  <li><strong>Notify your own insurer</strong> as your policy requires.</li>
  <li><strong>Request the collision report</strong> once available — our <a href="/blog/how-to-get-folsom-police-accident-report/">guide to requesting an accident report</a> explains the process.</li>
  <li><strong>Keep everything</strong>: medical records and bills, repair estimates, receipts, mileage to appointments, and a record of work missed.</li>
  <li><strong>Write down what happened</strong> while it is fresh, including road conditions and what each driver said.</li>
</ul>

<h2>Mistakes that quietly reduce a claim</h2>
<ul>
  <li><strong>Giving a recorded statement to the other driver's insurer</strong> before you understand how fault is being assessed. See <a href="/blog/dealing-with-insurance-adjusters-california/">dealing with insurance adjusters</a>.</li>
  <li><strong>Accepting a fast settlement</strong> before the full extent of your injuries is known. Once you sign a release, the claim is generally closed permanently.</li>
  <li><strong>Posting about the crash on social media.</strong> A single photo can be used to dispute your injuries.</li>
  <li><strong>Waiting.</strong> Most California injury claims are subject to a <a href="/blog/california-statute-of-limitations-injury-claims/">two-year deadline</a>, and claims involving a public entity can expire in six months.</li>
</ul>

<h2>Where a lawyer fits in</h2>
<p>Not every fender-bender needs one. But where there are real injuries, disputed fault, multiple vehicles, a commercial or government vehicle, or an insurer already assigning you blame, an early conversation costs nothing. Our <a href="/practice-areas/car-accidents/">California car accident attorneys</a> offer a free case review, and outcomes always depend on the specific facts.</p>
HTML,
'faqs' => [
  ['question' => 'Do I have to report a car accident in California?',
   'answer' => 'Yes, in two ways. Vehicle Code section 20008 requires the driver to report a crash involving injury or death within 24 hours to the CHP or local police. Separately, Vehicle Code section 16000 requires a DMV SR-1 within 10 days if there was injury, death, or property damage over $1,000.'],
  ['question' => 'Should I see a doctor even if I feel fine after a crash?',
   'answer' => 'Yes. Adrenaline can mask injuries, and soft-tissue and head injuries often appear hours or days later. Prompt evaluation protects your health and creates the medical record documenting that the injury came from the collision.'],
  ['question' => 'Should I give a recorded statement to the other driver\'s insurance company?',
   'answer' => 'Be cautious. You are generally not required to give a recorded statement to the other driver\'s insurer, and those statements are frequently used to establish comparative fault or minimize injuries. Consider speaking with an attorney first.'],
  ['question' => 'How long do I have to file a claim after a California car accident?',
   'answer' => 'Generally two years for personal injury under Code of Civil Procedure section 335.1, and three years for property damage. Claims against a public entity usually require a written government claim within six months.'],
],
];

/* ------------------------------------------------------- insurance adjusters */
$POSTS['dealing-with-insurance-adjusters-california'] = [
'excerpt' => 'The adjuster is friendly, but they work for the insurer. Here is what California law requires of them, what a recorded statement is really for, and how to handle the first offer without damaging your claim.',
'meta_desc' => 'Dealing with insurance adjusters in California: what Insurance Code 790.03 requires, why recorded statements are risky, how first offers work, and how to protect your claim.',
'content' => <<<HTML
<p>After a collision, an adjuster usually calls quickly. They are typically professional and sympathetic — and they are paid by a company whose financial interest is in resolving your claim for as little as possible. Understanding that is not cynicism; it is context. This is general information, not legal advice.</p>

<h2>Your insurer versus their insurer</h2>
<p>These are two different relationships. Your own insurer owes you contractual duties, including the covenant of good faith and fair dealing, and your policy generally requires you to cooperate. The <em>other</em> driver's insurer owes you no such duty. Their adjuster's job is to evaluate and limit their insured's exposure.</p>

<h2>What California law requires of adjusters</h2>
<p><a href="{$LEG}?lawCode=INS&sectionNum=790.03" target="_blank" rel="noopener">California Insurance Code section 790.03(h)</a> defines unfair claims settlement practices. Among the practices it prohibits when knowingly committed, or performed with such frequency as to indicate a general business practice:</p>
<ul>
  <li>Failing to acknowledge and act reasonably promptly upon claim communications</li>
  <li>Failing to adopt reasonable standards for prompt investigation and processing of claims</li>
  <li>Misrepresenting policy provisions or pertinent facts</li>
  <li>Failing to attempt a prompt, fair, and equitable settlement once liability has become reasonably clear</li>
  <li>Compelling insureds to litigate by offering substantially less than what is ultimately recovered</li>
  <li>Failing to provide a reasonable explanation for a denial or a compromise offer</li>
</ul>
<p>Adjusters are professionals operating within these rules. Knowing the rules exist helps you recognize when a claim is being handled unreasonably. The <a href="{$CDI}" target="_blank" rel="noopener">California Department of Insurance</a> accepts consumer complaints about claim handling.</p>

<h2>The recorded statement</h2>
<p>A request for a recorded statement usually arrives early and is framed as routine. It is worth understanding its purpose: the adjuster is trained to gather answers that establish comparative fault or minimize the injury.</p>
<p>Common patterns include opening with "how are you feeling?" — where a polite "I'm fine, thanks" becomes evidence you were not hurt — asking you to estimate speeds and distances you cannot actually know, and probing any gap between the crash and your first treatment.</p>
<p>You are generally not obligated to give a recorded statement to the <em>other</em> driver's insurer. Your own policy may require cooperation, which is different from being required to speculate.</p>

<h2>The first offer</h2>
<p>Early offers frequently arrive before the full scope of injuries is known, and once you sign a release the claim is generally closed permanently — even if you later need surgery. An offer is an opening position, not an assessment of what a claim is worth. Our guide to <a href="/blog/how-much-is-my-california-car-accident-case-worth/">what a California car accident case is worth</a> explains what actually drives value.</p>

<h2>Practical ways to protect your claim</h2>
<ul>
  <li><strong>Be truthful, brief, and factual.</strong> Never guess. "I don't know" is a complete answer.</li>
  <li><strong>Do not describe injuries as minor</strong> before treatment is complete.</li>
  <li><strong>Keep treating consistently.</strong> Gaps are the most commonly used argument against injury claims.</li>
  <li><strong>Put important communications in writing</strong> and keep a log of calls, names, and claim numbers.</li>
  <li><strong>Do not sign a blanket medical authorization</strong> without understanding its scope — broad releases can open unrelated medical history.</li>
  <li><strong>Watch the deadline.</strong> Negotiations do not pause the <a href="/blog/california-statute-of-limitations-injury-claims/">statute of limitations</a>.</li>
</ul>

<h2>When representation changes the dynamic</h2>
<p>When an insurer knows the other side is prepared to litigate, the negotiating calculus changes. If liability is disputed, injuries are significant, or you are being pushed toward a quick release, our <a href="/practice-areas/car-accidents/">California injury attorneys</a> offer a free consultation. Every case is different.</p>
HTML,
'faqs' => [
  ['question' => 'Do I have to give a recorded statement to the other driver\'s insurance company?',
   'answer' => 'Generally no. You are typically not obligated to give a recorded statement to the other driver\'s insurer. Your own policy may require cooperation with your insurer, which is different from being required to speculate about speeds, distances, or fault.'],
  ['question' => 'Should I accept the first settlement offer?',
   'answer' => 'Usually not without careful review. First offers often come before the full extent of injuries and future treatment is known, and signing a release generally closes the claim permanently, even if you later require additional care.'],
  ['question' => 'What counts as an unfair claims practice in California?',
   'answer' => 'Insurance Code section 790.03(h) lists prohibited practices including failing to respond promptly to communications, misrepresenting policy provisions, failing to attempt a prompt and fair settlement once liability is reasonably clear, and failing to explain a denial or compromise offer.'],
  ['question' => 'Can I report an insurance company in California?',
   'answer' => 'Yes. The California Department of Insurance accepts consumer complaints about claim handling and can investigate practices that may violate the Insurance Code.'],
],
];

/* ------------------------------------------------------------ case timeline */
$POSTS['how-long-does-a-california-injury-case-take'] = [
'excerpt' => 'Some California injury claims resolve in months; others take years. Here is what happens at each stage, why medical treatment sets the pace, and the factors that lengthen or shorten a case.',
'meta_desc' => 'How long does a California personal injury case take? The stages from treatment to settlement or trial, what causes delays, and why rushing a claim usually costs money.',
'content' => <<<HTML
<p>"How long will this take?" is one of the first questions injured people ask, and the honest answer is that it depends on facts not all knowable at the start. Some claims resolve in a few months. Others run for years. What follows is the realistic shape of the process. This is general information, not legal advice.</p>

<h2>The stages of a California injury claim</h2>

<h3>1. Medical treatment and recovery</h3>
<p>This stage drives everything else. Most claims are not seriously negotiated until you reach <strong>maximum medical improvement</strong> — the point where your condition has stabilized and your doctors can describe what future care you will need. Settling before then means guessing at the cost of your own recovery, and guessing low is permanent.</p>

<h3>2. Investigation and evidence gathering</h3>
<p>Running in parallel: obtaining the collision report, securing video before it is overwritten, interviewing witnesses, documenting the scene, identifying every applicable insurance policy, and collecting medical records and wage documentation.</p>

<h3>3. The demand and negotiation</h3>
<p>Once treatment stabilizes, a demand package goes to the insurer setting out liability and <a href="/blog/damages-in-a-california-injury-claim/">damages</a>. Negotiation follows — sometimes weeks, sometimes considerably longer where fault is disputed. Most claims resolve at this stage.</p>

<h3>4. Filing suit, if needed</h3>
<p>If the insurer will not offer a reasonable amount, a lawsuit is filed. Filing does not make a trial inevitable — the large majority of filed cases still settle — but it moves the case onto the court's schedule, which adds time.</p>

<h3>5. Discovery, mediation, trial</h3>
<p>Discovery — written questions, document exchange, depositions, and expert opinions — is usually the longest phase of litigation. Many cases resolve at mediation. A small minority reach trial.</p>

<h2>What lengthens a case</h2>
<ul>
  <li><strong>Serious or permanent injuries</strong>, where future care must be projected carefully</li>
  <li><strong>Disputed liability</strong>, including <a href="/blog/how-comparative-fault-works-in-california/">comparative fault</a> arguments</li>
  <li><strong>Multiple parties or policies</strong>, each with separate counsel and interests</li>
  <li><strong>Government defendants</strong>, which carry their own claim procedures and short presentation deadlines</li>
  <li><strong>Policy limits problems</strong>, requiring uninsured or underinsured motorist claims</li>
  <li><strong>Court congestion</strong>, which varies by county</li>
</ul>

<h2>What shortens it</h2>
<ul>
  <li>Clear liability — a rear-end collision, or a citation issued at the scene</li>
  <li>Injuries that resolve fully and are well documented</li>
  <li>Prompt, consistent medical treatment with no gaps</li>
  <li>Adequate insurance coverage on the other side</li>
  <li>Early, organized evidence preservation</li>
</ul>

<h2>The deadline running underneath all of it</h2>
<p>Negotiation does not stop the clock. Most California injury actions must be filed within two years under <a href="{$LEG}?lawCode=CCP&sectionNum=335.1" target="_blank" rel="noopener">Code of Civil Procedure section 335.1</a>, and claims against public entities generally require a written claim within six months. A case that drifts while an insurer is "still reviewing" can quietly lose its leverage — or its viability.</p>

<h2>Why fast is not always better</h2>
<p>The quickest resolution is to accept the first offer, which is also the most reliable way to be underpaid. The goal is not speed; it is a resolution that accounts for the care you will still need. Our <a href="/practice-areas/car-accidents/">California injury attorneys</a> can give you a realistic assessment of timing at no cost. Outcomes vary with the facts of each case.</p>
HTML,
'faqs' => [
  ['question' => 'How long does a personal injury case take in California?',
   'answer' => 'It varies widely. Straightforward claims with clear liability and complete recovery may resolve in a few months, while cases involving serious injuries, disputed fault, or litigation can take one to several years. The pace is largely set by medical treatment and whether the insurer negotiates reasonably.'],
  ['question' => 'Why does my lawyer want to wait before settling?',
   'answer' => 'Most claims are not settled until you reach maximum medical improvement, when doctors can describe the future care you will need. Settling earlier means estimating the cost of your own recovery, and a release generally cannot be reopened if that estimate was wrong.'],
  ['question' => 'Does filing a lawsuit mean my case will go to trial?',
   'answer' => 'No. The large majority of filed personal injury cases still settle, often at mediation. Filing suit primarily preserves the deadline and increases pressure when an insurer will not negotiate reasonably.'],
  ['question' => 'Does negotiating with the insurance company extend my filing deadline?',
   'answer' => 'No. The statute of limitations continues to run during negotiations. In California most injury actions must be filed within two years, and claims against public entities generally require a written claim within six months.'],
],
];

/* -------------------------------------------------------------- uber / lyft */
$POSTS['uber-lyft-accidents-california-insurance'] = [
'excerpt' => 'In a California rideshare crash, which policy pays depends on exactly what the driver was doing at that moment. Here are the three coverage periods set by state law, and the dollar figures attached to each.',
'meta_desc' => 'Uber and Lyft accidents in California: the TNC insurance periods under Public Utilities Code 5433, the coverage amounts for each, and how to protect your claim.',
'content' => <<<HTML
<p>Rideshare crashes are more complicated than ordinary collisions for one reason: coverage depends on the driver's status in the app at the moment of impact. California sets those rules by statute, and the difference between periods can be enormous. This is general information, not legal advice.</p>

<h2>The coverage periods</h2>
<p>California regulates transportation network companies (TNCs) such as Uber and Lyft under <a href="{$LEG}?lawCode=PUC&sectionNum=5433" target="_blank" rel="noopener">Public Utilities Code section 5433</a>, which sets minimum coverage tied to what the driver was doing.</p>

<h3>App off — personal driving</h3>
<p>The driver is not logged in. Only their <strong>personal auto policy</strong> applies; the TNC's statutory coverage is not implicated. Practically, this behaves like any ordinary <a href="/blog/how-much-is-my-california-car-accident-case-worth/">car accident claim</a>.</p>

<h3>App on, waiting for a ride request</h3>
<p>The driver is logged in and available but has not been matched. Section 5433 requires primary coverage of <strong>&#36;50,000 per person</strong> for death and personal injury, <strong>&#36;100,000 per incident</strong>, and <strong>&#36;30,000 for property damage</strong>, plus <strong>&#36;200,000 per occurrence</strong> in excess coverage.</p>
<p>This period is where disputes concentrate, because many personal auto policies exclude commercial activity — leaving the TNC coverage as the meaningful source.</p>

<h3>En route to a passenger, or passenger on board</h3>
<p>From acceptance of a ride through completion, section 5433 requires <strong>&#36;1,000,000</strong> in coverage for death, personal injury, and property damage. The statute also addresses uninsured and underinsured motorist coverage during this period.</p>

<h2>Why the period matters so much</h2>
<p>The same collision can be a &#36;50,000-limit claim or a &#36;1,000,000-limit claim depending on whether the driver accepted a ride seconds earlier. That is why establishing app status is one of the first things worth documenting.</p>

<h2>If you were a passenger</h2>
<p>Passengers are generally in the strongest position: you are almost never at fault, and the &#36;1,000,000 tier typically applies because a ride is in progress. Screenshot your trip receipt and ride details immediately — they establish the period.</p>

<h2>If you were hit by a rideshare driver</h2>
<p>Note the company, capture the app status if visible, photograph any trade dress, and obtain the collision report. Expect the driver's personal insurer and the TNC's insurer each to examine whether the other is responsible — a dispute that can stall a claim.</p>

<h2>When limits fall short</h2>
<p>Serious injuries can exceed even substantial limits. Your own uninsured/underinsured motorist coverage follows you and can matter here, as can <a href="/blog/california-30-60-15-insurance-minimums/">California's low minimum limits</a> on any personal policy in play.</p>

<h2>Protecting the claim</h2>
<ul>
  <li>Screenshot the trip, receipt, and driver details before they disappear from your history</li>
  <li>Report through the app, but be careful about detailed statements before you understand the coverage picture</li>
  <li>Seek medical attention promptly and treat consistently</li>
  <li>Preserve dashcam footage and witness contacts</li>
  <li>Watch the <a href="/blog/california-statute-of-limitations-injury-claims/">filing deadline</a></li>
</ul>
<p>Our <a href="/practice-areas/rideshare-accidents/">rideshare accident</a> attorneys can identify which policies apply and offer a free case review. Results depend on the facts of each case.</p>
HTML,
'faqs' => [
  ['question' => 'Whose insurance pays in an Uber or Lyft accident in California?',
   'answer' => 'It depends on the driver\'s status in the app. With the app off, the driver\'s personal policy applies. While logged in and waiting for a request, Public Utilities Code section 5433 requires $50,000 per person, $100,000 per incident, and $30,000 property damage, plus $200,000 excess. From ride acceptance through drop-off, $1,000,000 in coverage is required.'],
  ['question' => 'How much insurance does Uber carry in California?',
   'answer' => 'Under Public Utilities Code section 5433, $1,000,000 in coverage applies while a driver is en route to a passenger or has a passenger on board. A lower tier applies while the driver is logged in but has not yet accepted a ride.'],
  ['question' => 'What if I was a passenger in the rideshare vehicle?',
   'answer' => 'Passengers are rarely at fault and are generally covered under the $1,000,000 tier, since a ride is in progress. Save your trip receipt and ride details immediately, as they establish which coverage period applied.'],
  ['question' => 'Does my personal auto insurance cover rideshare driving?',
   'answer' => 'Often not. Many personal auto policies exclude commercial or rideshare activity, which is why the statutory TNC coverage frequently becomes the meaningful source of recovery. Some insurers offer rideshare endorsements that fill the gap.'],
],
];

/* --------------------------------------------------------------------- TBI */
$POSTS['understanding-traumatic-brain-injuries'] = [
'excerpt' => 'A traumatic brain injury does not require losing consciousness, and symptoms can take days to appear. Here is how TBIs happen in accidents, why they are under-diagnosed, and what makes these claims different.',
'meta_desc' => 'Traumatic brain injuries after an accident: how TBIs occur, why symptoms can be delayed, mild versus severe TBI, and what makes these injury claims complex.',
'content' => <<<HTML
<p>Traumatic brain injuries are among the most serious consequences of an accident — and among the most frequently missed. They do not require a skull fracture, a visible wound, or even a loss of consciousness. This is general information, not medical or legal advice; anyone with a suspected head injury should be evaluated by a qualified professional.</p>

<h2>What a TBI is</h2>
<p>A traumatic brain injury results from a bump, blow, or jolt to the head — or from a hit to the body forceful enough to move the head and brain rapidly back and forth. That second mechanism is why TBIs occur in collisions where the head never strikes anything: the brain moves within the skull.</p>
<p>A <strong>concussion</strong> is a form of mild TBI. "Mild" describes the initial clinical presentation, not the effect on a person's life, which can be substantial and lasting.</p>

<h2>Symptoms are often delayed</h2>
<p>The <a href="{$CDC}" target="_blank" rel="noopener">CDC notes</a> that concussion signs and symptoms may not appear right away — they can take hours or days to show up or be noticed. This is precisely why "I felt fine at the scene" is so common, and why prompt evaluation matters even when you walked away.</p>
<p>Commonly reported symptoms include:</p>
<ul>
  <li><strong>Physical</strong> — headache, nausea, dizziness, balance problems, sensitivity to light or noise, blurred vision, fatigue</li>
  <li><strong>Cognitive</strong> — difficulty concentrating, memory problems, mental fog, slowed processing</li>
  <li><strong>Emotional</strong> — irritability, anxiety, mood changes, uncharacteristic emotional reactions</li>
  <li><strong>Sleep</strong> — sleeping more or less than usual, or difficulty falling asleep</li>
</ul>
<p>The CDC advises seeking emergency care for danger signs including a headache that worsens, weakness, numbness, decreased coordination, convulsions, or seizures.</p>

<h2>How TBIs happen in accidents</h2>
<p>Motor vehicle collisions, motorcycle and bicycle crashes, pedestrian impacts, falls, and workplace incidents are all common mechanisms. Helmets and airbags reduce risk substantially but cannot eliminate it, because the injury is caused by the brain's movement rather than solely by external contact.</p>

<h2>Why TBI claims are different</h2>
<ul>
  <li><strong>Imaging is often normal.</strong> A routine CT scan can appear unremarkable in mild TBI, which insurers use to argue nothing happened.</li>
  <li><strong>The injury is invisible.</strong> There is no cast or scar to point to.</li>
  <li><strong>The effects are cognitive and behavioral</strong> — struggling at work, losing patience with family — and are easily attributed to stress instead.</li>
  <li><strong>Documentation frequently requires specialists</strong>, including neurologists and neuropsychological testing.</li>
  <li><strong>Future losses can be significant</strong>, particularly where the injury affects <a href="/blog/damages-in-a-california-injury-claim/">earning capacity</a>.</li>
</ul>

<h2>What helps</h2>
<p>Prompt evaluation and consistent follow-up create the medical record. Beyond that, contemporaneous notes from the injured person and from family members about concrete changes — missed appointments, difficulty following conversations, an inability to handle tasks that used to be routine — are often more persuasive than a general description of "not feeling right."</p>
<p>Because these injuries can evolve, resolving a claim before the picture is clear carries real risk, which also affects <a href="/blog/how-long-does-a-california-injury-case-take/">how long a case takes</a>.</p>

<h2>Getting help</h2>
<p>If you or a family member suffered a head injury in an accident, seek medical care first. Our <a href="/practice-areas/brain-injuries/">brain injury</a> attorneys can then review the circumstances at no cost. Every case is different, and outcomes depend on the specific facts and medical evidence.</p>
HTML,
'faqs' => [
  ['question' => 'Can you have a brain injury without losing consciousness?',
   'answer' => 'Yes. Most concussions occur without any loss of consciousness. A TBI can result from a jolt to the body that moves the head and brain rapidly, even when the head never strikes anything.'],
  ['question' => 'How soon do concussion symptoms appear?',
   'answer' => 'Not always right away. The CDC notes that concussion signs and symptoms may take hours or days to appear or be noticed, which is why prompt medical evaluation matters even if you felt fine at the scene.'],
  ['question' => 'Why do insurers dispute mild TBI claims?',
   'answer' => 'Routine imaging such as a CT scan is often normal in mild TBI, and the effects are cognitive and behavioral rather than visible. Insurers use that absence of obvious evidence to argue the injury is minor or unrelated, which is why specialist documentation matters.'],
  ['question' => 'What should I do if I hit my head in an accident?',
   'answer' => 'Seek medical evaluation promptly even if you feel able to continue, and seek emergency care for danger signs such as a worsening headache, weakness, numbness, decreased coordination, convulsions, or seizures. Follow up consistently and document changes you or your family notice.'],
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
        $set  = ['content = :c', 'excerpt = :e', 'meta_desc = :m'];
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
