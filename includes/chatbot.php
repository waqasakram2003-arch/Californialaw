<?php
/**
 * chatbot.php — floating site assistant (Mason Law, P.C.).
 * Included near the end of footer.php (session already started for CSRF).
 * Talks to /api/chat.php (Claude-powered when configured, smart fallback otherwise).
 */
$__ml_firm  = function_exists('cfg') ? cfg('firm_name', SITE_NAME) : SITE_NAME;
$__ml_phone = function_exists('cfg') ? cfg('site_phone', SITE_PHONE) : SITE_PHONE;
?>
<div class="chatbot" data-chatbot data-csrf="<?= e(csrf_token()) ?>" data-phone="<?= e($__ml_phone) ?>">
  <!-- Launcher -->
  <button class="chatbot__fab" type="button" data-chat-toggle aria-expanded="false" aria-controls="chatbot-panel"
          aria-label="Chat with the <?= e($__ml_firm) ?> assistant">
    <span class="chatbot__fab-icon chatbot__fab-icon--chat" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
    </span>
    <span class="chatbot__fab-icon chatbot__fab-icon--close" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
    </span>
    <span class="chatbot__fab-badge" aria-hidden="true">1</span>
  </button>

  <!-- Panel -->
  <section class="chatbot__panel" id="chatbot-panel" data-chat-panel role="dialog" aria-label="<?= e($__ml_firm) ?> assistant" hidden>
    <header class="chatbot__head">
      <span class="chatbot__avatar" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6l9-4 9 4M4 10v7M20 10v7M3 17h18M2 21h20"/><path d="M6 10v5M10 10v5M14 10v5M18 10v5"/></svg>
      </span>
      <div class="chatbot__head-text">
        <strong><?= e($__ml_firm) ?> Assistant</strong>
        <span class="chatbot__status"><span class="chatbot__dot"></span> Online — replies in seconds</span>
      </div>
      <button class="chatbot__close" type="button" data-chat-close aria-label="Close chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
      </button>
    </header>

    <div class="chatbot__log" data-chat-log role="log" aria-live="polite" aria-atomic="false">
      <div class="chatbot__msg chatbot__msg--bot">
        <p>Hi 👋 I'm the <?= e($__ml_firm) ?> assistant. Ask me about our practice areas, offices, fees, or booking a <strong>free consultation</strong>. How can I help?</p>
      </div>
    </div>

    <div class="chatbot__quick" data-chat-quick>
      <button type="button" class="chatbot__chip" data-chat-suggest="Do you offer a free consultation?">Free consultation?</button>
      <button type="button" class="chatbot__chip" data-chat-suggest="What types of cases do you handle?">What cases?</button>
      <button type="button" class="chatbot__chip" data-chat-suggest="How much does it cost to hire you?">Your fees?</button>
      <button type="button" class="chatbot__chip" data-chat-suggest="Where are your offices located?">Offices?</button>
    </div>

    <form class="chatbot__form" data-chat-form>
      <label class="sr-only" for="chatbot-input">Type your message</label>
      <textarea id="chatbot-input" data-chat-input rows="1" placeholder="Type your message…" maxlength="2000" autocomplete="off"></textarea>
      <button class="chatbot__send" type="submit" data-chat-send aria-label="Send message">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      </button>
    </form>

    <p class="chatbot__disclaimer">Automated assistant &middot; not legal advice &middot; no attorney-client relationship is formed here.</p>
  </section>
</div>
