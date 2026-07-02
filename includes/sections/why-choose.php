<?php /* Section 5 — Why choose us (alternating) */
$points = [
    [
        'num' => '01', 'heading' => 'No Win, No Fee — Ever',
        'body' => 'You pay nothing upfront. We work on a contingency fee basis, which means we only get paid if we recover compensation for you. Your consultation is always free.',
        'icon' => '<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
    ],
    [
        'num' => '02', 'heading' => 'California Law. California Experience.',
        'body' => 'Our attorneys focus exclusively on California personal injury law. We know the local courts, insurers, and rules that affect your case throughout the state.',
        'icon' => '<path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/>',
    ],
    [
        'num' => '03', 'heading' => 'We Negotiate. We Litigate. We Advocate.',
        'body' => 'We prepare every case as if it will go to trial, and we are ready to fight for you in the courtroom when insurers refuse to treat you fairly.',
        'icon' => '<path d="M14 4l6 6M3 21l9-9M12.5 6.5l5 5M16 2l6 6-4 4-6-6z"/>',
        'disclaimer' => '*Results may vary. Past results do not guarantee future outcomes.',
    ],
    [
        'num' => '04', 'heading' => 'Available 24/7 — We Come to You',
        'body' => 'Injuries do not keep business hours, and neither do we. Reach us any time, and if you cannot come to us, we will come to you anywhere in California.',
        'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    ],
];
?>
<section class="section section--muted why" aria-label="Why choose us">
  <div class="container">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Why Clients Trust Us</p>
      <h2 class="has-underline">Built to Fight for Californians</h2>
    </div>

    <div class="why__list">
      <?php foreach ($points as $i => $p): $rev = $i % 2 === 1; ?>
        <article class="why-row<?= $rev ? ' why-row--rev' : '' ?>">
          <div class="why-row__media animate-on-scroll" data-anim="<?= $rev ? 'right' : 'left' ?>">
            <span class="why-row__bgnum" aria-hidden="true"><?= e($p['num']) ?></span>
            <span class="why-row__icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?= $p['icon'] ?></svg>
            </span>
          </div>
          <div class="why-row__text animate-on-scroll" data-anim="<?= $rev ? 'left' : 'right' ?>">
            <span class="why-row__index" aria-hidden="true"><?= e($p['num']) ?></span>
            <h3><?= e($p['heading']) ?></h3>
            <p><?= e($p['body']) ?></p>
            <?php if (!empty($p['disclaimer'])): ?>
              <p class="why-row__disclaimer"><?= e($p['disclaimer']) ?></p>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
