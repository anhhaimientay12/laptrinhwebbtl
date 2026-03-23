<?php /* views/lop_hoc_phan/form.php */ ?>
<div style="max-width:800px">
  <div style="margin-bottom:20px"><a href="<?= BASE_URL ?>/lop-hoc-phan" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a></div>
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-layer-group" style="color:var(--clr-accent)"></i> <?= $lop ? 'Sửa Lớp học phần' : 'Thêm Lớp học phần mới' ?></span>
    </div>
    <div class="card-body">
      <form method="POST" action="<?= $action ?>">
        <div class="form-grid">
          <div class="form-group">
            <label>Mã Lớp *</label>
            <input type="text" name="ma_lop" value="<?= htmlspecialchars($lop['ma_lop'] ?? '') ?>" placeholder="VD: COMP101-01"
                   class="<?= !empty($errors['ma_lop']) ? 'field-error' : '' ?>"
                   <?= $lop ? 'readonly style="opacity:.6"' : '' ?>>
            <?php if (!empty($errors['ma_lop'])): ?><span class="form-error"><?= $errors['ma_lop'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Môn học *</label>
            <select name="ma_mon" class="<?= !empty($errors['ma_mon']) ? 'field-error' : '' ?>">
              <option value="">-- Chọn môn học --</option>
              <?php foreach ($danhSachMon as $m): ?>
                <option value="<?= $m['ma_mon'] ?>" <?= ($lop['ma_mon'] ?? '') === $m['ma_mon'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($m['ma_mon'] . ' — ' . $m['ten_mon']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Giảng viên</label>
            <select name="ma_gv">
              <option value="">-- Chưa phân công --</option>
              <?php foreach ($danhSachGV as $g): ?>
                <option value="<?= $g['ma_gv'] ?>" <?= ($lop['ma_gv'] ?? '') === $g['ma_gv'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($g['ma_gv'] . ' — ' . $g['ho_ten']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Học kỳ *</label>
            <select name="hoc_ky" class="<?= !empty($errors['hoc_ky']) ? 'field-error' : '' ?>">
              <option value="HK1" <?= ($lop['hoc_ky'] ?? '') === 'HK1' ? 'selected' : '' ?>>Học kỳ 1</option>
              <option value="HK2" <?= ($lop['hoc_ky'] ?? '') === 'HK2' ? 'selected' : '' ?>>Học kỳ 2</option>
              <option value="HK3" <?= ($lop['hoc_ky'] ?? '') === 'HK3' ? 'selected' : '' ?>>Học kỳ hè</option>
            </select>
          </div>
          <div class="form-group">
            <label>Năm học *</label>
            <input type="text" name="nam_hoc" value="<?= htmlspecialchars($lop['nam_hoc'] ?? '') ?>" placeholder="VD: 2024-2025">
          </div>
          <div class="form-group">
            <label>Sĩ số tối đa</label>
            <input type="number" name="si_so_max" value="<?= $lop['si_so_max'] ?? 40 ?>" min="1" max="200">
          </div>
          <div class="form-group">
            <label>Phòng học</label>
            <input type="text" name="phong_hoc" value="<?= htmlspecialchars($lop['phong_hoc'] ?? '') ?>" placeholder="VD: P201">
          </div>
          <div class="form-group">
            <label>Lịch học</label>
            <input type="text" name="lich_hoc" value="<?= htmlspecialchars($lop['lich_hoc'] ?? '') ?>" placeholder="T2-T4 (7h30-9h30)">
          </div>
          <div class="form-group">
            <label>Trạng thái</label>
            <select name="trang_thai">
              <option value="mo"       <?= ($lop['trang_thai'] ?? 'mo') === 'mo'       ? 'selected' : '' ?>>Mở</option>
              <option value="dong"     <?= ($lop['trang_thai'] ?? '') === 'dong'     ? 'selected' : '' ?>>Đóng</option>
              <option value="ket_thuc" <?= ($lop['trang_thai'] ?? '') === 'ket_thuc' ? 'selected' : '' ?>>Kết thúc</option>
            </select>
          </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:24px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-<?= $lop ? 'floppy-disk' : 'plus' ?>"></i> <?= $lop ? 'Cập nhật' : 'Thêm mới' ?></button>
          <a href="<?= BASE_URL ?>/lop-hoc-phan" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
