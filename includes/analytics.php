<?php
/**
 * analytics.php — privacy-first analytics + CCPA cookie consent.
 *
 * Tracking scripts (GA4 + Meta Pixel) are injected ONLY when:
 *   1. the corresponding ID is configured in admin Settings (cfg('ga_id'),
 *      cfg('pixel_id')), AND
 *   2. the visitor has actively accepted cookies (gsil_consent=accept).
 *
 * If consent is unset, a banner is shown (analytics_banner()). Declining stores
 * gsil_consent=decline and no trackers ever load. This satisfies CCPA's
 * opt-out expectation: no non-essential tracking before explicit acceptance.
 *
 * Usage:
 *   analytics_head();    // inside <head>, after styles
 *   analytics_banner();  // before </body>
 */

if (!function_exists('analytics_consent')) {
    /** 'accept' | 'decline' | '' (undecided). */
    function analytics_consent(): string
    {
        $c = $_COOKIE['gsil_consent'] ?? '';
        return in_array($c, ['accept', 'decline'], true) ? $c : '';
    }
}

if (!function_exists('analytics_head')) {
    function analytics_head(): void
    {
        if (analytics_consent() !== 'accept') {
            return; // no trackers until the visitor opts in
        }
        $ga    = cfg('ga_id');
        $pixel = cfg('pixel_id');
        if ($ga):
        ?>
  <!-- Google Analytics 4 (loaded after cookie consent) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= e($ga) ?>', { anonymize_ip: true });
  </script>
        <?php
        endif;
        if ($pixel):
        ?>
  <!-- Meta Pixel (loaded after cookie consent) -->
  <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}
    (window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init','<?= e($pixel) ?>');fbq('track','PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" alt=""
    src="https://www.facebook.com/tr?id=<?= e($pixel) ?>&ev=PageView&noscript=1"></noscript>
        <?php
        endif;
    }
}

if (!function_exists('analytics_banner')) {
    function analytics_banner(): void
    {
        // Nothing to consent to if no trackers are configured, or already decided.
        $hasTrackers = cfg('ga_id') || cfg('pixel_id');
        if (!$hasTrackers || analytics_consent() !== '') {
            return;
        }
        ?>
  <div class="cookie-banner" data-cookie-banner role="dialog" aria-live="polite"
       aria-label="Cookie consent" hidden>
    <p class="cookie-banner__text">
      This website uses cookies to improve your experience and analyze site traffic.
      We do <strong>not</strong> sell your personal information. See our
      <a href="/privacy-policy.php">Privacy Policy</a> for your California (CCPA) rights.
    </p>
    <div class="cookie-banner__actions">
      <button type="button" class="btn btn--ghost btn--sm" data-cookie-decline>Decline</button>
      <button type="button" class="btn btn--primary btn--sm" data-cookie-accept>Accept</button>
    </div>
  </div>
        <?php
    }
}
