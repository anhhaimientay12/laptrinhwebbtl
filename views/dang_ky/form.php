<?php /* views/dang_ky/form.php */ ?>
<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="fas fa-graduation-cap" style="color:var(--clr-accent)"></i> 
      Danh sách lớp học phần đang mở
    </span>
    <a href="<?= BASE_URL ?>/dang-ky" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>
  
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Mã Lớp</th>
          <th>Môn học</th>
          <th>Giảng viên</th>
          <th>Học kỳ</th>
          <th>Sĩ số</th>
          <th>Phòng / Lịch học</th>
          <th style="text-align:right">Đăng ký</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($openLops as $lop): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($lop['ma_lop']) ?></span></td>
          <td style="font-weight:500">
            <?= htmlspecialchars($lop['ten_mon']) ?><br>
            <span style="font-size:11px;color:var(--clr-muted)"><?= $lop['tin_chi'] ?> TC</span>
          </td>
          <td><?= htmlspecialchars($lop['ten_gv'] ?: '—') ?></td>
          <td>HK<?= htmlspecialchars($lop['hoc_ky']) ?> (<?= htmlspecialchars($lop['nam_hoc']) ?>)</td>
          <td>
            <span class="<?= $lop['si_so_hien'] >= $lop['si_so_max'] ? 'badge-red' : 'badge-green' ?>" style="font-size:11px; padding: 2px 6px; border-radius: 4px;">
              <?= $lop['si_so_hien'] ?>/<?= $lop['si_so_max'] ?>
            </span>
          </td>
          <td>
            <div style="font-size:12px">
              <i class="fas fa-location-dot"></i> <?= htmlspecialchars($lop['phong_hoc'] ?: '—') ?><br>
              <i class="fas fa-calendar-day"></i> <?= htmlspecialchars($lop['lich_hoc'] ?: '—') ?>
            </div>
          </td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <?php if ($lop['si_so_hien'] < $lop['si_so_max']): ?>
              <form action="<?= BASE_URL ?>/dang-ky/store" method="POST">
                <input type="hidden" name="ma_lop" value="<?= htmlspecialchars($lop['ma_lop']) ?>">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fas fa-plus"></i> Chọn
                </button>
              </form>
              <?php else: ?>
                <button class="btn btn-secondary btn-sm" disabled style="opacity:0.5; cursor:not-allowed">
                  <i class="fas fa-ban"></i> Đầy
                </button>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($openLops)): ?>
        <tr>
          <td colspan="7" style="text-align:center;padding:40px;color:var(--clr-muted)">
            Hiện không có lớp học phần nào đang mở hoặc bạn đã đăng ký hết.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
