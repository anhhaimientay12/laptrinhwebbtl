<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>404 — Không tìm thấy trang</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'Be Vietnam Pro', sans-serif;
  background: #0f1117; color: #e2e8f0;
  min-height: 100vh;
  display: flex; align-items: center; justify-content: center;
  text-align: center; padding: 20px;
}
.wrap { max-width: 460px; }
.code { font-size: 96px; font-weight: 700; color: #3b82f6; line-height: 1; letter-spacing: -4px; }
h1    { font-size: 22px; margin: 16px 0 8px; }
p     { color: #64748b; margin-bottom: 28px; }
a     { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px;
        background: #3b82f6; color: #fff; border-radius: 10px; text-decoration: none;
        font-weight: 600; transition: background .2s; }
a:hover { background: #2563eb; }
</style>
</head>
<body>
<div class="wrap">
  <div class="code">404</div>
  <h1>Trang không tìm thấy</h1>
  <p>Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
  <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>/dashboard">
    ← Về trang chủ
  </a>
</div>
</body>
</html>
