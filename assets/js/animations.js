/* ===========================================================================
   animations.js — Golden State Injury Lawyers
   Animation engine: GSAP (lazy-loaded from CDN) + ScrollTrigger, with an
   IntersectionObserver fallback. Always respects prefers-reduced-motion.

   Public API (window.GSILAnim):
     animateHero(), animateSection(el), animateCounter(el, target),
     animateParticles(canvas), initParallax(), initMagneticButtons(),
     initSmoothScroll(), initPageTransition(),
     showSkeleton(container), hideSkeleton(container), addRipple(button)
   =========================================================================== */
(function (global) {
  'use strict';

  var GSAP_CORE = 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js';
  var GSAP_ST   = 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js';

  var reduceMotion = global.matchMedia &&
    global.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Mark JS active so CSS no-JS fallbacks disengage ---- */
  document.documentElement.classList.add('js');

  /* =========================================================================
     CDN LOADER
     ========================================================================= */
  function loadScript(src) {
    return new Promise(function (resolve, reject) {
      if (document.querySelector('script[src="' + src + '"]')) { resolve(); return; }
      var s = document.createElement('script');
      s.src = src; s.async = true;
      s.onload = resolve;
      s.onerror = function () { reject(new Error('Failed to load ' + src)); };
      document.head.appendChild(s);
    });
  }

  function loadGSAP() {
    if (global.gsap && global.ScrollTrigger) return Promise.resolve(true);
    return loadScript(GSAP_CORE)
      .then(function () { return loadScript(GSAP_ST); })
      .then(function () {
        if (global.gsap && global.ScrollTrigger) {
          global.gsap.registerPlugin(global.ScrollTrigger);
          return true;
        }
        return false;
      })
      .catch(function () { return false; });
  }

  /* =========================================================================
     SCROLL REVEAL (GSAP primary, IntersectionObserver fallback)
     ========================================================================= */
  function revealAllImmediately() {
    document.querySelectorAll('.animate-on-scroll, .stagger-children')
      .forEach(function (el) { el.classList.add('animated'); });
  }

  function initIO() {
    if (!('IntersectionObserver' in global)) { revealAllImmediately(); return; }
    var io = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });
    document.querySelectorAll('.animate-on-scroll, .stagger-children')
      .forEach(function (el) { io.observe(el); });
  }

  function initGSAPReveals() {
    var gsap = global.gsap;
    // Exclude hero items — animateHero() owns those (avoids conflicting tweens).
    gsap.utils.toArray('.animate-on-scroll:not([data-hero-item])').forEach(function (el) {
      var anim = el.getAttribute('data-anim');
      var from = { autoAlpha: 0, y: 30 };
      if (anim === 'left')  { from = { autoAlpha: 0, x: -30 }; }
      if (anim === 'right') { from = { autoAlpha: 0, x: 30 }; }
      if (anim === 'scale') { from = { autoAlpha: 0, scale: 0.9 }; }
      gsap.fromTo(el, from, {
        autoAlpha: 1, x: 0, y: 0, scale: 1, duration: 0.8, ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        onStart: function () { el.classList.add('animated'); }
      });
    });

    gsap.utils.toArray('.stagger-children').forEach(function (group) {
      group.classList.add('animated');
      gsap.fromTo(group.children,
        { autoAlpha: 0, y: 24 },
        {
          autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', stagger: 0.1,
          scrollTrigger: { trigger: group, start: 'top 82%', once: true }
        });
    });

    // Recalculate positions once fonts/images settle so in-view triggers fire.
    global.ScrollTrigger.refresh();
  }

  /* =========================================================================
     EXPORTED ANIMATION FUNCTIONS
     ========================================================================= */

  /** Staggered entrance for the hero section ([data-hero]). */
  function animateHero() {
    var hero = document.querySelector('[data-hero]');
    if (!hero) return;
    var targets = hero.querySelectorAll('[data-hero-item]');
    if (!targets.length) return;

    // Content must NEVER stay hidden — this clears any animation from-state.
    function forceReveal() {
      for (var i = 0; i < targets.length; i++) {
        targets[i].style.visibility = 'visible';
        targets[i].style.opacity = '1';
        targets[i].style.transform = 'none';
      }
    }

    if (reduceMotion || !global.gsap) { forceReveal(); return; }

    var done = false;
    var tween = global.gsap.fromTo(targets,
      { autoAlpha: 0, y: 28 },
      { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: 0.1, delay: 0.1,
        onComplete: function () { done = true; } });

    // Deadline failsafe: if requestAnimationFrame is throttled (background /
    // headless tab) the tween may never complete. After the animation's normal
    // run time, reveal directly so content is never lost.
    global.setTimeout(function () {
      if (!done) { if (tween) tween.kill(); forceReveal(); }
    }, 2200);
  }

  /** Generic section entrance. */
  function animateSection(element) {
    if (!element) return;
    if (reduceMotion) { element.classList.add('animated'); return; }
    if (global.gsap) {
      global.gsap.fromTo(element,
        { autoAlpha: 0, y: 30 },
        {
          autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out',
          scrollTrigger: { trigger: element, start: 'top 85%', once: true }
        });
    } else {
      element.classList.add('animated');
    }
  }

  /** Count-up for stat numbers. Honors data-suffix / data-prefix. */
  function animateCounter(element, target) {
    if (!element) return;
    target = (typeof target === 'number') ? target : parseFloat(element.getAttribute('data-target')) || 0;
    var prefix = element.getAttribute('data-prefix') || '';
    var suffix = element.getAttribute('data-suffix') || '';
    var decimals = parseInt(element.getAttribute('data-decimals'), 10) || 0;

    if (reduceMotion) {
      element.textContent = prefix + target.toFixed(decimals) + suffix;
      return;
    }
    var duration = 1600, startTime = null, finished = false;
    function fmt(v) { return prefix + v.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + suffix; }
    function step(ts) {
      if (startTime === null) startTime = ts;
      var p = Math.min((ts - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
      element.textContent = fmt(target * eased);
      if (p < 1) requestAnimationFrame(step);
      else { finished = true; element.textContent = fmt(target); }
    }
    requestAnimationFrame(step);
    // Deadline failsafe: rAF may be throttled (background/headless) — guarantee
    // the final value is shown.
    setTimeout(function () { if (!finished) element.textContent = fmt(target); }, duration + 500);
  }

  /** Lightweight particle background on a <canvas>. */
  function animateParticles(canvas) {
    if (!canvas || reduceMotion || !canvas.getContext) return function () {};
    var ctx = canvas.getContext('2d');
    var particles = [], raf = null, w, h;

    function resize() {
      w = canvas.width = canvas.offsetWidth;
      h = canvas.height = canvas.offsetHeight;
    }
    function seed() {
      var count = Math.min(70, Math.floor((w * h) / 16000));
      particles = [];
      for (var i = 0; i < count; i++) {
        particles.push({
          x: Math.random() * w, y: Math.random() * h,
          vx: (Math.random() - 0.5) * 0.3, vy: (Math.random() - 0.5) * 0.3,
          r: Math.random() * 1.8 + 0.6
        });
      }
    }
    function tick() {
      ctx.clearRect(0, 0, w, h);
      var accent = getComputedStyle(document.documentElement)
        .getPropertyValue('--color-accent-rgb').trim() || '212,175,106';
      for (var i = 0; i < particles.length; i++) {
        var p = particles[i];
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(' + accent + ',0.5)';
        ctx.fill();
      }
      raf = requestAnimationFrame(tick);
    }
    resize(); seed(); tick();
    global.addEventListener('resize', function () { resize(); seed(); }, { passive: true });
    return function stop() { if (raf) cancelAnimationFrame(raf); };
  }

  /** Parallax on [data-parallax="0.2"] elements. */
  function initParallax() {
    if (reduceMotion) return;
    var els = document.querySelectorAll('[data-parallax]');
    if (!els.length) return;
    if (global.gsap && global.ScrollTrigger) {
      els.forEach(function (el) {
        var amount = parseFloat(el.getAttribute('data-parallax')) || 0.2;
        global.gsap.to(el, {
          yPercent: amount * 100, ease: 'none',
          scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true }
        });
      });
    } else {
      global.addEventListener('scroll', function () {
        var y = global.scrollY;
        els.forEach(function (el) {
          var amount = parseFloat(el.getAttribute('data-parallax')) || 0.2;
          el.style.transform = 'translateY(' + (y * amount) + 'px)';
        });
      }, { passive: true });
    }
  }

  /** Magnetic hover on [data-magnetic] CTAs. */
  function initMagneticButtons() {
    if (reduceMotion) return;
    document.querySelectorAll('[data-magnetic]').forEach(function (btn) {
      var strength = parseFloat(btn.getAttribute('data-magnetic')) || 0.3;
      btn.addEventListener('mousemove', function (e) {
        var r = btn.getBoundingClientRect();
        var mx = e.clientX - (r.left + r.width / 2);
        var my = e.clientY - (r.top + r.height / 2);
        btn.style.transform = 'translate(' + (mx * strength) + 'px,' + (my * strength) + 'px)';
      });
      btn.addEventListener('mouseleave', function () { btn.style.transform = ''; });
    });
  }

  /** Smooth in-page anchor scrolling. */
  function initSmoothScroll() {
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[href^="#"]');
      if (!a) return;
      var id = a.getAttribute('href');
      if (id === '#' || id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
      history.pushState(null, '', id);
    });
  }

  /** Fade page-transition veil on internal navigation. */
  function initPageTransition() {
    if (reduceMotion) return;
    var veil = document.createElement('div');
    veil.className = 'page-transition-veil';
    document.body.appendChild(veil);
    // Fade in on load.
    requestAnimationFrame(function () { veil.classList.remove('is-active'); });
    document.addEventListener('click', function (e) {
      var a = e.target.closest('a[href]');
      if (!a) return;
      var href = a.getAttribute('href');
      var sameOrigin = a.host === global.location.host;
      var isHash = href.indexOf('#') === 0;
      var newTab = a.target === '_blank' || e.metaKey || e.ctrlKey;
      if (!sameOrigin || isHash || newTab || a.hasAttribute('download')) return;
      e.preventDefault();
      veil.classList.add('is-active');
      setTimeout(function () { global.location.href = href; }, 280);
    });
  }

  /* =========================================================================
     SKELETON SCREENS
     ========================================================================= */
  function showSkeleton(container) {
    if (!container) return;
    container.querySelectorAll('[data-skeletonize]').forEach(function (el) {
      el.classList.add('skeleton');
    });
    container.setAttribute('aria-busy', 'true');
  }
  function hideSkeleton(container) {
    if (!container) return;
    container.querySelectorAll('.skeleton').forEach(function (el) {
      el.classList.remove('skeleton');
    });
    container.removeAttribute('aria-busy');
  }

  /* =========================================================================
     RIPPLE
     ========================================================================= */
  function addRipple(button) {
    if (!button) return;
    button.classList.add('has-ripple');
    button.addEventListener('click', function (e) {
      if (reduceMotion) return;
      var r = button.getBoundingClientRect();
      var size = Math.max(r.width, r.height);
      var span = document.createElement('span');
      span.className = 'ripple';
      span.style.width = span.style.height = size + 'px';
      span.style.left = (e.clientX - r.left - size / 2) + 'px';
      span.style.top = (e.clientY - r.top - size / 2) + 'px';
      button.appendChild(span);
      setTimeout(function () { span.remove(); }, 600);
    });
  }

  /* =========================================================================
     BOOT
     ========================================================================= */
  function autoInit() {
    initSmoothScroll();
    initMagneticButtons();
    document.querySelectorAll('[data-ripple]').forEach(addRipple);

    // Counters: trigger when visible.
    var counters = document.querySelectorAll('[data-counter]');
    if (counters.length && 'IntersectionObserver' in global) {
      var cio = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (en) {
          if (en.isIntersecting) { animateCounter(en.target); obs.unobserve(en.target); }
        });
      }, { threshold: 0.5 });
      counters.forEach(function (c) { cio.observe(c); });
    } else {
      counters.forEach(function (c) { animateCounter(c); });
    }

    if (reduceMotion) { revealAllImmediately(); }
    else {
      // Scroll reveals use IntersectionObserver (fires without rAF) + CSS
      // transitions, so the revealed end-state applies even when frames are
      // throttled. GSAP is used only for the hero entrance and parallax.
      initIO();
      revealSafetyNet();
    }

    loadGSAP().then(function (ok) {
      if (ok && !reduceMotion) { initParallax(); }
      animateHero();
    });
  }

  /* Safety net: setInterval fires even when rAF + IntersectionObserver are
     throttled (background / headless / automated tabs). Reveals any element
     once it is within the viewport — a no-op in normal browsers where IO has
     already done it, so the scroll-reveal UX is preserved. */
  function revealSafetyNet() {
    var ticks = 0;
    var id = global.setInterval(function () {
      ticks++;
      var els = document.querySelectorAll(
        '.animate-on-scroll:not(.animated), .stagger-children:not(.animated)');
      var vh = global.innerHeight || document.documentElement.clientHeight;
      for (var i = 0; i < els.length; i++) {
        var r = els[i].getBoundingClientRect();
        if (r.top < vh * 0.92 && r.bottom > 0) els[i].classList.add('animated');
      }
      if (els.length === 0 || ticks > 120) global.clearInterval(id);
    }, 900);
  }

  /* Expose API */
  var api = {
    loadGSAP: loadGSAP,
    animateHero: animateHero,
    animateSection: animateSection,
    animateCounter: animateCounter,
    animateParticles: animateParticles,
    initParallax: initParallax,
    initMagneticButtons: initMagneticButtons,
    initSmoothScroll: initSmoothScroll,
    initPageTransition: initPageTransition,
    showSkeleton: showSkeleton,
    hideSkeleton: hideSkeleton,
    addRipple: addRipple
  };
  global.GSILAnim = api;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoInit);
  } else { autoInit(); }
})(window);
