<?php /* views/diem_so/index.php */ ?>
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-star-half-stroke" style="color:var(--clr-amber)"></i> Quản lý Điểm số</span>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Mã Lớp</th><th>Môn học</th><th>Giảng viên</th>
          <th>Học kỳ</th><th>Năm học</th><th>Sĩ số</th><th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lops as $l): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($l['ma_lop']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($l['ten_mon'] ?? '—') ?></td>
          <td><?= htmlspecialchars($l['ten_gv'] ?? '—') ?></td>
          <td>HK<?= htmlspecialchars($l['hoc_ky']) ?></td>
          <td><?= htmlspecialchars($l['nam_hoc']) ?></td>
          <td><?= $l['si_so_hien'] ?>/<?= $l['si_so_max'] ?></td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="<?= BASE_URL ?>/diem-so/lop/<?= urlencode($l['ma_lop']) ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-table-list"></i> Nhập điểm
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($lops)): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--clr-muted)">
          <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:10px"></i>Chưa có lớp học phần nào
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
