<?php /* views/sinh_vien/show.php */ ?>
<div style="max-width:720px">
  <div style="margin-bottom:20px">
    <a href="<?= BASE_URL ?>/sinh-vien" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
  </div>
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-user" style="color:var(--clr-green)"></i> Chi tiết Sinh viên</span>
      <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
      <a href="<?= BASE_URL ?>/sinh-vien/edit/<?= urlencode($sv['ma_sv']) ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-pencil"></i> Sửa
      </a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <?php
        $fields = [
          'Mã Sinh viên' => $sv['ma_sv'],
          'Họ và tên'    => $sv['ho_ten'],
          'Email'        => $sv['email'],
          'Ngày sinh'    => $sv['ngay_sinh'] ? date('d/m/Y', strtotime($sv['ngay_sinh'])) : '—',
          'Giới tính'    => $sv['gioi_tinh'] ?? '—',
          'Lớp'          => $sv['lop'] ?? '—',
          'Ngành'        => $sv['nganh'] ?? '—',
          'Khoa'         => $sv['ten_khoa'] ?? '—',
          'Năm nhập học' => $sv['nam_nhap_hoc'] ?? '—',
          'Trạng thái'   => $sv['trang_thai'] ?? '—',
        ];
        foreach ($fields as $label => $value):
        ?>
        <div>
          <div style="font-size:11px;font-weight:600;color:var(--clr-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px"><?= $label ?></div>
          <div style="font-size:14px;color:var(--clr-text)"><?= htmlspecialchars((string)$value) ?></div>
        </div>
        <?php endforeach; ?>
        <?php if (!empty($sv['dia_chi'])): ?>
        <div style="grid-column:1/-1">
          <div style="font-size:11px;font-weight:600;color:var(--clr-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">Địa chỉ</div>
          <div style="font-size:14px;color:var(--clr-text)"><?= htmlspecialchars($sv['dia_chi']) ?></div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
