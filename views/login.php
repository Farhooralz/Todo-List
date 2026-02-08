<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Todo — Login</title>
  <style>
    :root{
      --bg-1: #D1F1FF;
      --bg-2: #F9D5FF;
      --card: rgba(255,255,255,.78);
      --stroke: rgba(17,24,39,.10);
      --text: #111827;
      --muted: rgba(17,24,39,.70);
      --primary: #7450EF;
      --primary-2: #9999F3;
      --shadow: 0 18px 60px rgba(17,24,39,.12);
      --radius: 22px;
    }

    *{ box-sizing: border-box; }

    body{
      margin: 0;
      min-height: 100vh;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
      color: var(--text);
      background:
        radial-gradient(1200px 700px at 20% 10%, var(--bg-2), transparent 55%),
        radial-gradient(900px 600px at 80% 30%, var(--bg-1), transparent 60%),
        linear-gradient(135deg, #ffffff, #fbfbff);
      display: grid;
      place-items: center;
      padding: 28px 18px;
    }

    .card{
      width: min(520px, 100%);
      background: var(--card);
      border: 1px solid var(--stroke);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      overflow: hidden;
    }

    header{
      padding: 26px 26px 0;
    }

    .badge{
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      border-radius: 999px;
      border: 1px solid var(--stroke);
      background: rgba(255,255,255,.65);
      font-size: 13px;
      color: var(--muted);
    }
    .dot{
      width: 10px; height: 10px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(127,229,178,.9), var(--primary));
      box-shadow: 0 6px 18px rgba(116,80,239,.25);
    }

    h1{
      margin: 12px 0 6px;
      font-size: 30px;
      letter-spacing: -0.02em;
    }

    p{
      margin: 0 0 14px;
      color: var(--muted);
      line-height: 1.6;
    }

    form{
      padding: 0 26px 24px;
      display: grid;
      gap: 14px;
    }

    .field{
      display: grid;
      gap: 7px;
    }

    label{
      font-size: 13px;
      color: rgba(17,24,39,.78);
      font-weight: 600;
    }

    input{
      width: 100%;
      padding: 12px 12px;
      border-radius: 14px;
      border: 1px solid rgba(17,24,39,.12);
      background: rgba(255,255,255,.68);
      color: var(--text);
      outline: none;
      transition: border-color .12s ease, box-shadow .12s ease, background .12s ease;
      min-height: 44px;
    }

    input:focus{
      border-color: rgba(116,80,239,.35);
      box-shadow: 0 0 0 4px rgba(116,80,239,.16);
      background: rgba(255,255,255,.82);
    }

    .row{
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 4px;
    }

    .link{
      color: rgba(116,80,239,.92);
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
    }
    .link:hover{ text-decoration: underline; }

    button{
      width: 100%;
      border: 1px solid transparent;
      border-radius: 14px;
      padding: 12px 16px;
      min-height: 44px;
      font-weight: 700;
      cursor: pointer;
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      box-shadow: 0 14px 34px rgba(116,80,239,.30);
      transition: transform .12s ease, box-shadow .12s ease;
    }
    button:hover{ transform: translateY(-1px); }
    button:active{ transform: translateY(0px); }

    button:focus-visible{
      outline: 3px solid rgba(116,80,239,.35);
      outline-offset: 3px;
    }

    footer{
      padding: 0 26px 26px;
      color: rgba(17,24,39,.55);
      font-size: 13px;
      line-height: 1.6;
    }
  </style>
</head>
<body>
  <main class="card" role="main" aria-label="Login">
    <header>
      <div class="badge"><span class="dot" aria-hidden="true"></span>Todo App</div>
      <h1>Login</h1>
      <p>Enter your username and password to continue.</p>
    </header>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="error">
        <?php
          echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['error']);
        ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="success">
        <?php
          echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['success']);
        ?>
      </div>
    <?php endif; ?>

    <form action="/login" method="post">
      <div class="field">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" autocomplete="username" required />
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required />
      </div>

      <button type="submit">Sign in</button>

      <div class="row" aria-label="Navigation links">
        <a class="link" href="/">← Back</a>
        <a class="link" href="/register">Create account</a>
      </div>
    </form>

    <footer>
      Tip: Use a strong password and don’t reuse it across sites.
    </footer>
  </main>
</body>
</html>
