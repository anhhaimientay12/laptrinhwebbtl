<?php /* views/khoa/form.php */ ?>

<div style="max-width:640px">
  <div style="margin-bottom:20px">
    <a href="<?= BASE_URL ?>/khoa" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="fas fa-<?= $khoa ? 'pencil' : 'plus' ?>" style="color:var(--clr-accent)"></i>
        <?= $khoa ? 'Sửa thông tin Khoa' : 'Thêm Khoa mới' ?>
      </span>
    </div>
    <div class="card-body">
      <form method="POST" action="<?= $action ?>">
        <div class="form-grid">
          <div class="form-group">
            <label for="ma_khoa">Mã Khoa <span style="color:var(--clr-red)">*</span></label>
            <input type="text" id="ma_khoa" name="ma_khoa"
                   value="<?= htmlspecialchars($khoa['ma_khoa'] ?? '') ?>"
                   placeholder="VD: CNTT"
                   class="<?= !empty($errors['ma_khoa']) ? 'field-error' : '' ?>"
                   <?= $khoa ? 'readonly style="opacity:.6;cursor:not-allowed"' : '' ?>>
            <?php if (!empty($errors['ma_khoa'])): ?>
              <span class="form-error"><i class="fas fa-circle-exclamation"></i> <?= $errors['ma_khoa'] ?></span>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="ten_khoa">Tên Khoa <span style="color:var(--clr-red)">*</span></label>
            <input type="text" id="ten_khoa" name="ten_khoa"
                   value="<?= htmlspecialchars($khoa['ten_khoa'] ?? '') ?>"
                   placeholder="VD: Công nghệ Thông tin"
                   class="<?= !empty($errors['ten_khoa']) ? 'field-error' : '' ?>">
            <?php if (!empty($errors['ten_khoa'])): ?>
              <span class="form-error"><i class="fas fa-circle-exclamation"></i> <?= $errors['ten_khoa'] ?></span>
            <?php endif; ?>
          </div>

          <div class="form-group full">
            <label for="mo_ta">Mô tả</label>
            <textarea id="mo_ta" name="mo_ta" placeholder="Mô tả về khoa..."><?= htmlspecialchars($khoa['mo_ta'] ?? '') ?></textarea>
          </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:24px">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-<?= $khoa ? 'floppy-disk' : 'plus' ?>"></i>
            <?= $khoa ? 'Cập nhật' : 'Thêm mới' ?>
          </button>
          <a href="<?= BASE_URL ?>/khoa" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>
