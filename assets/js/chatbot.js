/* ===========================================================================
   chatbot.js — site assistant widget for Mason Law, P.C.
   Progressive enhancement: the markup is inert until this wires it up.
   =========================================================================== */
(function () {
  'use strict';

  var root = document.querySelector('[data-chatbot]');
  if (!root) return;

  var fab      = root.querySelector('[data-chat-toggle]');
  var panel    = root.querySelector('[data-chat-panel]');
  var log      = root.querySelector('[data-chat-log]');
  var form     = root.querySelector('[data-chat-form]');
  var input    = root.querySelector('[data-chat-input]');
  var sendBtn  = root.querySelector('[data-chat-send]');
  var quick    = root.querySelector('[data-chat-quick]');
  var closeBtn = root.querySelector('[data-chat-close]');
  var csrf     = root.getAttribute('data-csrf') || '';
  var phone    = root.getAttribute('data-phone') || '';

  var history = [];          // [{role, content}]
  var busy = false;
  var opened = false;

  /* ---- open / close ------------------------------------------------------ */
  function open() {
    if (root.classList.contains('is-open')) return;
    panel.hidden = false;
    // next frame so the transition runs
    requestAnimationFrame(function () { root.classList.add('is-open', 'is-engaged'); });
    fab.setAttribute('aria-expanded', 'true');
    opened = true;
    setTimeout(function () { input && input.focus(); }, 120);
  }
  function close() {
    root.classList.remove('is-open');
    fab.setAttribute('aria-expanded', 'false');
    setTimeout(function () { if (!root.classList.contains('is-open')) panel.hidden = true; }, 260);
    fab.focus();
  }
  function toggle() { root.classList.contains('is-open') ? close() : open(); }

  fab.addEventListener('click', toggle);
  closeBtn.addEventListener('click', close);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && root.classList.contains('is-open')) close();
  });

  /* ---- rendering --------------------------------------------------------- */
  function scrollDown() { log.scrollTop = log.scrollHeight; }

  function escapeHtml(s) {
    return s.replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  // Escape first, then linkify a safe allow-list: tel:, /internal paths, https URLs, emails.
  function formatBot(text) {
    var safe = escapeHtml(text);
    safe = safe.replace(/\bhttps?:\/\/[^\s<]+/g, function (u) {
      return '<a href="' + u + '" target="_blank" rel="noopener">' + u + '</a>';
    });
    safe = safe.replace(/(^|[\s(])(\/[a-z0-9\-/.]+\.php)\b/gi, function (m, pre, path) {
      return pre + '<a href="' + path + '">' + path + '</a>';
    });
    safe = safe.replace(/([\w.+-]+@[\w-]+\.[\w.-]+)/g, '<a href="mailto:$1">$1</a>');
    if (phone) {
      var tel = phone.replace(/[^\d+]/g, '');
      safe = safe.replace(new RegExp(escapeHtml(phone).replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'),
        '<a href="tel:' + tel + '">' + escapeHtml(phone) + '</a>');
    }
    return safe.replace(/\n/g, '<br>');
  }

  function addMessage(text, who) {
    var el = document.createElement('div');
    el.className = 'chatbot__msg chatbot__msg--' + (who === 'user' ? 'user' : 'bot');
    if (who === 'user') { el.textContent = text; }
    else { el.innerHTML = '<p>' + formatBot(text) + '</p>'; }
    log.appendChild(el);
    scrollDown();
    return el;
  }

  function showTyping() {
    var t = document.createElement('div');
    t.className = 'chatbot__typing';
    t.setAttribute('data-typing', '');
    t.innerHTML = '<span></span><span></span><span></span>';
    log.appendChild(t);
    scrollDown();
    return t;
  }

  /* ---- sending ----------------------------------------------------------- */
  function send(text) {
    text = (text || '').trim();
    if (!text || busy) return;

    if (quick) quick.classList.add('is-hidden');
    addMessage(text, 'user');
    history.push({ role: 'user', content: text });
    input.value = '';
    autoGrow();

    busy = true;
    sendBtn.disabled = true;
    var typing = showTyping();

    fetch('/api/chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
      body: JSON.stringify({ message: text, history: history.slice(0, -1), csrf_token: csrf })
    })
      .then(function (r) { return r.json().catch(function () { return {}; }); })
      .then(function (data) {
        typing.remove();
        var reply = (data && data.reply) ? data.reply
          : "Sorry — I couldn't reach the assistant just now. Please call us at " + (phone || 'our office') + " and we'll be glad to help.";
        addMessage(reply, 'bot');
        history.push({ role: 'assistant', content: reply });
      })
      .catch(function () {
        typing.remove();
        addMessage("I'm having trouble connecting. Please call us at " + (phone || 'our office') + " for a fast response.", 'bot');
      })
      .finally(function () {
        busy = false;
        sendBtn.disabled = false;
        input.focus();
      });
  }

  form.addEventListener('submit', function (e) { e.preventDefault(); send(input.value); });

  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(input.value); }
  });

  /* auto-grow textarea */
  function autoGrow() {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 120) + 'px';
  }
  input.addEventListener('input', autoGrow);

  /* quick-reply chips */
  root.querySelectorAll('[data-chat-suggest]').forEach(function (chip) {
    chip.addEventListener('click', function () {
      if (!root.classList.contains('is-open')) open();
      send(chip.getAttribute('data-chat-suggest'));
    });
  });
})();
