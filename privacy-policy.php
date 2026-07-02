<?php
/**
 * privacy-policy.php — California (CCPA) privacy policy (canonical URL).
 * TEMPLATE CONTENT — have a licensed attorney customize before publishing.
 */
require_once __DIR__ . '/includes/functions.php';

$page = [
    'title'       => 'Privacy Policy',
    'description' => 'Privacy Policy for Golden State Injury Lawyers, including California '
                   . 'Consumer Privacy Act (CCPA) rights, the data we collect, and how to contact us.',
    'path'        => '/privacy-policy.php',
    'styles'      => ['/assets/css/home.css'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Privacy Policy', 'path' => '/privacy-policy.php'],
    ],
];

require __DIR__ . '/includes/header.php';
$firm  = e(cfg('firm_name', SITE_NAME));
$email = e(cfg('site_email', SITE_EMAIL));
$phone = e(cfg('site_phone', SITE_PHONE));
?>

<section class="pa-hero pa-hero--index" aria-label="Privacy Policy">
  <div class="container pa-hero__inner">
    <p class="eyebrow">Your Privacy Matters</p>
    <h1 class="pa-hero__title">Privacy Policy</h1>
    <p class="pa-hero__subtext">How <?= $firm ?> collects, uses, and protects your information &mdash; and your rights under California law.</p>
  </div>
</section>

<section class="legal-page section">
  <div class="container">
    <div class="legal-body">
      <p class="legal-updated">Last updated: <?= e(date('F j, Y')) ?></p>

      <div class="legal-callout">
        <strong>Template notice:</strong> This policy is provided as a starting template.
        Consult a licensed attorney to customize it for your firm&rsquo;s specific data
        practices before relying on it.
      </div>

      <p>This Privacy Policy explains how <?= $firm ?> (&ldquo;we,&rdquo; &ldquo;us,&rdquo; or
        &ldquo;our&rdquo;) collects, uses, and discloses information when you visit our website
        or contact us. We serve clients in California only.</p>

      <h2>Information We Collect</h2>
      <ul>
        <li><strong>Information you provide:</strong> name, phone number, email address, and the
          details you share when you submit a contact form, request a case evaluation, or call us.</li>
        <li><strong>Usage &amp; analytics data:</strong> if you accept cookies, we may collect
          anonymized data such as pages visited, device type, and approximate region through
          tools like Google Analytics and the Meta Pixel.</li>
        <li><strong>Cookies:</strong> small files used to remember your theme preference and,
          with your consent, to measure site traffic. You may decline non-essential cookies via
          our banner at any time.</li>
      </ul>

      <h2>How We Use Your Information</h2>
      <ul>
        <li>To respond to your inquiry and evaluate a potential legal matter.</li>
        <li>To communicate with you about your request or your case.</li>
        <li>To improve our website and understand how visitors use it (only with consent).</li>
        <li>To comply with legal and ethical obligations.</li>
      </ul>

      <h2>We Do Not Sell Your Personal Information</h2>
      <p>We do not sell or rent your personal information to third parties. We share information
        only with service providers who help us operate the website (for example, hosting or
        analytics), and only as needed to provide those services.</p>

      <h2>Your California Privacy Rights (CCPA/CPRA)</h2>
      <p>If you are a California resident, you have the right to:</p>
      <ul>
        <li><strong>Right to know</strong> what personal information we collect and how we use it.</li>
        <li><strong>Right to delete</strong> personal information we have collected, subject to
          legal exceptions.</li>
        <li><strong>Right to correct</strong> inaccurate personal information.</li>
        <li><strong>Right to opt out</strong> of the sale or sharing of personal information
          (note: we do not sell your information).</li>
        <li><strong>Right to non-discrimination</strong> for exercising your privacy rights.</li>
      </ul>
      <p>To exercise any of these rights, contact us using the details below. We will verify
        your request and respond within the timeframes required by law.</p>

      <h2>Attorney&ndash;Client Privilege</h2>
      <p>Submitting a form or contacting us does <em>not</em> create an attorney&ndash;client
        relationship. Please do not send confidential or time-sensitive information until a
        formal relationship has been established in writing. Once we represent you, your
        communications are protected by attorney&ndash;client privilege to the extent provided by law.</p>

      <h2>Data Security &amp; Retention</h2>
      <p>We use reasonable administrative and technical safeguards to protect your information,
        though no method of transmission over the Internet is completely secure. We retain
        information only as long as necessary for the purposes described here or as required by law.</p>

      <h2>Third-Party Links</h2>
      <p>Our site may link to external resources (for example, California government agencies).
        We are not responsible for the privacy practices of those websites.</p>

      <h2>Children&rsquo;s Privacy</h2>
      <p>Our website is not directed to children under 16, and we do not knowingly collect their
        personal information.</p>

      <h2>Changes to This Policy</h2>
      <p>We may update this Privacy Policy from time to time. The &ldquo;Last updated&rdquo; date
        above reflects the most recent revision.</p>

      <h2>Contact Us About Privacy</h2>
      <p>To make a privacy request or ask a question about this policy:</p>
      <ul>
        <li>Phone: <a href="tel:<?= e(cfg('site_phone_raw', SITE_PHONE_RAW)) ?>"><?= $phone ?></a></li>
        <li>Email: <a href="mailto:<?= $email ?>"><?= $email ?></a></li>
      </ul>

      <p><a href="/disclaimer.php">Read our legal disclaimer</a> &middot;
         <a href="/terms.php">Terms of Use</a></p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
