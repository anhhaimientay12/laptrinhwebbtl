<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? APP_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --sidebar-w: 260px;
  --header-h:  64px;
  --clr-bg:    #0f1117;
  --clr-surface:#161b27;
  --clr-card:  #1c2333;
  --clr-border:#2a3347;
  --clr-accent:#3b82f6;
  --clr-accent2:#6366f1;
  --clr-green: #22c55e;
  --clr-amber: #f59e0b;
  --clr-red:   #ef4444;
  --clr-text:  #e2e8f0;
  --clr-muted: #64748b;
  --clr-hover: #1e2740;
  --font:      'Be Vietnam Pro', sans-serif;
  --mono:      'JetBrains Mono', monospace;
  --radius:    10px;
  --shadow:    0 4px 24px rgba(0,0,0,.4);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: var(--font);
  background: var(--clr-bg);
  color: var(--clr-text);
  font-size: 14px;
  line-height: 1.6;
  display: flex;
  min-height: 100vh;
}

/* ── Sidebar ── */
.sidebar {
  width: var(--sidebar-w);
  background: var(--clr-surface);
  border-right: 1px solid var(--clr-border);
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0; left: 0;
  height: 100vh;
  z-index: 100;
  transition: transform .3s ease;
}
.sidebar-logo {
  padding: 20px 22px;
  border-bottom: 1px solid var(--clr-border);
  display: flex;
  align-items: center;
  gap: 12px;
}
.sidebar-logo .logo-icon {
  width: 38px; height: 38px;
  background: linear-gradient(135deg, var(--clr-accent), var(--clr-accent2));
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 17px; color: #fff; flex-shrink: 0;
}
.sidebar-logo .logo-text { font-size: 16px; font-weight: 700; color: var(--clr-text); }
.sidebar-logo .logo-sub  { font-size: 11px; color: var(--clr-muted); }

.sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
.nav-section-title {
  font-size: 10px; font-weight: 600;
  color: var(--clr-muted);
  text-transform: uppercase;
  letter-spacing: .1em;
  padding: 16px 22px 6px;
}
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 22px;
  color: var(--clr-muted);
  text-decoration: none;
  border-radius: 0;
  transition: all .2s;
  font-size: 13.5px;
  font-weight: 500;
  position: relative;
}
.nav-item:hover  { color: var(--clr-text); background: var(--clr-hover); }
.nav-item.active {
  color: var(--clr-accent);
  background: rgba(59,130,246,.08);
}
.nav-item.active::before {
  content: '';
  position: absolute; left: 0; top: 6px; bottom: 6px;
  width: 3px;
  background: var(--clr-accent);
  border-radius: 0 3px 3px 0;
}
.nav-item i { width: 18px; text-align: center; font-size: 14px; }
.nav-badge {
  margin-left: auto;
  background: var(--clr-accent);
  color: #fff;
  font-size: 10px;
  padding: 2px 7px;
  border-radius: 20px;
  font-weight: 600;
}

.sidebar-footer {
  padding: 16px 22px;
  border-top: 1px solid var(--clr-border);
}
.user-card {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px;
  background: var(--clr-card);
  border-radius: var(--radius);
  text-decoration: none;
  transition: background .2s;
}
.user-card:hover { background: var(--clr-hover); }
.user-avatar {
  width: 34px; height: 34px;
  background: linear-gradient(135deg, var(--clr-accent), var(--clr-accent2));
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; color: #fff; font-weight: 700; flex-shrink: 0;
}
.user-info .user-name  { font-size: 13px; font-weight: 600; color: var(--clr-text); }
.user-info .user-role  { font-size: 11px; color: var(--clr-muted); }

/* ── Main ── */
.main-wrapper {
  margin-left: var(--sidebar-w);
  flex: 1;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
.topbar {
  height: var(--header-h);
  background: var(--clr-surface);
  border-bottom: 1px solid var(--clr-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
  position: sticky; top: 0; z-index: 50;
}
.topbar-title { font-size: 16px; font-weight: 600; color: var(--clr-text); }
.topbar-actions { display: flex; align-items: center; gap: 12px; }
.topbar-btn {
  width: 36px; height: 36px;
  background: var(--clr-card);
  border: 1px solid var(--clr-border);
  border-radius: 8px;
  color: var(--clr-muted);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: all .2s; text-decoration: none;
  font-size: 14px;
}
.topbar-btn:hover { color: var(--clr-text); border-color: var(--clr-accent); }

.page-content { padding: 28px; flex: 1; }

/* ── Flash ── */
.flash {
  padding: 12px 16px;
  border-radius: var(--radius);
  margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px;
  font-size: 13.5px; font-weight: 500;
  animation: slideIn .3s ease;
}
.flash.success { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.3); color: #4ade80; }
.flash.error   { background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.3); color: #f87171; }
.flash.info    { background: rgba(59,130,246,.12); border: 1px solid rgba(59,130,246,.3); color: #60a5fa; }
@keyframes slideIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:none; } }

/* ── Cards ── */
.card {
  background: var(--clr-card);
  border: 1px solid var(--clr-border);
  border-radius: 12px;
  overflow: hidden;
}
.card-header {
  padding: 18px 22px;
  border-bottom: 1px solid var(--clr-border);
  display: flex; align-items: center; justify-content: space-between;
}
.card-title { font-size: 15px; font-weight: 600; color: var(--clr-text); display: flex; align-items: center; gap: 8px; }
.card-body { padding: 22px; }

/* ── Stats Grid ── */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
.stat-card {
  background: var(--clr-card);
  border: 1px solid var(--clr-border);
  border-radius: 12px;
  padding: 20px 22px;
  display: flex; align-items: center; gap: 16px;
  transition: border-color .2s, transform .2s;
}
.stat-card:hover { border-color: var(--clr-accent); transform: translateY(-2px); }
.stat-icon {
  width: 48px; height: 48px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; flex-shrink: 0;
}
.stat-icon.blue   { background: rgba(59,130,246,.15); color: var(--clr-accent); }
.stat-icon.green  { background: rgba(34,197,94,.15);  color: var(--clr-green);  }
.stat-icon.purple { background: rgba(99,102,241,.15); color: var(--clr-accent2);}
.stat-icon.amber  { background: rgba(245,158,11,.15); color: var(--clr-amber);  }
.stat-icon.red    { background: rgba(239,68,68,.15);  color: var(--clr-red);    }
.stat-value { font-size: 26px; font-weight: 700; color: var(--clr-text); font-family: var(--mono); }
.stat-label { font-size: 12px; color: var(--clr-muted); margin-top: 2px; font-weight: 500; }

/* ── Table ── */
.table-wrapper { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
thead th {
  background: rgba(255,255,255,.02);
  padding: 12px 16px;
  text-align: left;
  font-size: 11px; font-weight: 600;
  color: var(--clr-muted);
  text-transform: uppercase; letter-spacing: .06em;
  border-bottom: 1px solid var(--clr-border);
  white-space: nowrap;
}
tbody td {
  padding: 13px 16px;
  border-bottom: 1px solid rgba(255,255,255,.04);
  color: var(--clr-text);
  vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: rgba(255,255,255,.02); }
.mono { font-family: var(--mono); font-size: 12px; color: var(--clr-accent); }

/* ── Badges ── */
.badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 10px;
  border-radius: 20px; font-size: 11px; font-weight: 600;
}
.badge-blue   { background: rgba(59,130,246,.15); color: #60a5fa; }
.badge-green  { background: rgba(34,197,94,.15);  color: #4ade80; }
.badge-red    { background: rgba(239,68,68,.15);  color: #f87171; }
.badge-amber  { background: rgba(245,158,11,.15); color: #fbbf24; }
.badge-purple { background: rgba(99,102,241,.15); color: #a5b4fc; }
.badge-gray   { background: rgba(100,116,139,.15);color: #94a3b8; }

/* ── Buttons ── */
.btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 16px;
  border-radius: 8px; border: none;
  font-size: 13px; font-weight: 500;
  cursor: pointer; text-decoration: none;
  transition: all .2s; font-family: var(--font);
}
.btn-primary  { background: var(--clr-accent); color: #fff; }
.btn-primary:hover { background: #2563eb; }
.btn-success  { background: var(--clr-green); color: #fff; }
.btn-success:hover { background: #16a34a; }
.btn-danger   { background: var(--clr-red); color: #fff; }
.btn-danger:hover { background: #dc2626; }
.btn-secondary{ background: var(--clr-card); color: var(--clr-text); border: 1px solid var(--clr-border); }
.btn-secondary:hover { border-color: var(--clr-accent); color: var(--clr-accent); }
.btn-sm       { padding: 5px 11px; font-size: 12px; }
.btn-icon     { width: 32px; height: 32px; padding: 0; justify-content: center; }

/* ── Forms ── */
.form-grid   { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
.form-group  { display: flex; flex-direction: column; gap: 6px; }
.form-group.full { grid-column: 1 / -1; }
label        { font-size: 12px; font-weight: 600; color: var(--clr-muted); text-transform: uppercase; letter-spacing: .05em; }
input, select, textarea {
  background: var(--clr-surface);
  border: 1px solid var(--clr-border);
  border-radius: 8px;
  padding: 10px 14px;
  color: var(--clr-text);
  font-size: 13.5px;
  font-family: var(--font);
  transition: border-color .2s, box-shadow .2s;
  width: 100%;
}
input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: var(--clr-accent);
  box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
input::placeholder { color: var(--clr-muted); }
select option { background: var(--clr-surface); }
textarea { resize: vertical; min-height: 90px; }
.form-error { font-size: 12px; color: var(--clr-red); margin-top: 2px; }
.field-error { border-color: var(--clr-red) !important; }

/* ── Search bar ── */
.search-bar {
  display: flex; gap: 10px; align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
}
.search-input-wrap {
  position: relative; flex: 1; min-width: 220px;
}
.search-input-wrap i {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  color: var(--clr-muted); font-size: 13px;
}
.search-input-wrap input { padding-left: 36px; }

/* ── Pagination ── */
.pagination {
  display: flex; align-items: center; gap: 6px;
  padding: 16px 22px; border-top: 1px solid var(--clr-border);
  flex-wrap: wrap;
}
.page-info { color: var(--clr-muted); font-size: 12px; margin-right: auto; }
.page-link {
  min-width: 34px; height: 34px;
  display: flex; align-items: center; justify-content: center;
  border-radius: 8px;
  background: var(--clr-card); border: 1px solid var(--clr-border);
  color: var(--clr-muted); text-decoration: none; font-size: 13px;
  transition: all .2s; padding: 0 10px;
}
.page-link:hover  { border-color: var(--clr-accent); color: var(--clr-accent); }
.page-link.active { background: var(--clr-accent); border-color: var(--clr-accent); color: #fff; }

/* ── Scrollbar ── */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--clr-border); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--clr-muted); }

/* ── Responsive ── */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.open { transform: none; }
  .main-wrapper { margin-left: 0; }
  .stats-grid { grid-template-columns: 1fr 1fr; }
  .page-content { padding: 16px; }
}
</style>
</head>
<body>

<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath   = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
$path       = str_replace($basePath, '', $currentUri);
$path       = '/' . ltrim($path, '/');

function navActive(string $prefix, string $path): string {
    return str_starts_with($path, $prefix) ? 'active' : '';
}

$user = $_SESSION['user'] ?? [];
$initials = implode('', array_map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)),
    array_slice(explode(' ', $user['ho_ten'] ?? 'U'), -2)));
?>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
    <div>
      <div class="logo-text"><?= APP_NAME ?></div>
      <div class="logo-sub">Quản lý học tập</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-title">Tổng quan</div>
    <a href="<?= BASE_URL ?>/dashboard" class="nav-item <?= navActive('/dashboard', $path) ?: navActive('/', $path) ?>">
      <i class="fas fa-chart-pie"></i> Dashboard
    </a>

    <div class="nav-section-title">Quản lý</div>
    <a href="<?= BASE_URL ?>/khoa" class="nav-item <?= navActive('/khoa', $path) ?>">
      <i class="fas fa-building-columns"></i> Khoa
    </a>
    <a href="<?= BASE_URL ?>/giang-vien" class="nav-item <?= navActive('/giang-vien', $path) ?>">
      <i class="fas fa-chalkboard-user"></i> Giảng viên
    </a>
    <a href="<?= BASE_URL ?>/sinh-vien" class="nav-item <?= navActive('/sinh-vien', $path) ?>">
      <i class="fas fa-user-graduate"></i> Sinh viên
    </a>
    <a href="<?= BASE_URL ?>/mon-hoc" class="nav-item <?= navActive('/mon-hoc', $path) ?>">
      <i class="fas fa-book-open"></i> Môn học
    </a>
    <a href="<?= BASE_URL ?>/lop-hoc-phan" class="nav-item <?= navActive('/lop-hoc-phan', $path) ?>">
      <i class="fas fa-layer-group"></i> Lớp học phần
    </a>
    <a href="<?= BASE_URL ?>/diem-so" class="nav-item <?= navActive('/diem-so', $path) ?>">
      <i class="fas fa-star-half-stroke"></i> Điểm số
    </a>

        <!-- Thời khóa biểu theo role -->
    <?php 
    $role = $_SESSION['user']['role'] ?? '';
    if (in_array($role, ['sinh_vien', 'giang_vien', 'admin'])): 
    ?>
    <div class="nav-section-title">
        <?php 
        if ($role === 'sinh_vien') echo 'Sinh viên';
        elseif ($role === 'giang_vien') echo 'Giảng viên';
        else echo 'Hệ thống';
        ?>
    </div>
    <a href="<?= BASE_URL ?>/thoi-khoa-bieu" class="nav-item <?= navActive('/thoi-khoa-bieu', $path) ?>">
        <i class="fas fa-calendar-alt"></i> 
        <?php 
        if ($role === 'sinh_vien') echo 'Thời khóa biểu';
        elseif ($role === 'giang_vien') echo 'Lịch giảng dạy';
        else echo 'Quản lý lịch học';
        ?>
    </a>
    <?php endif; ?>

    <!-- Menu đăng ký học phần (chỉ sinh viên) -->
    <?php if ($role === 'sinh_vien'): ?>
    <a href="<?= BASE_URL ?>/dang-ky" class="nav-item <?= navActive('/dang-ky', $path) ?>">
      <i class="fas fa-file-signature"></i> Đăng ký học phần
    </a>
    <?php endif; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="<?= BASE_URL ?>/auth/logout" class="user-card">
      <div class="user-avatar"><?= htmlspecialchars($initials) ?></div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($user['ho_ten'] ?? '') ?></div>
        <div class="user-role"><?= match($user['role'] ?? '') {
          'admin' => '⚙ Quản trị viên',
          'giang_vien' => '👨‍🏫 Giảng viên',
          default => '👨‍🎓 Sinh viên'
        } ?></div>
      </div>
      <i class="fas fa-sign-out-alt" style="margin-left:auto;color:var(--clr-muted);font-size:13px"></i>
    </a>
  </div>
</aside>

<!-- Main -->
<div class="main-wrapper">
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:12px">
      <button class="topbar-btn" id="menuToggle" style="display:none">
        <i class="fas fa-bars"></i>
      </button>
      <span class="topbar-title"><?= htmlspecialchars($pageTitle ?? '') ?></span>
    </div>
    <div class="topbar-actions">
      <a href="<?= BASE_URL ?>/dashboard" class="topbar-btn" title="Dashboard">
        <i class="fas fa-home"></i>
      </a>
      <a href="<?= BASE_URL ?>/auth/logout" class="topbar-btn" title="Đăng xuất">
        <i class="fas fa-sign-out-alt"></i>
      </a>
    </div>
  </header>

  <main class="page-content">
    <?php if (isset($_SESSION['flash'])): ?>
      <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
      <div class="flash <?= $flash['type'] ?>">
        <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
        <?= htmlspecialchars($flash['message']) ?>
        <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;color:inherit;cursor:pointer;font-size:15px">×</button>
      </div>
    <?php endif; ?>

    <?php
      $viewFile = BASE_PATH . "/views/{$content_view}.php";
      if (file_exists($viewFile)) require $viewFile;
    ?>
  </main>
</div>

<script>
// Mobile menu
const menuBtn = document.getElementById('menuToggle');
const sidebar  = document.getElementById('sidebar');
if (menuBtn) {
  if (window.innerWidth <= 768) menuBtn.style.display = 'flex';
  menuBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
  document.addEventListener('click', e => {
    if (!sidebar.contains(e.target) && !menuBtn.contains(e.target))
      sidebar.classList.remove('open');
  });
}
// Auto-hide flash after 4s
document.querySelectorAll('.flash').forEach(el => {
  setTimeout(() => el.style.opacity = '0', 4000);
  setTimeout(() => el.remove(), 4400);
  el.style.transition = 'opacity .4s';
});
</script>
</body>
</html>
