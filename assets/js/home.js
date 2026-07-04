/* ===========================================================================
   home.js — Homepage behaviors.
   Counters, magnetic buttons, ripple, and scroll reveals are handled by
   animations.js (GSILAnim). This file adds: particle canvases, the results
   drag-carousel, the testimonial autoplay slider, the AJAX consultation form,
   and the page loader.
   =========================================================================== */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function ready(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  /* ---- Page loader (skeleton → fade) ---- */
  (function pageLoader() {
    var loader = document.createElement('div');
    loader.className = 'page-loader';
    loader.setAttribute('aria-hidden', 'true');
    loader.innerHTML =
      '<div style="text-align:center">' +
      '<img class="page-loader__mark" src="/assets/images/favicon.svg" alt="">' +
      '<div class="page-loader__bar"></div></div>';
    // Insert as first body child as soon as body exists.
    if (document.body) document.body.appendChild(loader);
    else document.addEventListener('DOMContentLoaded', function () { document.body.appendChild(loader); });

    function done() {
      loader.classList.add('is-done');
      setTimeout(function () { if (loader.parentNode) loader.parentNode.removeChild(loader); }, 600);
    }
    window.addEventListener('load', function () { setTimeout(done, reduceMotion ? 0 : 350); });
    // Safety: never trap the user behind the loader.
    setTimeout(done, 4000);
  })();

  ready(function () {

    /* ---- Particle canvases ---- */
    if (window.GSILAnim && typeof window.GSILAnim.animateParticles === 'function') {
      document.querySelectorAll('[data-particles]').forEach(function (c) {
        window.GSILAnim.animateParticles(c);
      });
    }

    /* ---- Results drag-carousel ---- */
    document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
      var track = carousel.querySelector('[data-carousel-track]');
      if (!track) return;
      var prev = carousel.querySelector('[data-carousel-prev]');
      var next = carousel.querySelector('[data-carousel-next]');
      var step = function () {
        var card = track.firstElementChild;
        return card ? card.getBoundingClientRect().width + 20 : 320;
      };
      if (prev) prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
      if (next) next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });

      // Pointer drag
      var isDown = false, startX = 0, startScroll = 0, moved = false;
      track.addEventListener('pointerdown', function (e) {
        isDown = true; moved = false; startX = e.clientX; startScroll = track.scrollLeft;
        track.classList.add('is-dragging'); track.setPointerCapture(e.pointerId);
      });
      track.addEventListener('pointermove', function (e) {
        if (!isDown) return;
        var dx = e.clientX - startX;
        if (Math.abs(dx) > 4) moved = true;
        track.scrollLeft = startScroll - dx;
      });
      function endDrag() { isDown = false; track.classList.remove('is-dragging'); }
      track.addEventListener('pointerup', endDrag);
      track.addEventListener('pointercancel', endDrag);
      track.addEventListener('pointerleave', endDrag);
      // Prevent click navigation after a drag
      track.addEventListener('click', function (e) { if (moved) { e.preventDefault(); } }, true);

      // Gentle continuous auto-scroll (ping-pong). Pauses on hover / focus / drag,
      // and is disabled entirely under prefers-reduced-motion.
      if (!reduceMotion) {
        // Two CSS features fight a JS marquee and must be neutralised on this
        // track: scroll-snap (x mandatory) snaps each tiny step back to a card
        // edge, and scroll-behavior:smooth (inherited from html) animates every
        // scrollLeft assignment so the 0.6px/frame steps never accumulate.
        track.style.scrollSnapType = 'none';
        track.style.scrollBehavior = 'auto';
        var auto = true, dir = 1;
        carousel.addEventListener('mouseenter', function () { auto = false; });
        carousel.addEventListener('mouseleave', function () { auto = true; });
        carousel.addEventListener('focusin', function () { auto = false; });
        carousel.addEventListener('focusout', function () { auto = true; });
        // Measure overflow INSIDE the loop, not once up-front: the track's width
        // isn't final until images/fonts/reveal settle, so an early check can see
        // 0 overflow and never start. This kicks in as soon as it overflows.
        (function loop() {
          var max = track.scrollWidth - track.clientWidth;
          if (auto && !isDown && max > 8) {
            track.scrollLeft += 0.6 * dir;
            if (track.scrollLeft >= max - 1) dir = -1;
            else if (track.scrollLeft <= 0) dir = 1;
          }
          requestAnimationFrame(loop);
        })();
      }
    });

    /* ---- Testimonial slider (autoplay + crossfade) ---- */
    document.querySelectorAll('[data-tslider]').forEach(function (slider) {
      var slides = Array.prototype.slice.call(slider.querySelectorAll('[data-tslide]'));
      if (slides.length === 0) return;
      var dotsWrap = slider.querySelector('[data-tslider-dots]');
      var prev = slider.querySelector('[data-tslider-prev]');
      var next = slider.querySelector('[data-tslider-next]');
      var viewport = slider.querySelector('.tslider__viewport');
      var index = 0, timer = null;

      // Build dots
      var dots = [];
      if (dotsWrap) {
        slides.forEach(function (_, i) {
          var b = document.createElement('button');
          b.type = 'button';
          b.setAttribute('role', 'tab');
          b.setAttribute('aria-label', 'Go to testimonial ' + (i + 1));
          b.addEventListener('click', function () { go(i); restart(); });
          dotsWrap.appendChild(b);
          dots.push(b);
        });
      }

      function sizeViewport() {
        if (!viewport) return;
        var max = 0;
        slides.forEach(function (s) {
          var prevState = s.style.cssText;
          s.style.visibility = 'hidden'; s.style.opacity = '0'; s.style.position = 'relative';
          max = Math.max(max, s.offsetHeight);
          s.style.cssText = prevState;
        });
        if (max) viewport.style.minHeight = max + 'px';
      }

      function go(i) {
        index = (i + slides.length) % slides.length;
        slides.forEach(function (s, k) { s.classList.toggle('is-active', k === index); });
        dots.forEach(function (d, k) { d.classList.toggle('is-active', k === index); });
      }
      function nextSlide() { go(index + 1); }
      function start() { if (!reduceMotion) timer = setInterval(nextSlide, 5000); }
      function stop() { if (timer) { clearInterval(timer); timer = null; } }
      function restart() { stop(); start(); }

      if (prev) prev.addEventListener('click', function () { go(index - 1); restart(); });
      if (next) next.addEventListener('click', function () { go(index + 1); restart(); });
      slider.addEventListener('mouseenter', stop);
      slider.addEventListener('mouseleave', start);
      slider.addEventListener('focusin', stop);
      slider.addEventListener('focusout', start);

      go(0);
      sizeViewport();
      window.addEventListener('resize', sizeViewport, { passive: true });
      start();
    });

    /* ---- AJAX consultation form ---- */
    document.querySelectorAll('[data-ajax-form]').forEach(function (form) {
      var successBox = form.querySelector('[data-form-success]');
      var successMsg = form.querySelector('[data-form-success-msg]');

      function fieldOf(el) { return el.closest('.field') || el.closest('.consent-check'); }
      function noteOf(wrap) {
        var n = wrap.querySelector('.field__error');
        if (n) return n;
        var sib = wrap.nextElementSibling;
        return (sib && sib.classList && sib.classList.contains('field__error')) ? sib : null;
      }
      function setError(input, msg) {
        var f = fieldOf(input); if (!f) return;
        f.classList.toggle('field--error', !!msg);
        var note = noteOf(f);
        if (note) note.textContent = msg || '';
        input.setAttribute('aria-invalid', msg ? 'true' : 'false');
      }
      function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
      function validate() {
        var ok = true;
        form.querySelectorAll('input[required], textarea[required], select[required]').forEach(function (f) {
          if (f.type === 'checkbox') {
            if (!f.checked) { setError(f, 'Please check this box to continue.'); ok = false; } else setError(f, '');
            return;
          }
          var v = (f.value || '').trim();
          if (!v) { setError(f, 'This field is required.'); ok = false; }
          else if (f.type === 'email' && !isEmail(v)) { setError(f, 'Enter a valid email address.'); ok = false; }
          else if (f.type === 'tel' && v.replace(/\D/g, '').length < 7) { setError(f, 'Enter a valid phone number.'); ok = false; }
          else setError(f, '');
        });
        // Optional phone (not required) still gets a light check.
        var phone = form.querySelector('[name="phone"]:not([required])');
        if (phone && phone.value.trim() && phone.value.replace(/\D/g, '').length < 7) { setError(phone, 'Enter a valid phone number.'); ok = false; }
        return ok;
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        // Honeypot
        var hp = form.querySelector('[name="website"]');
        if (hp && hp.value) return;
        if (!validate()) {
          var firstErr = form.querySelector('.field--error input, .field--error select');
          if (firstErr) firstErr.focus();
          return;
        }
        var btn = form.querySelector('[type="submit"]');
        if (btn) { btn.disabled = true; btn.dataset.label = btn.textContent; btn.textContent = 'Sending…'; }

        fetch(form.getAttribute('action'), {
          method: 'POST',
          headers: { 'Accept': 'application/json' },
          body: new FormData(form)
        })
          .then(function (r) { return r.json().then(function (j) { return { status: r.status, body: j }; }); })
          .then(function (res) {
            if (res.body && res.body.ok) {
              if (successMsg && res.body.message) successMsg.textContent = res.body.message;
              if (successBox) { successBox.hidden = false; requestAnimationFrame(function () { successBox.classList.add('is-shown'); }); }
            } else {
              if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Submit'; }
              var errs = (res.body && res.body.errors) || {};
              var any = false;
              Object.keys(errs).forEach(function (k) {
                var input = form.querySelector('[name="' + k + '"]');
                if (input) { setError(input, errs[k]); any = true; }
              });
              if (!any && res.body && res.body.message) {
                var phone = form.querySelector('[name="phone"]');
                if (phone) setError(phone, res.body.message);
              }
            }
          })
          .catch(function () {
            if (btn) { btn.disabled = false; btn.textContent = btn.dataset.label || 'Submit'; }
            var phone = form.querySelector('[name="phone"]');
            if (phone) setError(phone, 'Network error. Please call us directly.');
          });
      });

      // Clear error as the user corrects a field
      form.querySelectorAll('input, select').forEach(function (input) {
        input.addEventListener('input', function () {
          if (input.getAttribute('aria-invalid') === 'true') setError(input, '');
        });
      });
    });
  });
})();
