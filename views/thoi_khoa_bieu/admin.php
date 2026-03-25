<?php /* views/thoi_khoa_bieu/admin.php */ ?>
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-calendar-alt" style="color:var(--clr-accent)"></i> <?= $pageTitle ?? 'Quản lý lịch học' ?></span>
        <a href="<?= BASE_URL ?>/lop-hoc-phan" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Quản lý lớp
        </a>
    </div>
    <div class="card-body">
        
        <!-- Form chọn học kỳ -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Học kỳ</label>
                <select name="hoc_ky" class="form-select">
                    <option value="HK1" <?= $hoc_ky == 'HK1' ? 'selected' : '' ?>>Học kỳ 1</option>
                    <option value="HK2" <?= $hoc_ky == 'HK2' ? 'selected' : '' ?>>Học kỳ 2</option>
                    <option value="HK3" <?= $hoc_ky == 'HK3' ? 'selected' : '' ?>>Học kỳ hè</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Năm học</label>
                <select name="nam_hoc" class="form-select">
                    <?php foreach ($danhSachHK as $hk): ?>
                    <option value="<?= $hk['nam_hoc'] ?>" <?= $nam_hoc == $hk['nam_hoc'] ? 'selected' : '' ?>>
                        <?= $hk['nam_hoc'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Xem</button>
            </div>
        </form>
        
        <?php if (empty($tkb)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Không có dữ liệu lịch học trong học kỳ này.
        </div>
        <?php else: ?>
        
        <?php 
        $thu_map = [2=>'Thứ 2', 3=>'Thứ 3', 4=>'Thứ 4', 5=>'Thứ 5', 6=>'Thứ 6', 7=>'Thứ 7', 8=>'CN'];
        ?>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Thứ</th>
                        <th>Tiết</th>
                        <th>Mã lớp</th>
                        <th>Môn học</th>
                        <th>Phòng</th>
                        <th>Giảng viên</th>
                        <th>Sĩ số</th>
                        <th>TC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tkb as $lich): ?>
                    <tr>
                        <td><?= $thu_map[$lich['thu']] ?></td>
                        <td><?= $lich['tiet_bat_dau'] ?>-<?= $lich['tiet_bat_dau'] + $lich['so_tiet'] - 1 ?></td>
                        <td><span class="mono"><?= htmlspecialchars($lich['ma_lop']) ?></span></td>
                        <td><?= htmlspecialchars($lich['ten_mon']) ?></td>
                        <td><?= htmlspecialchars($lich['phong_hoc']) ?></td>
                        <td><?= htmlspecialchars($lich['ten_gv'] ?? '—') ?></td>
                        <td class="text-center"><?= $lich['si_so'] ?? 0 ?></td>
                        <td class="text-center"><?= $lich['tin_chi'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>