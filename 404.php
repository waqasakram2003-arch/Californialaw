<?php
/**
 * 404.php — on-brand "Page Not Found".
 * Self-contained (no DB / includes) so it renders even under failure conditions.
 */
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, follow">
<title>Page Not Found (404) | Mason Law, P.C. | California Personal Injury Attorney</title>
<script>try{var t=localStorage.getItem('theme');if(t==='dark'||(!t&&matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.setAttribute('data-theme','dark');}catch(e){}</script>
<style>
  :root{--primary:#1B2A4A;--secondary:#C8A96E;--accent:#D4AF6A;--bg:#F8F9FC;--text:#2D3748;--muted:#64748B;--card:#FFFFFF;--border:#E2E8F0;}
  html[data-theme="dark"]{--primary:#0D1B2A;--secondary:#E8C97E;--accent:#D4AF6A;--bg:#0A0F1E;--text:#E2E8F0;--muted:#94A3B8;--card:#111B2E;--border:#1E293B;}
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:Georgia,'Times New Roman',serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;line-height:1.6;}
  .wrap{max-width:600px;text-align:center;}
  .scales{width:160px;height:160px;margin:0 auto 1.5rem;}
  .scales .beam{transform-origin:100px 46px;animation:tip 4s ease-in-out infinite;}
  @keyframes tip{0%,100%{transform:rotate(-9deg);}50%{transform:rotate(9deg);}}
  @media (prefers-reduced-motion:reduce){.scales .beam{animation:none;}}
  .code{font-family:'Helvetica Neue',Arial,sans-serif;font-size:5rem;font-weight:800;color:var(--secondary);letter-spacing:.05em;line-height:1;}
  h1{font-size:1.9rem;color:var(--primary);margin:.5rem 0 .75rem;}
  html[data-theme="dark"] h1{color:var(--secondary);}
  p{color:var(--muted);font-size:1.05rem;margin-bottom:1.75rem;}
  form{display:flex;gap:.5rem;max-width:420px;margin:0 auto 1.75rem;}
  input[type=search]{flex:1;padding:.8rem 1rem;border:1px solid var(--border);border-radius:8px;background:var(--card);color:var(--text);font-size:1rem;}
  input[type=search]:focus{outline:2px solid var(--accent);outline-offset:1px;}
  button,.btn{font-family:'Helvetica Neue',Arial,sans-serif;font-weight:600;border:none;cursor:pointer;border-radius:8px;padding:.8rem 1.25rem;font-size:.95rem;background:var(--primary);color:#fff;text-decoration:none;display:inline-block;transition:transform .15s ease,box-shadow .15s ease;}
  html[data-theme="dark"] button,html[data-theme="dark"] .btn-primary{background:var(--secondary);color:#0A0F1E;}
  .links{display:flex;flex-wrap:wrap;gap:.6rem;justify-content:center;}
  .btn-ghost{background:transparent;color:var(--primary);border:1px solid var(--border);}
  html[data-theme="dark"] .btn-ghost{color:var(--text);}
  .btn:hover,button:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(0,0,0,.15);}
</style>
</head>
<body>
  <main class="wrap">
    <svg class="scales" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <line x1="100" y1="30" x2="100" y2="150" stroke="var(--primary)" stroke-width="6" stroke-linecap="round"/>
      <rect x="70" y="150" width="60" height="10" rx="4" fill="var(--primary)"/>
      <rect x="55" y="160" width="90" height="9" rx="4" fill="var(--secondary)"/>
      <circle cx="100" cy="30" r="8" fill="var(--accent)"/>
      <g class="beam">
        <line x1="40" y1="46" x2="160" y2="46" stroke="var(--secondary)" stroke-width="6" stroke-linecap="round"/>
        <line x1="40" y1="46" x2="40" y2="78" stroke="var(--muted)" stroke-width="2"/>
        <line x1="160" y1="46" x2="160" y2="78" stroke="var(--muted)" stroke-width="2"/>
        <path d="M22 78 A18 14 0 0 0 58 78 Z" fill="var(--primary)" opacity=".85"/>
        <path d="M142 78 A18 14 0 0 0 178 78 Z" fill="var(--primary)" opacity=".55"/>
      </g>
    </svg>
    <div class="code">404</div>
    <h1>Page Not Found</h1>
    <p>The page you're looking for has moved or no longer exists. Let's help you find your way back.</p>
    <form action="/blog/" method="get" role="search">
      <input type="search" name="q" placeholder="Search our site&hellip;" aria-label="Search">
      <button type="submit">Search</button>
    </form>
    <nav class="links" aria-label="Helpful links">
      <a class="btn btn-primary" href="/">Home</a>
      <a class="btn btn-ghost" href="/practice-areas/">Practice Areas</a>
      <a class="btn btn-ghost" href="/contact.php">Contact</a>
    </nav>
  </main>
</body>
</html>
