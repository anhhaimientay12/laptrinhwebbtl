<?php /* views/mon_hoc/form.php */ ?>
<div style="max-width:720px">
  <div style="margin-bottom:20px"><a href="<?= BASE_URL ?>/mon-hoc" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a></div>
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-book-open" style="color:var(--clr-amber)"></i> <?= $mon ? 'Sửa Môn học' : 'Thêm Môn học mới' ?></span>
    </div>
    <div class="card-body">
      <form method="POST" action="<?= $action ?>">
        <div class="form-grid">
          <div class="form-group">
            <label>Mã Môn *</label>
            <input type="text" name="ma_mon" value="<?= htmlspecialchars($mon['ma_mon'] ?? '') ?>" placeholder="VD: COMP101"
                   class="<?= !empty($errors['ma_mon']) ? 'field-error' : '' ?>"
                   <?= $mon ? 'readonly style="opacity:.6"' : '' ?>>
            <?php if (!empty($errors['ma_mon'])): ?><span class="form-error"><?= $errors['ma_mon'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Tên Môn học *</label>
            <input type="text" name="ten_mon" value="<?= htmlspecialchars($mon['ten_mon'] ?? '') ?>" placeholder="Lập trình cơ bản"
                   class="<?= !empty($errors['ten_mon']) ? 'field-error' : '' ?>">
            <?php if (!empty($errors['ten_mon'])): ?><span class="form-error"><?= $errors['ten_mon'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label>Số tín chỉ *</label>
            <input type="number" name="tin_chi" value="<?= htmlspecialchars($mon['tin_chi'] ?? 3) ?>" min="1" max="10"
                   class="<?= !empty($errors['tin_chi']) ? 'field-error' : '' ?>">
          </div>
          <div class="form-group">
            <label>Khoa</label>
            <select name="ma_khoa">
              <option value="">-- Chọn khoa --</option>
              <?php foreach ($danhSachKhoa as $k): ?>
                <option value="<?= $k['ma_khoa'] ?>" <?= ($mon['ma_khoa'] ?? '') === $k['ma_khoa'] ? 'selected' : '' ?>><?= htmlspecialchars($k['ten_khoa']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Môn tiên quyết</label>
            <select name="mon_tien_quyet">
              <option value="">-- Không có --</option>
              <?php foreach ($danhSachMon as $dm): ?>
                <?php if ($dm['ma_mon'] !== ($mon['ma_mon'] ?? '')): ?>
                <option value="<?= $dm['ma_mon'] ?>" <?= ($mon['mon_tien_quyet'] ?? '') === $dm['ma_mon'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($dm['ma_mon'] . ' — ' . $dm['ten_mon']) ?>
                </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group full">
            <label>Mô tả</label>
            <textarea name="mo_ta" placeholder="Mô tả nội dung môn học..."><?= htmlspecialchars($mon['mo_ta'] ?? '') ?></textarea>
          </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:24px">
          <button type="submit" class="btn btn-primary"><i class="fas fa-<?= $mon ? 'floppy-disk' : 'plus' ?>"></i> <?= $mon ? 'Cập nhật' : 'Thêm mới' ?></button>
          <a href="<?= BASE_URL ?>/mon-hoc" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
