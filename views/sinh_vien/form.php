<?php /* views/sinh_vien/form.php */ ?>
<div style="max-width:900px">
  <div style="margin-bottom:20px">
    <a href="<?= BASE_URL ?>/sinh-vien" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
  </div>
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="fas fa-<?= $sv ? 'pencil' : 'user-plus' ?>" style="color:var(--clr-green)"></i>
        <?= $sv ? 'Sửa thông tin Sinh viên' : 'Thêm Sinh viên mới' ?>
      </span>
    </div>
    <div class="card-body">
      <form method="POST" action="<?= $action ?>">
        <div class="form-grid">
          <div class="form-group">
            <label>Mã Sinh viên <span style="color:var(--clr-red)">*</span></label>
            <input type="text" name="ma_sv" value="<?= htmlspecialchars($sv['ma_sv'] ?? '') ?>"
                   placeholder="VD: SV21001"
                   class="<?= !empty($errors['ma_sv']) ? 'field-error' : '' ?>"
                   <?= $sv ? 'readonly style="opacity:.6"' : '' ?>>
            <?php if (!empty($errors['ma_sv'])): ?><span class="form-error"><?= $errors['ma_sv'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Họ và tên <span style="color:var(--clr-red)">*</span></label>
            <input type="text" name="ho_ten" value="<?= htmlspecialchars($sv['ho_ten'] ?? '') ?>"
                   placeholder="Nguyễn Văn A"
                   class="<?= !empty($errors['ho_ten']) ? 'field-error' : '' ?>">
            <?php if (!empty($errors['ho_ten'])): ?><span class="form-error"><?= $errors['ho_ten'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Email <span style="color:var(--clr-red)">*</span></label>
            <input type="email" name="email" value="<?= htmlspecialchars($sv['email'] ?? '') ?>"
                   placeholder="sv@edu.vn"
                   class="<?= !empty($errors['email']) ? 'field-error' : '' ?>">
            <?php if (!empty($errors['email'])): ?><span class="form-error"><?= $errors['email'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Ngày sinh</label>
            <input type="date" name="ngay_sinh" value="<?= htmlspecialchars($sv['ngay_sinh'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Giới tính</label>
            <select name="gioi_tinh">
              <option value="Nam"  <?= ($sv['gioi_tinh'] ?? 'Nam') === 'Nam'  ? 'selected' : '' ?>>Nam</option>
              <option value="Nu"   <?= ($sv['gioi_tinh'] ?? '') === 'Nu'   ? 'selected' : '' ?>>Nữ</option>
              <option value="Khac" <?= ($sv['gioi_tinh'] ?? '') === 'Khac' ? 'selected' : '' ?>>Khác</option>
            </select>
          </div>
          <div class="form-group">
            <label>Khoa</label>
            <select name="ma_khoa">
              <option value="">-- Chọn khoa --</option>
              <?php foreach ($danhSachKhoa as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>" <?= ($sv['ma_khoa'] ?? '') === $k['ma_khoa'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($k['ten_khoa']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Lớp</label>
            <input type="text" name="lop" value="<?= htmlspecialchars($sv['lop'] ?? '') ?>" placeholder="VD: CNTT2021A">
          </div>
          <div class="form-group">
            <label>Ngành học</label>
            <input type="text" name="nganh" value="<?= htmlspecialchars($sv['nganh'] ?? '') ?>" placeholder="VD: Kỹ thuật phần mềm">
          </div>
          <div class="form-group">
            <label>Năm nhập học</label>
            <input type="number" name="nam_nhap_hoc" value="<?= htmlspecialchars($sv['nam_nhap_hoc'] ?? '') ?>"
                   placeholder="2024" min="2000" max="2100">
          </div>
          <div class="form-group">
            <label>Trạng thái</label>
            <select name="trang_thai">
              <option value="dang_hoc"       <?= ($sv['trang_thai'] ?? 'dang_hoc') === 'dang_hoc' ? 'selected' : '' ?>>Đang học</option>
              <option value="tam_nghi"       <?= ($sv['trang_thai'] ?? '') === 'tam_nghi'       ? 'selected' : '' ?>>Tạm nghỉ</option>
              <option value="da_tot_nghiep"  <?= ($sv['trang_thai'] ?? '') === 'da_tot_nghiep'  ? 'selected' : '' ?>>Đã tốt nghiệp</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Địa chỉ</label>
            <textarea name="dia_chi" placeholder="Địa chỉ thường trú..."><?= htmlspecialchars($sv['dia_chi'] ?? '') ?></textarea>
          </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:24px">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-<?= $sv ? 'floppy-disk' : 'user-plus' ?>"></i>
            <?= $sv ? 'Cập nhật' : 'Thêm mới' ?>
          </button>
          <a href="<?= BASE_URL ?>/sinh-vien" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
