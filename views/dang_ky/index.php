<?php /* views/dang_ky/index.php */ ?>
<div class="card">
  <div class="card-header">
    <span class="card-title">
      <i class="fas fa-list-check" style="color:var(--clr-accent)"></i> 
      Học phần đã đăng ký
    </span>
    <a href="<?= BASE_URL ?>/dang-ky/moi" class="btn btn-primary btn-sm">
      <i class="fas fa-plus"></i> Đăng ký mới
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
          <th>Phòng / Lịch học</th>
          <th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list as $item): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($item['ma_lop']) ?></span></td>
          <td style="font-weight:500">
            <?= htmlspecialchars($item['ten_mon']) ?><br>
            <span style="font-size:11px;color:var(--clr-muted)"><?= $item['tin_chi'] ?> TC</span>
          </td>
          <td><?= htmlspecialchars($item['ten_gv'] ?: 'Chưa phân công') ?></td>
          <td>HK<?= htmlspecialchars($item['hoc_ky']) ?> (<?= htmlspecialchars($item['nam_hoc']) ?>)</td>
          <td>
            <div style="font-size:12px">
              <i class="fas fa-location-dot"></i> <?= htmlspecialchars($item['phong_hoc'] ?: '—') ?><br>
              <i class="fas fa-calendar-day"></i> <?= htmlspecialchars($item['lich_hoc'] ?: '—') ?>
            </div>
          </td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="<?= BASE_URL ?>/dang-ky/huy/<?= $item['id'] ?>" 
                 class="btn btn-danger btn-sm" 
                 onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký học phần này?')">
                <i class="fas fa-trash-can"></i> Hủy
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($list)): ?>
        <tr>
          <td colspan="6" style="text-align:center;padding:40px;color:var(--clr-muted)">
            Bạn chưa đăng ký học phần nào trong học kỳ này.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
