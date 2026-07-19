<?php
/**
 * case-evaluation.php — 3-step free case evaluation intake form.
 */
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/repo.php';
require_once __DIR__ . '/includes/pa-helpers.php';

$incidentTypes = [
    ['v' => 'Car Accident', 'icon' => 'car'],
    ['v' => 'Truck Accident', 'icon' => 'truck'],
    ['v' => 'Motorcycle Accident', 'icon' => 'motorcycle'],
    ['v' => 'Pedestrian Accident', 'icon' => 'pedestrian'],
    ['v' => 'Rideshare Accident', 'icon' => 'rideshare'],
    ['v' => 'Slip & Fall', 'icon' => 'slip'],
    ['v' => 'Dog Bite', 'icon' => 'dog'],
    ['v' => 'Workplace Injury', 'icon' => 'workplace'],
    ['v' => 'Wrongful Death', 'icon' => 'wrongful-death'],
    ['v' => 'Brain Injury', 'icon' => 'brain'],
    ['v' => 'Other', 'icon' => ''],
];

$caCounties = ['Sacramento','Placer','El Dorado','Marin','Yolo','Solano','Sutter','Yuba','Nevada','Amador',
    'San Joaquin','Contra Costa','Alameda','Other California county'];

$injuriesList = ['Broken bones / fractures','Head or brain injury','Neck or back injury','Spinal cord injury',
    'Cuts, bruises, lacerations','Burns','Internal injuries','Soft tissue injury','Emotional distress','Other'];

$page = [
    'title'       => 'Free Case Evaluation',
    'description' => 'Request a free, confidential case evaluation from Mason Law, P.C. '
                   . 'No upfront fees. Tell us about your California injury claim.',
    'path'        => '/case-evaluation.php',
    'robots'      => 'noindex, follow',
    'styles'      => ['/assets/css/home.css', '/assets/css/forms.css'],
    'scripts'     => ['/assets/js/case-evaluation.js'],
    'breadcrumbs' => [
        ['name' => 'Home', 'path' => '/'],
        ['name' => 'Free Case Evaluation', 'path' => '/case-evaluation.php'],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="pa-hero pa-hero--index" aria-label="Free case evaluation">
  <div class="container pa-hero__inner">
    <p class="eyebrow">No Fee Unless We Win</p>
    <h1 class="pa-hero__title">Free Case Evaluation</h1>
    <p class="pa-hero__subtext">Tell us what happened. Your information is confidential, and submitting this form costs nothing and creates no obligation.</p>
  </div>
</section>

<section class="section">
  <div class="container container--narrow">
    <form class="msform" data-multistep action="/api/case-evaluation-handler.php" method="post" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="source" value="case-evaluation">
      <div class="hp-field" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <!-- Progress -->
      <div class="msform__progress" data-progress>
        <div class="msform__bar"><span class="msform__fill" data-progress-fill></span></div>
        <ol class="msform__steps">
          <li class="is-active" data-progress-step="1"><span>1</span>Incident</li>
          <li data-progress-step="2"><span>2</span>Injuries</li>
          <li data-progress-step="3"><span>3</span>Contact</li>
        </ol>
      </div>

      <!-- STEP 1 -->
      <fieldset class="step is-active" data-step="1">
        <legend class="step__title">Tell us about your incident</legend>

        <div class="field">
          <label>Type of incident <span class="req">*</span></label>
          <div class="choice-grid" data-validate-required>
            <?php foreach ($incidentTypes as $it): ?>
              <label class="choice-card">
                <input type="radio" name="incident_type" value="<?= e($it['v']) ?>">
                <span class="choice-card__icon"><?= $it['icon'] ? practice_icon($it['icon']) : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>' ?></span>
                <span class="choice-card__label"><?= e($it['v']) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <span class="field__error" aria-live="polite"></span>
        </div>

        <div class="att-form__row">
          <div class="field">
            <label for="ce-date">Date of incident</label>
            <input type="date" id="ce-date" name="incident_date" max="">
          </div>
          <div class="field">
            <label for="ce-loc">County</label>
            <select id="ce-loc" name="county">
              <option value="">Select a county</option>
              <?php foreach ($caCounties as $c): ?><option value="<?= e($c) ?>"><?= e($c) ?></option><?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="field">
          <label for="ce-desc">Brief description</label>
          <textarea id="ce-desc" name="description" rows="4" maxlength="300" data-counter placeholder="What happened?"></textarea>
          <span class="char-counter"><span data-counter-out>0</span>/300</span>
        </div>

        <div class="att-form__row">
          <div class="field">
            <label for="ce-opposing">Opposing party&rsquo;s name <span class="req">*</span></label>
            <input type="text" id="ce-opposing" name="opposing_party" required placeholder="Full name of the other party">
            <span class="field__hint text-muted" style="font-size:var(--text-sm)">Required so we can run a conflicts check.</span>
          </div>
          <div class="field">
            <label>Does the opposing party have a lawyer?</label>
            <div class="toggle-group">
              <label class="toggle"><input type="radio" name="opposing_has_lawyer" value="yes"><span>Yes</span></label>
              <label class="toggle"><input type="radio" name="opposing_has_lawyer" value="no" checked><span>No</span></label>
              <label class="toggle"><input type="radio" name="opposing_has_lawyer" value="unknown"><span>Not sure</span></label>
            </div>
          </div>
        </div>

        <div class="step__nav">
          <span></span>
          <button type="button" class="btn btn--primary" data-next>Continue <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></button>
        </div>
      </fieldset>

      <!-- STEP 2 -->
      <fieldset class="step" data-step="2">
        <legend class="step__title">Your injuries</legend>

        <div class="field">
          <label>Injuries sustained (select all that apply)</label>
          <div class="chip-grid">
            <?php foreach ($injuriesList as $inj): ?>
              <label class="chip"><input type="checkbox" name="injuries[]" value="<?= e($inj) ?>"><span><?= e($inj) ?></span></label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="field">
          <label>Did you receive medical treatment?</label>
          <div class="toggle-group" data-toggle-reveal="medical-extra">
            <label class="toggle"><input type="radio" name="medical_treatment" value="yes"><span>Yes</span></label>
            <label class="toggle"><input type="radio" name="medical_treatment" value="no" checked><span>No</span></label>
          </div>
        </div>

        <div class="reveal" data-reveal-target="medical-extra" hidden>
          <div class="att-form__row">
            <div class="field"><label for="ce-treat">Type of treatment</label><input type="text" id="ce-treat" name="treatment_type" placeholder="e.g. ER, physical therapy"></div>
            <div class="field"><label for="ce-phys">Treating physician(s)</label><input type="text" id="ce-phys" name="physicians" placeholder="Doctor or facility"></div>
          </div>
          <div class="field">
            <label>Are you still receiving treatment?</label>
            <div class="toggle-group">
              <label class="toggle"><input type="radio" name="still_treating" value="yes"><span>Yes</span></label>
              <label class="toggle"><input type="radio" name="still_treating" value="no" checked><span>No</span></label>
            </div>
          </div>
        </div>

        <div class="att-form__row">
          <div class="field">
            <label>Police / accident report filed?</label>
            <div class="toggle-group">
              <label class="toggle"><input type="radio" name="police_report" value="yes"><span>Yes</span></label>
              <label class="toggle"><input type="radio" name="police_report" value="no" checked><span>No</span></label>
            </div>
          </div>
          <div class="field">
            <label>Insurance claim filed?</label>
            <div class="toggle-group">
              <label class="toggle"><input type="radio" name="insurance_claim" value="yes"><span>Yes</span></label>
              <label class="toggle"><input type="radio" name="insurance_claim" value="no" checked><span>No</span></label>
            </div>
          </div>
        </div>

        <div class="step__nav">
          <button type="button" class="btn btn--ghost" data-back><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Back</button>
          <button type="button" class="btn btn--primary" data-next>Continue</button>
        </div>
      </fieldset>

      <!-- STEP 3 -->
      <fieldset class="step" data-step="3">
        <legend class="step__title">How can we reach you?</legend>

        <div class="att-form__row">
          <div class="field"><label for="ce-name">Your full legal name <span class="req">*</span></label><input type="text" id="ce-name" name="name" required autocomplete="name" placeholder="Your official name"><span class="field__error" aria-live="polite"></span></div>
          <div class="field"><label for="ce-phone">Phone <span class="req">*</span></label><input type="tel" id="ce-phone" name="phone" required autocomplete="tel" data-phone-mask placeholder="(000) 000-0000"><span class="field__error" aria-live="polite"></span></div>
        </div>
        <div class="field"><label for="ce-email">Email <span class="req">*</span></label><input type="email" id="ce-email" name="email" required autocomplete="email" placeholder="you@example.com"><span class="field__error" aria-live="polite"></span></div>

        <div class="att-form__row">
          <div class="field">
            <label for="ce-pref">Preferred contact method</label>
            <select id="ce-pref" name="preferred_contact"><option value="">No preference</option><option>Phone</option><option>Email</option><option>Text message</option></select>
          </div>
          <div class="field">
            <label for="ce-time">Best time to call</label>
            <select id="ce-time" name="best_time"><option value="">Anytime</option><option>Morning</option><option>Afternoon</option><option>Evening</option></select>
          </div>
        </div>

        <div class="field">
          <label for="ce-hear">How did you hear about us?</label>
          <select id="ce-hear" name="hear_about"><option value="">Select one</option><option>Google search</option><option>Referral from friend or family</option><option>Social media</option><option>Online advertisement</option><option>Previous client</option><option>Other</option></select>
        </div>

        <label class="consent-check field <?= '' ?>">
          <input type="checkbox" name="consent" value="1" required>
          <span>I understand that submitting this form does not create an attorney-client relationship, and I agree to be contacted about my inquiry. <span class="req">*</span></span>
        </label>
        <span class="field__error" data-consent-error aria-live="polite"></span>

        <div class="step__nav">
          <button type="button" class="btn btn--ghost" data-back><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Back</button>
          <button type="submit" class="btn btn--primary" data-ripple>Submit My Evaluation</button>
        </div>
      </fieldset>

      <!-- SUCCESS -->
      <div class="ce-success" data-form-success hidden>
        <span class="form-success__check" aria-hidden="true"><svg viewBox="0 0 52 52"><circle class="fs-circle" cx="26" cy="26" r="24" fill="none"/><path class="fs-check" fill="none" d="M14 27l8 8 16-16"/></svg></span>
        <h2 data-form-success-msg>Thank you. Your request has been received.</h2>
        <p class="text-muted">Here&rsquo;s what happens next:</p>
        <ol class="next-steps">
          <li><span class="next-steps__n">1</span><div><strong>We review your information</strong><p>Our team reviews the details you provided, usually within one business day.</p></div></li>
          <li><span class="next-steps__n">2</span><div><strong>We reach out to you</strong><p>We&rsquo;ll contact you to learn more and answer your questions &mdash; at no cost.</p></div></li>
          <li><span class="next-steps__n">3</span><div><strong>We discuss your options</strong><p>If we can help, we&rsquo;ll explain how the process works. There&rsquo;s no fee unless we recover for you.</p></div></li>
        </ol>
        <p class="disclaimer-note" style="margin-top:1.5rem;">This confirmation is not legal advice and does not create an attorney-client relationship. Past results do not guarantee future outcomes.</p>
        <a class="btn btn--ghost" href="/" style="margin-top:1rem;">Return Home</a>
      </div>
    </form>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
