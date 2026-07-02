<?php /* Section 7 — Testimonials slider */
$testimonials = getTestimonials();
?>
<section class="section testimonials" aria-label="Client testimonials">
  <div class="container">
    <div class="section-head section-head--center animate-on-scroll">
      <p class="eyebrow">Client Experiences</p>
      <h2 class="has-underline">What Our Clients Say</h2>
      <p class="disclaimer-note" style="margin-inline:auto;display:inline-block;">Individual results may vary.</p>
    </div>

    <div class="tslider" data-tslider>
      <div class="tslider__viewport">
        <div class="tslider__track" data-tslider-track>
          <?php foreach ($testimonials as $idx => $t): ?>
            <figure class="tslide glass<?= $idx === 0 ? ' is-active' : '' ?>" data-tslide>
              <div class="tslide__stars" aria-label="<?= (int)$t['rating'] ?> out of 5 stars">
                <?php for ($s = 0; $s < 5; $s++): ?>
                  <svg class="tslide__star<?= $s < (int)$t['rating'] ? ' is-on' : '' ?>" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <?php endfor; ?>
              </div>
              <blockquote class="tslide__quote"><?= e($t['testimonial']) ?></blockquote>
              <figcaption class="tslide__by">
                <span class="tslide__name"><?= e($t['client_name']) ?></span>
                <span class="tslide__case"><?= e($t['case_type']) ?></span>
              </figcaption>
            </figure>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="tslider__nav">
        <button type="button" class="carousel-btn" data-tslider-prev aria-label="Previous testimonial">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="tslider__dots" data-tslider-dots role="tablist" aria-label="Testimonial navigation"></div>
        <button type="button" class="carousel-btn" data-tslider-next aria-label="Next testimonial">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>
  </div>
</section>
