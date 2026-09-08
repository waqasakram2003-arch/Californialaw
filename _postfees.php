<?php
/** TEMP blog publisher: California personal injury lawyer fees. Key-guarded, self-deleting. */
if (($_GET['key'] ?? '') !== 'fees-2w6') { http_response_code(404); exit; }
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/includes/db.php';

/**
 * Verified 2026-09-08 on leginfo.legislature.ca.gov / courts.ca.gov before publication:
 *   B&P 6147   — contingency agreement must be written + signed with a duplicate copy to
 *                the client; must state the fee rate; how disbursements/costs affect the fee
 *                AND the client's recovery; what compensation (if any) is owed for related
 *                matters; and the negotiability statement ("not set by law but negotiable"),
 *                or for 6146 claims that the rates are maximum limits. Non-compliance =>
 *                voidable at the plaintiff's option, attorney entitled to a reasonable fee.
 *   B&P 6146   — medical malpractice caps: 25% before a complaint/arbitration demand is
 *                filed, 33% after. PLUS a motion-for-higher-fee exception on good cause
 *                where the case goes to trial/arbitration (the draft omitted this; added).
 *   Filing fee — $435 statewide base for a first paper in an unlimited civil case
 *                (Statewide Civil Fee Schedule, eff. 1/1/2023); some counties add a
 *                surcharge. Stated precisely rather than as a vague "$435-$450".
 *
 * ⚠ FIRM REVIEW FLAG (from the brief): the percentage ranges are MARKET-STANDARD, not
 * Mason Law's own rates. Nothing in this post asserts what Mason Law charges. Copy is
 * written so no sentence can be read as a firm-specific fee claim, and an explicit line
 * directs the reader to the firm's own written agreement. Flagged to the user to confirm
 * the ranges sit alongside their actual retainer.
 */
$LEG = 'https://leginfo.legislature.ca.gov/faces/codes_displaySection.xhtml';
$FEE = 'https://courts.ca.gov/sites/default/files/courts/default/2024-12/statewide-civil-fee-schedule-eff-01012023.pdf';

$slug     = 'personal-injury-lawyer-fees-california';
$title    = 'What Does a Personal Injury Lawyer Cost in California? The Line-by-Line Answer';
$excerpt  = 'Nothing upfront — but "no win, no fee" has a precise meaning, and the fee is only one line on the final accounting. Here is what you pay, what happens if you lose, and the actual math from settlement check to bank account.';
$metaT    = 'Personal Injury Lawyer Fees in California: What You Pay';
$metaD    = 'How contingency fees work in California — the percentage lawyers take, fees vs. case costs, and what you owe if you lose. Explained line by line.';
$featured = '/assets/images/generated/blog-pi-fees-featured.webp';
$aname    = 'Elena Marquez';
$aslug    = 'elena-marquez';

$content = <<<HTML
<p>How much does a personal injury lawyer cost in California? It is usually the first question people want to ask and the last one they actually do — because asking about money feels awkward, and because the fee system has just enough moving parts (percentages, costs, liens) that vague answers are easy to hide behind.</p>
<p>This guide skips the vagueness: what you pay, when you pay it, what happens if you lose, and the part almost nobody publishes — the actual math from the settlement check to your bank account. This is general information, not legal advice.</p>

<h2>The short answer: nothing upfront</h2>
<p><strong>Nothing upfront.</strong> California personal injury lawyers work on contingency: the fee is a percentage of what they recover — typically 33⅓% to 40% across the market — paid out of the settlement at the end. No recovery generally means no fee, though how case <em>costs</em> are handled varies by agreement.</p>
<p>That last clause is doing important work, and the rest of this guide unpacks it. The "no win, no fee" promise is real, but <em>fee</em> has a precise meaning in a retainer agreement, and it is not the only line on the final accounting.</p>

<h2>How contingency fees work</h2>
<p>A contingency fee ties the lawyer's payment to the outcome: instead of billing by the hour, the firm invests its time and money into your case and is paid a percentage of the result. If the result is zero, the fee is zero.</p>
<p>The arrangement exists for a simple reason — almost nobody with a hospital bill and missed paychecks can pay a litigator by the hour, and contingency puts trial-quality representation within reach of anyone with a strong case.</p>

<h3>What percentage do injury lawyers take?</h3>
<p>Across the California market, contingency rates in ordinary injury cases generally run from <strong>33⅓% (one third) to 40%</strong>, most often structured in tiers tied to how far the case goes. A common structure is one third if the case settles before a lawsuit is filed, stepping up to 40% once litigation begins; some firms add a middle tier or a separate trial tier.</p>
<p>There is <strong>no statutory cap for ordinary injury cases</strong> — the rate is set by the agreement — with one notable exception covered next. Fees in cases involving injured minors carry an extra layer of protection, because a court must approve a minor's compromise, including the fee.</p>
<p><strong>An important distinction:</strong> the ranges above describe the market, not any particular firm. Every firm's actual percentages, tiers, and cost policy live in its own written agreement — which is exactly why the questions further down matter.</p>

<h3>The medical malpractice exception</h3>
<p>Medical malpractice is the one area where California caps the rate by statute. Under <a href="{$LEG}?lawCode=BPC&sectionNum=6146." target="_blank" rel="noopener">Business and Professions Code section 6146</a>, an attorney may not contract for or collect more than <strong>25%</strong> of the recovery where the matter resolves before a civil complaint or arbitration demand is filed, or <strong>33%</strong> after filing. Where a case proceeds to trial or arbitration, the statute allows an attorney to move the court for a higher fee on evidence establishing good cause.</p>

<h3>Why the percentage steps up when a lawsuit is filed</h3>
<p>The tiers are not arbitrary. A pre-suit settlement might represent a few months of investigation, demand work, and negotiation. A filed lawsuit means court deadlines, written discovery, depositions, expert witnesses, motion practice, and trial preparation — several times the attorney hours and a much larger outlay of advanced costs, with the same zero-recovery risk attached.</p>
<p>The practical takeaway for clients: a good firm does not file suit to trigger a higher tier. It files because the insurer's best pre-suit number is worse than the expected result of litigating — and that calculation should be explained to you, not made silently.</p>

<h2>What California law requires in the fee agreement</h2>
<p>This is not a handshake area. Under <a href="{$LEG}?lawCode=BPC&sectionNum=6147." target="_blank" rel="noopener">Business and Professions Code section 6147</a>, a contingency fee agreement must be in writing and signed by both you and the attorney, with a duplicate copy given to you at the time the contract is made. It must also state:</p>
<ul>
  <li>the <strong>contingency fee rate</strong> you and the attorney agreed on;</li>
  <li>how <strong>disbursements and costs</strong> will affect both the fee and your recovery;</li>
  <li>to what extent, if any, you could owe <strong>compensation for related matters</strong> the agreement does not cover; and</li>
  <li>that the fee <strong>is not set by law and is negotiable</strong> between attorney and client (for medical malpractice claims, that the section 6146 rates are maximum limits and the fee may be negotiated below them).</li>
</ul>
<p>Failure to comply with any provision of section 6147 renders the agreement <strong>voidable at the client's option</strong>, and the attorney is then entitled only to a reasonable fee. If a firm hands you something that does not check these boxes, that tells you something before a single call is made on your case.</p>

<h2>Fees vs. costs: the distinction that confuses everyone</h2>
<p>Here is the sentence that prevents the most common surprise in personal injury representation: <strong>the fee and the costs are two different lines</strong>, and "no fee if we lose" is a statement about only one of them.</p>

<h3>What counts as a cost</h3>
<p>The fee pays the lawyers. Costs are the out-of-pocket expenses of building the case: court filing fees, medical records and the copy services that produce them, the police report, deposition court reporters and transcripts, process servers, mediator fees, trial exhibits — and, in serious cases, expert witnesses, whose fees are routinely the largest single cost item.</p>
<p>For scale: under the <a href="{$FEE}" target="_blank" rel="noopener">Statewide Civil Fee Schedule</a>, the base fee for a first paper in an unlimited civil case is <strong>&#36;435</strong>, with some counties adding a surcharge. A straightforward pre-suit settlement might accumulate a few hundred to a couple of thousand dollars in costs; a litigated case with medical experts can run well into five figures.</p>

<h3>Who advances them, and who repays</h3>
<p>At virtually every contingency firm the firm advances costs as the case proceeds — you write no checks along the way. When the case resolves, those advanced costs are repaid out of the settlement as their own line item, separate from the fee.</p>
<p>One detail worth asking about, because it changes real money: whether the percentage is calculated on the <strong>gross</strong> settlement before costs come out, or on the <strong>net</strong> after. Section 6147 requires the agreement to tell you how it works either way.</p>

<h2>What you owe if you lose</h2>
<p>The honest answer has two parts. The fee part is simple: <strong>no recovery, no attorney's fee.</strong> That is the core of the contingency bargain.</p>
<p>The costs part varies by agreement, and this is the read-before-signing moment of the entire relationship. Some firms absorb advanced costs entirely on a loss — a true "no win, no fee, no costs." Others write the agreement so the client remains responsible for repaying costs even if the case fails. Both structures are lawful; section 6147 simply requires the agreement to say which one you are signing.</p>
<p>In everyday practice the distinction matters less than it sounds — contingency firms decline cases they expect to lose, and a firm pursuing a former client for cost repayment is uncommon. But "usually does not happen" is not the same as "cannot happen." Read the costs clause, ask the question out loud, and have the answer pointed to on the page.</p>

<figure>
  <img src="/assets/images/generated/blog-pi-fees-math.webp" alt="A calculator resting on case files and paperwork" width="1200" height="750" loading="lazy" decoding="async">
  <figcaption>The fee is one line. Costs and liens are separate lines — and all three decide what actually reaches you.</figcaption>
</figure>

<h2>A worked example: from settlement to your bank account</h2>
<p>Numbers make this real, so here is an <strong>illustrative example</strong> — round figures, not any actual case — of an &#36;85,000 settlement in a pre-litigation car accident claim with a one-third fee:</p>

<table>
  <thead><tr><th>From gross settlement to net recovery — illustrative</th><th>&nbsp;</th></tr></thead>
  <tbody>
    <tr><td>Gross settlement</td><td>&#36;85,000</td></tr>
    <tr><td>Attorney's fee (33⅓% of gross)</td><td>&minus; &#36;28,333</td></tr>
    <tr><td>Case costs advanced by the firm (records, report, filings)</td><td>&minus; &#36;1,850</td></tr>
    <tr><td>Health insurance lien — asserted at &#36;9,200, negotiated down</td><td>&minus; &#36;5,500</td></tr>
    <tr><td><strong>Net to client</strong></td><td><strong>&#36;49,317</strong></td></tr>
  </tbody>
</table>

<p>Three things in that table deserve attention.</p>
<p><strong>First, the lien line.</strong> Your health insurer typically has a right to reimbursement from your settlement for crash-related treatment it paid for, and that lien gets negotiated. Here, the reduction from &#36;9,200 to &#36;5,500 put &#36;3,700 back in the client's pocket — money that is easy to miss because it shows up as a smaller deduction rather than a bigger check. Lien negotiation is one of the least visible things a firm does and one of the most valuable.</p>
<p><strong>Second, the order of operations</strong> — fee on gross, then costs, then liens — is a common structure, and yours should be spelled out in your agreement.</p>
<p><strong>Third, and most important:</strong> the right comparison is not &#36;49,317 versus &#36;85,000. It is &#36;49,317 versus what the insurer was offering before a firm got involved.</p>
<p>Attorney fees in a car accident case follow this same anatomy whether the settlement is &#36;20,000 or &#36;2 million — only the numbers change. Our guide to <a href="/blog/damages-in-a-california-injury-claim/">the damages you can recover</a> covers what makes up the gross figure in the first place.</p>

<h2>Questions to ask before signing any fee agreement</h2>
<p>Take this list into any consultation — ours included. A firm that answers crisply has nothing to hide; a firm that gets vague just told you something valuable.</p>
<ol>
  <li>What is the exact percentage, and does it step up if a lawsuit is filed — or at any other stage?</li>
  <li>Is the fee calculated on the gross settlement, or after costs are deducted?</li>
  <li>Who advances case costs, and do I owe them back if we lose?</li>
  <li>Roughly what costs do you expect in a case like mine?</li>
  <li>Who negotiates my medical liens, and is there any separate charge for that work?</li>
  <li>Will you tell me the fee-and-cost breakdown of any settlement offer before I am asked to accept it?</li>
  <li>If I have questions mid-case, who do I actually reach — and how fast?</li>
  <li>Does this agreement include everything section 6147 requires, including the statement that the fee is negotiable?</li>
</ol>
<p>Any California firm worth hiring will respect you more, not less, for asking all eight.</p>

<h2>Can you switch lawyers mid-case?</h2>
<p>Yes. A client may discharge their attorney at any time, for any reason, and it happens more often than people think. The mechanics matter, though: your former attorney generally holds a lien for the reasonable value of the work already performed, payable out of the eventual recovery.</p>
<p>In practice the old firm and the new firm typically divide a single contingency fee between them based on their respective contributions — meaning you do not pay two full fees for switching. If communication has broken down or you have lost confidence, the fee structure should not be the thing keeping you in place. A second opinion costs nothing.</p>

<h2>Is the fee actually worth it?</h2>
<p>An honest framing: it depends on the case. A minor crash with no real injury and a fair property-damage offer does not need a lawyer, and a good one will tell you so at the free consultation.</p>
<p>Where injuries are real, the calculation shifts for structural reasons rather than magic. Insurers value claims against the risk of what a jury might award, and an unrepresented claimant — who cannot credibly take a case to trial — presents comparatively little risk. Adjusters also understand timing: early offers arrive before the full extent of treatment is known, precisely because that is when claims are cheapest. Our guide to <a href="/blog/dealing-with-insurance-adjusters-california/">dealing with insurance adjusters</a> covers those tactics directly.</p>
<p>A firm changes the equation on both fronts, and adds the components that do not show up in a first offer at all: future medical care, lost earning capacity, properly documented pain and suffering, every applicable insurance policy, and negotiated liens.</p>
<p>So the question is not "is one third a lot?" It is "is my net <em>with</em> representation likely to beat my net <em>without</em> it?" For seriously injured people the answer is often yes — and a free consultation is the no-cost way to find out whether you are in that category. For the time investment involved, see <a href="/blog/how-long-does-a-california-injury-case-take/">how long a California injury case takes</a>, and note that the <a href="/blog/california-statute-of-limitations-injury-claims/">filing deadlines</a> run whether or not you have hired anyone.</p>

<h2>The bottom line</h2>
<p>You pay nothing to start, nothing along the way, and — if there is no recovery — no fee at the end. What you give up is a defined percentage of a successful result, minus the case costs and liens that exist whether or not a lawyer is involved.</p>
<p>The system's honesty lives in the paperwork: section 6147 forces every material term onto the page, and the eight questions above force the rest into the open. Read the agreement, run the math, and hire the firm that welcomes both.</p>
<p>At Mason Law, P.C. the fee conversation happens first, not last. In a <a href="/case-evaluation.php">free case evaluation</a> we will walk through our fee agreement line by line — the percentage, the tiers, the cost policy, the lien handling — and give you an honest read on whether your case justifies a lawyer at all. <a href="/about.php">Get to know our firm</a>, bring the eight questions above, and you will leave with real answers either way. If your crash happened locally, our <a href="/blog/folsom-car-accident-guide/">Folsom car accident guide</a> covers what else to expect. Call <a href="tel:+19165872997">(916) 587-2997</a> to get started — you pay nothing unless we recover for you, and every case turns on its own facts.</p>
HTML;

$faqs = json_encode([
    ['question' => 'What percentage do personal injury lawyers take in California?',
     'answer'   => 'Across the California market, contingency rates in ordinary injury cases generally run from 33 1/3% to 40% of the recovery, usually structured in tiers — commonly one third if the case settles before a lawsuit and a higher rate after filing. Medical malpractice fees are capped by statute, and fees in a minor\'s case require court approval. Any particular firm\'s rate is set by its own written agreement.'],
    ['question' => 'Do I pay anything upfront for a personal injury lawyer?',
     'answer'   => 'No. Contingency representation means no retainer, no hourly bills, and no invoices during the case. The firm advances case costs and is paid its fee and reimbursed those costs out of the settlement at the end.'],
    ['question' => 'What are "case costs" in an injury claim?',
     'answer'   => 'The out-of-pocket expenses of building the case: court filing fees, medical records, the police report, deposition transcripts, mediators, and expert witnesses. They are separate from the attorney\'s fee and appear as their own line in the final settlement accounting. The statewide base fee for a first paper in an unlimited civil case is $435, and some counties add a surcharge.'],
    ['question' => 'What happens if I lose my personal injury case?',
     'answer'   => 'You owe no attorney\'s fee — that is the heart of the contingency system. Whether you would owe repayment of advanced costs depends on your specific agreement: some firms absorb them, others do not. Business and Professions Code section 6147 requires the agreement to state how costs are handled, so read that clause before signing.'],
    ['question' => 'Is a contingency fee negotiable in California?',
     'answer'   => 'Yes. Outside statutorily capped medical malpractice cases, section 6147 requires the agreement to state that the fee is not set by law and is negotiable between attorney and client. Whether a particular firm will negotiate is a business decision, but you are entitled to ask.'],
    ['question' => 'What must a California contingency fee agreement include?',
     'answer'   => 'Under Business and Professions Code section 6147 it must be in writing and signed by both parties with a duplicate copy given to the client, and must state the fee rate, how disbursements and costs affect the fee and the recovery, any compensation owed for related matters not covered, and the negotiability statement. Failure to comply renders the agreement voidable at the client\'s option, leaving the attorney entitled only to a reasonable fee.'],
    ['question' => 'Are medical malpractice attorney fees capped in California?',
     'answer'   => 'Yes. Business and Professions Code section 6146 limits the contingency fee to 25% of the recovery where the matter resolves before a civil complaint or arbitration demand is filed, and 33% after filing. Where a case proceeds to trial or arbitration, an attorney may move the court for a higher fee on evidence establishing good cause.'],
    ['question' => 'Can I switch personal injury lawyers mid-case?',
     'answer'   => 'Yes. A client may discharge their attorney at any time. The former attorney generally holds a lien for the reasonable value of work already performed, payable from the eventual recovery, and in practice the firms typically divide a single contingency fee based on their contributions rather than the client paying two full fees.'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

try {
    $pdo = db();
    $cols = $pdo->query('SHOW COLUMNS FROM blog_posts')->fetchAll(PDO::FETCH_COLUMN);
    $has = static fn($c) => in_array($c, $cols, true);

    $st = $pdo->prepare('SELECT id FROM blog_categories WHERE slug = ?');
    $st->execute(['legal-tips']);
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
    if ($has('og_image_alt')) { $data['og_image_alt'] = 'A person signing a legal agreement at a desk'; }

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
    echo 'category=legal-tips(' . ($catId ?: 'NULL') . ') words=' . str_word_count(strip_tags($content)) . " faqs=8\nDONE.\n";
} catch (Throwable $e) { echo 'ERROR: ' . $e->getMessage() . "\n"; }
@unlink(__FILE__);
