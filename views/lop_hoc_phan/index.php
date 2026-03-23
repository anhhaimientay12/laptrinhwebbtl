<?php /* views/lop_hoc_phan/index.php */ ?>
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-layer-group" style="color:var(--clr-accent)"></i> Danh sách Lớp học phần</span>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <a href="<?= BASE_URL ?>/lop-hoc-phan/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm Lớp HP</a>
    <?php endif; ?>
  </div>
  <div class="card-body" style="padding-bottom:0">
    <form method="GET" class="search-bar">
      <div class="search-input-wrap"><i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Tìm theo mã lớp, tên môn..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tìm</button>
      <?php if ($search): ?><a href="<?= BASE_URL ?>/lop-hoc-phan" class="btn btn-secondary"><i class="fas fa-times"></i></a><?php endif; ?>
    </form>
  </div>
  <div class="table-wrapper">
    <table>
      <thead><tr><th>Mã Lớp</th><th>Môn học</th><th>Giảng viên</th><th>Học kỳ</th><th>Năm học</th><th>Sĩ số</th><th>Phòng</th><th>Trạng thái</th><th style="text-align:right">Thao tác</th></tr></thead>
      <tbody>
        <?php foreach ($result['data'] as $l): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($l['ma_lop']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($l['ten_mon'] ?? '—') ?><br><span style="font-size:11px;color:var(--clr-muted)"><?= $l['tin_chi'] ?? '' ?> TC</span></td>
          <td><?= htmlspecialchars($l['ten_gv'] ?? '—') ?></td>
          <td>HK<?= htmlspecialchars($l['hoc_ky']) ?></td>
          <td><?= htmlspecialchars($l['nam_hoc']) ?></td>
          <td><?= $l['si_so_hien'] ?>/<?= $l['si_so_max'] ?></td>
          <td><?= htmlspecialchars($l['phong_hoc'] ?? '—') ?></td>
          <td>
            <?php
              $b = match($l['trang_thai']) {
                'mo' => ['green','Mở'], 'dong' => ['amber','Đóng'], default => ['gray','Kết thúc']
              };
            ?>
            <span class="badge badge-<?= $b[0] ?>"><?= $b[1] ?></span>
          </td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="<?= BASE_URL ?>/diem-so/lop/<?= urlencode($l['ma_lop']) ?>" class="btn btn-secondary btn-sm btn-icon" title="Bảng điểm"><i class="fas fa-star-half-stroke"></i></a>
              <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
              <a href="<?= BASE_URL ?>/lop-hoc-phan/edit/<?= urlencode($l['ma_lop']) ?>" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-pencil"></i></a>
              <a href="<?= BASE_URL ?>/lop-hoc-phan/delete/<?= urlencode($l['ma_lop']) ?>" class="btn btn-danger btn-sm btn-icon"
                 onclick="return confirm('Xóa lớp học phần này?')"><i class="fas fa-trash"></i></a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($result['data'])): ?><tr><td colspan="9" style="text-align:center;padding:40px;color:var(--clr-muted)">Không có dữ liệu</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($result['last_page'] > 1): ?>
  <div class="pagination">
    <span class="page-info"><?= count($result['data']) ?> / <?= $result['total'] ?> bản ghi</span>
    <?php for ($p = 1; $p <= $result['last_page']; $p++): ?>
      <a href="?page=<?= $p ?><?= $search ? '&search='.urlencode($search) : '' ?>" class="page-link <?= $p === $result['current_page'] ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>
