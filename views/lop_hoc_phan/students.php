<?php /* views/lop_hoc_phan/students.php */ ?>
<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:12px">
  <a href="<?= BASE_URL ?>/lop-hoc-phan" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="card" style="margin-bottom:24px">
  <div class="card-header"><span class="card-title"><i class="fas fa-user-plus" style="color:var(--clr-green)"></i> Thêm sinh viên vào lớp</span></div>
  <div class="card-body">
    <form action="<?= BASE_URL ?>/lop-hoc-phan/sinh-vien/add/<?= urlencode($lop['ma_lop']) ?>" method="POST" class="search-bar" style="margin-bottom:0">
      <div class="search-input-wrap">
        <i class="fas fa-id-card"></i>
        <input type="text" name="ma_sv" placeholder="Nhập mã sinh viên (VD: SV2021001)..." required>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm vào lớp</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-users" style="color:var(--clr-accent)"></i> Danh sách sinh viên — <?= htmlspecialchars($lop['ma_lop']) ?></span>
    <span class="badge badge-blue"><?= count($students) ?> / <?= $lop['si_so_max'] ?> SV</span>
  </div>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Mã SV</th>
          <th>Họ tên</th>
          <th>Lớp</th>
          <th>Ngày đăng ký</th>
          <th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $i => $s): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><span class="mono"><?= htmlspecialchars($s['ma_sv']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($s['ho_ten']) ?></td>
          <td><?= htmlspecialchars($s['lop_sv'] ?? '—') ?></td>
          <td style="font-size:12px;color:var(--clr-muted)"><?= date('d/m/Y H:i', strtotime($s['ngay_dk'])) ?></td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <a href="<?= BASE_URL ?>/lop-hoc-phan/sinh-vien/remove/<?= $s['id'] ?>" 
                 class="btn btn-danger btn-sm btn-icon" 
                 title="Xóa khỏi lớp"
                 onclick="return confirm('Xóa sinh viên này khỏi lớp học phần?')">
                <i class="fas fa-user-minus"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($students)): ?>
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--clr-muted)">Chưa có sinh viên trong lớp này</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
