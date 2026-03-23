<?php /* views/sinh_vien/index.php */ ?>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-user-graduate" style="color:var(--clr-green)"></i> Danh sách Sinh viên</span>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <a href="<?= BASE_URL ?>/sinh-vien/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm SV</a>
    <?php endif; ?>
  </div>
  <div class="card-body" style="padding-bottom:0">
    <form method="GET" class="search-bar">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Tìm theo tên, mã SV, email..."
               value="<?= htmlspecialchars($search) ?>">
      </div>
      <select name="khoa" style="width:auto;min-width:160px">
        <option value="">-- Tất cả khoa --</option>
        <?php foreach ($danhSachKhoa as $k): ?>
          <option value="<?= $k['ma_khoa'] ?>" <?= $maKhoa === $k['ma_khoa'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($k['ten_khoa']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Lọc</button>
      <?php if ($search || $maKhoa): ?>
        <a href="<?= BASE_URL ?>/sinh-vien" class="btn btn-secondary"><i class="fas fa-times"></i></a>
      <?php endif; ?>
    </form>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Mã SV</th><th>Họ tên</th><th>Email</th>
          <th>Lớp</th><th>Khoa</th><th>Trạng thái</th>
          <th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result['data'] as $sv): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($sv['ma_sv']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($sv['ho_ten']) ?></td>
          <td style="color:var(--clr-muted);font-size:12px"><?= htmlspecialchars($sv['email']) ?></td>
          <td><?= htmlspecialchars($sv['lop'] ?? '—') ?></td>
          <td><?= htmlspecialchars($sv['ten_khoa'] ?? '—') ?></td>
          <td>
            <?php
              $b = match($sv['trang_thai']) {
                'dang_hoc' => ['green', 'Đang học'],
                'tam_nghi' => ['amber', 'Tạm nghỉ'],
                default    => ['gray',  'Tốt nghiệp'],
              };
            ?>
            <span class="badge badge-<?= $b[0] ?>"><?= $b[1] ?></span>
          </td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="<?= BASE_URL ?>/sinh-vien/show/<?= urlencode($sv['ma_sv']) ?>" class="btn btn-secondary btn-sm btn-icon" title="Xem"><i class="fas fa-eye"></i></a>
              <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
              <a href="<?= BASE_URL ?>/sinh-vien/edit/<?= urlencode($sv['ma_sv']) ?>" class="btn btn-secondary btn-sm btn-icon" title="Sửa"><i class="fas fa-pencil"></i></a>
              <a href="<?= BASE_URL ?>/sinh-vien/delete/<?= urlencode($sv['ma_sv']) ?>"
                 class="btn btn-danger btn-sm btn-icon"
                 onclick="return confirm('Xóa sinh viên <?= htmlspecialchars(addslashes($sv['ho_ten'])) ?>?')" title="Xóa">
                <i class="fas fa-trash"></i>
              </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($result['data'])): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--clr-muted)">
          <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:10px"></i>Không có dữ liệu
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($result['last_page'] > 1): ?>
  <div class="pagination">
    <span class="page-info">Hiển thị <?= count($result['data']) ?> / <?= $result['total'] ?> bản ghi</span>
    <?php for ($p = 1; $p <= $result['last_page']; $p++): ?>
      <a href="?page=<?= $p ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $maKhoa ? '&khoa='.urlencode($maKhoa) : '' ?>"
         class="page-link <?= $p === $result['current_page'] ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>
