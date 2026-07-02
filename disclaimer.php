<?php
/**
 * disclaimer.php — Legal Disclaimer (Attorney Advertising, no guarantee, CA only).
 * TEMPLATE CONTENT — have a licensed attorney customize before publishing.
 */
require_once __DIR__ . '/includes/functions.php';

$page = [
    'title'       => 'Legal Disclaimer',
    'description' => 'Legal disclaimer for Golden State Injury Lawyers. Attorney Advertising. '
                   . 'Past results do not guarantee future outcomes. California only.',
    'path'        => '/disclaimer.php',
    'styles'      => ['/assets/css/home.css'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Legal Disclaimer', 'path' => '/disclaimer.php'],
    ],
];

require __DIR__ . '/includes/header.php';
$firm = e(cfg('firm_name', SITE_NAME));
?>

<section class="pa-hero pa-hero--index" aria-label="Legal Disclaimer">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Important Notice</p>
    <h1 class="pa-hero__title">Legal Disclaimer</h1>
    <p class="pa-hero__subtext">Please read this important information about the use of our website.</p>
  </div>
</section>

<section class="legal-page section">
  <div class="container">
    <div class="legal-body">
      <p class="legal-updated">Last updated: <?= e(date('F j, Y')) ?></p>

      <div class="legal-callout">
        <strong>Attorney Advertising.</strong> This website may be considered attorney advertising
        under the rules of the State Bar of California. The information presented should not be
        construed as a guarantee, warranty, or prediction regarding the outcome of any legal matter.
      </div>

      <h2>No Legal Advice</h2>
      <p>The information on this website is for general informational purposes only and is not legal
        advice. Reading this website or contacting <?= $firm ?> does not create an
        attorney&ndash;client relationship. You should consult a licensed California attorney
        regarding your specific circumstances.</p>

      <h2>Past Results Do Not Guarantee Future Outcomes</h2>
      <p>Any case results, testimonials, or descriptions of prior matters described on this website
        are specific to the facts of those cases and do not guarantee or predict a similar result
        in any other matter. Every case is different, and the outcome of your case will depend on
        its own facts, evidence, and applicable law.</p>

      <h2>Testimonials</h2>
      <p>Client testimonials or endorsements on this website do not constitute a guarantee,
        warranty, or prediction regarding the outcome of your legal matter. Results vary from
        case to case.</p>

      <h2>California Practice Only</h2>
      <p><?= $firm ?> is licensed to practice law in the State of California only. Nothing on this
        website is intended to solicit clients for legal matters outside California or to provide
        legal services where we are not licensed.</p>

      <h2>No Confidential Information</h2>
      <p>Please do not send any confidential or sensitive information through this website or by
        email until an attorney&ndash;client relationship has been established in writing.
        Unsolicited information may not be treated as confidential or privileged.</p>

      <h2>Accuracy of Information</h2>
      <p>While we strive to keep the information on this website current and accurate, the law
        changes frequently and we make no representations or warranties about the completeness or
        accuracy of the content.</p>

      <h2>Questions</h2>
      <p>If you have questions about this disclaimer, please <a href="/contact.php">contact us</a>.</p>

      <p><a href="/privacy-policy.php">Privacy Policy</a> &middot;
         <a href="/terms.php">Terms of Use</a></p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
