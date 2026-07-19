<?php
/**
 * terms.php — Terms of Use.
 * TEMPLATE CONTENT — have a licensed attorney customize before publishing.
 */
require_once __DIR__ . '/includes/functions.php';

$page = [
    'title'       => 'Terms of Use',
    'description' => 'Terms of Use governing your access to and use of the Mason Law, P.C. '
                   . 'Lawyers website, a California personal injury law firm.',
    'path'        => '/terms.php',
    'styles'      => ['/assets/css/home.css'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Terms of Use', 'path' => '/terms.php'],
    ],
];

require __DIR__ . '/includes/header.php';
$firm = e(cfg('firm_name', SITE_NAME));
?>

<section class="pa-hero pa-hero--index" aria-label="Terms of Use">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Website Terms</p>
    <h1 class="pa-hero__title">Terms of Use</h1>
    <p class="pa-hero__subtext">The rules that govern your use of this website.</p>
  </div>
</section>

<section class="legal-page section">
  <div class="container">
    <div class="legal-body">
      <p class="legal-updated">Last updated: <?= e(date('F j, Y')) ?></p>

      <div class="legal-callout">
        <strong>Template notice:</strong> These Terms are a starting template. Consult a licensed
        attorney to tailor them to your firm before publishing.
      </div>

      <p>By accessing or using the <?= $firm ?> website (the &ldquo;Site&rdquo;), you agree to
        these Terms of Use. If you do not agree, please do not use the Site.</p>

      <h2>No Legal Advice</h2>
      <p>The content on this Site is provided for general informational purposes only and does not
        constitute legal advice. Every case is different. You should not act, or refrain from
        acting, based on information on this Site without seeking advice from a licensed California
        attorney about your specific situation.</p>

      <h2>No Attorney&ndash;Client Relationship</h2>
      <p>Using this Site, submitting a form, or contacting us does not create an
        attorney&ndash;client relationship. An attorney&ndash;client relationship is formed only
        when you and the firm sign a written agreement. Please do not send confidential information
        until such a relationship is established.</p>

      <h2>Attorney Advertising</h2>
      <p>This Site may be considered attorney advertising under the rules of the State Bar of
        California. Past results do not guarantee future outcomes. The outcome of any case depends
        on its unique facts and circumstances.</p>

      <h2>Jurisdiction</h2>
      <p><?= $firm ?> is licensed to practice law in California only. The information on this Site
        is intended for residents of California and concerns California law.</p>

      <h2>Intellectual Property</h2>
      <p>All content on this Site &mdash; text, graphics, logos, and design &mdash; is owned by or
        licensed to <?= $firm ?> and is protected by applicable intellectual property laws. You may
        not reproduce or distribute it without written permission.</p>

      <h2>Third-Party Links</h2>
      <p>The Site may contain links to third-party websites for your convenience. We do not control
        and are not responsible for their content or practices.</p>

      <h2>Disclaimer of Warranties</h2>
      <p>The Site is provided &ldquo;as is&rdquo; without warranties of any kind, express or
        implied. We do not warrant that the Site will be uninterrupted, error-free, or free of
        harmful components.</p>

      <h2>Limitation of Liability</h2>
      <p>To the fullest extent permitted by law, <?= $firm ?> is not liable for any damages arising
        from your use of, or inability to use, the Site.</p>

      <h2>Changes to These Terms</h2>
      <p>We may update these Terms at any time. Your continued use of the Site after changes are
        posted constitutes acceptance of the revised Terms.</p>

      <h2>Contact</h2>
      <p>Questions about these Terms? <a href="/contact.php">Contact us</a>.</p>

      <p><a href="/privacy-policy.php">Privacy Policy</a> &middot;
         <a href="/disclaimer.php">Legal Disclaimer</a></p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
