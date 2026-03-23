<?php /* views/giang_vien/form.php */ ?>
<div style="max-width:720px">
  <div style="margin-bottom:20px"><a href="<?= BASE_URL ?>/giang-vien" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a></div>
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-<?= $gv ? 'pencil' : 'plus' ?>" style="color:var(--clr-accent2)"></i> <?= $gv ? 'Sửa Giảng viên' : 'Thêm Giảng viên mới' ?></span>
    </div>
    <div class="card-body">
      <form method="POST" action="<?= $action ?>">
        <div class="form-grid">
          <div class="form-group">
            <label>Mã Giảng viên *</label>
            <input type="text" name="ma_gv" value="<?= htmlspecialchars($gv['ma_gv'] ?? '') ?>" placeholder="VD: GV001"
                   class="<?= !empty($errors['ma_gv']) ? 'field-error' : '' ?>"
                   <?= $gv ? 'readonly style="opacity:.6"' : '' ?>>
            <?php if (!empty($errors['ma_gv'])): ?><span class="form-error"><?= $errors['ma_gv'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Họ và tên *</label>
            <input type="text" name="ho_ten" value="<?= htmlspecialchars($gv['ho_ten'] ?? '') ?>" placeholder="Nguyễn Văn B"
                   class="<?= !empty($errors['ho_ten']) ? 'field-error' : '' ?>">
            <?php if (!empty($errors['ho_ten'])): ?><span class="form-error"><?= $errors['ho_ten'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($gv['email'] ?? '') ?>" placeholder="gv@edu.vn"
                   class="<?= !empty($errors['email']) ? 'field-error' : '' ?>">
            <?php if (!empty($errors['email'])): ?><span class="form-error"><?= $errors['email'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Điện thoại</label>
            <input type="text" name="dien_thoai" value="<?= htmlspecialchars($gv['dien_thoai'] ?? '') ?>" placeholder="0901234567">
          </div>
          <div class="form-group">
            <label>Khoa</label>
            <select name="ma_khoa">
              <option value="">-- Chọn khoa --</option>
              <?php foreach ($danhSachKhoa as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>" <?= ($gv['ma_khoa'] ?? '') === $k['ma_khoa'] ? 'selected' : '' ?>><?= htmlspecialchars($k['ten_khoa']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Học vị</label>
            <select name="hoc_vi">
              <?php foreach (['Cu nhan', 'Thac si', 'Tien si', 'Pho giao su', 'Giao su'] as $hv): ?>
                <option value="<?= $hv ?>" <?= ($gv['hoc_vi'] ?? 'Thac si') === $hv ? 'selected' : '' ?>><?= $hv ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:24px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-<?= $gv ? 'floppy-disk' : 'plus' ?>"></i> <?= $gv ? 'Cập nhật' : 'Thêm mới' ?></button>
          <a href="<?= BASE_URL ?>/giang-vien" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
