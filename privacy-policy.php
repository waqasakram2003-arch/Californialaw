<?php
/**
 * privacy-policy.php — California (CCPA) privacy policy (canonical URL).
 * TEMPLATE CONTENT — have a licensed attorney customize before publishing.
 */
require_once __DIR__ . '/includes/functions.php';

$page = [
    'title'       => 'Privacy Policy',
    'description' => 'Privacy Policy for Mason Law, P.C., including California '
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
      <?php
        // Set this to the date the policy text actually changed. It was previously
        // date('F j, Y'), which re-dated the policy to "today" on every page load and
        // so could never tell a reader when the terms really changed.
        $policyUpdated = '2026-09-27';
      ?>
      <p class="legal-updated">Last updated: <?= e(date('F j, Y', strtotime($policyUpdated))) ?></p>

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
          with your consent, to measure site traffic and to measure the performance of our
          advertising. You may decline non-essential cookies via our banner at any time.</li>
      </ul>

      <h2>How We Use Your Information</h2>
      <ul>
        <li>To respond to your inquiry and evaluate a potential legal matter.</li>
        <li>To communicate with you about your request or your case.</li>
        <li>To improve our website and understand how visitors use it (only with consent).</li>
        <li>To comply with legal and ethical obligations.</li>
      </ul>

      <h2>Advertising Cookies &amp; Conversion Tracking</h2>
      <p>We advertise our legal services online. To understand whether those advertisements are
        working, this website uses Google&rsquo;s advertising and conversion-tracking technology
        (the Google tag). When it is active, it may set or read cookies and similar identifiers in
        order to measure actions such as submitting a contact form or requesting a case evaluation,
        and to report those actions to us in aggregate.</p>
      <p><strong>These advertising technologies load only after you select &ldquo;Accept&rdquo; on
        our cookie banner.</strong> If you decline, or simply take no action, no advertising or
        analytics scripts are loaded on your browser at all. You may withdraw your consent at any
        time by clearing this site&rsquo;s cookies in your browser, after which the banner will
        appear again and your new choice will apply.</p>
      <p>We do not use these technologies to collect the substance of any inquiry you send us, and
        we do not provide the details of your legal matter to advertising providers. Information
        handled by Google in connection with these services is governed by
        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google&rsquo;s
        Privacy Policy</a> and its description of
        <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener">how
        Google uses information from sites that use its services</a>. You can also manage the ads you
        see through <a href="https://myadcenter.google.com/" target="_blank" rel="noopener">Google My
        Ad Center</a>.</p>

      <h2>We Do Not Sell Your Personal Information</h2>
      <p>We do not sell or rent your personal information to third parties, and we have never
        exchanged your information for money.</p>
      <p>California law also regulates &ldquo;sharing&rdquo; personal information for
        cross-context behavioral advertising, which is a separate concept from selling. Depending
        on how the advertising cookies described above are configured, their use may be treated as
        &ldquo;sharing&rdquo; under the CPRA. Because we load those technologies only after you
        affirmatively accept cookies, you control whether this occurs: declining the banner, or
        never accepting it, means no advertising identifiers are set through this website.</p>
      <p>Apart from the advertising technologies described above, we share information only with
        service providers who help us operate the website (for example, hosting or analytics), and
        only as needed to provide those services.</p>

      <h2>Your California Privacy Rights (CCPA/CPRA)</h2>
      <p>If you are a California resident, you have the right to:</p>
      <ul>
        <li><strong>Right to know</strong> what personal information we collect and how we use it.</li>
        <li><strong>Right to delete</strong> personal information we have collected, subject to
          legal exceptions.</li>
        <li><strong>Right to correct</strong> inaccurate personal information.</li>
        <li><strong>Right to opt out</strong> of the sale or sharing of personal information.
          We do not sell your information. You can opt out of any sharing through advertising
          cookies by declining our cookie banner, or by clearing this site&rsquo;s cookies and
          declining when the banner reappears.</li>
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
