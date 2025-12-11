<?php require_once __DIR__.'/../config/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8; /* professional light gray-blue */
    }

    /* ---------- Typewriter effect ---------- */
    .typewriter {
      font-family: 'Segoe UI', sans-serif;
      font-size: 28px;
      font-weight: 700;
      color: #4a90e2; /* professional blue */
      text-align: center;
      margin-bottom: 20px;
      overflow: hidden;
      white-space: nowrap;
      border-right: 3px solid #4a90e2; /* match text color */
      width: 22ch;
      animation: blink 0.7s infinite;
    }

    @keyframes blink {
      0%, 100% { border-color: transparent; }
      50% { border-color: #4a90e2; }
    }

    /* ---------- Login Button (Uiverse.io) ---------- */
    .cssbuttons-io-button {
      background: #4a90e2; /* primary blue */
      color: white;
      font-family: inherit;
      padding: 0.35em;
      padding-left: 1.2em;
      font-size: 17px;
      font-weight: 500;
      border-radius: 0.9em;
      border: none;
      letter-spacing: 0.05em;
      display: flex;
      align-items: center;
      box-shadow: inset 0 0 1.6em -0.6em #357ABD;
      overflow: hidden;
      position: relative;
      height: 2.8em;
      padding-right: 3.3em;
      cursor: pointer;
      transition: all 0.3s;
    }

    .cssbuttons-io-button .icon {
      background: white;
      margin-left: 1em;
      position: absolute;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 2.2em;
      width: 2.2em;
      border-radius: 0.7em;
      box-shadow: 0.1em 0.1em 0.6em 0.2em #357ABD;
      right: 0.3em;
      transition: all 0.3s;
    }

    .cssbuttons-io-button .icon svg {
      width: 1.1em;
      transition: transform 0.3s;
      color: #357ABD;
    }

    .cssbuttons-io-button:hover {
      background-color: #357ABD;
    }

    .cssbuttons-io-button:hover .icon {
      width: calc(100% - 0.6em);
    }

    .cssbuttons-io-button:hover .icon svg {
      transform: translateX(0.1em);
      color: #ffffff;
    }

    .cssbuttons-io-button:active .icon {
      transform: scale(0.95);
    }

    /* ---------- Card ---------- */
    .card {
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .card h2 {
      color: #2c3e50;
    }

    /* ---------- Form Inputs ---------- */
    .form-control {
      background-color: #f9f9f9;
      border: 1px solid #ccc;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
      border-color: #4a90e2;
      box-shadow: 0 0 5px rgba(74,144,226,0.3);
    }

    /* ---------- Alerts ---------- */
    .alert-danger {
      background-color: #fdecea;
      border-color: #f5c6cb;
      color: #a94442;
    }

    .alert-success {
      background-color: #eafaf1;
      border-color: #b7e4c7;
      color: #27ae60;
    }

    /* ---------- Links ---------- */
    a {
      color: #4a90e2;
    }
    a:hover {
      color: #357ABD;
    }

  </style>
</head>
<body>
  <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">

    <!-- Typewriter Title -->
    <div id="typewriter" class="typewriter"></div>

    <!-- LOGIN CARD -->
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
      <h2 class="text-center mb-4 text-primary">Login</h2>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php elseif (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
      <?php endif; ?>

      <form method="post" action="login_action.php">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>

        <!-- Login Button -->
        <button class="cssbuttons-io-button">
          Login
          <div class="icon">
            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 0h24v24H0z" fill="none"></path>
              <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
            </svg>
          </div>
        </button>
      </form>

      <p class="mt-3 text-center">No account? <a href="register.php">Register</a></p>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    const text = "TRUSTED CV GENERATOR";
    const typewriter = document.getElementById('typewriter');
    let index = 0;
    let forward = true;

    function typeEffect() {
      if (forward) {
        index++;
        if (index > text.length) {
          forward = false;
          setTimeout(typeEffect, 1000);
          return;
        }
      } else {
        index--;
        if (index < 0) {
          forward = true;
          setTimeout(typeEffect, 500);
          return;
        }
      }
      typewriter.textContent = text.slice(0, index);
      setTimeout(typeEffect, forward ? 150 : 80);
    }

    typeEffect();
  </script>
</body>
</html>
