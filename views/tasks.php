<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? 'Guest';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Todo — Tasks</title>
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
      --success: #7FE5B2;
      --danger: #E24B6A;
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
      display: flex;
      justify-content: center;
      padding: 28px 18px;
    }

    .shell{
      width: min(960px, 100%);
      display: grid;
      grid-template-columns: 1.2fr .9fr;
      gap: 18px;
      align-items: start;
    }

    .card{
      background: var(--card);
      border-radius: var(--radius);
      border: 1px solid var(--stroke);
      box-shadow: var(--shadow);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      overflow: hidden;
    }

    header.app-header{
      width: min(960px, 100%);
      margin: 0 auto 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .app-title{
      font-size: 20px;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .user-chip{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid var(--stroke);
      background: rgba(255,255,255,.7);
      font-size: 13px;
      color: var(--muted);
    }

    .badge-dot{
      width: 9px;
      height: 9px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--success), var(--primary));
    }

    a.link{
      color: rgba(116,80,239,.92);
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
    }
    a.link:hover{ text-decoration: underline; }

    .tasks-main{
      padding: 24px 24px 20px;
    }

    .tasks-main h1{
      margin: 0 0 6px;
      font-size: 26px;
      letter-spacing: -0.02em;
    }

    .tasks-main p{
      margin: 0 0 18px;
      color: var(--muted);
      font-size: 14px;
    }

    form.new-task{
      display: flex;
      gap: 10px;
      margin-bottom: 18px;
      flex-wrap: wrap;
    }

    form.new-task input[type="text"]{
      flex: 1 1 260px;
      padding: 10px 12px;
      border-radius: 14px;
      border: 1px solid rgba(17,24,39,.12);
      background: rgba(255,255,255,.82);
      min-height: 40px;
      outline: none;
      transition: border-color .12s ease, box-shadow .12s ease;
    }

    form.new-task input[type="text"]:focus{
      border-color: rgba(116,80,239,.40);
      box-shadow: 0 0 0 3px rgba(116,80,239,.18);
    }

    form.new-task button{
      border-radius: 14px;
      border: 1px solid transparent;
      padding: 10px 16px;
      min-height: 40px;
      font-weight: 600;
      cursor: pointer;
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      box-shadow: 0 10px 26px rgba(116,80,239,.28);
      transition: transform .1s ease, box-shadow .1s ease;
    }

    form.new-task button:hover{
      transform: translateY(-1px);
    }

    form.new-task button:active{
      transform: translateY(0);
    }

    .flash{
      margin-bottom: 14px;
      padding: 10px 12px;
      border-radius: 14px;
      font-size: 13px;
      line-height: 1.5;
    }

    .flash.error{
      border: 1px solid rgba(226,75,106,.45);
      background: rgba(226,75,106,.08);
      color: rgba(159,32,52,1);
    }

    .flash.success{
      border: 1px solid rgba(127,229,178,.60);
      background: rgba(127,229,178,.10);
      color: rgba(9,95,58,1);
    }

    ul.task-list{
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 8px;
    }

    .task-item{
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 10px 12px;
      border-radius: 14px;
      border: 1px solid rgba(17,24,39,.06);
      background: rgba(255,255,255,.72);
    }

    .task-text{
      font-size: 14px;
      line-height: 1.5;
    }

    .task-actions{
      display: inline-flex;
      gap: 6px;
      flex-shrink: 0;
    }

    .btn-icon{
      border-radius: 999px;
      padding: 6px 10px;
      border: 1px solid rgba(17,24,39,.10);
      background: rgba(255,255,255,.9);
      font-size: 12px;
      cursor: pointer;
    }

    .btn-icon.danger{
      border-color: rgba(226,75,106,.45);
      color: rgba(159,32,52,1);
    }

    .summary-card{
      padding: 22px 22px 18px;
      display: grid;
      gap: 12px;
    }

    .summary-card h2{
      margin: 0 0 4px;
      font-size: 16px;
      letter-spacing: -0.01em;
    }

    .summary-card p{
      margin: 0;
      font-size: 14px;
      color: var(--muted);
    }

    .pill{
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid var(--stroke);
      background: rgba(255,255,255,.8);
      font-size: 13px;
      color: var(--muted);
    }

    .pill span{
      font-weight: 600;
      color: var(--text);
    }

    @media (max-width: 860px){
      body{
        padding: 16px 12px 24px;
      }
      header.app-header{
        flex-direction: column;
        align-items: flex-start;
      }
      .shell{
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<header class="app-header">
  <div class="app-title">Todo — Tasks</div>
  <div style="display:flex;align-items:center;gap:12px;">
    <div class="user-chip">
      <span class="badge-dot"></span>
      <span><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <a class="link" href="/logout">Logout</a>
    <a class="link" href="/">Home</a>
  </div>
</header>

<main class="shell">
  <section class="card tasks-main" aria-label="Tasks">

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="flash error">
        <?php
          echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['error']);
        ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="flash success">
        <?php
          echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['success']);
        ?>
      </div>
    <?php endif; ?>

    <h1>Your tasks</h1>
    <p>Add a new item and keep today under control.</p>

    <form class="new-task" action="/tasks" method="post">
      <input
        type="text"
        name="task"
        placeholder="Type a task and press Add"
        required
      />
      <button type="submit">Add task</button>
    </form>

    <ul class="task-list">
      <?php if (!empty($tasks) && is_array($tasks)): ?>
        <?php foreach ($tasks as $task): ?>
          <li class="task-item">
            <div class="task-text">
              <?php echo htmlspecialchars($task['task'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div class="task-actions">
              <form action="/tasks/done" method="post" style="margin:0;">
                <input type="hidden" name="id" value="<?php echo (int)$task['id']; ?>">
                <button class="btn-icon" type="submit">Done</button>
              </form>
              <form action="/tasks/delete" method="post" style="margin:0;">
                <input type="hidden" name="id" value="<?php echo (int)$task['id']; ?>">
                <button class="btn-icon danger" type="submit">Delete</button>
              </form>
            </div>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <li class="task-item">
          <div class="task-text" style="color:var(--muted);">
            No tasks yet. Add your first one above.
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </section>

  <aside class="card summary-card" aria-label="Summary">
    <h2>Today</h2>
    <p>Keep your list short and focused. Mark items done as you complete them.</p>

    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">
      <div class="pill">
        Total
        <span><?php echo !empty($tasks) ? count($tasks) : 0; ?></span>
      </div>
    </div>
  </aside>
</main>

</body>
</html>
