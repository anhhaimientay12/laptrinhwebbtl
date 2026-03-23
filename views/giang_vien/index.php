<?php /* views/giang_vien/index.php */ ?>
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-chalkboard-user" style="color:var(--clr-accent2)"></i> Danh sách Giảng viên</span>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <a href="<?= BASE_URL ?>/giang-vien/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm GV</a>
    <?php endif; ?>
  </div>
  <div class="card-body" style="padding-bottom:0">
    <form method="GET" class="search-bar">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Tìm theo tên, mã GV..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tìm</button>
      <?php if ($search): ?><a href="<?= BASE_URL ?>/giang-vien" class="btn btn-secondary"><i class="fas fa-times"></i></a><?php endif; ?>
    </form>
  </div>
  <div class="table-wrapper">
    <table>
      <thead><tr><th>Mã GV</th><th>Họ tên</th><th>Email</th><th>Khoa</th><th>Học vị</th><th style="text-align:right">Thao tác</th></tr></thead>
      <tbody>
        <?php foreach ($result['data'] as $gv): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($gv['ma_gv']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($gv['ho_ten']) ?></td>
          <td style="color:var(--clr-muted);font-size:12px"><?= htmlspecialchars($gv['email']) ?></td>
          <td><?= htmlspecialchars($gv['ten_khoa'] ?? '—') ?></td>
          <td><span class="badge badge-purple"><?= htmlspecialchars($gv['hoc_vi']) ?></span></td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
              <a href="<?= BASE_URL ?>/giang-vien/edit/<?= urlencode($gv['ma_gv']) ?>" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-pencil"></i></a>
              <a href="<?= BASE_URL ?>/giang-vien/delete/<?= urlencode($gv['ma_gv']) ?>" class="btn btn-danger btn-sm btn-icon"
                 onclick="return confirm('Xóa giảng viên này?')"><i class="fas fa-trash"></i></a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($result['data'])): ?><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--clr-muted)">Không có dữ liệu</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($result['last_page'] > 1): ?>
  <div class="pagination">
    <span class="page-info">Hiển thị <?= count($result['data']) ?> / <?= $result['total'] ?> bản ghi</span>
    <?php for ($p = 1; $p <= $result['last_page']; $p++): ?>
      <a href="?page=<?= $p ?><?= $search ? '&search='.urlencode($search) : '' ?>" class="page-link <?= $p === $result['current_page'] ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>
