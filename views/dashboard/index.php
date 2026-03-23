<?php /* views/dashboard/index.php */ ?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fas fa-building-columns"></i></div>
    <div>
      <div class="stat-value"><?= $stats['total_khoa'] ?></div>
      <div class="stat-label">Khoa</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple"><i class="fas fa-chalkboard-user"></i></div>
    <div>
      <div class="stat-value"><?= $stats['total_gv'] ?></div>
      <div class="stat-label">Giảng viên</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-user-graduate"></i></div>
    <div>
      <div class="stat-value"><?= $stats['total_sv'] ?></div>
      <div class="stat-label">Sinh viên</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"><i class="fas fa-book-open"></i></div>
    <div>
      <div class="stat-value"><?= $stats['total_mon'] ?></div>
      <div class="stat-label">Môn học</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
    <div>
      <div class="stat-value"><?= $stats['total_lop'] ?></div>
      <div class="stat-label">Lớp học phần</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
    <div>
      <div class="stat-value"><?= $stats['sv_dang_hoc'] ?></div>
      <div class="stat-label">SV đang học</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;flex-wrap:wrap">

  <!-- Sinh viên mới nhất -->
  <div class="card" style="grid-column:span 1">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-user-plus" style="color:var(--clr-accent)"></i> Sinh viên mới đăng ký</span>
      <a href="<?= BASE_URL ?>/sinh-vien" class="btn btn-secondary btn-sm">Xem tất cả</a>
    </div>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr><th>Mã SV</th><th>Họ tên</th><th>Khoa</th><th>Trạng thái</th></tr>
        </thead>
        <tbody>
          <?php foreach ($recentSV as $sv): ?>
          <tr>
            <td><span class="mono"><?= htmlspecialchars($sv['ma_sv']) ?></span></td>
            <td><?= htmlspecialchars($sv['ho_ten']) ?></td>
            <td><?= htmlspecialchars($sv['ten_khoa'] ?? '—') ?></td>
            <td>
              <?php
                $badge = match($sv['trang_thai']) {
                  'dang_hoc' => ['green', 'Đang học'],
                  'tam_nghi' => ['amber', 'Tạm nghỉ'],
                  default    => ['gray',  'Tốt nghiệp'],
                };
              ?>
              <span class="badge badge-<?= $badge[0] ?>"><?= $badge[1] ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($recentSV)): ?>
          <tr><td colspan="4" style="text-align:center;color:var(--clr-muted);padding:24px">Chưa có dữ liệu</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Phân bổ SV theo Khoa -->
  <div class="card" style="grid-column:span 1">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-chart-bar" style="color:var(--clr-green)"></i> Sinh viên theo Khoa</span>
    </div>
    <div class="card-body">
      <?php
        $totalSV = array_sum(array_column($svByKhoa, 'so_luong')) ?: 1;
        $colors  = ['var(--clr-accent)', 'var(--clr-green)', 'var(--clr-accent2)', 'var(--clr-amber)', 'var(--clr-red)'];
        $i = 0;
      ?>
      <?php foreach ($svByKhoa as $row): ?>
        <?php
          $pct = round(($row['so_luong'] / $totalSV) * 100);
          $clr = $colors[$i++ % count($colors)];
        ?>
        <div style="margin-bottom:16px">
          <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px">
            <span><?= htmlspecialchars($row['ten_khoa']) ?></span>
            <span style="color:var(--clr-muted)"><?= $row['so_luong'] ?> SV &nbsp;<strong style="color:var(--clr-text)"><?= $pct ?>%</strong></span>
          </div>
          <div style="height:8px;background:var(--clr-border);border-radius:4px;overflow:hidden">
            <div style="height:100%;width:<?= $pct ?>%;background:<?= $clr ?>;border-radius:4px;transition:width .8s ease"></div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($svByKhoa)): ?>
        <p style="color:var(--clr-muted);text-align:center;padding:20px 0">Chưa có dữ liệu</p>
      <?php endif; ?>
    </div>
  </div>

</div>

<!-- Quick links -->
<div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:22px">
  <a href="<?= BASE_URL ?>/khoa/create"      class="btn btn-secondary"><i class="fas fa-plus"></i> Thêm Khoa</a>
  <a href="<?= BASE_URL ?>/sinh-vien/create" class="btn btn-secondary"><i class="fas fa-plus"></i> Thêm Sinh viên</a>
  <a href="<?= BASE_URL ?>/mon-hoc/create"   class="btn btn-secondary"><i class="fas fa-plus"></i> Thêm Môn học</a>
  <a href="<?= BASE_URL ?>/giang-vien/create"class="btn btn-secondary"><i class="fas fa-plus"></i> Thêm Giảng viên</a>
</div>
