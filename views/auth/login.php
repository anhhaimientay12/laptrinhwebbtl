<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'Đăng nhập' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'Be Vietnam Pro', sans-serif;
  background: #0f1117;
  color: #e2e8f0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  position: relative;
  overflow: hidden;
}
/* Animated background */
body::before {
  content: '';
  position: fixed; inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 20% 20%, rgba(59,130,246,.08) 0%, transparent 60%),
    radial-gradient(ellipse 50% 60% at 80% 80%, rgba(99,102,241,.07) 0%, transparent 60%);
  pointer-events: none;
}
/* Grid pattern */
body::after {
  content: '';
  position: fixed; inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,.015) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.015) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
}

.login-wrap {
  width: 100%;
  max-width: 420px;
  position: relative;
  z-index: 1;
  animation: fadeUp .5s ease both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: none; }
}

.login-header {
  text-align: center;
  margin-bottom: 32px;
}
.login-logo {
  width: 60px; height: 60px;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  border-radius: 16px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 26px; color: #fff;
  margin-bottom: 18px;
  box-shadow: 0 8px 32px rgba(59,130,246,.35);
}
.login-title    { font-size: 24px; font-weight: 700; color: #f1f5f9; }
.login-subtitle { font-size: 13.5px; color: #64748b; margin-top: 6px; }

.login-card {
  background: #161b27;
  border: 1px solid #2a3347;
  border-radius: 16px;
  padding: 32px 36px;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
}

.form-group { margin-bottom: 20px; }
label {
  display: block;
  font-size: 12px; font-weight: 600;
  color: #64748b;
  text-transform: uppercase; letter-spacing: .06em;
  margin-bottom: 8px;
}
.input-wrap { position: relative; }
.input-wrap i {
  position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
  color: #475569; font-size: 14px; pointer-events: none;
}
.input-wrap input {
  width: 100%;
  background: #0f1117;
  border: 1px solid #2a3347;
  border-radius: 10px;
  padding: 12px 14px 12px 42px;
  color: #e2e8f0;
  font-size: 14px;
  font-family: inherit;
  transition: border-color .2s, box-shadow .2s;
}
.input-wrap input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.input-wrap input::placeholder { color: #475569; }
.input-wrap .toggle-pw {
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  color: #475569; cursor: pointer; font-size: 14px;
  transition: color .2s;
  width: 44px;
  height: 100%;
}
.input-wrap .toggle-pw:hover { color: #94a3b8; }

.error-msg {
  font-size: 12px; color: #f87171;
  margin-top: 6px; display: flex; align-items: center; gap: 5px;
}
.error-general {
  background: rgba(239,68,68,.1);
  border: 1px solid rgba(239,68,68,.25);
  border-radius: 10px;
  padding: 12px 16px;
  color: #f87171;
  font-size: 13px;
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 20px;
}
.input-error { border-color: #ef4444 !important; }

.btn-login {
  width: 100%;
  padding: 13px;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  border: none; border-radius: 10px;
  color: #fff; font-size: 15px; font-weight: 600;
  font-family: inherit;
  cursor: pointer;
  transition: opacity .2s, transform .15s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  margin-top: 8px;
}
.btn-login:hover   { opacity: .92; }
.btn-login:active  { transform: scale(.98); }

.login-hint {
  margin-top: 24px;
  background: rgba(59,130,246,.06);
  border: 1px solid rgba(59,130,246,.18);
  border-radius: 10px;
  padding: 14px 16px;
  font-size: 12.5px;
  color: #64748b;
}
.login-hint strong { color: #93c5fd; }
.login-hint table  { width: 100%; border-collapse: collapse; margin-top: 8px; }
.login-hint td     { padding: 3px 0; }
.login-hint td:last-child { color: #7dd3fc; font-family: 'JetBrains Mono', monospace; }
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-header">
    <div class="login-logo"><i class="fas fa-graduation-cap"></i></div>
    <div class="login-title"><?= APP_NAME ?></div>
    <div class="login-subtitle">Hệ thống quản lý học tập</div>
  </div>

  <div class="login-card">
    <?php if (!empty($errors['general'])): ?>
      <div class="error-general">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($errors['general']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/auth/login">
      <div class="form-group">
        <label for="username">Tên đăng nhập</label>
        <div class="input-wrap">
          <i class="fas fa-user"></i>
          <input
            type="text"
            id="username"
            name="username"
            placeholder="Nhập tên đăng nhập..."
            value="<?= htmlspecialchars($username ?? '') ?>"
            class="<?= !empty($errors['username']) ? 'input-error' : '' ?>"
            autofocus
            autocomplete="username"
          >
        </div>
        <?php if (!empty($errors['username'])): ?>
          <div class="error-msg"><i class="fas fa-circle-exclamation"></i><?= $errors['username'] ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="password">Mật khẩu</label>
        <div class="input-wrap">
          <i class="fas fa-lock"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Nhập mật khẩu..."
            class="<?= !empty($errors['password']) ? 'input-error' : '' ?>"
            autocomplete="current-password"
          >
          <span class="toggle-pw" onclick="togglePw()"><i class="fas fa-eye" id="eyeIcon"></i></span>
        </div>
        <?php if (!empty($errors['password'])): ?>
          <div class="error-msg"><i class="fas fa-circle-exclamation"></i><?= $errors['password'] ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn-login">
        <i class="fas fa-right-to-bracket"></i> Đăng nhập
      </button>
    </form>
  </div>

  <!-- Gợi ý tài khoản demo -->
  <div class="login-hint">
    <strong>Tài khoản demo (password: <code>password</code>)</strong>
    <table>
      <tr><td>Admin</td><td>admin</td></tr>
      <tr><td>Giảng viên</td><td>gv001</td></tr>
      <tr><td>Sinh viên</td><td>sv2021001</td></tr>
    </table>
  </div>
</div>

<script>
function togglePw() {
  const inp  = document.getElementById('password');
  const icon = document.getElementById('eyeIcon');
  if (inp.type === 'password') {
    inp.type = 'text';
    icon.className = 'fas fa-eye-slash';
  } else {
    inp.type = 'password';
    icon.className = 'fas fa-eye';
  }
}
</script>
</body>
</html>
